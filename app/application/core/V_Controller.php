<?php
class V_Controller extends CI_Controller
{
    public $current_user_id ;
    public $appuser;

	function __construct()
	{
		parent::__construct();
        $this->appuser = $this->session->userdata('user');
        if(isset($this->session->userdata('user')->id))
            $this->current_user_id = $this->session->userdata('user')->id;

        if( $this->uri->segment(1) == 'u'){
            $this->current_user_id = $this->uri->segment(2,$this->current_user_id);
        }

        $class = $this->router->fetch_class();
        $method = $this->router->fetch_method();
        if(!isset($this->session->userdata('user')->id)) {
            if($class != 'site' && $method !='logout')
                header('location: /login');
        } else {
            //loggedin
            if($class == 'site')
                header('location: /files');
        }
        
	}

	public function template($template_name, $vars = array(), $return = FALSE)
    {
    	$vars['api_url'] = $this->config->item('api_url');
    	$vars['appuser'] = $this->session->userdata('user');
        $vars['loggedin'] = isset($this->session->userdata('user')->id)?true:false;
        $token = $this->session->userdata('token');
        $vars['token'] = $token;

        $bug = $this->session->userdata('family');
        if(isset($bug->detail) && $bug->detail == 'Authentication credentials were not provided.')
            $this->session->unset_userdata('family');

        if($this->router->fetch_class() != 'site'){
            if(!isset($this->session->userdata['family'])){
                $family = $this->apiCall('get','users/');
              //  var_dump($family);die();
               $this->session->set_userdata('family',$family);
            }
            
            $vars['family'] = $this->session->userdata['family'];
                
        }
        
        $vars['current_user_id'] = $this->current_user_id;
        
        $content  = $this->load->view('templates/header', $vars, $return);
        $content  = $this->load->view('templates/nav', $vars, $return);
        $content  = $this->load->view('templates/body', $vars, $return);
        $content .= $this->load->view($template_name, $vars, $return);
        $content .= $this->load->view('templates/footer', $vars, $return);

        if ($return)
        {
            return $content;
        }
    }

    public function apicall($method, $url, $params =array())
    {
        return Vapi::apicall($method, $url, $params =array());
    }

    

}