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
    //Load User goal ids with type
    $weightGoalId= UserWeightGoal::model()->active()->find(array(
        'select'=>'id',
        'condition'=>'user_id=:profile_id',
        'params'=>array(':profile_id'=>Yii::app()->user->id)
        )
      );
    //Make ENUM for goal type. Consider abstracting the common goal properties
    if($weightGoalId != null )$goalsIdArr['weight'] = $weightGoalId->id;
		$this->render('index',array( 'profile_id'=>Yii::app()->user->id));
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