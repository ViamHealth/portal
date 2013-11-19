<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Vapi
{
    
	public static function apicall($method, $url, $params =array())
    {
        $CI =& get_instance();
         
        $apiBaseUrl = $CI->config->item('api_url');
        //$CI->load->packages( 'REST');
        $rest = new REST();
        $fuid =  $CI->input->get('fuid',null);
        //TODO:Hacky!!
        if($fuid && ( $url !='users/' && $url !='/users/me/'))
        {
            $url = $url."?user_id=$fuid";
        }
        $rest->initialize(array('server' => $apiBaseUrl));
        $token = $CI->session->userdata('token');
        if($token)
            $rest->http_header('Authorization','Token '.$token);
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
        //var_dump($result);
        return $result;

        //$class = $CI->router->fetch_class();
        //$method = $CI->router->fetch_method();
        
        //return ($class == $controller && $view == $method) ? 'active' : '';
    }

   
}