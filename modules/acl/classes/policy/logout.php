<?php
/**
 * Policy class to determine if the user can logout
 *
 * @package   ACL
 * @author    Oleh Burkhay <atma@atmaworks.com>
 */
class Policy_Logout extends Policy
{
	const NOT_LOGGED_IN   = 1;
	const NOT_ACTIVE_USER = 2;

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
        if (! Auth::instance()->logged_in())
		{
			return self::NOT_LOGGED_IN;
		}
		elseif ($user->id != Auth::instance()->get_user()->id)
		{
			return self::NOT_ACTIVE_USER;
		}
		else

		return TRUE;
	}
}