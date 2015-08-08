<?php
class Controllerpaymentzarinpal extends Controller {
	private $error = array();

	public function index() {
		$this->load->language('payment/zarinpal');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('zarinpal', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_developers'] = $this->language->get('text_developers');

		$data['entry_MerchantID'] = $this->language->get('entry_MerchantID');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');

		$data['help_encryption'] = $this->language->get('help_encryption');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		$data['tab_general'] = $this->language->get('tab_general');
		$data['entry_template'] = $this->language->get('entry_template');
		

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['MerchantID'])) {
			$data['error_MerchantID'] = $this->error['MerchantID'];
		} else {
			$data['error_MerchantID'] = '';
		}

		//$this->document->breadcrumbs = array();
        $data['breadcrumbs'] = array();
   	$this->document->breadcrumbs[] = array(
       		//'href'      => $this->url->https('common/home'),
			'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_home'),
      		'separator' => FALSE
   		);

   		$this->document->breadcrumbs[] = array(
       		//'href'      => $this->url->https('extension/payment'),
			'href'      => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('text_payment'),
      		'separator' => ' :: '
   		);

   		$this->document->breadcrumbs[] = array(
       		//'href'      => $this->url->https('payment/sb24'),
			'href'      => $this->url->link('payment/zarinpal', 'token=' . $this->session->data['token'], 'SSL'),
       		'text'      => $this->language->get('heading_title'),
      		'separator' => ' :: '
   		);


			$data['action'] = $this->url->link('payment/zarinpal', 'token=' . $this->session->data['token'], 'SSL');

		  $data['cancel'] = $this->url->link('extension/zarinpal', 'token=' . $this->session->data['token'], 'SSL');

		if (isset($this->request->post['zarinpal_MerchantID'])) {
			$data['zarinpal_MerchantID'] = $this->request->post['zarinpal_MerchantID'];
		} else {
			$data['zarinpal_MerchantID'] = $this->config->get('zarinpal_MerchantID');
		}


		if (isset($this->request->post['zarinpal_order_status_id'])) {
			$data['zarinpal_order_status_id'] = $this->request->post['zarinpal_order_status_id'];
		} else {
			$data['zarinpal_order_status_id'] = $this->config->get('zarinpal_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['zarinpal_status'])) {
			$data['zarinpal_status'] = $this->request->post['zarinpal_status'];
		} else {
			$data['zarinpal_status'] = $this->config->get('zarinpal_status');
		}

		if (isset($this->request->post['zarinpal_sort_order'])) {
			$data['zarinpal_sort_order'] = $this->request->post['zarinpal_sort_order'];
		} else {
			$data['zarinpal_sort_order'] = $this->config->get('zarinpal_sort_order');
		}
		
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		//$this->response->setOutput($this->render(TRUE), $this->config->get('config_compression'));
		
		$this->response->setOutput($this->load->view('payment/zarinpal.tpl', $data));
	}

	private function validate() {

		if (!$this->user->hasPermission('modify', 'payment/zarinpal')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['zarinpal_MerchantID']) {
			$this->error['MerchantID'] = $this->language->get('error_MerchantID');
		}


		if (!$this->error) {
			return TRUE;
		} else {
			return FALSE;
		}
	}
}
?>
