<?php

class RemindersController extends Controller
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
      $model = Reminder::model()->find(array(
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
		$ReminderModel=Reminder::model();
		$this->render('index',array('ReminderModel'=>$ReminderModel, 'profile_id'=>$this->getCurrentUserId()));
	}

  public function actionAdd()
  {
    
    $model=Reminder::model();
    if(isset($_POST['Reminder'])){
      $attributes = $_POST['Reminder'];
      $attributes['status'] = 'ACTIVE';
      $attributes['start_timestamp'] = strtotime($attributes['start_timestamp']);
      $model->attributes = $attributes;
      if($model->save(true,$attributes)){
        $this->redirect(array('reminders/index'));
      }
    }

    //$this->render('view',array('model'=>$model));
  }

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		if(isset($_POST['Reminder'])){
			$model->attributes = $_POST['Reminder'];
      $model->start_timestamp = strtotime($model->start_timestamp);
      if($model->save())
        $this->redirect(array('reminders/index'));
		}
    //TODO: design a model view helper module
    $model->start_timestamp = date('Y-m-d', $model->start_timestamp);
		$this->render('update',array('model'=>$model));
	}

  
	public function actionDelete($id)
  {
    $model = $this->loadModel($id);
    if($model->delete())
        $this->redirect(array('reminders/index'));
    /*
      $this->loadModel($id)->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax']))
          $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    */
  }

}