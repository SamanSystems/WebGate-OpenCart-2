<?php
class ModelPaymentZarinpal extends Model {
  	public function getMethod() {
		$this->load->language('payment/zarinpal');

		if ($this->config->get('zarinpal_status')) {
      		  	$status = TRUE;
      	} else {
			$status = FALSE;
		}
		
		$method_data = array();
	
		if ($status) {  
      		$method_data = array( 
			    'terms'      => '',
        		'code'       => 'zarinpal',
        		'title'      => $this->language->get('text_title')."<br/><img src='catalog/view/theme/default/image/zarinpal.png'/>",
				'sort_order' => $this->config->get('zarinpal_sort_order')
      		);
    	}
   
    	return $method_data;
  	}
}
?>
