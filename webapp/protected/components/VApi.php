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
        $rest->initialize(array('server' => self::$apiBaseUrl));
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
            case 'delete':
                $result = $rest->delete($url);
                break;
            default:
                throw new CHttpException(500, 'Illegal API call.');
        }
        //TODO Hacky !! Move class away from static functions to avoid these situations
        if ( $rest->status() == '204' ) return true;

        return json_decode($result);
 
	}

    public static function fetchToken($params=array()){
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
                throw new CHttpException(500, 'API Server down');
            }
            else
            {
                return json_decode($result);    
            }
            

        }
    }

    //TO Be used only with Get requests
    public static function getData($response=array())
    {
        if(isset($response->results))
            return $response->results;
        else
            throw new CHttpException(500, 'API Server error');
    }

    //NBelow functions not used
    //TO Be used only with non Safe requests
    public static function getDataArray($response=array())
    {
        if(empty($response))
            throw new CHttpException(500, 'API Server error');
        $res = self::jsonToArray($response);
        return $res;
    }

    public static function jsonToArray($response)
    {
        foreach ($response as $key => $value) {
            if (is_object($value)) {

                //$res[$key] = self::jsonToArray($value);
            } else{
                $res[$key] = $value;    
            }
        }
        return $res;
    }
}