<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	private $_url;
	private $_token;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
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
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		}
		else if(isset($res->token)){
			$this->errorCode=self::ERROR_NONE;
			$this->_token = $res->token;
			Yii::app()->params->token = $this->_token;
			$user_id = $res->user_id;
			$rest->initialize(array('server' => 'http://127.0.0.1:8080/'));
			$rest->set_header('Authorization','Token '.$this->_token);
			$res = $rest->get('users/'.$user_id);
			//TOD:check for status code
			$res = json_decode($res);
			$this->_id = $user_id;
		}

		/*$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);
		if(!isset($users[$this->username]))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		elseif($users[$this->username]!==$this->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
			$this->errorCode=self::ERROR_NONE;*/
		return !$this->errorCode;
	}

	public function getId()
    {
        return $this->_id;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function getToken()
    {
        return $this->_token;
    }
}