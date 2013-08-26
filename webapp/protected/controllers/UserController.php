<?php

class UserController extends Controller
{
	//TODO: Current Profile being watched. points to logged in user for now


	public function filters()
  {
      return array( 'accessControl' ); // perform access control for CRUD operations
  }

  public function accessRules()
  {
      return array(
          array('allow', // allow authenticated users to access all actions
              'users'=>array('@'),
          ),
          array('deny'),
      );
  }

  

	public function actionIndex()
	{
		$this->render('index',array('profile_id'=>$this->getCurrentUserId()));
	}

  public function actionAdd()
  {
    $this->render('add',array('profile_id'=>$this->getCurrentUserId()));  
  }
	

}