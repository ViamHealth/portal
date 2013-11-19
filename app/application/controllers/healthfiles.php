<?php

class Healthfiles extends V_Controller {


	public function index()
	{
		$data['title'] = 'Food Diary';
		$this->template('healthfiles/index',$data);
	
	}
}