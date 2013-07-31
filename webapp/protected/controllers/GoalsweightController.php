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
    $UserWeightGoalModel = UserWeightGoal::model();
    $this->renderPartial('index',array(
      'weight_view' => '_edit_weight',
      'profile_id'=>Yii::app()->user->id,
      'wModel'=>$UserWeightGoalModel
      )
    );
  }

  public function actionGetweightgoal()
  {
    $res = $this->apiCall('get','goals/weight/');
    echo json_encode($res);
  }

  public function actionSetreading()
  {
    $post = $_POST['UserWeightReading'];
    $id =  addslashes($post['user_weight_goal_id']);
    $post_data = array(
      "weight"=>addslashes($post['weight']),
      "reading_date"=>addslashes($post['reading_date']),
    );
    $res = $this->apiCall('post','goals/weight/'.$id.'/set-reading/', $post_data);
    if($res->status == 'reading set')
      $this->redirect(array('index'));
    else echo "error";
  }

  public function actionIndex()
  {
    //TODO: Optimize the no. of calls
    
    $res = $this->apiCall('get','goals/weight/');
    $goal = $res->results;
    $user_weight_goal_id = $res->results[0]->id;

    if($res->count > 0 ){
      $UserGoalReadingModel = UserWeightReading::model();
      $this->render('index',array(
        'profile_id'=>Yii::app()->user->id,
        'model'=>$UserGoalReadingModel,
        'user_weight_goal_id'=>$user_weight_goal_id,
        )
      );
    } else {
      
    }
  }
}