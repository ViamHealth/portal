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
    /*$res = $this->apiCall('get','goals/weight/');
    $weight = $res->results;
    $model_weight_url = '';
    if($res->count > 0 )
    {
      $model_weight_url = $res->results[0]->url;
    }*/ 
    $this->render('index',
      array(
        //'model_weight_url'=>$model_weight_url, 
        'profile_id'=>Yii::app()->user->id
        )
      );
	}  
}