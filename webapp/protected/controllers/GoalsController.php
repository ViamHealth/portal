<?php

class GoalsController extends Controller
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
		$this->render('index',array('profile_id'=>Yii::app()->user->id));
	}  

  public function actionGetweightgoal()
  {
    $rest = new RESTClient();
    $rest->initialize(array('server' => 'http://127.0.0.1:8080/'));
    $rest->set_header('Authorization','Token '.Yii::app()->user->token);
    $res = $rest->get('goals/weight/');
    //Set User Data
    $res = json_decode($res);
    $goal = $res->results;
    $count = $res->count;
    echo json_encode($res);
  }

}