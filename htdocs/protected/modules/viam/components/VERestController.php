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

	public function outputHelper($message, $results, $totalCount=0, $model=null)
	{
		if(is_null($model))
			$model = lcfirst(get_class($this->model));
		else
			$model = lcfirst($model);	

		$this->renderJson($this->allToArray($results));
	}

	//Supporting HAS_MANY sub resources
	//TODO: support plural and case insensitive
	/*public function validateSubResource($subResourceName, $subResourceID=null)
	{
		if(is_null($relations = $this->getModel()->relations()))
			return false;
		if(!isset($relations[$subResourceName]))
			return false;
		if($relations[$subResourceName][0] != CActiveRecord::MANY_MANY && $relations[$subResourceName][0] != CActiveRecord::HAS_MANY)
			return false;
		if(!is_null($subResourceID))
			return filter_var($subResourceID, FILTER_VALIDATE_INT) !== false;

		return true;
	}*/
}