<?php
/**
 * @author Konrad Baron <konradbaron@gmail.com> http://kobatechnologies.com
 */

class Faxage {
	/**
     * The company_id is assigned to you once you create an account with faxage
     */

	private $faxage_api_url = "https://www.faxage.com/httpsfax.php";
	private $user_name;
	private $company_id;
	private $password;
	private $fax_number;
	private $fax_content;
	private $recipient_name;
	
	
    public function __construct($user_name, $company_id, $password)
    {
        if (!is_numeric($company_id)) throw new Exception('Company ID must be numeric only.');;
		
        $this->user_name = $user_name;
        $this->company_id = $company_id;
        $this->password = $password;
    }
	
	public function set_fax_number($fax_number){
		$this->fax_number = preg_replace('/[^0-9]/', '',$fax_number);
		if (strlen($this->fax_number) != 10) throw new Exception('Fax number must be 10 digits, numeric only.');
		return $this;
	}
	
	public function set_fax_content($fax_content){
		$this->fax_content = base64_encode($fax_content);
		return $this;
	}
	
	public function set_recipient_name($recipient_name){
		if (strlen($recipient_name) > 32) throw new Exception('Recipient name max is 32 char.');
		$this->recipient_name = $recipient_name;
		return $this;
	}

    /**
     * On success returns a job ID variable from faxage, which can later be used to
     * to track status of your fax
     */
	public function send_fax() {
		$fields = array(
			'username'=>$this->user_name,
			'company'=>$this->company_id,
			'password'=>$this->password,
			'faxno'=>$this->fax_number,
			'recipname'=>$this->recipient_name,
			'faxfiledata[0]'=>$this->fax_content,
			'operation'=>'sendfax',
			'faxfilenames[0]'=>$this->fax_number.".html",
		);
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, $this->faxage_api_url);
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch,CURLOPT_POSTFIELDS, http_build_query($fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		
		$result = curl_exec($ch);
		
		if (curl_errno($ch)) {
			$error = curl_error($ch);
			curl_close($ch);
			
			throw new Exception("Failed retrieving  '" . $this->faxage_api_url . "' because of ' " . $error . "'.");
		}
		
		
		if (strstr(strtolower($result), 'jobid:')) {
			$job_id_arr = explode(":", $result);
			return trim($job_id_arr[1]);
		} else {
			throw new Exception("Faxage returns error: ".$result.".");
		}
	}
}
