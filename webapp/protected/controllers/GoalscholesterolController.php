<?php
class GoalscholesterolController extends Controller
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
    $model = UserCholesterolGoal::model();

    if(isset($_POST['UserCholesterolGoal'])){
      $attributes = $_POST['UserCholesterolGoal'];
      $model->attributes = $attributes;
      if($model->save(true,$attributes)){
        $this->redirect(array('goalscholesterol/index'));
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
    $res = $this->apiCall('get','cholesterol-goals/');
    echo json_encode($res);
  }

  public function actionSetreading()
  {

    $post = $_POST['UserCholesterolReading'];
    $id =  addslashes($post['user_cholesterol_goal_id']);
    $post_data = array(
      "hdl"=>addslashes($post['hdl']),
      "ldl"=>addslashes($post['ldl']),
      "triglycerides"=>addslashes($post['triglycerides']),
      "total_cholesterol"=>addslashes($post['total_cholesterol']),
      "reading_date"=>addslashes($post['reading_date']),
    );
    $res = VApi::apiCall('post','cholesterol-goals/'.$id.'/set-reading/', $post_data);
    $this->redirect(array('index'));
  }

  public function actionIndex()
  {
    //TODO: Optimize the no. of calls
    
    $res = $this->apiCall('get','cholesterol-goals/');
    $goal = $res->results;
    

    if($res->count > 0 ){
      $user_cholesterol_goal_id = $res->results[0]->id;
      $model = UserCholesterolReading::model();
      $this->render('index',array(
        'profile_id'=>Yii::app()->user->id,
        'model'=>$model,
        'user_cholesterol_goal_id'=>$user_cholesterol_goal_id,
        )
      );
    } else {
      $this->redirect(array('add'));
    }
  }
}