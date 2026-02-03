global class OIDCRegistrationHandlerToFlow implements Auth.RegistrationHandler {
    private OIDC_Login_Handler_Settings__mdt getFlowSettings() {
        OIDC_Login_Handler_Settings__mdt config = new OIDC_Login_Handler_Settings__mdt();
        try {
            config = [
                SELECT User_Processing_Flow_API_Name__c,LocaleSidKey__c,Default_Profile_ID__c
                FROM OIDC_Login_Handler_Settings__mdt LIMIT 1
            ];
        } catch (Exception e) {
            System.debug('RECORD ERROR: There is no OIDC_Login_Handler_Settings__mdt Custom Metadata setting to read (requires: User_Processing_Flow_API_Name__c,LocaleSidKey__c,Default_Profile_ID__c)');
        }
        return config;
    }
    private User processUser(Id uId, Auth.UserData data) {
        OIDC_Login_Handler_Settings__mdt flowSettings = getFlowSettings();
        // get claim data
        Map<String,Object> rawClaims = (Map<String,Object>) JSON.deserializeUntyped(data.idTokenJSONString);
        String rawGroups = rawClaims.containsKey('groups') ? JSON.serialize(rawClaims.get('groups')) : '';
        List<String> groupList = rawGroups.replace('"','').replace('[','').replace(']','').split(',');
        String username = data.email;
        User u = new User();
        List<User> users = new List<User>();
        try {
            users = [SELECT Id, Username, Email, FirstName, LastName FROM User WHERE Id = :uId OR Username = :username LIMIT 1];
        } catch (Exception e) {}
        if (users.isEmpty()) {
            String userAlias = (data.firstName.substring(0,1) + data.lastName).substring(0,7) + String.valueOf(Math.abs(Crypto.getRandomLong())).substring(0,1);
            u.Username  = username;
            u.Email     = data.email;
            u.FirstName = data.firstName;
            u.LastName  = data.lastName;
            u.Alias = userAlias;
            u.LocaleSidKey = String.isNotEmpty(flowSettings.LocaleSidKey__c) ? flowSettings.LocaleSidKey__c : 'en_US';
            u.TimeZoneSidKey = 'America/New_York';
            u.LanguageLocaleKey = 'en_US';
            u.EmailEncodingKey = 'ISO-8859-1';
            u.ProfileId = String.isNotEmpty(flowSettings.Default_Profile_ID__c) ? flowSettings.Default_Profile_ID__c : '00e410000018Fro';
            insert u;
        } else {
            u = users[0];
        }

        Map<String, Object> flowInput = new Map<String, Object>();
        flowInput.put('UserId', u.Id);
        flowInput.put('Username', u.Username);
        flowInput.put('Email', u.Email);
        flowInput.put('FirstName', u.FirstName);
        flowInput.put('LastName', u.LastName);
        flowInput.put('Groups', groupList);
        List<String> rawClaimPairs = new List<String>();
        for (String key : rawClaims.keySet()) {
            rawClaimPairs.add(key+'='+rawClaims.get(key));
        }
        flowInput.put('rawClaims', rawClaimPairs);
        if (flowSettings.User_Processing_Flow_API_Name__c != null) {
            Flow.Interview flow = Flow.Interview.createInterview(flowSettings.User_Processing_Flow_API_Name__c, flowInput);
            flow.start();
        } else {
            System.debug('No Flow API Name found in Custom Metadata. Skipping Flow execution.');
        }
        return u;
    }

    global User createUser(Id portalId, Auth.UserData data) {
        return processUser(portalId, data);
    }

    global void updateUser(Id userId, Id portalId, Auth.UserData data) {
        processUser(userId, data);
    }
}
