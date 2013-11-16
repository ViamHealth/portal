<?php

class App extends V_Controller {


	public function index()
	{
		
		redirect(viam_url('/files'), 'refresh');
	}
}