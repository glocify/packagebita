<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $login_email;
	public $password;
	public $rememberMe;

	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('login_email, password', 'required'),
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	/*public function attributeLabels()
	{
		return array(
			'rememberMe'=>'Remember me next time',
		);
	}*/

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 */
	public function authenticate($attribute,$params)
	{
		//die("dd");
		if(!$this->hasErrors())
		{
			
			$this->_identity=new UserIdentity($this->login_email,$this->password);
			if(!$this->_identity->authenticate())
				$this->addError('password','Incorrect username or password.');
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		//print_r($_POST);die;
		
			$this->_identity=new UserIdentity($this->login_email,$this->password);
			
			$this->_identity->authenticate();
			
		
		//echo $this->_identity->errorCode;die;
		//ECHO "<BR>";
		//echo UserIdentity::ERROR_NONE;
		//DIE;
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			
			return true;
		}
		else
			return false;
	}
}