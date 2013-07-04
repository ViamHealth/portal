<?php

class HealthmonitorController extends Controller
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
		//$HealthfileModel=Healthfile::model();
    $HealthfileModel = '';
    //Load User goal ids with type
		$this->render('index',array('HealthfileModel'=>$HealthfileModel, 'profile_id'=>Yii::app()->user->id));
	}

  public function actionLoadChildByAjax($index)
  {
      $model = new HealthfileTag;
      $this->renderPartial('_form_healthfileTag', array(
          'model' => $model,
          'index' => $index,
      ));
  }

}