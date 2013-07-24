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
    //TODO: Optimize the no. of calls
    $rest = $this->getRestObject();
    $res = $rest->get('goals/weight/');
    //Set User Data
    $res = json_decode($res);
    $goal = $res->results;
    if($res->count > 0 ){
      $UserGoalReadingModel = UserWeightReading::model();
      $this->render('index',array(
        'weight_view' => '_view_weight',
        'profile_id'=>Yii::app()->user->id,
        'wModel'=>$UserGoalReadingModel,
        )
      );
    } else {
      $UserWeightGoalModel = UserWeightGoal::model();
  		$this->render('index',array(
        'weight_view' => '_edit_weight',
        'profile_id'=>Yii::app()->user->id,
        'wModel'=>$UserWeightGoalModel
        )
      );
    }
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