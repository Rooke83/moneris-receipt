<?php

class Moneris_Receipt {
	
	/**
	* A class for generating receipts for Moneris Purchases.
	* ------------------------------------------------
	* Copyright (C) 2016 Baldwin Browne
	* @author Baldwin Browne (@b_browne)
	*
	* Permission is hereby granted, free of charge, to any person obtaining a copy 
	* of this software and associated documentation files (the "Software"), to deal 
	* in the Software without restriction, including without limitation the rights 
	* to use, copy, modify, merge, publish, distribute, sublicense, and/or sell 
	* copies of the Software, and to permit persons to whom the Software is 
	* furnished to do so, subject to the following conditions:
	*
	* The above copyright notice and this permission notice shall be included in 
	* all copies or substantial portions of the Software.
	*
	* THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR 
	* IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, 
	* FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL 
	* THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER 
	* LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, 
	* OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE 
	* SOFTWARE.
	*
	*/
	
	/**
	* This class holds (except for return policy) the information needed in a receipt for a Moneris Transaction
	* according to their documentation pdf eSELECTplus_PHP_IG.pdf
	*
	* This class is construced with the XML response for Purchase trannsaction from 
	* Moneris and an array of additional details about the purchase
	*/
	
	/**
	* @var array
	* this array contains all the information needed in the receipt
	*/
	protected $_receipt_info;
	
	/**
	* These variables represent information required in the Moneris receipt
	*/
	
	// amount processed during transactoin transaction
	protected $_amount;
	
	// type of tranaaction
	protected $_type;
	
	// the date of transaction
	protected $_date_time;
	
	// order ID
	protected $_order_id;
	
	// response code
	protected $_response_code;
	
	// ISO code
	protected $_ISO_code;
	
	// response message
	protected $_response_message;
	
	// reference number
	protected $_ref_num;
	
	// merchant name
	protected $_merch_name;
	
	// merchant URL
	protected $_merch_url;
	
	// cardholder name
	protected $_card_holder_name;
	
	// card holder eamil
	protected $_card_holder_email;
	
	// card holder address
	protected $_card_holder_address;
	
	// card holder city
	protected $_card_holder_city;
	
	// card holder province
	protected $_card_holder_province;
	
	// card holder postal code
	protected $_card_holder_pc;
	
	// authorization code
	protected $_auth_code;
	
	/**
	* goods or services order
	* @var array
	*/
	protected $_items;
	
	//protected $_desc;
	
	
	/**
	* @param SimpleXMLElement returned by Moneris
	* @param array of additional info
	*			Keys:
	*				- type - string - the type of transaction
	*				- items - array - a list of items bought
	*				- merch_name - string - the merchant's name
	*				- merch_url - string - the merchant's url
	*				- card_holder_name - string - the name of the card holder
	*				- card_holder_address - string - the street address of the card holder
	*				- card_holder_province - string - the province the card holder lives in
	*				- card_holder_city - string - the city of the car holder
	*				- card_holder_pc - string - the postal code of the card holder
	*				- card_holder_email - string - the email of the card holder
	*/
	public function __construct(SimpleXMLElement $result, array $additional_info = array()) {
		$this->prepare_receipt($result, $additional_info);
	}
	
