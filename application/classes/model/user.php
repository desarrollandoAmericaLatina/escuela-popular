<?php 

/**
 * Model_User class extended to implement the methods needed to 
 * handle ACL policies
 * 
 * @author Oleh Burkhay <atma@atmaworks.com>
 */
class Model_User extends Useradmin_Model_User implements Model_ACL_User {

    /**
     * A user has many tokens, roles and identities
     *
     * @var array Relationhips
     */
    protected $_has_many = array(
        // auth
        'roles' => array('through' => 'roles_users'),
        'user_tokens' => array(),
        // for facebook / twitter / google identities
        'user_identity' => array(),
        'school_rating' => array(),
    );
    
    protected $_created_column = array('column' => 'created', 'format' => 'Y-m-d H:i:s');

    protected $_updated_column = array('column' => 'modified', 'format' => 'Y-m-d H:i:s');
    
    /**
	 * Labels for fields in this model
	 *
	 * @return array Labels
	 */
	public function labels()
	{
		return array(
			'username'         => 'username',
			'email'            => 'email address',
			'password'         => 'password',
		);
	}
    
    /**
	 * Override basic transcribe allowing all unicode letters
	 * 
	 * @param string $string
	 * @return string
	 */
	function transcribe($string) 
	{
		$string = preg_replace("/([^\p{L}\.]+)/u", "", $string);
		return($string);
	}
    
    /**
    * Wrapper method to execute ACL policies. Only returns a boolean, if you
    * need a specific error code, look at Policy::$last_code
    * @param string $policy_name the policy to run
    * @param array $args arguments to pass to the rule
    * @return boolean
    */
    public function can($policy_name, $args = array())
    {
        $status = FALSE;
        try
        {
            $refl = new ReflectionClass('Policy_' . ucfirst($policy_name));
            $class = $refl->newInstanceArgs();
            $status = $class->execute($this, $args);
            if (TRUE === $status)
                return TRUE;
        }
        catch (ReflectionException $ex) // try and find a message based policy
        {
            // Try each of this user's roles to match a policy
            foreach ($this->roles->find_all() as $role)
            {
                $status = Kohana::message('policy', $policy_name.'.'.$role->id);
                if ($status)
                    return TRUE;
            }
        }
        // We don't know what kind of specific error this was
        if (FALSE === $status)
        {
            $status = Policy::GENERAL_FAILURE;
        }
        Policy::$last_code = $status;
        return TRUE === $status;
    }

    /**
    * Wrapper method for self::can() but throws an exception instead of bool
    * @param string $policy_name the policy to run
    * @param array $args arguments to pass to the rule
    * @throws Policy_Exception
    * @return null
    */
    public function assert($policy_name, $args = array())
    {
        $status = $this->can($policy_name, $args);
        if (TRUE !== $status)
        {
            throw new Policy_Exception(
                'Could not authorize policy :policy',
                array(':policy' => $policy_name),
                Policy::$last_code
            );
        }
    }   
}