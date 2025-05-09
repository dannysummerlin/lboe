function DriveManager(specs = {}) {
  const drives = () => {
    const list = (all=false, pageToken=null) => {
      let optionalArgs = {};
      optionalArgs['maxResults'] = 100;
      optionalArgs['useDomainAdminAccess'] = false;
      if (pageToken) {
        optionalArgs['pageToken'] = pageToken;
      }
      let sharedDrives = [];
      do {
        const response = Drive.Drives.list(optionalArgs);
        sharedDrives = sharedDrives.concat(response.items);
        optionalArgs.pageToken = response.nextPageToken

      } while (optionalArgs.pageToken && all === true)
      return sharedDrives;
    }
    const get = (sharedDriveId) => {
      return Drive.Drives.get(sharedDriveId, { useDomainAdminAccess: false });
    }
    return Object.freeze({
      list,
      get
    });
  }
  const permissions = () => {
    const list = (driveId, all=false, pageToken=null) => {
      let optionalArgs = {};
      optionalArgs['supportsAllDrives'] = true;
      optionalArgs['useDomainAdminAccess'] = false;
      if (pageToken) {
        optionalArgs['pageToken'] = pageToken;
      }
      let permissions = [];
     do {
        const response = Drive.Permissions.list(driveId, optionalArgs);
        permissions = permissions.concat(response.items);
        optionalArgs.pageToken = response.nextPageToken
      } while (optionalArgs.pageToken && all === true)
      return permissions;
    };
    return Object.freeze({
      list: list
    })
  }
  const folders = () => {
    const list = (driveId, all=false, pageToken=null) => {
      const query = "mimeType = 'application/vnd.google-apps.folder'"; 
      // '"root" in parents and trashed = false' +
        // ' and mimeType = "application/vnd.google-apps.folder"'; // +
        // ` and driveId = "${driveId}"`;
      let folders = [];
      do {
        try {
          f = Drive.Files.list({
            q: query,
            spaces: 'drive',
            corpora: 'drive',
            includeItemsFromAllDrives: true,
            driveId: driveId,
            pageSize: 1000,
            pageToken: pageToken
          });
          if (!f.files || f.files.length === 0) {
            console.log('All folders found.');
            return;
          } else {
            console.log(f[0])
          }
          for (let i = 0; i < f.files.length; i++) {
            const folder = f.files[i];
            folders.concat(folder)
          }
          pageToken = folders.nextPageToken;
        } catch (err) {
          // TODO (developer) - Handle exception
          console.log('Failed with error %s', err.message);
        }
      }
      while (pageToken);
    }
    return Object.freeze({
      list: list
    })
  }

  return Object.freeze({
    Drives: drives(),
    Permissions: permissions(),
    Folders: folders()
  });
}
