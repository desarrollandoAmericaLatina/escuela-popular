<?php
/**
 * Policy class to determine if the user can login
 *
 * @package   ACL
 * @author    Oleh Burkhay <atma@atmaworks.com>
 */
class Policy_Login extends Policy
{
	const LOGGED_IN = 1;

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
        if (Auth::instance()->logged_in())
		{
			return self::LOGGED_IN;
		}
		elseif ($user->has('roles', Policy::$roles['login']))
		{
			return TRUE;
		}
		
		return FALSE;
	}
}