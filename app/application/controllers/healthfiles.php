<?php

class Healthfiles extends V_Controller {


	public function index()
	{
		$data['title'] = 'Food Diary';
		$this->template('healthfiles/index',$data);
	
	}

	public function download_healthfile($id)
	{
		$data = $this->apicall('get', "healthfiles/".$id.'/');
		if(!$data || (isset($data->detail) && $data->detail == 'Not Found'))  die("");
    	$url = $data->download_url;
    	$urla = str_replace( "http://api.viamhealth.com/", $this->config->item('api_url'), $url);
    	
	    $context = stream_context_create(array(
	    'http' => array(
	        'method' => 'GET',
	        'header' => "Authorization: Token ".$this->session->userdata('token')."\r\n",
	      )
	    ));
	    header('Content-Disposition: attachment; filename='.$data->name);
	    header('Content-Transfer-Encoding: binary');
	    header('Content-Description: File Transfer');
	    header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
	    header("Content-Type: ".$data->mime_type);
	    $content = file_get_contents($urla,false,$context);
	    if($content === FALSE)
	    	die("File Not Found");
	    else echo $content;

	}
}