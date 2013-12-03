<?php
//temporarya user model layer
class User_model  {

	public $id;
	public $username;
	public $email;
	public $first_name;
	public $last_name;
	public $profile;
	public $bmi_profile;

	public function __construct(){
		$this->profile = new Userprofile();
		$this->bmi_profile = new Userbmiprofile();
	}

	public function set_data($data=array()) {
		
		foreach($data as $k=>$v){
			if($k =='profile'){
				$this->profile->set_data($v);
			}
			else if($k =='bmi_profile'){
				$this->bmi_profile->set_data($v);
			} else {
				$this->$k = $v;	
			}
			
		}
	}

	
}

class Userprofile {
	public $gender;
	public $date_of_birth;
	public $profile_picture_url;
	public $mobile;
	public $blood_group;
	public $fb_profile_id;
	public $fb_username;
	public $organization;
	public $street;
	public $city;
	public $state;
	public $country;
	public $zip_code;
	public $lattitude;
	public $longitude;
	public $address;


	public function set_data($data=array()) {
		foreach($data as $k=>$v){
			$this->$k = $v;
		}
	}
}

class Userbmiprofile {
	public $id;
	//public $user;
	public $height;
	public $weight;
	public $lifestyle;
	public $bmi_classification;
	public $bmr;
	public $systolic_pressure;
	public $diastolic_pressure;
	public $pulse_rate;
	public $bp_classification;
	public $random;
	public $fasting;
	public $sugar_classification;
	public $hdl;
	public $ldl;
	public $triglycerides;
	public $total_cholesterol;
	public $cholesterol_classification;


	public function set_data($data=array()) {
		foreach($data as $k=>$v){
			//$this->$k = $v;
			if($k=='latest_readings')
				foreach ($v as $lk => $lv) { 
					$this->$lk = $lv;
				}
		}
	}

    public function get_bmi_classification_text(){
    	switch($this->bmi_classification){
			case '1' : return 'Underweight';
				break;
			case '2' : return 'Normal range';
				break;
			case '3' : return  'Overweight';
				break;
			case '4' : return  'Obese';
				break;
			default : return '';
				break;
		}
    }

    public function get_bp_classification_text(){
    	switch($this->bp_classification){
			case '1' : return  'Low';
				break;
			case '2' : return  'Normal';
				break;
			case '3' : return  'High';
				break;
			default : return  '';
				break;
		}
    }

    public function get_cholesterol_classification_text(){
    	switch($this->cholesterol_classification){
			default : return '';
				break;
		}
    }

    public function get_sugar_classification_text(){
    	switch($this->sugar_classification){
			case '1' : return 'Low';
				break;
			case '2' : return 'Normal';
				break;
			case '3' : return 'High';
				break;
			default : return '';
				break;
		}

    }


    
}