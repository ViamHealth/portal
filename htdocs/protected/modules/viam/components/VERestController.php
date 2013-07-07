<?php
class VERestController extends ERestController
{
	//TODO: Pagination
	//TODO: Filters
	//TODO: Fields
	//TODO: Secure Access
	public function filterRestAccessRules( $c )
	{
		//Temporary gate pass for all requests
		$c->run(); 
		return;
	}
}