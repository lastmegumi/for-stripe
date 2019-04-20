<?php
	// use net\authorize\api\contract\v1 as AnetAPI;
	// use net\authorize\api\controller as AnetController;
 	// require ROOT_PATH . 'include/card/vendor/autoload.php';
  	require ROOT_PATH . 'include/stripe/init.php';
    // define("AUTHORIZENET_LOG_FILE", "phplog");
class card{
	public $response = array("message" 	=> "",
							 "status"	=>	0,
							 "data"		=> null,
							);
	private $_test_key = "sk_test_aaaaaaaaaaaaaaaaaaaaaaaa";	# your test key
	private $_live_key = "sk_live_bbbbbbbbbbbbbbbbbbbbbbbb";	# your live key
	private $test_mode = false;
	public $_min_pay = 1;

	function __construct(){
		\Stripe\Stripe::setApiKey($this->_test_key);
	}

	function currency_mark($currency = null){
		switch ($currency) {
			case 'usd':
				return "$";
				break;
			default:
				return "$";
				# code...
				break;
		}return null;
	}

	function charge($token = null, $amount = 0, $description = null){
		// Token is created using Checkout or Elements!
		// Get the payment token ID submitted by the form:
		//$token = $_POST['stripeToken'];

		try {
			$charge = \Stripe\Charge::create([
			    'amount' => $amount,
			    'currency' => 'usd',
			    'description' => $description,
			    'source' => $token,
			]);
			$this->response['status'] 	= 1;
			$this->response['message']	= "SUCCESS";
			$this->response['data']	=	$charge;
		} catch (Exception $e) {
			$this->response['message'] = $e->getMessage();
		}
		return $this->response;
	}

	function refund($charge_id, $amount = false){
		try{
			if(!$amount):
				$refund = \Stripe\Refund::create([
				    'charge' => $charge_id,
				]);
			else:
				$refund = \Stripe\Refund::create([
				    'charge' => $charge_id,
				    'amount' => $amount,
				]);
			endif;
			$this->response['status'] 	= 1;
			$this->response['message']	= "SUCCESS";
			$this->response['data']	=	$refund;
		}catch(Exception $e){
			$this->response['message'] = $e->getMessage();
		}
		return $this->response;
	}

	function card_token($obj){
		try {
			$token = \Stripe\Token::create([
			  "card" => [
			    "number"		=> $obj['card'],
			    "exp_month" 	=> $obj['exp_month'],
			    "exp_year" 		=> $obj['exp_year'],
			    "cvc" 			=> $obj['cvc'],
			    "name"			=> $obj['name'],
			    "address_line1"	=> $obj['address_line1'],
			    "address_line2"	=> $obj['address_line2'],
			    "address_city"	=> $obj['address_city'],
			    "address_state"	=> $obj['address_state'],
			    "address_zip"	=> $obj['address_zip'],
			    //"address_line1"	=> $obj['address_line1'],
			  ]
			]);
			$this->response['status'] 	= 1;
			$this->response['message']	= "SUCCESS";
			$this->response['data']	=	$token;
		} catch (Exception $e) {
			$this->response['message'] = $e->getMessage();
		}
		return $this->response;

	}

	function receive_charge($charge_id = null){
		if($charge_id):
			try{
				$data = \Stripe\Charge::retrieve($charge_id);
				$this->response['status'] 	= 1;
				$this->response['message']	= "SUCCESS";
				$this->response['data']	=	$data;
			} catch (Exception $e) {
				$this->response['message'] = $e->getMessage();
			}
		else:
			try {
				$data = \Stripe\Charge::all(["limit" => 3]);
				$this->response['status'] 	= 1;
				$this->response['message']	= "SUCCESS";
				$this->response['data']	=	$data;
			} catch (Exception $e) {
				$this->response['message'] = $e->getMessage();
			}
		endif;
		//print_r($this->response);
		return $this->response;
	}

	function _render($func = null){
		if(method_exists($this, $func)){
			return $this->$func();
		}
		return false;
	}
}