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














































	// private $ID = "2N2v9BWxja37";
	// private $KEY = "5NSGt4K758rU46uu";
	// private $batch_id = "8855898";
	// public $status = array("authorizedPendingCapture"				=> "已授权，等待卖家收款",
	// 						"capturedPendingSettlement"				=> "已收款，正在处理中",
	// 						"communicationError"					=> "交易出错",
	// 						"refundSettledSuccessfully"				=> "已退款",
	// 						"refundPendingSettlement"				=> "已提交，退款处理中",
	// 						"approvedReview"						=> "已审核",
	// 						"declined"								=> "已拒绝",
	// 						"couldNotVoid"							=> "无法取消",
	// 						"expired"								=> "已过期",
	// 						"generalError"							=> "交易出错",
	// 						"failedReview"							=> "审核失败",
	// 						"settledSuccessfully"					=> "已过帐",
	// 						"settlementError"						=> "过账出错",
	// 						"underReview"							=> "待审核",
	// 						"voided"								=> "已取消",
	// 						"FDSPendingReview"						=> "FDS待审核",
	// 						"FDSAuthorizedPendingReview"			=> "FDS待授权",
	// 						"returnedItem"							=> "退货商品");


	// public $error_msg = array(0		=>		"未知错误",
	// 						1		=>		"操作成功",
	// 						2		=>		"操作被拒绝",
	// 						3		=>		"操作被拒绝",
	// 						4		=>		"操作被拒绝",
	// 						5		=>		"请输入有效金额",
	// 						6		=>		"信用卡号错误",
	// 						7		=>		"信用卡有效期限错误",
	// 						8		=>		"信用卡已过期",
	// 						9		=>		"ABA CODE错误",
	// 						10	=>		"无效的金额",
	// 						11	=>		"请不要重复提交",
	// 						12	=>		"需要验证号码",
	// 						13	=>		"商家出错，请联系商家",
	// 						14	=>		"响应URL无效",
	// 						15	=>		"交易ID不存在",
	// 						16	=>		"未找到交易记录",
	// 						17	=>		"商家不接受该类信用卡",
	// 						18	=>		"商家不接受ACH交易",
	// 						19	=>		"交易过程中出错，请稍后再试",
	// 						20	=>		"交易过程中出错，请稍后再试",
	// 						21	=>		"交易过程中出错，请稍后再试",
	// 						22	=>		"交易过程中出错，请稍后再试",
	// 						23	=>		"交易过程中出错，请稍后再试",
	// 						24	=>		"Elavon银行编号或终端ID不正确。致电商家服务提供商。",
	// 						25	=>		"交易过程中出错，请稍后再试",
	// 						26	=>		"交易过程中出错，请稍后再试",
	// 						27	=>		"交易被拒绝，地址不匹配",
	// 						28	=>		"商家不接受该类信用卡",
	// 						36	=>		"已授权，但是过账出错",
	// 						41	=>		"该交易被拒绝",
	// 						42	=>		"缺少必要信息",
	// 						44	=>		"该交易被拒绝",
	// 						45	=>		"该交易被拒绝",
	// 						46	=>		"网页已过期，请刷新重试",
	// 						47	=>		"交易金额过大",
	// 						49	=>		"交易金额过大",
	// 						50	=>		"正在处理过账，还不能退款，请稍后重试",
	// 						51	=>		"交易金额超过原始金额",
	// 						54	=>		"与交易记录不匹配，请重试",
	// 						55	=>		"交易金额超过原始交易金额",
	// 						56	=>		"商家拒绝此次交易",
	// 						64	=>		"历史交易记录未完成，请稍后重试",
	// 						65	=>		"交易被拒绝",
	// 						93	=>		"请输入有效的国家",
	// 						94	=>		"请输入有效的州",
	// 						"E00003"	=>	"格式错误",
	// 						"O1"		=>	"金额错误",
	// 			);


	// public function is_refundable($status){
	// 	if(in_array($status, array("settledSuccessfully"))):
	// 		return 1;
	// 	endif;
	// 	return 0;
	// }

	// public function is_cancelable($status){
	// 	if(in_array($status, array("authorizedPendingCapture", "capturedPendingSettlement", "refundPendingSettlement", "FDSPendingReview", "FDSAuthorizedPendingReview"))):
	// 		return 1;
	// 	endif;
	// 	return 0;
	// }

	// public function get_error_txt($code){
	// 	if(isset($this->error_msg[$code])):
	// 		return "错误代号" . $code . ":" . $this->error_msg[$code];
	// 	endif;
	// 	return "错误代号" . $code . ": 请联系商家";
	// }

	// function __construction(){
	// 	if(!defined('DONT_RUN_SAMPLES'));
	// 	##refundTransaction( "2.23");
	// }

	// function _render($func = null){
	// 	if(method_exists($this, $func)){
	// 		return $this->$func();
	// 	}
	// 	return false;
	// }

	// function form(){
	// 	return '123';
	// }

	// function chargeCreditCard($info, $amount){
	// 	$cardnumber = $info['card_number'];
	// 	$cardexp = $info['card_exp_date']; //format yyyy-mm
	// 	$cardcode = $info['card_cvv'];

	// 	$invoicenumber = $info['order_id'];
	// 	$Description = $info['order_des'];

	// 	$firstname = $info['customer_first_name'];
	// 	$lastname = $info['customer_last_name'];
	// 	$company = $info['customer_company'];
	// 	$address = $info['customer_address'];
	// 	$city = $info['custoemr_city'];
	// 	$state = $info['customer_state'];
	// 	$zip = $info['customer_zip'];
	// 	$country = $info['customer_country'];
	// 	$type = 'individual';//$info[''];
	// 	$c_id = $info['customer_id'];
	// 	$c_email = $info['customer_email'];


	//     /* Create a merchantAuthenticationType object with authentication details
	//        retrieved from the constants file */
	//     $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	//     $merchantAuthentication->setName($this->ID);
	//     $merchantAuthentication->setTransactionKey($this->KEY);
	    
	//     // Set the transaction's refId
	//     $refId = 'ref' . time();

	//     // Create the payment data for a credit card
	//     $creditCard = new AnetAPI\CreditCardType();
	//     $creditCard->setCardNumber($cardnumber);
	//     $creditCard->setExpirationDate($cardexp);
	//     $creditCard->setCardCode($cardcode);

	//     // Add the payment data to a paymentType object
	//     $paymentOne = new AnetAPI\PaymentType();
	//     $paymentOne->setCreditCard($creditCard);

	//     // Create order information
	//     $order = new AnetAPI\OrderType();
	//     $order->setInvoiceNumber($invoicenumber);
	//     $order->setDescription($Description);

	//     // Set the customer's Bill To address
	//     $customerAddress = new AnetAPI\CustomerAddressType();
	//     $customerAddress->setFirstName($firstname);
	//     $customerAddress->setLastName($lastname);
	//     $customerAddress->setCompany($company);
	//     $customerAddress->setAddress($address);
	//     $customerAddress->setCity($city);
	//     $customerAddress->setState($state);
	//     $customerAddress->setZip($zip);
	//     $customerAddress->setCountry($country);

	//     // Set the customer's identifying information
	//     $customerData = new AnetAPI\CustomerDataType();
	//     $customerData->setType("individual");
	//     $customerData->setId($c_id);
	//     $customerData->setEmail($c_email);

	//     // Add values for transaction settings
	//     $duplicateWindowSetting = new AnetAPI\SettingType();
	//     $duplicateWindowSetting->setSettingName("duplicateWindow");
	//     $duplicateWindowSetting->setSettingValue("60");

	//     // Add some merchant defined fields. These fields won't be stored with the transaction,
	//     // but will be echoed back in the response.
	    
	//     $merchantDefinedField1 = new AnetAPI\UserFieldType();
	//     $merchantDefinedField1->setName("customerLoyaltyNum");
	//     $merchantDefinedField1->setValue("1128836273");

	//     $merchantDefinedField2 = new AnetAPI\UserFieldType();
	//     $merchantDefinedField2->setName("favoriteColor");
	//     $merchantDefinedField2->setValue("blue");
	    

	//     // Create a TransactionRequestType object and add the previous objects to it
	//     $transactionRequestType = new AnetAPI\TransactionRequestType();
	//     $transactionRequestType->setTransactionType("authCaptureTransaction");
	//     $transactionRequestType->setAmount($amount);
	//     $transactionRequestType->setOrder($order);
	//     $transactionRequestType->setPayment($paymentOne);
	//     $transactionRequestType->setBillTo($customerAddress);
	//     $transactionRequestType->setCustomer($customerData);
	//     $transactionRequestType->addToTransactionSettings($duplicateWindowSetting);
	//     $transactionRequestType->addToUserFields($merchantDefinedField1);
	//     $transactionRequestType->addToUserFields($merchantDefinedField2);

	//     // Assemble the complete transaction request
	//     $request = new AnetAPI\CreateTransactionRequest();
	//     $request->setMerchantAuthentication($merchantAuthentication);
	//     $request->setRefId($refId);
	//     $request->setTransactionRequest($transactionRequestType);

	//     // Create the controller and get the response
	//     $controller = new AnetController\CreateTransactionController($request);
	//     $response = $controller->executeWithApiResponse(\net\authorize\api\constants\ANetEnvironment::SANDBOX);
	    
	//     if ($response != null) {
	//         // Check to see if the API request was successfully received and acted upon
	//         if ($response->getMessages()->getResultCode() == "Ok") {
	//             // Since the API request was successful, look for a transaction response
	//             // and parse it to display the results of authorizing the card
	//             $tresponse = $response->getTransactionResponse();
	//             if ($tresponse != null && $tresponse->getMessages() != null) {
	//             	$r['status'] = 1;      
	//                 $r['transaction_id'] =  $tresponse->getTransId();
	//                 $r['response_code'] = $tresponse->getResponseCode();

	//                 $r['message_code'] = $tresponse->getMessages()[0]->getCode();
	//                 $r['auth_code'] = $tresponse->getAuthCode();
	//                	$r['message'] = $tresponse->getMessages()[0]->getDescription();
	//             } else {
	//             	$r['status'] = 0;
	//         		$r['message'] = '';
	//                 $r['message'] .= "Transaction Failed";
	//                 if ($tresponse->getErrors() != null) {
	//                     $r['error_code'] = $tresponse->getErrors()[0]->getErrorCode();
	//                     $r['message'] .= " Error Message : " . $tresponse->getErrors()[0]->getErrorText();
	//                 }
	//             }
	//             // Or, print errors if the API request wasn't successful
	//         } else {
 //            	$r['status'] = 0;
 //        		$r['message'] = '';
 //                $r['message'] .= "Transaction Failed";
	//             $tresponse = $response->getTransactionResponse();
	        
	//             if ($tresponse != null && $tresponse->getErrors() != null) {
	//                 $r['error_code'] = $tresponse->getErrors()[0]->getErrorCode();
	//                 $r['message'] .= " Error Message : " . $tresponse->getErrors()[0]->getErrorText();
	//             } else {
	//                 $r['error_code'] = $response->getMessages()->getMessage()[0]->getCode();
	//                 $r['message'] .= " Error Message : " . $response->getMessages()->getMessage()[0]->getText();
	//             }
	//         }
	//     } else {
	//     	$r['status'] = 0;
 //            $r['message'] = "No response returned";
	//     }
	//     return $r;
	// }

	// function refundTransaction($refTransId, $amount, $card){
	//      Create a merchantAuthenticationType object with authentication details
	//        retrieved from the constants file 
	//     $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	//     $merchantAuthentication->setName($this->ID);
	//     $merchantAuthentication->setTransactionKey($this->KEY);
	    
	//     // Set the transaction's refId
	//     $refId = 'ref' . time();

	//     // Create the payment data for a credit card
	//     $creditCard = new AnetAPI\CreditCardType();
	//     $creditCard->setCardNumber($card["num"]); //格式 XXXX
	//     $creditCard->setExpirationDate($card["expdate"]); //格式 MMYY
	//     $paymentOne = new AnetAPI\PaymentType();
	//     $paymentOne->setCreditCard($creditCard);
	//     //create a transaction
	//     $transactionRequest = new AnetAPI\TransactionRequestType();
	//     $transactionRequest->setTransactionType( "refundTransaction"); 
	//     $transactionRequest->setAmount($amount);
	//     $transactionRequest->setPayment($paymentOne);
	//     $transactionRequest->setRefTransId($refTransId);
	 

	//     $request = new AnetAPI\CreateTransactionRequest();
	//     $request->setMerchantAuthentication($merchantAuthentication);
	//     $request->setRefId($refId);
	//     $request->setTransactionRequest( $transactionRequest);
	//     $controller = new AnetController\CreateTransactionController($request);
	//     $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);

	//     if ($response != null)
	//     {
	//       if($response->getMessages()->getResultCode() == "Ok")
	//       {
	//         $tresponse = $response->getTransactionResponse();
	        
	// 	      if ($tresponse != null && $tresponse->getMessages() != null)   
	//         {
	//         	$r['status'] = 1;
	//           	$r['response_code'] =  $tresponse->getResponseCode();
	//           	$r['trans_id'] = $tresponse->getTransId();
	//           	$r['message_code'] = $tresponse->getMessages()[0]->getCode();
	// 	        $r['description'] = $tresponse->getMessages()[0]->getDescription();
	//         }
	//         else
	//         {
	//         	$r['status'] = 0;
	//           	$r['description'] = "Transaction Failed";
	//           if($tresponse->getErrors() != null)
	//           {
	//             $r['error_code'] = $tresponse->getErrors()[0]->getErrorCode();
	//             $r['error_message'] = $tresponse->getErrors()[0]->getErrorText();
	//           }
	//         }
	//       }
	//       else
	//       {
	//       	$r['status'] = 0;
	//         $r['description'] = "Transaction Failed";
	//         $tresponse = $response->getTransactionResponse();
	//         if($tresponse != null && $tresponse->getErrors() != null)
	//         {
	//           $r['error_code'] = $tresponse->getErrors()[0]->getErrorCode();
	//           $r['error_message'] = $tresponse->getErrors()[0]->getErrorText();
	//         }
	//         else
	//         {
	//           $r['error_code'] = $response->getMessages()->getMessage()[0]->getCode();
	//           $r['error_message'] = $response->getMessages()->getMessage()[0]->getText();
	//         }
	//       }      
	//     }
	//     else
	//     {
	//     	$r['status'] = 0;
	//       	$r['description'] = "No response returned";
	//     }
	//     return $r;
	//   }

	// function voidTransaction($transactionid){
	//     /* Create a merchantAuthenticationType object with authentication details
	//        retrieved from the constants file */
	//     $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	//     $merchantAuthentication->setName($this->ID);
	//     $merchantAuthentication->setTransactionKey($this->KEY);
	    
	//     // Set the transaction's refId
	//     $refId = 'ref' . time();
	//     //create a transaction
	//     $transactionRequestType = new AnetAPI\TransactionRequestType();
	//     $transactionRequestType->setTransactionType( "voidTransaction"); 
	//     $transactionRequestType->setRefTransId($transactionid);
	//     $request = new AnetAPI\CreateTransactionRequest();
	//     $request->setMerchantAuthentication($merchantAuthentication);
	// 	  $request->setRefId($refId);
	//     $request->setTransactionRequest( $transactionRequestType);
	//     $controller = new AnetController\CreateTransactionController($request);
	//     $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	    
	//     if ($response != null)
	//     {
	//       if($response->getMessages()->getResultCode() == "Ok")
	//       {
	//         $tresponse = $response->getTransactionResponse();
	        
	// 	      if ($tresponse != null && $tresponse->getMessages() != null)   
	//         {
	//           $r['status'] = 1;
	//           $r['recponse_code']  = $tresponse->getResponseCode();
	//           $r["auth_code"] = $tresponse->getAuthCode();
	//           $r['trans_id'] = $tresponse->getTransId();
	//           $r['message_code'] = $tresponse->getMessages()[0]->getCode(); 
	// 	      $r["description"] = $tresponse->getMessages()[0]->getDescription();
	//         }
	//         else
	//         {
	//           $r["status"] = 0;
	//           $r["description"] = "Transaction Failed";
	//           if($tresponse->getErrors() != null)
	//           {
	//             $r["error_code"] = $tresponse->getErrors()[0]->getErrorCode();
	//             $r["error_message"] = $tresponse->getErrors()[0]->getErrorText();            
	//           }
	//         }
	//       }
	//       else
	//       {
	//       	$r["status"] = 0;
	//         $r["description"] =  "Transaction Failed";
	//         $tresponse = $response->getTransactionResponse();
	//         if($tresponse != null && $tresponse->getErrors() != null)
	//         {
	//           $r["error_code"] =  $tresponse->getErrors()[0]->getErrorCode();
	//           $r["error_message"] =  $tresponse->getErrors()[0]->getErrorText();                      
	//         }
	//         else
	//         {
	//           $r["error_code"] =  $response->getMessages()->getMessage()[0]->getCode();
	//           $r["error_message"] = $response->getMessages()->getMessage()[0]->getText();
	//         }
	//       }      
	//     }
	//     else
	//     {
	//     	$r["status"] = 0;
	//       	$r['description'] = "No response returned";
	//     }
	//     return $r;
	//   }

	// function getTransactionDetails($transactionId)
	// {
	//     /* Create a merchantAuthenticationType object with authentication details
	//        retrieved from the constants file */
	//     $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	//     $merchantAuthentication->setName($this->ID);
	//     $merchantAuthentication->setTransactionKey($this->KEY);
	    
	//     // Set the transaction's refId
	//     // The refId is a Merchant-assigned reference ID for the request.
	//     // If included in the request, this value is included in the response. 
	//     // This feature might be especially useful for multi-threaded applications.
	//     $refId = 'ref' . time();
	//     $request = new AnetAPI\GetTransactionDetailsRequest();
	//     $request->setMerchantAuthentication($merchantAuthentication);
	//     $request->setTransId($transactionId);
	//     $controller = new AnetController\GetTransactionDetailsController($request);
	//     $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	//     if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
	//     {
	//     	$r['status'] = 1;
	//     	$r['description'] = "SUCCESS";
	//     	$r['status'] = $response->getTransaction()->getTransactionStatus();
	//     	$r['amount'] = number_format($response->getTransaction()->getAuthAmount(), 2, '.', '');
	//         $r['trans_id'] = $response->getTransaction()->getTransId();
	//      }
	//     else
	//     {
	//     	$r['status'] = 0;
	//         $r['description'] = "Invalid response";
	//         $r['error_message'] = $response->getMessages()->getMessage();
	//         $r['message'] = $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText();
	//     }
	//     return $r;
	// }

	// function getTransactionList()
	// {
	//     /* Create a merchantAuthenticationType object with authentication details
	//        retrieved from the constants file */
	//     $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
	//     $merchantAuthentication->setName($this->ID);
	//     $merchantAuthentication->setTransactionKey($this->KEY);
	    
	//     // Set the request's refId
	//     $refId = 'ref' . time();
	//     //Setting a valid batch Id for the Merchant
	//     $batchId = $this->bactch_id;
	//     $request = new AnetAPI\GetTransactionListRequest();
	//     $request->setMerchantAuthentication($merchantAuthentication);
	//     $request->setBatchId($this->batch_id);
	//     $controller = new AnetController\GetTransactionListController($request);
	//     //Retrieving transaction list for the given Batch Id
	//     $response = $controller->executeWithApiResponse( \net\authorize\api\constants\ANetEnvironment::SANDBOX);
	//     if (($response != null) && ($response->getMessages()->getResultCode() == "Ok"))
	//     {
	//     		echo "SUCCESS: Get Transaction List for BatchID : " . $batchId;
	//   	  if ($response->getTransactions() == null) {
	//   	  	echo "No Transaction to display in this Batch.";
	//   	  	return ;
	//   	  }
	//   	  //Displaying the details of each transaction in the list
	//   	  $count = 0;
	//   	  $r['status'] = 1;
	//   	  $r['data'] = array();
	//   	  foreach ($response->getTransactions() as $transaction) {
	//   	  	$r['data'][$count]["trans_id"] = $transaction->getTransId(); 
	//   	  	$r['data'][$count]["submitted"] = date_format($transaction->getSubmitTimeLocal(), 'Y-m-d H:i:s');
	//   	  	$r['data'][$count]["status"] = $transaction->getTransactionStatus();
	//   	  	$r['data'][$count]["settled_amount"] = number_format($transaction->getSettleAmount(), 2, '.', '');
	//   	  	$count++;
	//   	  }
	//      }
	//     else
	//     {
	//     	$r['status'] = 0;
	//         $r['error'] = "Invalid response";
	//         $r['error_message'] = $response->getMessages()->getMessage();
	//         $r['response'] = $errorMessages[0]->getCode() . "  " .$errorMessages[0]->getText();
	//     }
	//     return $r;
	//   }


}