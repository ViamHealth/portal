<?php

class Goals extends V_Controller {


	public function index()
	{
		$data['title'] = 'Goals ';
		$this->template('goals/index',$data);
	
	}
}