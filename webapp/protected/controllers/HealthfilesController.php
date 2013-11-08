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
      	'params'=>array(':id'=>$id,':profile_id'=>$this->getCurrentUserId())
      	)
      );
      if ($model === null)
          throw new CHttpException(404, 'The requested page does not exist.');
      return $model;
  }

	public function actionIndex()
	{
		$this->render('files',array('profile_id'=>$this->getCurrentUserId()));
		//$model=Healthfile::model();
		//$this->render('index',array('model'=>$model, 'profile_id'=>$this->getCurrentUserId()));
	}

  public function actionGetdownloadurl($id)
  {

    

    $data = VApi::apiCall('get', "healthfiles/".$id);
    //var_dump($data);
    $url = $data->download_url;
    //echo $url;
    $urla = str_replace( "http://api.viamhealth.com/", 'http://localhost:8080/', $url);
    $context = stream_context_create(array(
    'http' => array(
        'method' => 'GET',
        'header' => "Authorization: Token ".Yii::app()->user->token."\r\n"
      )
    ));

    //header('Content-Disposition: attachment; filename:image.jpg');
    //echo file_get_contents($url);
    header("content-type: image/jpg");
    echo file_get_contents($urla,false,$context);

  }
  public function actionDownloadfile($url)
  {
    
  }

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
    
		if(isset($_POST['Healthfile'])){
      $posted_tags = $_POST['Healthfile']['tags'];
      $posted_tags = explode(",",$posted_tags);
      foreach ($posted_tags as $key => $value) {
        $data['tags['.$key.']'] = $value;  
      }
			$model->attributes = $_POST['Healthfile'];
      $data['description'] = $_POST['Healthfile']['description'];
      $data['id'] = $model->id;
      if($model->save(false,$data))
        $this->redirect(array('healthfiles/index'));
		}

    $tagdata = HealthfileTag::model()->findAll(array(
        'condition'=>'healthfile_id=:id ',
        'params'=>array(':id'=>$id)
      )
    );
    $tag_arr = array();
    foreach ($tagdata as $tagitem) {
      $tag_arr[] = $tagitem->tag;
    }
		$this->render('update',array('model'=>$model,'tag_arr'=>$tag_arr));
	}

  
	public function actionDelete($id)
  {
    $model = $this->loadModel($id);
    if($model->delete())
      if (!isset($_GET['ajax']))
        $this->redirect(array('healthfiles/index'));
      else
        echo "done";
    /*
      $this->loadModel($id)->delete();

      // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
      if (!isset($_GET['ajax']))
          $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
    */
  }

}
