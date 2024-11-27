<?php
# Plugin Name: Azure Group to WordPress Roles
# Plugin URI:  
# Description: 
# Version:     20241127
# Author:      Danny Summerlin
# Author URI:  https://summerlin.co
# License:     GPL2
# License URI: https://www.gnu.org/licenses/gpl-2.0.html
# Text Domain: summerlinco

// plugin settings variables
$baseDomain = 'example.com';
$tenantId = 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxxxxxx';
// need to determine which hook is best, or else do it on per plugin basis
$hookLogin = 'wpo365/saml/authenticated ';
// $hookLogin = 'wp_saml_auth_pre_authentication';
$hookLoginNew = FALSE;
$credentials = parse_ini_file('/some/protected/dir/creds.ini', true);

function curl_call($url, $method = 'post', $data = NULL, array $options = array()) {
	$defaults = array(
		CURLOPT_HEADER => 0,
		CURLOPT_URL => $url,
		CURLOPT_RETURNTRANSFER => 1,
		CURLOPT_TIMEOUT => 4
	);
	if($method === 'post') {
		$defaults[CURLOPT_POSTFIELDS] = $data;
		$defaults[CURLOPT_POST] = 1;
	} elseif ($method === 'get') {
		$defaults[CURLOPT_URL] = $url;
		if($data != NULL)
			$defaults[CURLOPT_URL] .= (strpos($url, '?') === FALSE ? '?' : ''). http_build_query($data);
	}
	$ch = curl_init();
	curl_setopt_array($ch, ($options + $defaults));
	if( ! $result = curl_exec($ch)) { trigger_error(curl_error($ch)); }
	curl_close($ch);
	return $result;	
}
function curl_post($url, $post = NULL, array $options = array()) { return curl_call($url, 'post', $post, $options); }
function curl_get($url, array $get = NULL, array $options = array()) { return curl_call($url, 'get', $get, $options); }

function getAzureGroups($groupLink, $tenantId) {
	global $credentials;
	global $tenantId;
	// Get Azure Token for MS Graph
		$azureToken = get_transient('azureToken');
		if (false == $azureToken) {
			$tokenString = curl_post('https://login.microsoftonline.com/'.$tenantId.'/oauth2/v2.0/token', http_build_query(array(
				'client_id' => $credentials['MS_Graph']['client_id'],
				'client_secret' => $credentials['MS_Graph']['client_secret'],
				'scope' => 'https://graph.microsoft.com/.default',
				'grant_type' => 'client_credentials'
			)));
	
			if($tokenString !== '') {
				$tokenResponse = json_decode($tokenString);
				$azureToken = $tokenResponse->access_token;
				set_transient( 'azureToken', $azureToken, $tokenResponse->expires_in);
			}
		}
	// with token, get all groups
		if($azureToken) {
			if ( false === ( $azureGroups = get_transient( 'azureGroups' ) ) ) {
				$rawAzureGroupsResponse = curl_get('https://graph.microsoft.com/v1.0/groups?$select=id,displayName,onPremisesSamAccountName&$top=999&$filter=onPremisesSyncEnabled%20eq%20true', null, array(
					CURLOPT_HTTPHEADER => array(
						"Authorization: Bearer ".urlencode($azureToken),
						"Content-Type: application/json"
					)
				));
				$rawAzureGroups = json_decode($rawAzureGroupsResponse);
				$azureGroups = $rawAzureGroups->value;
				set_transient( 'azureGroups', $azureGroups, 12 * HOUR_IN_SECONDS);
			}
	// get users groups
			$url = str_replace("https://graph.windows.net", "https://graph.microsoft.com/v1.0", $groupLink);
			$url = str_replace('Objects', 'Groups', $url);
			$rawUserGroups = json_decode(curl_post($url, json_encode(array('securityEnabledOnly'=>true)), array(
				CURLOPT_HTTPHEADER => array(
					"Authorization: Bearer ".urlencode($azureToken),
					"Content-Type: application/json"
				)
			)));
			$groupIds = $rawUserGroups->value;
			$userGroups = array();
			$groupIdCount = count($groupIds);
			for($i=0;$i<$groupIdCount;$i++) {
				$g = $groupIds[$i];
				$group = array_filter($azureGroups, function($k) use ($g) {
					return ($k->id === $g);
				});
				if(count($group))
					array_push($userGroups, array_pop($group)->onPremisesSamAccountName);
			}
			return $userGroups;
		}
}

