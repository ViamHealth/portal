<?php

class HealthfilesController extends Controller
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
      	'params'=>array(':id'=>$id,':profile_id'=>Yii::app()->user->id)
      	)
      );
      if ($model === null)
          throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
  }

	public function actionIndex()
	{
		$HealthfileModel=Healthfile::model();
		$this->render('index',array('HealthfileModel'=>$HealthfileModel, 'profile_id'=>Yii::app()->user->id));
	}

//Temporary code. to be deleted
  public function actionApiIndex()
  {
    $rest = new RESTClient();
    $rest->initialize(array('server' => 'http://dev.viam.com/api/'));
    //$tweet = $rest->get('statuses/user_timeline/'.$username.'.xml');
    $res = $rest->get('healthfile');
    $json_res = json_decode($res);
    if($json_res->success == true){
      $hfArr = $json_res->data->healthfile;
    }
  }

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		
		if(isset($_POST['Healthfile'])){
			$model->attributes = $_POST['Healthfile'];
			if (isset($_POST['HealthfileTag']))
      {
      	//Need to clean input ??
          $healthfileTags = $_POST['HealthfileTag'];
          foreach($healthfileTags as $k=>$t){
          	$hft = new HealthfileTag();
          	$hft->tag = $t['tag'];
          	$hft_arr[] = $hft;
          }
          $model->healthfileTags = $hft_arr;
          $model->saveWithRelated('healthfileTags');
      }
			if($model->save())
				$this->redirect(array('index'));
		}
		
		$this->render('update',array('model'=>$model));
	}

	public function actionDelete($id)
  {
      $this->loadModel($id)->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax']))
          $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
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