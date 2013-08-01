<?php

class VCActiveRecord extends CActiveRecord
{
	public function save($runValidation=true,$attributes=null)
	{
		if(empty($attributes)) $attributes = $this->attributes;
		if(!$runValidation || $this->validate($attributes))
		{
			//TODO: temporaray - sending logged in user always. send current user profile instead
			$attributes['user'] = Yii::app()->user->url;
			$method = 'post';
			$url = $this->resourceUrl();
			if(isset($attributes['id'])){
				$url = $url.$attributes['id'].'/';
				$method = 'put';
			}
			return VApi::apiCall($method, $url, $attributes);
		}
		else{
			return false;
		}
	}
	
}