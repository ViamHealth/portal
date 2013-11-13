<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
 
 
if ( ! function_exists('active_link'))
{
    function active_link($controller,$view)
    {
        $CI =& get_instance();
         
        $class = $CI->router->fetch_class();
        $method = $CI->router->fetch_method();
 		
        return ($class == $controller && $view == $method) ? 'active' : '';
    }
}