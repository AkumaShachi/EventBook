<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Event_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function create_event($data)
	{
		$this->db->insert('events', $data);
		return $this->db->insert_id();
	}

	public function create_tickets($tickets)
	{
		if (empty($tickets)) {
			return;
		}
		$this->db->insert_batch('tickets', $tickets);
	}

	public function get_events()
	{
		$this->db->select('e.event_id, e.event_name, e.event_location, e.event_start_date, e.event_end_date, e.event_amount, e.event_required, e.event_description, MIN(CASE WHEN t.role_id = 1 OR t.role_id IS NULL THEN t.ticket_price END) AS event_price', FALSE);
		$this->db->from('events AS e');
		$this->db->join('tickets AS t', 't.event_id = e.event_id', 'left');
		$this->db->group_by('e.event_id');
		$this->db->order_by('e.event_start_date', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_event($event_id)
	{
		$query = $this->db->get_where('events', array('event_id' => $event_id));
		return $query->row_array();
	}

	public function get_booked_event_ids($user_id)
	{
		$this->db->select('t.event_id', FALSE);
		$this->db->from('books AS b');
		$this->db->join('tickets AS t', 't.ticket_id = b.ticket_id');
		$this->db->where('b.user_id', $user_id);
		$query = $this->db->get();
		return array_map(function ($row) {
			return $row['event_id'];
		}, $query->result_array());
	}

	public function get_user_tickets($user_id)
	{
		$this->db->select('b.ticket_buy_date, b.ticket_receipt, t.ticket_id, t.ticket_type, t.ticket_price, e.event_id, e.event_name, e.event_start_date, e.event_location');
		$this->db->from('books AS b');
		$this->db->join('tickets AS t', 't.ticket_id = b.ticket_id');
		$this->db->join('events AS e', 'e.event_id = t.event_id');
		$this->db->where('b.user_id', $user_id);
		$this->db->order_by('b.ticket_buy_date', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function get_all_tickets()
	{
		$this->db->select('b.ticket_buy_date, b.ticket_receipt, t.ticket_id, t.ticket_type, t.ticket_price, e.event_id, e.event_name, e.event_start_date, e.event_location');
		$this->db->from('books AS b');
		$this->db->join('tickets AS t', 't.ticket_id = b.ticket_id');
		$this->db->join('events AS e', 'e.event_id = t.event_id');
		$this->db->order_by('b.ticket_buy_date', 'DESC');
		$query = $this->db->get();
		return $query->result_array();
	}

	public function create_book($data)
	{
		$this->db->insert('books', $data);
		return $this->db->insert_id();
	}

	public function get_event_tickets($event_id, $role_id = NULL)
	{
		$this->db->select('t.*, r.role_name');
		$this->db->from('tickets AS t');
		$this->db->join('roles AS r', 'r.role_id = t.role_id', 'left');
		$this->db->where('t.event_id', $event_id);
		if ($role_id !== NULL) {
			$this->db->where('(t.role_id = ' . (int) $role_id . ' OR t.role_id IS NULL)');
		}
		$this->db->order_by("FIELD(t.ticket_type, 'single', 'early', 'late')", '', FALSE);
		$this->db->order_by('t.ticket_end_date', 'ASC');
		$query = $this->db->get();
		return $query->result_array();
	}
}
