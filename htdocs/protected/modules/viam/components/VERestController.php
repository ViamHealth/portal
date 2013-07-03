<?php
class VERestController extends ERestController
{
	public function filterRestAccessRules( $c )
	{
		//Temporary gate pass for all requests
		$c->run(); 
		return;
	}
}