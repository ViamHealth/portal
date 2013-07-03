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


}