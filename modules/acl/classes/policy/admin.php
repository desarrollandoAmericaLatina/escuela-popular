<?php
/**
 * Policy class to determine if the user can login to admin panel
 *
 * @package   ACL
 * @author    Oleh Burkhay <atma@atmaworks.com>
 */
class Policy_Admin extends Policy
{
	/**
	 * Method to execute the policy
	 * 
	 * @param Model_ACL_User $user  the user account to run the policy on
	 * @param array          $extra an array of extra parameters that this policy
	 *                              can use
	 *
	 * @return bool/int
	 */
	public function execute(Model_ACL_User $user, array $extra = NULL)
	{
        if ($user->has('roles', Policy::$roles['login']) AND $user->has('roles', Policy::$roles['admin']))
		{
			return TRUE;
		}
		
		return FALSE;
	}
}