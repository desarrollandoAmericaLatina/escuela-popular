<?php defined('SYSPATH') or die('No direct script access.');
/**
 * General app template container
 *
 * @package    Application
 * @author     Oleh Burkhay <atma@atmaworks.com>
 */
class Controller_Core extends Controller_Twig {

    public $template_file = 'welcome';
	/**
	 * Controls access for the whole controller, if not set to FALSE we will only allow user roles specified
	 *
	 * Can be set to a string or an array, for example array('login', 'admin') or 'login'
	 */
	public $auth_required = FALSE;

	/** Controls access for separate actions
	 * 
	 * Examples:
	 * 'adminpanel' => 'admin' will only allow users with the role admin to access action_adminpanel
	 * 'moderatorpanel' => array('login', 'moderator') will only allow users with the roles login and moderator to access action_moderatorpanel
	 */
	public $secure_actions = FALSE;

    protected $session;
    
	/**
	 * Called from before() when the user does not have the correct rights to access a controller/action.
	 *
	 * Override this in your own Controller if you need to handle
	 * responses differently.
	 *
	 * For example:
	 * - handle JSON requests by returning a HTTP error code and a JSON object
	 * - redirect to a different failure page from one part of the application
	 */
	public function access_required()
	{
		$this->request->redirect('user/noaccess');
	}

	/**
	 * Called from before() when the user is not logged in but they should.
	 *
	 * Override this in your own Controller.
	 */
	public function login_required()
	{
		$this->request->redirect('user/login');
	}

	/**
	 * The before() method is called before your controller action.
	 * In our template controller we override this method so that we can
	 * set up default values. These variables are then available to our
	 * controllers if they need to be modified.
	 *
	 * @return  void
	 */
	public function before()
	{
		// This codeblock is very useful in development sites:
		// What it does is get rid of invalid sessions which cause exceptions, which may happen
		// 1) when you make errors in your code.
		// 2) when the session expires!
		try
		{
			$this->session = Session::instance();
		}
		catch (ErrorException $e)
		{
			session_destroy();
		}
		// Execute parent::before first
		parent::before();
		// Open session
		$this->session = Session::instance();

        //if we're not logged in, but auth type is orm. gives us chance to auto login
        $supports_auto_login = new ReflectionClass(get_class(Auth::instance()));
        $supports_auto_login = $supports_auto_login->hasMethod('auto_login');
        if(!Auth::instance()->logged_in() && $supports_auto_login){
            Auth::instance()->auto_login();
        }

		// Check user auth and role
		$action_name = Request::current()->action();
		if 
		(
			// auth is required AND user role given in auth_required is NOT logged in
			( $this->auth_required !== FALSE && Auth::instance()->logged_in($this->auth_required) === FALSE ) ||
			// OR secure_actions is set AND the user role given in secure_actions is NOT logged in
			( is_array($this->secure_actions) && array_key_exists($action_name, $this->secure_actions) && Auth::instance()->logged_in($this->secure_actions[$action_name]) === FALSE )
		)
		{
			if (Auth::instance()->logged_in())
			{
				// user is logged in but not on the secure_actions list
				$this->access_required();
			}
			else
			{
				$this->login_required();
			}
		}
		if ($this->auto_render)
		{
			// ControllerName will contain the name of the Controller in the Template
			$this->template->controllerName = $this->request->controller();
			// ActionName will contain the name of the Action in the Template
			$this->template->actionName = $this->request->action();
            
            $this->template->base_url = $this->base_url = URL::base();
            // retrieve the current user and set the view variable accordingly
            $this->template->user = $this->user = Auth::instance()->get_user();
		}
	}

	/**
	 * The after() method is called after your controller action.
	 * In our template controller we override this method so that we can
	 * make any last minute modifications to the template before anything
	 * is rendered.
	 */
	public function after()
	{
		return parent::after();
	}
    
        /*
     * 
     */
    public function set_return_url()
    {
        $baseUrl = Url::base(true);
        if(substr($this->request->referrer(),0,strlen($baseUrl)) == $baseUrl){
            $urlPath = ltrim(parse_url($this->request->referrer(),PHP_URL_PATH),'/');
            $processedRef = Request::process_uri($urlPath);
            $referrerController = Arr::path(
                $processedRef,
                'params.controller',
                false
            );
            if($referrerController && $referrerController != 'user' && !Session::instance()->get('noReturn',false)){
                Session::instance()->set('returnUrl',$this->request->referrer());
            }
        }
    }
}
