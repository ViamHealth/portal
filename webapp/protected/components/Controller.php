<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
	/**
	 * @var string the default layout for the controller view. Defaults to '//layouts/column1',
	 * meaning using a single column layout. See 'protected/views/layouts/column1.php'.
	 */
	public $layout='//layouts/column1';
	/**
	 * @var array context menu items. This property will be assigned to {@link CMenu::items}.
	 */
	public $menu=array();
	/**
	 * @var array the breadcrumbs of the current page. The value of this property will
	 * be assigned to {@link CBreadcrumbs::links}. Please refer to {@link CBreadcrumbs::links}
	 * for more details on how to specify this property.
	 */
	public $breadcrumbs=array();

    private $fuid;

    private $current_user_id;

    private $familyFetched = false;

    private $family = array();

	public function beforeAction($action)
    {
        Yii::app()->user->setReturnUrl(Yii::app()->request->getUrl());
        if (Yii::app()->user->isGuest ){
        	if(($this->id == "site" && $action->id == "login")){
        		//Login page
        	}
        	else{
        		if(Yii::app()->request->isAjaxRequest){
        			die("user not logged in");
        			//Handle ajax call
        		} else {
                	$this->redirect(Yii::app()->createUrl('site/login'));
            	}
            }
        }
        else
        {
            $this->fuid =  Yii::app()->request->getParam('fuid',null);
            $fuidIsFamily = false;
            if($this->fuid)
            {
                $family = $this->getFamilyUsers();
                foreach ($family as $key => $value) {
                    if($value->id == $this->fuid)
                        $fuidIsFamily = true;
                }
                if($fuidIsFamily == true)
                    $this->current_user_id = $this->fuid;
                else
                    $this->current_user_id = Yii::app()->user->id;   
            }
            else
            {
                $this->current_user_id = Yii::app()->user->id;   
            }
            
            //var_dump($family);
            //die();
        }
        //optionally include code here if its an authenticated user
        return true;
    }

    protected function apiCall($method, $url, $params =array())
    {
        return VApi::apiCall($method, $url, $params);
    }

    public function getCurrentUserId()
    {
        return $this->current_user_id;
    }

    public function getFamilyUsers()
    {
        if($this->familyFetched)
            return $this->family;

        $family = array();
        if (!Yii::app()->user->isGuest ){
            $family = VApi::apiCall('get', 'users/');
            $this->family = $family;
            $this->familyFetched = true;
        }

        return $family;
    }

}