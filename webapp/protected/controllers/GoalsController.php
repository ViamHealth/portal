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

  private function getRestObject()
  {
    $rest = new RESTClient();
    $rest->initialize(array('server' => 'http://127.0.0.1:8080/'));
    $rest->set_header('Authorization','Token '.Yii::app()->user->token);
    return $rest;
  }

	public function actionIndex()
	{
    $UserGoalReadingModel = UserWeightReading::model();
		$this->render('index',array(
      'profile_id'=>Yii::app()->user->id,
      'UserGoalReadingModel'=>$UserGoalReadingModel,
      )
    );
	}  

  public function actionGetweightgoal()
  {
    $rest = $this->getRestObject();
    $res = $rest->get('goals/weight/');
    //Set User Data
    $res = json_decode($res);
    $goal = $res->results;
    $count = $res->count;
    echo json_encode($res);
  }

  public function actionAddreading()
  {
    $rest = $this->getRestObject();
    $res = $rest->post('goals/weight/2/set-reading/',
      array(
        'weight'=>$_POST['UserWeightReading']['weight'],
        'reading_date'=>$_POST['UserWeightReading']['reading_date'],
      )
    );
    $this->redirect(array('index'));
  }

}