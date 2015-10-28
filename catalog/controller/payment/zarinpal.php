<?php
require_once(DIR_SYSTEM.'library/zarinpal_methods/methods.php');
class Controllerpaymentzarinpal extends Controller {
	public function index() {
		$this->load->language('payment/zarinpal');

		$data['text_instruction'] = $this->language->get('text_instruction');
		$data['text_description'] = $this->language->get('text_description');
		$data['text_payment'] = $this->language->get('text_payment');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['bank'] = nl2br($this->config->get('zarinpal_bank' . $this->config->get('config_language_id')));

		$data['continue'] = $this->url->link('checkout/success');

		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/zarinpal.tpl')) {
			return $this->load->view($this->config->get('config_template') . '/template/payment/zarinpal.tpl', $data);
		} else {
			return $this->load->view('default/template/payment/zarinpal.tpl', $data);
		}
	}
public function confirm() {

		$this->load->model('checkout/order');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));

		//$data['Amount'] = $this->currency->format($order_info['total'], 'TMN', $order_info['value'], FALSE);


		$data['Amount'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);

		//$data['Amount']=$data['Amount']\10;


		$data['MerchantID']=$this->config->get('zarinpal_MerchantID');


		$data['ResNum'] = $this->session->data['order_id'];

		$data['return'] = $this->url->link('checkout/success', '', 'SSL');
		//$data['return'] = HTTPS_SERVER . 'index.php?route=checkout/success';

		$data['cancel_return'] = $this->url->link('checkout/payment', '', 'SSL');
		//$data['cancel_return'] = HTTPS_SERVER . 'index.php?route=checkout/payment';

		$data['back'] = $this->url->link('checkout/payment', '', 'SSL');



		$amount = $data['Amount'];
		if($this->currency->getCode()!='RLS') {
		    $amount=$amount * 1;
	    }

        $data['order_id'] = $encryption->encrypt($this->session->data['order_id']);

	//	$callbackUrl  =  $this->url->link('payment/zarinpal/callback', 'order_id=' . $data['order_id'], 'SSL');
		$callbackUrl  =  $this->url->link('payment/zarinpal/callback&order_id=' . $data['order_id']);

        $result = Request($data['MerchantID'],$amount,'پرداحت سفارش شماره : '.$this->session->data['order_id'],$callbackUrl);
        if($result->Status != 100){
            $json = array();
	    	$json['error']= "Can not connect to zarinpal.<br>";

		    @$this->response->setOutput(json_encode($json));
        }


		if($result->Status == 100){

		$data['action'] = "https://www.zarinpal.com/pg/StartPay/" . $result->Authority;
		$json = array();
		$json['success']= $data['action'];

		$this->response->setOutput(json_encode($json));

		} else {

			$this->CheckState($result->Status );
			//die();
		}

//



}

	public function CheckState($status) {
		$json = array();



			$json['error']=  $status ;



		$this->response->setOutput(json_encode($json));

}

function verify_payment($Authority,$Amount){

    $data['MerchantID'] = $this->config->get('zarinpal_MerchantID');
    $result = Verification($data['MerchantID'],$Amount,$Authority);
	$this->CheckState($result);

	if($result->Status==100)
		return true;

	else {
		return false;
	}

	}


	public function callback() {
		$this->load->library('encryption');

		$encryption = new Encryption($this->config->get('config_encryption'));
        $Authority = $_GET['Authority'];
		$order_id = $encryption->decrypt($this->request->get['order_id']);
		$MerchantID=$this->config->get('zarinpal_MerchantID');
		$debugmod=false;

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);


			$Amount = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false);		//echo $data['Amount'];
		@$amount = $Amount/$order_info['currency_value'];

		if ($order_info) {
			if(($this->verify_payment($Authority,$amount )) or ($debugmod==true)) {
			$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('zarinpal_order_status_id'),'شماره رسيد ديجيتالي; Authority: ');

				$this->response->setOutput('<html><head><meta http-equiv="refresh" CONTENT="2; url=' . $this->url->link('checkout/success') . '"></head><body><table border="0" width="100%"><tr><td>&nbsp;</td><td style="border: 1px solid gray; font-family: tahoma; font-size: 14px; direction: rtl; text-align: right;">با تشکر پرداخت تکمیل شد.لطفا چند لحظه صبر کنید و یا  <a href="' . $this->url->link('checkout/success') . '"><b>اینجا کلیک نمایید</b></a></td><td>&nbsp;</td></tr></table></body></html>');
			}else{
                $this->response->setOutput('<html><body><table border="0" width="100%"><tr><td>&nbsp;</td><td style="border: 1px solid gray; font-family: tahoma; font-size: 14px; direction: rtl; text-align: right;">.<br />خريد انجام نشد<br /><a href="' . $this->url->link('checkout/cart').  '"><b>بازگشت به فروشگاه</b></a></td><td>&nbsp;</td></tr></table></body></html>');
            }
		} else {
			$this->response->setOutput('<html><body><table border="0" width="100%"><tr><td>&nbsp;</td><td style="border: 1px solid gray; font-family: tahoma; font-size: 14px; direction: rtl; text-align: right;">.<br /><br /><a href="' . $this->url->link('checkout/cart').  '"><b>بازگشت به فروشگاه</b></a></td><td>&nbsp;</td></tr></table></body></html>');
		}
	}

}
?>
