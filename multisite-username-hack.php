<?php
/*
 Plugin Name: Multisite Username Hack
 Plugin URI:
 Description: Lifts restrictions on multisite username sanitisation to include email
 Version: 1.0.0
 Author: EverydayPublishing
 Author URI: https://www.everydaypublishing.com.au
*/

/*
 *	@param array $result
 *	@return array
 */
		   
function ep_multisite_username_hack ($result) {
	
			// check for user_name errors, and that we have a user_name
	if ($result['user_name'] && isset($result['errors']->errors['user_name'])) {
		
			// get just the user_name errors
		$errors = $result['errors']->errors['user_name'];
		
			// make sure it's a character validation error, and no others
		if (count($errors) === 1 && $errors[0] === __('Usernames can only contain lowercase letters (a-z) and numbers.')) {
			
				// get a clean user_name again
			$user_name = preg_replace('/\s+/', '', sanitize_user($result['user_name'], true));
			
				// make sure user_name is an email address
			if (is_email($user_name)) {
					// set clean user_name and clear the user_name error
				$result['user_name'] = $user_name;
				$result['errors']->remove('user_name');
			}
		}
	}
	return $result;
}
	
add_filter('wpmu_validate_user_signup', 'ep_multisite_username_hack');
?>
