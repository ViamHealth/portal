<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * @var User $user user model that we will get by email/username
	 */
	public $user;

	public function __construct($username,$password=null)
	{
		// sets username and password values
		parent::__construct($username,$password);

		$this->user = AuthUser::model()->find('LOWER(username)=?',array(strtolower($this->username)));
		if($password === null)
		{
			/**
			 * you can set here states for user logged in with oauth if you need
			 * you can also use hoauthAfterLogin()
			 * @link https://github.com/SleepWalker/hoauth/wiki/Callbacks
			 */
			$this->errorCode=self::ERROR_NONE;
		}
	}

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate() 
	{
		$res = VApi::fetchToken(
			array(
				'username'=>$this->username,
				'password'=>$this->password
				)
		);
		if(isset($res->non_field_erros)){
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else if(isset($res->token)){
			$this->errorCode=self::ERROR_NONE;
			$this->user->_token = $res->token;
			Yii::app()->user->setState('token', $this->user->_token);
			
			$res = VApi::apiCall('get','users/me/');

			$this->user->setAttribute('email',$res->username);
			$this->user->setAttribute('name',$res->username);

			Yii::app()->user->setState('username', $this->user->username);
			Yii::app()->user->setState('url', Yii::app()->params['apiBaseUrl'],'users/'.$this->user->id.'/');
		} else {
			
		}
		/*
		if ($this->user === null)
			$this->errorCode = self::ERROR_USERNAME_INVALID;
		elseif (!$this->user->validatePassword($this->password))
			$this->errorCode = self::ERROR_PASSWORD_INVALID;
		else 
		{
			$this->errorCode = self::ERROR_NONE;
		}
		*/
		return $this->errorCode == self::ERROR_NONE;
	}

	public function getId()
	{
		return $this->user->id;
	}

	public function getName()
	{
		return $this->user->email;
	}
}