<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class VApi
{
	public static function apiCall($method, $url, $params =array())
	{
		$rest = new RESTClient();
        $fuid =  Yii::app()->request->getParam('fuid',null);
        if($fuid)
        {
            $url = $url."?user_id=$fuid";
        }
        $rest->initialize(array('server' => 'http://127.0.0.1:8080/'));
        $rest->set_header('Authorization','Token '.Yii::app()->user->token);
        switch($method){
            case 'get': 
                $result = $rest->get($url);
                break;
            case 'post': 
                $result = $rest->post($url, $params);
                break;
            case 'put': 
                $result = $rest->put($url, $params);
                break;
            default:
                throw new CHttpException(500, 'Illegal API call.');
        }
        
        return json_decode($result);
 
	}
}