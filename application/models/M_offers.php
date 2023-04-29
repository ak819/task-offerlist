<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_offers extends CI_Model {

	var $table = 'offers';
	var $column_order = array(null,'heading','start_date_time','end_date_time',null,null); //set column field  for datatable orderable
	var $column_search = array('heading','description'); //set column field  for datatable searchable
	var $order = array('id' => 'desc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query($startdate,$enddate,$status)
	{
		
		$this->db->from($this->table);

		if($startdate && $enddate)
		{
           $this->db->where('DATE(start_date_time) >=',$startdate);
            $this->db->where('DATE(end_date_time) <=',$enddate);
		}
		if($startdate && $enddate=="")
		{
            $this->db->where('DATE(start_date_time) >=',$startdate);
		}
		if($enddate && $startdate=="")
		{
             $this->db->where('DATE(end_date_time) <=',$enddate);
		}
		if($status!=="")
		{
			$this->db->where('is_active',$status);
		}

		$i = 0;
	
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables($startdate,$enddate,$status)
	{ 
		
		$this->_get_datatables_query($startdate,$enddate,$status);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered($startdate,$enddate,$status)
	{
		$this->_get_datatables_query($startdate,$enddate,$status);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_guid($guid)
	{
		$this->db->from($this->table);
		$this->db->where('guid',$guid);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_guid($guid)
	{
		$this->db->where('guid', $guid);
		$this->db->delete($this->table);
	}

	public function getOffers($rowperpage,$rowno)
    {
          // get offer by current date btween to start_date_time and end_date_time
        $todaydate=date('Y-m-d');
        $this->db->select('guid,heading,description,start_date_time,end_date_time,photo,DATEDIFF( CURDATE() ,end_date_time ) as endtime');
         $this->db->from($this->table);
          $this->db->where('DATE(start_date_time) >=',$todaydate);
         $this->db->where('DATE(end_date_time) >=',$todaydate);
         $this->db->where('is_active',1);
         $this->db->order_by('id','desc'); 
         $this->db->limit($rowperpage, $rowno);
         $query = $this->db->get();
        if ($query->num_rows() > 0) {
            return $query->result();
        } else {
            return array();
        }

      
    }


      public function getAllActiveOfferCount()
    { 
    	// get all count of offer for pagination
    	$todaydate=date('Y-m-d');
        $this->db->select('guid');
        $this->db->from($this->table);
         $this->db->where('DATE(start_date_time) >=',$todaydate);
         $this->db->where('DATE(end_date_time) >=',$todaydate);
         $this->db->where('is_active',1);
         $query = $this->db->get();
         return $query->num_rows();
    }



}
