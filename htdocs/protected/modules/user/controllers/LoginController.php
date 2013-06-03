<?php

class LoginController extends Controller
{
	public $defaultAction = 'login';


	public function actions()
	{
	  return array(
	    'oauth' => array(
	      'class'=>'ext.hoauth.HOAuthAction',
	        // Yii alias for your user's model, or simply class name, when it already on yii's import path
	        // default value of this property is: User
	        'model' => 'User', 
	        // map model attributes to attributes of user's social profile
	        // model attribute => profile attribute
	        // the list of avaible attributes is below
	        'attributes' => array(
	          'email' => 'email',
	          'fname' => 'firstName',
	          'lname' => 'lastName',
	          'gender' => 'genderShort',
	          'birthday' => 'birthDate',
	          // you can also specify additional values, 
	          // that will be applied to your model (eg. account activation status)
	          'acc_status' => 1,
			),	      
	    ),
	    'oauthadmin' => array(
	      'class'=>'ext.hoauth.HOAuthAdminAction',
	    ),
	  );
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		if (Yii::app()->user->isGuest) {
			$model=new UserLogin;
			// collect user input data
			if(isset($_POST['UserLogin']))
			{
				$model->attributes=$_POST['UserLogin'];
				// validate user input and redirect to previous page if valid
				if($model->validate()) {
					$this->lastViset();
					if (Yii::app()->getBaseUrl()."/index.php" === Yii::app()->user->returnUrl)
						$this->redirect(Yii::app()->controller->module->returnUrl);
					else
						$this->redirect(Yii::app()->user->returnUrl);
				}
			}
			// display the login form
			$this->render('/user/login',array('model'=>$model));
		} else
			$this->redirect(Yii::app()->controller->module->returnUrl);
	}
	
	private function lastViset() {
		$lastVisit = User::model()->notsafe()->findByPk(Yii::app()->user->id);
		$lastVisit->lastvisit_at = date('Y-m-d H:i:s');
		$lastVisit->save();
	}

}