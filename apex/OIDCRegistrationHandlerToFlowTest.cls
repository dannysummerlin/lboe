@isTest
private class OIDCRegistrationHandlerToFlowTest {
    private static String getUserDomain() {
        OrgWideEmailAddress orgEmail = [SELECT Id, Address, DisplayName FROM OrgWideEmailAddress LIMIT 1];
        return orgEmail.Address.substringAfter('@');
    }
    private static Auth.UserData createUserData(String firstName, String lastName, String email, Map<String, Object> claims) {
        String idTokenJson = JSON.serialize(claims);
        String userDataJson = '{"identifier":"' + email + '",' +
            '"firstName":"' + firstName + '",' +
            '"lastName":"' + lastName + '",' +
            '"fullName":"' + firstName + ' ' + lastName + '",' +
            '"email":"' + email + '",' +
            '"link":null,' +
            '"userName":"' + email + '",' +
            '"locale":"en_US",' +
            '"provider":"oidc",' +
            '"siteLoginUrl":null,' +
            '"attributeMap":{},' +
            '"idTokenJSONString":' + JSON.serialize(idTokenJson) + '}';
        return (Auth.UserData)JSON.deserialize(userDataJson, Auth.UserData.class);
    }
    @isTest
    static void testCreateUser() {
        OIDCRegistrationHandlerToFlow handler = new OIDCRegistrationHandlerToFlow();
        Map<String, Object> claims = new Map<String, Object>{
            'sub' => '12345',
            'email' => 'testuser@'+getUserDomain(),
            'given_name' => 'Test',
            'family_name' => 'User',
            'groups' => new List<String>{'Group1', 'Group2'}
        };
        Auth.UserData userData = createUserData('Test', 'User', 'testuser@'+getUserDomain(), claims);

        Test.startTest();
        User result = handler.createUser(null, userData);
        Test.stopTest();

        System.assertNotEquals(null, result, 'User should be created');
        System.assertEquals('testuser@'+getUserDomain(), result.Email);
        System.assertEquals('Test', result.FirstName);
        System.assertEquals('User', result.LastName);

        List<User> users = [SELECT Id FROM User WHERE Email = 'testuser@'+getUserDomain()];
        System.assertEquals(1, users.size(), 'User should exist in database');
    }
    @isTest
    static void testCreateUserWithoutGroups() {
        OIDCRegistrationHandlerToFlow handler = new OIDCRegistrationHandlerToFlow();
        Map<String, Object> claims = new Map<String, Object>{
            'sub' => '67890',
            'email' => 'nogroups@'+getUserDomain(),
            'given_name' => 'No',
            'family_name' => 'Groups'
        };
        Auth.UserData userData = createUserData('No', 'Groups', 'nogroups@'+getUserDomain(), claims);

        Test.startTest();
        User result = handler.createUser(null, userData);
        Test.stopTest();

        System.assertNotEquals(null, result);
        System.assertEquals('nogroups@'+getUserDomain(), result.Email);
    }
    @isTest
    static void testUpdateUser() {
        Profile p = [SELECT Id FROM Profile WHERE Name = 'Standard User' LIMIT 1];
        User existingUser = new User(
            FirstName = 'Existing',
            LastName = 'User',
            Email = 'existing@'+getUserDomain(),
            Username = 'existing@'+getUserDomain()+'.test' + System.currentTimeMillis(),
            Alias = 'euser',
            TimeZoneSidKey = 'America/New_York',
            LocaleSidKey = 'en_US',
            EmailEncodingKey = 'UTF-8',
            LanguageLocaleKey = 'en_US',
            ProfileId = p.Id
        );
        insert existingUser;
        OIDCRegistrationHandlerToFlow handler = new OIDCRegistrationHandlerToFlow();
        Map<String, Object> claims = new Map<String, Object>{
            'sub' => '11111',
            'email' => 'existing@'+getUserDomain(),
            'given_name' => 'Existing',
            'family_name' => 'User',
            'groups' => new List<String>{'UpdatedGroup'}
        };
        Auth.UserData userData = createUserData('Existing', 'User', 'existing@'+getUserDomain()+'.test' + System.currentTimeMillis(), claims);

        Test.startTest();
        handler.updateUser(existingUser.Id, null, userData);
        Test.stopTest();

        User updatedUser = [SELECT Id, Email FROM User WHERE Id = :existingUser.Id];
        System.assertNotEquals(null, updatedUser);
    }
    @isTest
    static void testCreateUserWithLongName() {
        OIDCRegistrationHandlerToFlow handler = new OIDCRegistrationHandlerToFlow();
        Map<String, Object> claims = new Map<String, Object>{
            'sub' => '33333',
            'email' => 'longname@'+getUserDomain(),
            'given_name' => 'VeryLongFirstName',
            'family_name' => 'VeryLongLastName'
        };
        Auth.UserData userData = createUserData('VeryLongFirstName', 'VeryLongLastName', 'longname@'+getUserDomain(), claims);

        Test.startTest();
        User result = handler.createUser(null, userData);
        Test.stopTest();

        System.assertNotEquals(null, result);
        System.assertEquals('longname@'+getUserDomain(), result.Email);
        System.assert(result.Alias.length() <= 8, 'Alias should be 8 characters or less');
    }
    @isTest
    static void testProcessUserWithMultipleClaims() {
        OIDCRegistrationHandlerToFlow handler = new OIDCRegistrationHandlerToFlow();
        Map<String, Object> claims = new Map<String, Object>{
            'sub' => '44444',
            'email' => 'claims@'+getUserDomain(),
            'given_name' => 'Claims',
            'family_name' => 'Test',
            'groups' => new List<String>{'Group1', 'Group2', 'Group3'},
            'custom_claim' => 'custom_value',
            'another_claim' => 12345
        };
        Auth.UserData userData = createUserData('Claims', 'Test', 'claims@'+getUserDomain(), claims);

        Test.startTest();
        User result = handler.createUser(null, userData);
        Test.stopTest();

        System.assertNotEquals(null, result);
        System.assertEquals('claims@'+getUserDomain(), result.Email);
    }
    @isTest
    static void testCreateUserWithEmptyGroupString() {
        OIDCRegistrationHandlerToFlow handler = new OIDCRegistrationHandlerToFlow();
        Map<String, Object> claims = new Map<String, Object>{
            'sub' => '55555',
            'email' => 'emptygroup@'+getUserDomain(),
            'given_name' => 'Empty',
            'family_name' => 'Group',
            'groups' => ''
        };
        Auth.UserData userData = createUserData('Empty', 'Group', 'emptygroup@'+getUserDomain(), claims);

        Test.startTest();
        User result = handler.createUser(null, userData);
        Test.stopTest();

        System.assertNotEquals(null, result);
    }
}