	protected function prepare_receipt(SimpleXMLElement $response, array $additional_info = array()) {
		
		
		
		$params['amount'] = $response->receipt->TransAmount ? $response->receipt->TransAmount : "Amount not set";
		
		//TODO get plain text transaction type from Moneris XML response
		$params['type'] = isset($additional_info['type']) ? $additional_info['type'] : "Amount not set";
		$params['date'] = $response->receipt->TransDate ? $response->receipt->TransDate : "Date not set";
		$params['time'] = $response->receipt->TransTime ? $response->receipt->TransTime : "Time not set";
		
		$params['order_id'] = $response->receipt->ReceiptId ? $response->receipt->ReceiptId : "Order ID not set.";
		
		$params['response_code'] = $response->receipt->ResponseCode ? $response->receipt->ResponseCode : "Response code not set";
		$params['iso_code'] = $response->receipt->ISO ? $response->receipt->ISO : "ISO code not set.";
		$params['auth_code'] = $response->receipt->AuthCode ? $response->receipt->AuthCode : "Auth code not set";
		$params['response_message'] = $response->receipt->Message ? $response->receipt->Message : "Message not set";
		$params['ref_num'] = $response->receipt->ReferenceNum ? $response->receipt->ReferenceNum : "Reference Number not set";

		$params['items'] = isset($additional_info['items']) ? $additional_info['items'] : "No items given.";
		$params['merch_name'] = isset($additional_info['merch_name']) ? $additional_info['merch_name'] : "Merchant name not set";
		$params['merch_url'] = isset($additional_info['merch_url']) ? $additional_info['merch_url'] : "Merchant URL not set";
		
		$params['card_holder_name'] = isset($additional_info['card_holder_name']) ? $additional_info['card_holder_name'] : "Card holder name not set";
		$params['card_holder_address'] = isset($additional_info['card_holder_address']) ? $additional_info['card_holder_address'] : "Card holder address not set";
		$params['card_holder_province'] = isset($additional_info['card_holder_province']) ? $additional_info['card_holder_province'] : "Card holder province not set";
		$params['card_holder_pc'] = isset($additional_info['card_holder_pc']) ? $additional_info['card_holder_pc'] : "Card holder postal code not set";
		$params['card_holder_city'] = isset($additional_info['card_holder_city']) ? $additional_info['card_holder_city'] : "Card holder city not set";
		$params['card_holder_email'] = isset($additional_info['card_holder_email']) ? $additional_info['card_holder_email'] : "Card holder email not set";
		
		//$params['description'] = isset($additional_info['description']) ? $additional_info['description'] : "No additional info given.";
		
		
		
		$prepared_receipt = array(
			'type' => $params['type'],
			'amount' => $params['amount'],
			'date' => $params['date'],
			'time' => $params['time'],
			'order_id' => $params['order_id'],
			'response_code' => $params['response_code'],
			'iso_code' => $params['iso_code'],
			'response_message' => $params['response_message'],
			'ref_num' => $params['ref_num'],
			'items' => $params['items'],
			'merch_name' => $params['merch_name'],
			'merch_url' => $params['merch_url'],
			'card_holder_name' => $params['card_holder_name'],
			'card_holder_address' => $params['card_holder_address'],
			'card_holder_province' => $params['card_holder_province'],
			'card_holder_pc' => $params['card_holder_pc'],
			'card_holder_city' => $params['card_holder_city'],
			'card_hplder_email' => $params['card_holder_email'],
			'items' => $params['items'],
			//'description' => $params['description'],
			'auth_code' => $params['auth_code']
		);
		
		$this->_receipt_info = $prepared_receipt;
		
		$this->_amount = $this->_receipt_info['amount'];
		$this->_type = $this->_receipt_info['type'];
		$this->_date_time = $this->_receipt_info['date'] ." ". $this->_receipt_info['time'];
		$this->_order_id = $this->_receipt_info['order_id'];
		$this->_response_code = $this->_receipt_info['response_code'];
		$this->_ISO_code = $this->_receipt_info['iso_code'];
		$this->_response_message = $this->_receipt_info['response_message'];
		$this->_ref_num = $this->_receipt_info['ref_num'];
		$this->_items = $this->_receipt_info['items'];
		$this->_merch_name = $this->_receipt_info['merch_name'];
		$this->_merch_url = $this->_receipt_info['merch_url'];
		$this->_card_holder_name = $this->_receipt_info['card_holder_name'];
		$this->_card_holder_email = $this->_receipt_info['card_hplder_email'];
		$this->_card_holder_address = $this->_receipt_info['card_holder_address'];
		$this->_card_holder_city = $this->_receipt_info['card_holder_city'];
		$this->_card_holder_province = $this->_receipt_info['card_holder_province'];
		$this->_card_holder_pc = $this->_receipt_info['card_holder_pc'];
		$this->_auth_code = $this->_receipt_info['auth_code'];
		//$this->_desc = $this->_receipt_info['description'];
		
		
	}
	
	public function getReceiptArray() {
		return $this->_receipt_info;
	}
	
	public function getAmount() {
		return $this->_amount;
	}
	
	public function getType() {
		return $this->_type;
	}

	public function getDateTime() {
		return $this->_date_time;
	}
	
	public function getOrderID() {
		return $this->_order_id;
	}
	
	public function getResponseCode() {
		return $this->_response_code;
	}
	
	public function getISO() {
		return $this->_ISO_code;
	}
	
	public function getResponseMessage() {
		return $this->_response_message;
	}
	
	public function getRefNum() {
		return $this->_ref_num;
	}
	
	/**
	* @return array of items
	*/
	public function getItems() {
		return $this->_items;
	}
	
	public function getMerchName() {
		return $this->_merch_name;
	}
	
	public function getMerchUrl() {
		return $this->_merch_url;
	}
	
	public function getCHName() {
		return $this->_card_holder_name;
	}
	
	public function getCHEmail() {
		return $this->_card_holder_email;
	}
	
	public function getCHAddress() {
		return $this->_card_holder_address;
	}
	
	public function getCHPC() {
		return $this->_card_holder_pc;
	}
	
	public function getCHCity() {
		return $this->_card_holder_city;
	}
	
	public function getCHProvince() {
		return $this->_card_holder_province;
	}
	
	public function getAuthCode() {
		return $this->_auth_code;
	}
	
	
	/**
	* TODO Complete this function to generate and send an email using the receipt 
	* information.
	*/
	public function sendEmail() {
		
		$subject = "";
		
		$body = "";
		
		$headers  = array();
		$headers[] = "MIME-Version: 1.0";
		$headers[] = "Content-type:";
		$headers[] = "From:";
		$headers[] = "To: <".$this_card_holder_email.">";
		//$headers[] = "Bcc:";
		$headers[] = "Date: " . date('r', $_SERVER['REQUEST_TIME']);
		$headers[] = "Subject: {$subject}";
		$headers[] = "X-Mailer: PHP/".phpversion();
		
		mail($this->_card_holder_email, $subject, $body, implode("\r\n", $headers));
		
		
	}
	
	//public function getDesc() {
	//	return $this->_desc;
	//}
}

?>