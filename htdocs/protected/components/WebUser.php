<?php

class WebUser extends CWebUser
{

    /**
     * @var boolean whether to enable cookie-based login. Defaults to false.
     */
    public $allowAutoLogin=true;
    /**
     * @var string|array the URL for login. If using array, the first element should be
     * the route to the login action, and the rest name-value pairs are GET parameters
     * to construct the login URL (e.g. array('/site/login')). If this property is null,
     * a 403 HTTP exception will be raised instead.
     * @see CController::createUrl
     */
    public $loginUrl=array('/site/login');

    public function getRole()
    {
        return $this->getState('__role');
    }
    
    public function getId()
    {
        return $this->getState('__id') ? $this->getState('__id') : 0;
    }

//    protected function beforeLogin($id, $states, $fromCookie)
//    {
//        parent::beforeLogin($id, $states, $fromCookie);
//
//        $model = new UserLoginStats();
//        $model->attributes = array(
//            'user_id' => $id,
//            'ip' => ip2long(Yii::app()->request->getUserHostAddress())
//        );
//        $model->save();
//
//        return true;
//    }

    public function login($identity,$duration=0)
    {
        $id=$identity->getId();
        $states=$identity->getPersistentStates();
        if($this->beforeLogin($id,$states,false))
        {
            $this->changeIdentity($id,$identity->getName(),$states);

            if($duration>0)
            {
                if($this->allowAutoLogin)
                    $this->saveToCookie($duration);
                else
                    throw new CException(Yii::t('yii','{class}.allowAutoLogin must be set true in order to use cookie-based authentication.',
                        array('{class}'=>get_class($this))));
            }

            $this->afterLogin(false);
        }
        return !$this->getIsGuest();
    }

    protected function afterLogin($fromCookie)
	{
        parent::afterLogin($fromCookie);
        $this->updateSession();
	}

    public function updateSession() {
        //$user = Yii::app()->user($this->id);
        $rest = new RESTClient();
        $rest->initialize(array('server' => 'http://127.0.0.1:8080/'));
        $rest->set_header('Authorization','Token '.Yii::app()->params->token);
        $res = $rest->get('users/'.$this->id);
        $user = json_decode($res);
        $this->name = $user->username;
        $userAttributes = CMap::mergeArray(array(
                                                'email'=>$user->email,
                                                'username'=>$user->username,
                                                'url'=>$user->url,
                                                //'create_at'=>$user->create_at,
                                                //'lastvisit_at'=>$user->lastvisit_at,
                                                ), array());
                                           //),$user->profile->getAttributes());
        foreach ($userAttributes as $attrName=>$attrValue) {
            $this->setState($attrName,$attrValue);
        }
    }

    public function model($id=0) {
        return Yii::app()->user($id);
    }

    public function user($id=0) {
        return $this->model($id);
    }

    public function getUserByName($username) {
        return Yii::app()->getUserByName($username);
    }

    public function getAdmins() {
        return Yii::app()->getAdmins();
    }

    public function isAdmin() {
        return Yii::app()->isAdmin();
    }

}