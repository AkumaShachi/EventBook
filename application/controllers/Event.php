<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event extends MY_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->global['isNavbar']  = TRUE;
		$this->global['isFooter']  = TRUE;
		$this->global['bodyClass'] = 'home-body';
	}

	public function index()
	{
		$this->load->model('Event_model');

		$palette = array('#ff6b6b', '#4ecdc4', '#f9ca24', '#6c5ce7', '#00b894', '#fd79a8');

		$events = array();
		foreach ($this->Event_model->get_events() as $row) {
			$price = $row['event_price'] !== NULL ? $row['event_price'] : 0;

			$events[] = array(
				'id'       => $row['event_id'],
				'title'    => $row['event_name'],
				'date'     => date('M j, Y', strtotime($row['event_start_date'])),
				'time'     => date('g:i A', strtotime($row['event_start_date'])),
				'location' => $row['event_location'],
				'price'    => $price,
				'capacity' => $row['event_amount'] > 0 ? number_format(intval($row['event_amount'])) : 'Unlimited',
				'color'    => $palette[array_rand($palette)]
			);
		}

		// DEBUG ONLY — print the pulled event data
		log_message('error', 'EVENTS PULLED: ' . print_r($events, true));

		$this->global['pageTitle']  = 'EventBook - Home';
		$this->global['activeNav']  = 'browse';

		$data = array(
			'events' => $events,
			'pageScripts' => array(
				'<script>console.log(\'events pulled:\', ' . json_encode($events, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . ');</script>'
			)
		);

		$this->_render('home/index', $data);
	}

	public function add_event()
	{
		$this->load->model('Event_model');

		$ticketEvents = array();
		foreach ($this->Event_model->get_events() as $row) {
			$ticketEvents[] = array(
				'id'    => $row['event_id'],
				'title' => $row['event_name'],
				'date'  => date('M j, Y', strtotime($row['event_start_date']))
			);
		}

		$this->global['pageTitle'] = 'EventBook - Add Event';
		$this->global['activeNav'] = 'add';

		$this->_render('home/add_event', array('ticketEvents' => $ticketEvents));
	}

	public function event_detail($event_id)
	{
		$this->load->model('Event_model');

		$event = $this->Event_model->get_event($event_id);
		if (!$event) {
			$this->output
				->set_content_type('application/json')
				->set_output(json_encode(array('success' => false, 'message' => 'Event not found')));
			return;
		}

		$role_id = $this->session->userdata('r_id');
		if (empty($role_id)) {
			$role_id = 1;
		}

		$user_id = $this->session->userdata('user_id');
		$booked_event_ids = $user_id ? $this->Event_model->get_booked_event_ids($user_id) : array();

		$required_events = array();
		$seen = array($event_id => true);
		$current_id = !empty($event['event_required']) ? $event['event_required'] : NULL;
		while ($current_id && !isset($seen[$current_id])) {
			$seen[$current_id] = true;
			$req_event = $this->Event_model->get_event($current_id);
			if (!$req_event) {
				break;
			}
			if (!in_array($current_id, $booked_event_ids, true)) {
				$required_events[] = array(
					'event'   => $req_event,
					'tickets' => $this->Event_model->get_event_tickets($current_id, $role_id)
				);
			}
			$current_id = !empty($req_event['event_required']) ? $req_event['event_required'] : NULL;
		}

		$this->output
			->set_content_type('application/json')
			->set_output(json_encode(array(
				'success'         => true,
				'event'           => $event,
				'tickets'         => $this->Event_model->get_event_tickets($event_id, $role_id),
				'required_events' => $required_events
			)));
	}

	public function do_add_event()
	{
		$user_id = $this->session->userdata('user_id');
		if (!$user_id) {
			redirect('login');
			return;
		}

		$json = $this->input->post('payload');
		$data = json_decode($json, true);

		$basics  = !empty($data['Basics'])         ? $data['Basics']         : array();
		$when    = !empty($data['When'])           ? $data['When']           : array();
		$cap     = !empty($data['Capacity'])       ? $data['Capacity']       : array();
		$pricing = !empty($data['Pricing'])        ? $data['Pricing']        : array();
		$req     = !empty($data['Event Required']) ? $data['Event Required'] : array();
		$about   = !empty($data['About'])          ? $data['About']          : array();

		$today = date('Y-m-d');

		$event = array();

		// Basics
		$event['event_name']        = $basics['eventTitle'];
		$event['event_location']    = $basics['eventLocation'];
		$event['event_description'] = $about['eventDescription'];

		// When
		$date_type = $when['eventDateType'];
		if ($date_type === 'single') {
			$event['event_start_date'] = $today;
			$event['event_end_date']   = $when['eventDate'];
		} else {
			$event['event_start_date'] = $when['eventStartDate'];
			$event['event_end_date']   = $when['eventEndDate'];
		}

		// Capacity
		if ($cap['eventCapacityType'] === 'limited') {
			$event['event_amount'] = intval($cap['eventCapacity']);
		} else {
			$event['event_amount'] = 0;
		}

		// Pricing
		$role_pricing = $pricing['eventRolePricing'];
		$price_type   = $pricing['eventPriceType'];
		$roles_map    = array('guest' => 1, 'student' => 2, 'member' => 3);
		$tickets      = array();

		if ($price_type === 'single') {
			if ($role_pricing === 'yes') {
				$map = array('guest' => 'guestPrice', 'student' => 'studentPrice', 'member' => 'memberPrice');
				foreach ($map as $r => $key) {
					$tickets[] = array(
						'role_id'           => $roles_map[$r],
						'ticket_type'       => 'single',
						'ticket_start_date' => $event['event_start_date'],
						'ticket_end_date'   => $pricing[$key . 'Date'],
						'ticket_price'      => $pricing[$key]
					);
				}
			} else {
				foreach ($roles_map as $role_id) {
					$tickets[] = array(
						'role_id'           => $role_id,
						'ticket_type'       => 'single',
						'ticket_start_date' => $event['event_start_date'],
						'ticket_end_date'   => $pricing['eventPriceDate'],
						'ticket_price'      => $pricing['eventPrice']
					);
				}
			}

		} else {
			$early_map = array('guest' => 'earlyGuest', 'student' => 'earlyStudent', 'member' => 'earlyMember');
			$late_map  = array('guest' => 'lateGuest',  'student' => 'lateStudent',  'member' => 'lateMember');
			foreach ($roles_map as $r => $role_id) {
				$early_key     = ($role_pricing === 'yes') ? $early_map[$r]              : 'earlyPrice';
				$early_date_key = ($role_pricing === 'yes') ? $early_map[$r] . 'Date'    : 'earlyDate';
				$late_key      = ($role_pricing === 'yes') ? $late_map[$r]               : 'latePrice';
				$late_date_key = ($role_pricing === 'yes') ? $late_map[$r] . 'Date'      : 'lateDate';

				$tickets[] = array(
					'role_id'           => $role_id,
					'ticket_type'       => 'early',
					'ticket_start_date' => $event['event_start_date'],
					'ticket_end_date'   => $pricing[$early_date_key],
					'ticket_price'      => $pricing[$early_key]
				);
				$tickets[] = array(
					'role_id'           => $role_id,
					'ticket_type'       => 'late',
					'ticket_start_date' => $pricing[$early_date_key],
					'ticket_end_date'   => $pricing[$late_date_key],
					'ticket_price'      => $pricing[$late_key]
				);
			}
		}

		// Event Required
		if ($req['eventRequiresTicket'] === 'yes') {
			$event['event_required'] = $req['eventRequiresWhich'];

			$this->load->model('Event_model');
			$required = $this->Event_model->get_event($req['eventRequiresWhich']);
			if ($required && !empty($required['event_end_date'])) {
				$event['event_start_date'] = $required['event_end_date'];
			}
		} else {
			$event['event_required'] = NULL;
		}

		// DEBUG ONLY — print the transformed payload, no DB save yet
		log_message('error', 'ADD EVENT TRANSFORMED: ' . print_r(array('event' => $event, 'tickets' => $tickets), true));

		$inserted_id = $this->Event_model->create_event($event);

		if ($inserted_id) {
			$event['event_id'] = $inserted_id;
			foreach ($tickets as &$ticket) {
				$ticket['event_id'] = $inserted_id;
			}
			$this->Event_model->create_tickets($tickets);
			redirect('home');
		} else {
			$this->session->set_flashdata('event_error', 'Failed to save event. Please try again.');
			redirect('event/add_event');
		}
	}
}