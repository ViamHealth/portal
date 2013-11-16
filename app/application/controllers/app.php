<?php

class App extends V_Controller {


	public function index()
	{
		$data['title'] = 'Food Diary';
		$this->template('fooddiary/index',$data);
		//redirect(viam_url('/files'), 'refresh');
	}
}