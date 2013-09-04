<?php

class HealthwatchController extends Controller
{
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
    $this->render('index',
            array(
        //'model_weight_url'=>$model_weight_url, 
        'profile_id'=>Yii::app()->user->id
        ));
	}  
}