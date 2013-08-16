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
	public function deleteByPk($pk,$condition='',$params=array())
	{
	    Yii::trace(get_class($this).'.deleteByPk()','system.db.ar.CActiveRecord');
	    if(!$pk) return false;
	    $method = 'delete';
	    $url = $this->resourceUrl();
	    $url = $url.$pk.'/';
	    return VApi::apiCall($method, $url);
	}
	
}