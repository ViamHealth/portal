<?php

class FooddiaryController extends Controller
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

  public function loadModel($id)
  {
      $model = Healthfile::model()->find(array(
      	'condition'=>'id=:id AND user_id=:profile_id',
      	'params'=>array(':id'=>$id,':profile_id'=>$this->getCurrentUserId())
      	)
      );
      if ($model === null)
          throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
  }

	public function actionIndex()
	{	
		$this->render('diary');
	}

  public function actionGetdiary($type)
  {
    $res = $this->apiCall('get',"diet-tracker/?meal_type=$type");
    echo json_encode($res);
  }

  public function actionGetfooditem($id)
  {
    $res = $this->apiCall('get',"food-items/".$id."/");
    echo json_encode($res);
  }

  public function actionSavefooditem()
  {
    $res = $this->apiCall('post',"diet-tracker/",array(
      'food_item'=>$_POST['food_item_id'],
      'food_quantity_multiplier'=>$_POST['food_quantity_multiplier'],
      'meal_type'=>$_POST['meal_type'],
      ));
    echo json_encode($res);
  }

  public function actionSearchfooditem($str)
  {
    $res = $this->apiCall('get',"food-items/search/".$str."/");
    echo json_encode($res);
  }

}