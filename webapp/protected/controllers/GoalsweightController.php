<?php
class GoalsweightController extends Controller
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
    $model = UserWeightGoal::model();

    if(isset($_POST['UserWeightGoal'])){
      $attributes = $_POST['UserWeightGoal'];
      $model->attributes = $attributes;
      if($model->save(true,$attributes)){
        $this->redirect(array('goalsweight/index'));
      }
    }
    $this->render('_edit_weight',array(
      //'weight_view' => '_edit_weight',
      'profile_id'=>Yii::app()->user->id,
      'model'=>$model
      )
    );
  }

  public function actionGetweightgoal()
  {
    $res = $this->apiCall('get','weight-goals/');
    echo json_encode($res);
  }

  public function actionSetreading()
  {
    /*$model=UserWeightReading::model();
    if(isset($_POST['UserWeightReading'])){
      $attributes = $_POST['UserWeightReading'];
      //$attributes['status'] = 'ACTIVE';
      //$attributes['start_timestamp'] = strtotime($attributes['start_timestamp']);
      $model->attributes = $attributes;
      if($model->save(true,$attributes)){
        $this->redirect(array('goalsweight/index'));
      } else {
        $this->redirect(array('goalsweight/index'));
      }

    }*/

    $post = $_POST['UserWeightReading'];
    $id =  addslashes($post['user_weight_goal_id']);
    $post_data = array(
      "weight"=>addslashes($post['weight']),
      "reading_date"=>addslashes($post['reading_date']),
      "weight_measure"=>'METRIC',
    );
    $res = VApi::apiCall('post','weight-goals/'.$id.'/set-reading/', $post_data);
    $this->redirect(array('index'));
  }

  public function actionIndex()
  {
    //TODO: Optimize the no. of calls
    
    $res = $this->apiCall('get','weight-goals/');
    $goal = $res->results;
    

    if($res->count > 0 ){
      $user_weight_goal_id = $res->results[0]->id;
      $UserGoalReadingModel = UserWeightReading::model();
      $this->render('index',array(
        'profile_id'=>Yii::app()->user->id,
        'model'=>$UserGoalReadingModel,
        'user_weight_goal_id'=>$user_weight_goal_id,
        )
      );
    } else {
      $this->redirect(array('add'));
    }
  }
}