<?php

class Reminders extends V_Controller {


	public function index()
	{
		$data['title'] = 'Reminders';
		$this->template('reminders/index',$data);
	
	}
}