function mapGroupsToRoles($user, $rolesActiveDirectory) {
	$rolesActiveDirectory = $rolesActiveDirectory ?? array();
	$rolesActiveDirectory[] = 'subscriber';
	try {
		// downcase all role names
		array_walk($rolesActiveDirectory, function (&$a, $i) { $a = strtolower($a); });
		$rolesWordPress = $user->roles;
		$rolesAdd = array_diff($rolesActiveDirectory, $rolesWordPress);
		$rolesRemove = array_diff($rolesWordPress, $rolesActiveDirectory);
		foreach($rolesAdd as $adRole) {
			$wpRole = get_role($adRole);
			if(isset($wpRole)) {
				$user->add_role($adRole);
			} // add else create role and add AFTER we clean up AD roles on older staff
		}
		foreach($rolesRemove as $wpRole) { 
			$user->remove_role($wpRole);
		}
		wp_cache_delete($user->ID, 'users');
		wp_cache_delete($user->user_login, 'userlogins');
	} catch (Exception $e) {
		echo 'mapGroupsToRoles - Caught exception: ',  $e->getMessage(), "\n";
		wp_die();
	}
};

/**
 * Update user attributes after a user has logged in via SAML.
 * (from the plugin vendor themselves)
 */
function updateSSOUser($user, $attributes) {
	$user_args = array('ID' => $user->ID);
	try {
		if(is_array($attributes['http://schemas.microsoft.com/claims/groups.link']) && count($attributes['http://schemas.microsoft.com/claims/groups.link'])) {
			$azGroups = getAzureGroups($attributes['http://schemas.microsoft.com/claims/groups.link'][0], $tenantId);
			if(!empty($azGroups) && count($azGroups))
				mapGroupsToRoles($user, $azGroups);
		} else {
			mapGroupsToRoles($user, $attributes['http://schemas.microsoft.com/ws/2008/06/identity/claims/groups']);
		}
	} catch (Exception $e) {
		echo 'wp_saml_auth_existing_user_authenticated - Caught exception: ',  $e->getMessage(), "\n";
		wp_die();
	}
	$attributeTypes = array( 'display_name', 'first_name', 'last_name'); // dropped user_email because it appears to be incorrect right now
	foreach ( $attributeTypes as $type ) {
		$attribute          = \WP_SAML_Auth::get_option( "{$type}_attribute" );
		$user_args[ $type ] = ! empty( $attributes[ $attribute ][0] ) ? $attributes[ $attribute ][0] : '';
	}
	$custom_attributes = wp_get_user_contact_methods();
	foreach($custom_attributes as $attr => $label) {
		$user_args[ $attr ] = ! empty( $attributes[ $attr ][0] ) ? $attributes[ $attr ][0] : '';
	}
	wp_update_user( $user_args );
}
add_action( $hookLogin, 'updateSSOUser', 10, 2);
if($hookLoginNew) {
	add_action($hookLoginNew, 'updateSSOUser', 10, 2 );
}
// * Reject authentication if $attributes doesn't include the authorized group.
add_filter($hookLogin, function( $ret, $attributes ) {
	global $baseDomain;
	$username = $attributes['http://schemas.xmlsoap.org/ws/2005/05/identity/claims/name'][0];
	if ( !stristr($username, $baseDomain) ) {
		return new WP_Error( 'unauthorized-domain', "Please make sure to login with your $baseDomain account. (currently using $username)" );
	}
	return $ret;
}, 10, 2 );
add_filter( 'login_redirect', function ($url, $request, $user) {
	if(isset($_REQUEST) && isset($_REQUEST['RelayState']) && !stristr($_REQUEST['RelayState'], 'login.php')) {
		wp_redirect($_REQUEST['RelayState']);
		die;
	}
	return $url;
}, 10, 3 );
?>
