<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class VApi
{
    private static $apiBaseUrl = 'http://127.0.0.1:8080/';

	public static function apiCall($method, $url, $params =array())
	{
		$rest = new RESTClient();
        $fuid =  Yii::app()->request->getParam('fuid',null);
        //TODO:Hacky!!
        if($fuid && ( $url !='users/' && $url !='/users/me/'))
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

    public static function getToken($params=array()){
        if(empty($params))
            return false;
        if(isset($params['username']) && isset($params['password']))
        {
            $post_data['username'] = $params['username'];
            $post_data['password'] = $params['password'];
            $url = 'api-token-auth/';
            $rest = new RESTClient();
            $rest->initialize(array('server' => self::$apiBaseUrl));
            if(! $result = $rest->post($url, $post_data))
            {
                //return false;
                throw new CHttpException(500, 'API Server down', 500);
            }
            else
            {
                return json_decode($result);    
            }
            

        }
    }
}