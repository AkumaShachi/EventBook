<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Buying extends MY_Controller {

	public function booking_modal()
	{
		$this->load->view('modal/booking_modal');
	}

	public function payment_modal()
	{
		$this->load->view('modal/payment_modal');
	}

	public function my_tickets()
	{
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			redirect('login');
		}

		$this->global['isNavbar']   = TRUE;
		$this->global['isFooter']   = TRUE;
		$this->global['bodyClass']  = 'home-body';
		$this->global['pageTitle']  = 'EventBook - My Tickets';
		$this->global['activeNav']  = 'tickets';

		$this->load->model('Event_model');

		$role = (int) $this->session->userdata('r_id');
		$tickets = ($role === 4)
			? $this->Event_model->get_all_tickets()
			: $this->Event_model->get_user_tickets($user_id);

		$this->_render('buying/my_tickets', array(
			'tickets' => $tickets
		));
	}

	public function do_booking()
	{
		$this->load->model('Event_model');
		$this->load->library('upload');

		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			$this->_json(array('success' => false, 'message' => 'Not logged in'));
			return;
		}

		$event_id   = intval($this->input->post('event_id'));
		$qty        = intval($this->input->post('qty'));
		$ticket_ids = $this->input->post('ticket_ids');

		if ($qty < 1) {
			$qty = 1;
		}

		if (empty($ticket_ids)) {
			$this->_json(array('success' => false, 'message' => 'No ticket selected'));
			return;
		}

		$event = $this->Event_model->get_event($event_id);
		if (!$event) {
			$this->_json(array('success' => false, 'message' => 'Event not found'));
			return;
		}

		$dir = 'assets/receipt/' . $this->_slugify($event['event_name']) . '/';
		if (!is_dir($dir)) {
			mkdir($dir, 0777, true);
		}

		$this->upload->initialize(array(
			'upload_path'   => './' . $dir,
			'allowed_types' => 'jpg|jpeg|png|gif|webp',
			'max_size'      => 4096,
			'file_name'     => 'receipt_' . time()
		));

		if (!$this->upload->do_upload('receipt')) {
			$this->_json(array('success' => false, 'message' => $this->upload->display_errors('', '')));
			return;
		}

		$up          = $this->upload->data();
		$receipt_path = $dir . $up['file_name'];
		$buy_date    = date('Y-m-d H:i:s');

		$count = 0;
		foreach ($ticket_ids as $tid) {
			$tid = intval($tid);
			if ($tid < 1) {
				continue;
			}
			for ($i = 0; $i < $qty; $i++) {
				$this->Event_model->create_book(array(
					'user_id'         => $user_id,
					'ticket_id'       => $tid,
					'ticket_buy_date' => $buy_date,
					'ticket_receipt'  => $receipt_path
				));
				$count++;
			}
		}

		$this->_json(array(
			'success' => $count > 0,
			'count'   => $count,
			'receipt' => $receipt_path
		));
	}
}