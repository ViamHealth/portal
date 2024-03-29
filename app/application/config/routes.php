<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "site/login";
$route['heartbeat'] = "site/heartbeat";
$route['login'] = "site/login";
$route['login/(:any)'] = "site/login/$1";
$route['signup'] = "site/signup";
$route['logout'] = "site/logout";
$route['forgot_password'] = "site/forgot_password";
$route['loginapi/(:any)'] = "site/loginapi/$1";

$route['home'] = "app/index";
$route['u/(:num)/home'] = "app/index/";


$route['files'] = "healthfiles/index";
$route['download_healthfile/(:num)'] = "healthfiles/download_healthfile/$1";
$route['diary'] = "fooddiary/index";
$route['goals'] = "goals/index";
$route['reminders'] = "reminders/index";
$route['user'] = "user/index";
$route['user/add'] = "user/add";
$route['resetsessionuserdata'] = "app/resetsessionuserdata";
$route['u/(:num)/files'] = "healthfiles/index/";
$route['u/(:num)/goals'] = "goals/index";
$route['u/(:num)/reminders'] = "reminders/index";
$route['u/(:num)/diary'] = "fooddiary/index";
$route['u/(:num)/user'] = "user/index";
$route['u/(:num)/resetsessionuserdata'] = "app/resetsessionuserdata";


//$route['(:any)'] = 'site/view/$1';
$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./application/config/routes.php */