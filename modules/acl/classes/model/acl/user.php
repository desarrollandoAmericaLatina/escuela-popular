<?php
/**
 * ACL enabled user model class
 *
 * @package   ACL
 * @author    Oleh Burkhay <atma@atmaworks.com>
 */
interface Model_ACL_User
{
	/**
	 * Wrapper method to execute ACL policies. Only returns a boolean, if you
	 * need a specific error code, look at Policy::$last_code
	 * 
	 * @param string $policy_name the policy to run
	 * @param array  $args        arguments to pass to the rule
	 *
	 * @return boolean
	 */
	public function can($policy_name, $args = array());

	/**
	 * Wrapper method for self::can() but throws an exception instead of bool
	 * 
	 * @param string $policy_name the policy to run
	 * @param array  $args        arguments to pass to the rule
	 * 
	 * @throws Policy_Exception
	 *
	 * @return null
	 */
	public function assert($policy_name, $args = array());
}