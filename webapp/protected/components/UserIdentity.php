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
		$rest = new RESTClient();
		#$rest->initialize(array('server' => Yii::app()->params->apiServerHost.':'.Yii::app()->params->apiServerPort));
		$rest->initialize(array('server' => 'http://127.0.0.1:8080/'));
		$res = $rest->post('api-token-auth/',
			array(
				'username'=>$this->username,
				'password'=>$this->password
				)
			);
		//TOD:check for status code
		$res = json_decode($res);

		if(isset($res->non_field_erros)){
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		}
		else if(isset($res->token)){
			$this->errorCode=self::ERROR_NONE;
			$this->user->_token = $res->token;
			$rest->initialize(array('server' => 'http://127.0.0.1:8080/'));
			$rest->set_header('Authorization','Token '.$this->user->_token);
			$res = $rest->get('users/me/');
			//Set User Data
			$res = json_decode($res);
			$this->user->setAttribute('email',$res->username);
			$this->user->setAttribute('name',$res->username);
			Yii::app()->user->setState('username', $this->user->username);
			Yii::app()->user->setState('token', $this->user->_token);
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