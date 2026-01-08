<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Registration extends CI_Controller {
	 public function __construct() {
        parent::__construct();
        // if (!is_login()) {
        //     redirect( base_url().'auth/login', 'refresh');
        // }
        $this->load->helper(['url', 'function','form']);
        $this->load->library('session');
        $this->load->database();
        $this->db2 = $this->load->database('second', TRUE);

        $this->load->library('session');



        $this->data['css_files'] = [
            base_url('assets/css/bootstrap.min.css'),
            base_url('assets/css/bootstrap-datepicker.min.css'),
            base_url('assets/css/style.css')
        ];

        $this->data['js_files'] = [
            base_url('assets/js/jquery-3.6.0.min.js'),
            base_url('assets/js/bootstrap.bundle.min.js'),
            base_url('assets/js/bootstrap-datepicker.min.js'),
            base_url('assets/js/custom.js')
        ];

        $this->data['copyright'] = '© 2026 Haryana Government. All Rights Reserved.';


    }


    public function index(){
    	$this->data['pageTitle']= 'Registration Forest Department - Self Registration';
		$this->data['breadcrumbs'] = array('Login');
    
		$this->load->view('layout/header', $this->data);
		$this->load->view('registration/registration_view',$this->data);
		$this->load->view('layout/footer', $this->data);
    }

    public function step2(){
    // Allow only POST request
    if ($this->input->method() !== 'post') {
        redirect('registration');
        return;
    }

    // Check required POST value
    if (!$this->input->post('otp_mobile_number')) {
        redirect('registration');
        return;
    }

    // Sanitize POST data
    $postData = $this->input->post(NULL, TRUE);

    //$mobile = base64_decode($postData['otp_mobile_number']);
    $mobile = $postData['otp_mobile_number'];

    // Generate HMAC
    $HMAC_KEY  = privateKey; // defined in constants.php
    $hmac_data = $HMAC_KEY . $mobile;
    $HMAC      = hash_hmac('sha1', $hmac_data, $HMAC_KEY);

    // Validate HMAC
    if ($HMAC === $postData['HMAC_TOKEN']) {
    	$this->data['post'] = $postData;
    	$this->load->view('layout/header', $this->data);
        $this->load->view('registration/self_register',  $this->data);
        $this->load->view('layout/footer', $this->data);
        

    } else {
        // Invalid request
        show_error('Invalid request!', 401);
    }
}

		public function sendotp() {
			//$_POST['mobile'] = preg_replace('/[^0-9]/', '', $_POST['mobile_number']);

			if ($this->input->method() !== 'post') {
				        show_error('The action you have requested is not allowed.', 403);
				    }

				    $mobile = trim($this->input->post('mobile_number'));



				    if (empty($mobile)) {
				        echo json_encode(['error' => 'MISSING_PARAMS']);
				        return;
				    }

				    $returnArray = [];

				    // Mobile number validation
				    // if (!preg_match("/^[0-9]{3}-[0-9]{4}-[0-9]{4}$/", $mobile)) {
				    //     $returnArray['error'] = 'MOBILE_NUMBER_ERROR';
				    //     echo json_encode($returnArray);
				    //     return;
				    // }

				    if (!preg_match("/^[0-9]{10,11}$/", $mobile)) {
					    $returnArray['error'] = 'MOBILE_NUMBER_ERROR';
					    echo json_encode($returnArray);
					    return;
					}

				    // Generate HMAC
				    $HMAC_KEY = privateKey;
				    $hmac_data = $HMAC_KEY . $mobile;
				    $HMAC = hash_hmac('sha1', $hmac_data, $HMAC_KEY);

				    // Check if mobile already registered
				    if ($this->isMobileNumberRegistred($mobile)) {

				        $returnArray['error'] = 'ALREADY_REGISTERED';

				    } else {

				        // Generate OTP
				        $otp = generate_otp(6);

//$messageStr = 'You requested an OTP for Forest Dept. login. Your OTP is '. $otp . '. Please DO NOT SHARE it with anyone.';

				        // Send OTP SMS
				        //$smsresponse = Utility::sendOtpSms($mobile, $messageStr);
					$messageStr = 'You requested an OTP for Forest Dept. login. Your OTP is ' . $otp . '. Please DO NOT SHARE it with anyone.';
				        $smsresponse = sendOtpSms($mobile,$messageStr);

				        if ($smsresponse === 'Messages submitted successfully') {

				            // Insert OTP
				            $this->db->insert('self_otp', ['otp_code'=> $otp,'mobile_number' => $mobile]);

				            $returnArray['hmac_token'] = $HMAC;
				            $returnArray['otpmobilenumber'] = $mobile;
				            $returnArray['STATUS_ID']  = '000';

				        } else {

				            $returnArray['RESPONSE']  = $smsresponse;
				            $returnArray['STATUS_ID'] = '555';
				        }
				    }

				    echo json_encode($returnArray);
			
		}



		public function isMobileNumberRegistred($mobileNo){
	        $mobileNo = trim($mobileNo);
	        $refEvent_Init = 1;

	        // Load second database (db2)
	        $db2 = $this->load->database('second', TRUE);

	        $db2->select('COUNT(*) AS count');
	        $db2->from('tblResident');
	        $db2->where('Mobile_Number', $mobileNo);
	        $db2->where('refEvent_Init', $refEvent_Init);

	        $query = $db2->get();
	        $row = $query->row_array();

	        return ($row['count'] > 0);
    }

    

	public function isValidOtp($fields){
    $mobNo = $fields['Mobile_No'];
    $otp   = $fields['OTP_No'];

    // Query the database
    $this->db->select('COUNT(*) AS count, is_active');
    $this->db->from('self_otp');
    $this->db->where('otp_code', $otp);
    $this->db->where('mobile_number', $mobNo);
    $this->db->group_by(['mobile_number', 'is_active']);
    $this->db->order_by('is_active', 'DESC');
    $this->db->limit(1);

    $query = $this->db->get();
    $row = $query->row_array();

    if ($row && $row['count'] > 0) {
        return $row['is_active'];
    } else {
        return 'EMPTY';
    }
}

public function validateotp(){

	    $returnArray = [];
	    if (isset($_POST['otp']) && isset($_POST['mobile_number'])) {
	        $mobile = $_POST['mobile_number'];
	        //$mobile = base64_decode($mobile);
	        //$mobile = base64_decode($mobile);
	        $otp = $_POST['otp'];
	        $params = array(
	            "Mobile_No" => $mobile,
	            "OTP_No"    => $otp
	        );
	        $isOtpValid = $this->isValidOtp($params);

	        //echo '<pre>';print_r($isOtpValid);die();
	        if ($isOtpValid) {
	            if ($isOtpValid == '1') {
	                $is_active = '0';
	                $otpVerifiedOn = date('Y-m-d H:i:s');
	                // Deactivate OTP after successful match
	                $this->db->set('is_active', $is_active);
	                $this->db->set('verified_timestamp', $otpVerifiedOn);
	                $this->db->where('otp_code', $otp);
	                $this->db->where('mobile_number', $mobile);
	                $this->db->update('self_otp');
	                $returnArray['STATUS_ID'] = '000';
	            } elseif ($isOtpValid == 'EMPTY') {

	                $returnArray['STATUS_ID'] = '111';
	                $returnArray['RESPONSE'] = 'Invalid OTP provided';
	            } else {
	                $returnArray['STATUS_ID'] = '222';
	                $returnArray['RESPONSE'] = 'Something went wrong! Please try again.';
	            }
	        } else {
	            $returnArray['STATUS_ID'] = '222';
	            $returnArray['RESPONSE'] = 'This OTP has been expired! Please provide latest OTP you received.';
	        }
	        // Return JSON response
	        echo json_encode($returnArray);
	        return;
	    } else {
	        echo "MISSING_PARAMS";
	        return;
	    }
	}


	public function getallcitiesindistrict(){


    	$is_Active = 'Y';
	    // Load second database
	    $db2 = $this->load->database('second', TRUE);


	    $db2->select('
	        tblCity.City_ID,
	        tblLandRegion.LR_Name AS CITY_NAME,
	        tblLandRegion.LR_Latitude AS Latitude,
	        tblLandRegion.LR_Longitude AS Longitude
	    ');
	    $db2->from('tblCity');
	    $db2->join(
	        'tblLandRegion',
	        "tblCity.City_ID = tblLandRegion.LR_ID AND tblLandRegion.LR_Type = 'City'",
	        'left'
	    );
	    $db2->where('tblCity.District_ID', $_REQUEST['distID']);
	    $db2->where('tblCity.isActive', $is_Active);

	    $query = $db2->get();

	    //echo $db2->last_query();die('ggg');
			echo json_encode($query->result_array());
			return ;
	}



		public function saveuserdetails(){
		    // Log POST data
		    //log_message('error', json_encode(['$_POST' => $_POST]));

		    if ($this->input->post('mobile_number')) {



		        // Sanitize input (keep your existing utility if needed)
		        $post = $this->security->xss_clean($this->input->post());



		        // HMAC validation
		        $HMAC_KEY = privateKey;
		        $hmac_data = $HMAC_KEY . $post['mobile_number'];
		        $HMAC = hash_hmac('sha1', $hmac_data, $HMAC_KEY);

		        if ($HMAC !== $post['HMAC_TOKEN']) {
		            $data['error'] = 'Mobile number has been tampered. Please try again.';
		            $this->load->view('registration/thankyou', $data);
		            return;
		        }



		        

		        //log_message('error', json_encode(['HMAC' => 'YES MATCHED']));

		        // Format DOB
		        if (!empty($post['date_of_birth'])) {

		            $post['date_of_birth'] = date('Y-m-d H:i:s', strtotime($post['date_of_birth']));
		        }


		        // Prepare params (same mapping as Yii)
		        $params = array(
		            'Mobile_No'        => $post['mobile_number'],
		            'Full_Name'        => $post['full_name'],
		            'Date_of_Birth'    => $post['date_of_birth'],
		            'Place_of_Birth'   => $post['birthplace_city_id'],
		            'Address'          => $post['address'],
		            'Email'            => $post['email'],
		            'Gender'           => $post['gender'],
		            'UID'              => $post['uid'],
		            'EID'              => $post['eid'],
		            'refEvent_Init'    => 1
		        );

		        //log_message('error', json_encode(['ALLGOODDD' => $params]));

		        // Save data
		        $response = $this->saveResidentInSrdb($params);

		        // Load response view
		        $this->load->view('registration/thankyou', ['response' => $response]);
		        return;

		    } else {
		        redirect('registration/index');
		    }
		}

 function saveResidentInSrdb($dataToSave){
    // Load second database
    $db2 = $this->load->database('second', TRUE);

    // Convert English name to Hindi
    $hindiName = engtohin($dataToSave['Full_Name']);

    // Check duplicate mobile number
    $chkdup = $this->isMobileNumberRegistred($dataToSave['Mobile_No']);



    $returnArray = array();

    if ($chkdup === true) {

        $returnArray['STATUS_ID'] = '111';
        $returnArray['RESPONSE']  = 'User already registered.';

    } else {

        // Build simple query directly
        $sql = "
            INSERT INTO tblResident
            (Resident_Name_En, Resident_Name_LL, Mobile_Number, Gender, Date_of_Birth, Birth_City_ID, LAF, Email_Address, Resident_UID, Resident_EID, refEvent_Init)
            VALUES (
                '".$db2->escape_str($dataToSave['Full_Name'])."',
                '".$db2->escape_str($hindiName)."',
                '".$db2->escape_str($dataToSave['Mobile_No'])."',
                '".$db2->escape_str($dataToSave['Gender'])."',
                '".$db2->escape_str($dataToSave['Date_of_Birth'])."',
                '".$db2->escape_str($dataToSave['Place_of_Birth'])."',
                '".$db2->escape_str($dataToSave['Address'])."',
                '".$db2->escape_str($dataToSave['Email'])."',
                '".$db2->escape_str($dataToSave['UID'])."',
                '".$db2->escape_str($dataToSave['EID'])."',
                '".$db2->escape_str($dataToSave['refEvent_Init'])."'
            )
        ";

        $result = $db2->query($sql);

        if ($result) {
            $returnArray['STATUS_ID'] = '000';
        } else {
            $returnArray['STATUS_ID'] = '111';
            $returnArray['RESPONSE']  = 'Unable to save info into database! Please try again.';
        }
    }

    return (object) $returnArray;
}


  

  }     