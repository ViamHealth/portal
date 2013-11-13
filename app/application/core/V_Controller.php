<?php
class V_Controller extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
        //loggedin
	}

	public function template($template_name, $vars = array(), $return = FALSE)
    {
    	$vars['api_url'] = $this->config->item('api_url');
    	$vars['appuser'] = $this->session->userdata('user');
        $vars['loggedin'] = isset($this->session->userdata('user')->id)?true:false;
        $token = $this->session->userdata('token');
        $vars['token'] = $token;
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
}