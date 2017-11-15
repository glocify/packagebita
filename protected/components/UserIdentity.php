<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public $userType = 'Front';
	public function authenticate()
	{
		if($this->userType=='Front') // This is front login
        {
		$record = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_Users')
					->where(array('like', 'login_email', "$this->username"))
					->queryRow();
		//echo "<pre>";print_r($record);echo "</pre>";die;
		
		$users=array(
			// username => password
			$record['login_email'] => $record['password'],
			
		);
		
		if(!isset($users[$this->username]))
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		elseif($users[$this->username]!==md5($this->password))
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else
		{
			Yii::app()->session['userid'] = $record['id'];
			
			$this->errorCode=self::ERROR_NONE;
		}
			
		//echo $this->errorCode;		
		//die();
		return !$this->errorCode;
	}
	if($this->userType=='Back')// This is admin login
        {
			$record = Yii::app()->db->createCommand()
					->select('*')
					->from('cads_Users')
					->where(array('like', 'login_email', "$this->username"))
					->andWhere(array('like', 'role', "admin"))
					->queryRow();
		//echo "<pre>";print_r($record);echo "</pre>";die;
		
		$users=array(
			// username => password
			$record['login_email'] => $record['password'],
			
		);
		
		if(!isset($users[$this->username]))
		{
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		elseif($users[$this->username]!==md5($this->password))
		{
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else
		{
			Yii::app()->session['userid'] = $record['id'];
			Yii::app()->session['userrole'] = $record['role'];
			
			$this->errorCode=self::ERROR_NONE;
		}
			
		//echo $this->errorCode;		
		//die();
		return !$this->errorCode;
		}
		
	}
	
	public function getId()
    {
		
        return $this->_id;
    }
}
