<?php

class HealthfilesController extends Controller
{
	public function actionIndex()
	{
		$HealthfileModel=Healthfile::model();
		//TODO: Current Profile being watched. Hard Coded for now
		$profile_id = 5;

		$this->render('index',array('HealthfileModel'=>$HealthfileModel, 'profile_id'=>$profile_id));
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
    
	public function loadModel($id)
  {
      $model = Healthfile::model()->findByPk($id);
      if ($model === null)
          throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
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