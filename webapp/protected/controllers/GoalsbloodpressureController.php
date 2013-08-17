<?php
class GoalsbloodpressureController extends Controller
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

  public function actionAdd()
  {
    $model = UserBloodPressureGoal::model();

    if(isset($_POST['UserBloodPressureGoal'])){
      $attributes = $_POST['UserBloodPressureGoal'];
      $model->attributes = $attributes;
      if($model->save(true,$attributes)){
        $this->redirect(array('goalsbloodpressure/index'));
      }
    }
    $this->render('_edit',array(
      'profile_id'=>Yii::app()->user->id,
      'model'=>$model
      )
    );
  }

  public function actionGetgoal()
  {
    $res = $this->apiCall('get','blood-pressure-goals/');
    echo json_encode($res);
  }

  public function actionSetreading()
  {

    $post = $_POST['UserBloodPressureReading'];
    $id =  addslashes($post['user_blood_pressure_goal_id']);
    $post_data = array(
      "systolic_pressure"=>addslashes($post['systolic_pressure']),
      "diastolic_pressure"=>addslashes($post['diastolic_pressure']),
      "systolic_pressure"=>addslashes($post['systolic_pressure']),
      "pulse_rate"=>addslashes($post['pulse_rate']),
      "reading_date"=>addslashes($post['reading_date']),
    );
    $res = VApi::apiCall('post','blood-pressure-goals/'.$id.'/set-reading/', $post_data);
    $this->redirect(array('index'));
  }

  public function actionIndex()
  {
    //TODO: Optimize the no. of calls
    
    $res = $this->apiCall('get','blood-pressure-goals/');
    $goal = $res->results;
    

    if($res->count > 0 ){
      $user_blood_pressure_goal_id = $res->results[0]->id;
      $model = UserBloodPressureReading::model();
      $this->render('index',array(
        'profile_id'=>Yii::app()->user->id,
        'model'=>$model,
        'user_blood_pressure_goal_id'=>$user_blood_pressure_goal_id,
        )
      );
    } else {
      $this->redirect(array('add'));
    }
  }
}