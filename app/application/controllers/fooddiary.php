<?php

class Fooddiary extends V_Controller {


	public function index()
	{
		$data['title'] = 'Food Diary';
		$this->template('fooddiary/index',$data);
	
	}
}