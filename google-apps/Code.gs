function onOpen() {
  const addOnMenu = SpreadsheetApp.getUi().createAddonMenu()
  addOnMenu.addItem('Step 1: List Shared Drives', 'readSharedDrives');
  addOnMenu.addItem('Step 2: List permissions', 'readPermissions');
  addOnMenu.addItem('Step 3: List folders', 'readSharedDriveFolders');
  addOnMenu.addToUi();
}

function readSharedDrives() {
  const driveManager = DriveManager();
  const sharedDrives = driveManager.Drives.list(all=true);  
  let preparedData = [
    ['id', 'name', 'status', 'note']
  ];
  if (sharedDrives) {
    console.log(`Found ${sharedDrives.length} shared drives`);
    sharedDrives.forEach(sharedDrive => {
      preparedData.push([
        sharedDrive.id,
        sharedDrive.name,
        QUEUESTATUS_PENDING,
        ''
      ])
    })
    let sheet = SpreadsheetApp.getActive().getSheetByName(SHARED_DRIVES_SHEET_NAME);
    if (!sheet) {
      sheet = SpreadsheetApp.getActive().insertSheet(SHARED_DRIVES_SHEET_NAME);
    }
    sheet.clearContents();
    sheet.getRange(1,1,preparedData.length, preparedData[0].length).setValues(preparedData);    
    sheet.autoResizeColumns(1, preparedData[0].length);
    SpreadsheetApp.flush();
  }
}

function readSharedDriveFolders(resume=false) {
  let sheet = SpreadsheetApp.getActive().getSheetByName(SHARED_DRIVES_SHEET_NAME);
  let firstRun = true;
  if (resume) {
    firstRun = false;
  }
  const timeManager = gaspTimeManager({
    availableTime: 30*60*1000 // we have 30 minutes to complete the task
  });
  if (!sheet) {
    throw Error('You must run list shared drives before we can list permissions');
  }
  const sharedDrives = sheet.getDataRange().getValues();
  const driveManager = DriveManager();
    let preparedData = [
    ['driveId', 'driveName', 'id', 'displayName']
  ];
  let folderSheet = SpreadsheetApp.getActive().getSheetByName(SHARED_DRIVES_FOLDER_SHEET_NAME);
  if (!folderSheet) {
    folderSheet = SpreadsheetApp.getActive().insertSheet(SHARED_DRIVES_FOLDER_SHEET_NAME); 
  }
  sharedDrives.some((sharedDriveRow, idx) => {
    if(!timeManager.acquireTime(30000)) {
      // continue over 5 minutes
      ScriptApp.newTrigger('resumeSharedDriveFolders').timeBased().after(5*60*1000).create();
      return true;
    }
    // if(sharedDriveRow[2] !== 'PENDING') {
    //   return;
    // }
    // update to pending
    sheet.getRange(idx + 1, 3).setValue(QUEUESTATUS_IN_PROGRESS);
    SpreadsheetApp.flush();
    try {
      const items = driveManager.Folders.list(sharedDriveRow[0], all=true);
      if (items) {
        items.forEach(f => {
          preparedData.push(
            [
              sharedDriveRow[0],
              sharedDriveRow[1] || '',
              f.id || '',
              f.type || '',
              f.displayName || ''
            ]
          );        
        })
      }
      if(firstRun) {
        firstRun = false;
        folderSheet.clearContents();
        folderSheet.getRange(1,1,preparedData.length, preparedData[0].length).setValues(preparedData);
      } else {
        if (preparedData.length > 0) {
          folderSheet.getRange(folderSheet.getLastRow() + 1,1,preparedData.length, preparedData[0].length).setValues(preparedData);
        }
      }
      preparedData = [];
      sheet.getRange(idx + 1, 3).setValue(QUEUESTATUS_COMPLETED);
      SpreadsheetApp.flush();
    } catch (err) {
      console.error(err);
      // update to pending
      sheet.getRange(idx + 1, 3).setValue(QUEUESTATUS_FAILED);
      sheet.getRange(idx + 1, 4).setValue(err);
      SpreadsheetApp.flush();
    }
  })
  folderSheet.autoResizeColumns(1, folderSheet.getLastColumn());
  SpreadsheetApp.flush();
}

function readPermissions(resume=false) {
  let sheet = SpreadsheetApp.getActive().getSheetByName(SHARED_DRIVES_SHEET_NAME);
  let firstRun = true;
  if (resume) {
    firstRun = false;
  }
  const timeManager = gaspTimeManager({
    availableTime: 30*60*1000 // we have 30 minutes to complete the task
  });
  if (!sheet) {
    throw Error('You must run list shared drives before we can list permissions');
  }
  const sharedDrives = sheet.getDataRange().getValues();
  const driveManager = DriveManager();
    let preparedData = [
    ['driveId', 'driveName', 'permissionId', 'type', 'emailAddress', 'domain', 'role', 'allowFileDiscovery', 'displayName', 'expirationTime', 'deleted']
  ];
  let permissionSheet = SpreadsheetApp.getActive().getSheetByName(SHARED_DRIVES_PERMISSIONS_SHEET_NAME);
  if (!permissionSheet) {
    permissionSheet = SpreadsheetApp.getActive().insertSheet(SHARED_DRIVES_PERMISSIONS_SHEET_NAME);
  }
  sharedDrives.some((sharedDriveRow, idx) => {
    if(!timeManager.acquireTime(30000)) {
      // continue over 5 minutes
      ScriptApp.newTrigger('resumePermissions').timeBased().after(5*60*1000).create();
      return true;
    }
    if(sharedDriveRow[2] !== 'PENDING') {
      return;
    }
    // update to pending
    sheet.getRange(idx + 1, 3).setValue(QUEUESTATUS_IN_PROGRESS);
    SpreadsheetApp.flush();
    try {
      const permissions = driveManager.Permissions.list(sharedDriveRow[0], all=true);
      if (permissions) {
        permissions.forEach(permission => {
          preparedData.push(
            [
              sharedDriveRow[0],
              sharedDriveRow[1] || '',
              permission.id || '',
              permission.type || '',
              permission.emailAddress || '',
              permission.domain || '',
              permission.role || '',
              permission.allowFileDiscovery || '',
              permission.displayName || '',
              permission.expirationTime || '',
              permission.deleted || '',
            ]
          );        
        })
      }
      if(firstRun) {
        firstRun = false;
        permissionSheet.clearContents();
        permissionSheet.getRange(1,1,preparedData.length, preparedData[0].length).setValues(preparedData);
      } else {
        if (preparedData.length > 0) {
          permissionSheet.getRange(permissionSheet.getLastRow() + 1,1,preparedData.length, preparedData[0].length).setValues(preparedData);
        }
      }
      preparedData = [];
      sheet.getRange(idx + 1, 3).setValue(QUEUESTATUS_COMPLETED);
      SpreadsheetApp.flush();
    } catch (err) {
      console.error(err);
      // update to pending
      sheet.getRange(idx + 1, 3).setValue(QUEUESTATUS_FAILED);
      sheet.getRange(idx + 1, 4).setValue(err);
      SpreadsheetApp.flush();
    }
  })
  permissionSheet.autoResizeColumns(1, permissionSheet.getLastColumn());
  SpreadsheetApp.flush();
}
