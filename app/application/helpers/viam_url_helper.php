<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 
if ( ! function_exists('viam_url'))
{
    function viam_url($route,$user_id=null)
    {
        $CI =& get_instance();

        if(isset($CI->current_user_id) && $CI->current_user_id )
        {
            if($user_id)
            {
                if($user_id == $CI->appuser->id)
                {
                    return $route;
                }
                if(strpos($route, '/u/') !== 0)
                    if(strpos($route,'/') === 0)
                        $route = '/u/'.$user_id.$route;
                    else
                        $route = '/u/'.$user_id.'/'.$route;
            }
            else if ($CI->current_user_id != $CI->appuser->id)
            {
                //Check list if statement. 
                if(strpos($route, '/u/') !== 0)
                    if(strpos($route,'/') === 0)
                        $route = '/u/'.$CI->current_user_id.$route;
                    else
                        $route = '/u/'.$CI->current_user_id.'/'.$route;
            }    
        }
        return site_url($route);
    }
}