<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Offer extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->model('m_offers','offers');
		$this->img_path = realpath(APPPATH.'../uploads/offers');
	}

	Public function index()
	{

      $this->load->view('display-offers');

	}

	public function offerlist()
	{

		$this->load->view('manage-offers');
	}

	public function ajax_list()
	{
		$startdate=($this->input->post('start_date'))?date_dbformate($this->input->post('start_date')) : "";
		$enddate=($this->input->post('end_date'))?date_dbformate($this->input->post('end_date')) : "";
		$status=$this->input->post('is_active');

		$list = $this->offers->get_datatables($startdate,$enddate,$status);
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $offer) {
			$no++;
			$row = array();
			if($offer->photo)
			{
				$row[] = '<a href="'.base_url('uploads/offers/'.$offer->photo).'" target="_blank"><img src="'.base_url('uploads/offers/'.$offer->photo).'" height="150px"; width="150px" class="img-responsive" /></a>';
			}
			else{
				$row[] = 'No photo';
			}
			$row[] = $offer->heading;
			$row[] = datetimeformat($offer->start_date_time);
			$row[] = datetimeformat($offer->end_date_time);
			$row[] = ($offer->is_active)?"Yes":'No';

			//binding html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_offer('."'".$offer->guid."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_offer('."'".$offer->guid."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';
		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->offers->count_all(),
						"recordsFiltered" => $this->offers->count_filtered($startdate,$enddate,$status),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function edit($guid)
	{
		$data = $this->offers->get_by_guid($guid);
		$data->start_date_time = datetimeformat($data->start_date_time);
		$data->end_date_time =datetimeformat($data->end_date_time); 
	
		echo json_encode($data);
	}

	public function store()
	{
		$this->_validate();
		$data = array(
			    'guid'=>getGUID(),
				'heading' => $this->input->post('heading'),
				'description' => $this->input->post('description'),
				'start_date_time'=>convert_datetimeto24hr($this->input->post('start_date')),
				'end_date_time'=> convert_datetimeto24hr($this->input->post('end_date')),
				'is_active' => $this->input->post('is_active'),
			);

		if(!empty($_FILES['photo']['name']))
		{
			 $image=uplodeImage($this->img_path,'photo');	
		}
		else
		     {

	    	 $image="";

             }
         $data['photo'] = $image;
		$insert = $this->offers->save($data);

		echo json_encode(array("status" => TRUE));
	}

	public function update()
	{
		$this->_validate();
		$data = array(
				'heading' => $this->input->post('heading'),
				'description' => $this->input->post('description'),
				'start_date_time'=>convert_datetimeto24hr($this->input->post('start_date')),
				'end_date_time'=> convert_datetimeto24hr($this->input->post('end_date')),
				'is_active' => $this->input->post('is_active'),
			);

	     
         $offer = $this->offers->get_by_guid($this->input->post('id'));
         // if photo available then upload new remove old one
	     if(!empty($_FILES['photo']['name']))
		{    
			if(file_exists('uploads/offers/'.$offer->photo) && $offer->photo)
				unlink('uploads/offers/'.$offer->photo);
			 $image=uplodeImage($this->img_path,'photo');
			 $data['photo'] = $image;	
		}

		$this->offers->update(['id' =>$offer->id], $data);
		echo json_encode(array("status" => TRUE));
	}

	public function delete($guid)
	{
		
		$offer = $this->offers->get_by_guid($guid);
		if(file_exists('upload/offers/'.$offer->photo) && $offer->photo)
			//delete file from directory
			unlink('upload/offers/'.$offer->photo); 
		
		$this->offers->delete_by_guid($guid);
		echo json_encode(array("status" => TRUE));
	}

	
	private function _validate()
	{
		// offer form validation json response with errors 
		$data = array();
		$data['error_string'] = array();
		$data['inputerror'] = array();
		$data['status'] = TRUE;

		if($this->input->post('heading') == '')
		{
			$data['inputerror'][] = 'heading';
			$data['error_string'][] = 'Heading is required';
			$data['status'] = FALSE;
		}
        
        if($this->input->post('description') == '')
		{
			$data['inputerror'][] = 'description';
			$data['error_string'][] = 'Description is required';
			$data['status'] = FALSE;
		}
		

		if($this->input->post('start_date') == '')
		{
			$data['inputerror'][] = 'start_date';
			$data['error_string'][] = 'Start date is required';
			$data['status'] = FALSE;
		}
		if($this->input->post('end_date') == '')
		{
			$data['inputerror'][] = 'end_date';
			$data['error_string'][] = 'End date is required';
			$data['status'] = FALSE;
		}


		if($data['status'] === FALSE)
		{
			echo json_encode($data);
			exit();
		}
	}
    
    public function loadOffers($rowno)
    {

	    $rowperpage = 4;
	    if($rowno != 0){

	      $rowno = ($rowno-1) * $rowperpage;
	    
	    }
         $activity=$datacount="";
    
			$offers = $this->offers->getOffers($rowperpage,$rowno);
			$datacount=$this->offers->getAllActiveOfferCount();
       

  
        $offerhtml='';
        if($offers)
        {  
        	$limit="";
        	foreach ($offers as $val) {

                // time left to end this offer
        		$end_time = new DateTime($val->end_date_time);
				$current_time = new DateTime();
				$interval = $current_time->diff($end_time);

				if ($interval->invert) {
				  $limit="The end date has already passed.";
				} else {
				   $limit=$interval->format("Time left until %a days, %h hours, %i minutes, and %s seconds.");
				}
                // binding html to display offers
               $offerhtml.='<div class="col-md-3 col-sm-4 col-12">
                    <div class="card">
                        <div class="img-part">
                            <img src="'.base_url('uploads/offers/'.$val->photo).'" alt="" />
                        </div>
                        <div class="text-part">
                            <strong>'.$val->heading.'</strong>
                            <p>'.$val->description.'</p>
                            <strong>'.$limit.'</strong>
                        </div>
                    </div>
                </div>';
               
        	}



        }
        
          $data['offers']=$offerhtml;
	    $config['base_url'] = base_url().'display-offer';
	    $config['use_page_numbers'] = TRUE;
	    $config['full_tag_open'] = '<div class="row negpad"><nav aria-label="..."><ul class="pagination">';
		$config['full_tag_close'] = '</ul></nav></div>';
		$config['first_link'] = false;
		$config['last_link'] = false;
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = '&laquo';
		$config['prev_tag_open'] = '<li class="page-item prev">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = '&raquo';
		$config['next_tag_open'] = '<liclass="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<liclass="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="page-item active"><a class="page-link">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
	    $config['total_rows'] = floatval($datacount);
	    $config['per_page'] = $rowperpage;
	    // Initialize
	    $this->pagination->initialize($config);
	    $data['pagination'] = $this->pagination->create_links();
	    $data['result']=$data['offers'];
	    $data['row'] = $rowno;
	     echo json_encode($data);

    }

}
