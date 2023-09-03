<?php



if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Book extends Parent_Controller {



    function __construct() {

        parent::__construct();

    }



    public function index() {

        $this->session->set_userdata('top_menu', 'Library');

        $this->session->set_userdata('sub_menu', 'book/index');

        $data['title'] = 'Add Book';

        $data['title_list'] = 'Book Details';

        $search_title= (trim($this->input->get('search_title')) == ''?null:trim($this->input->get('search_title')));
			$search_book_no= (trim($this->input->get('search_book_no')) == ''?null:trim($this->input->get('search_book_no')));
			$search_isbn= (trim($this->input->get('search_isbn')) == ''?null:trim($this->input->get('search_isbn')));
			$search_author= (trim($this->input->get('search_author')) == ''?null:trim($this->input->get('search_author')));
			$search_barcode= (trim($this->input->get('search_barcode')) == ''?null:trim($this->input->get('search_barcode')));
            $search_barcode= (trim($search_barcode,"*"));
			$search_subject= (trim($this->input->get('search_subject')) == ''?null:trim($this->input->get('search_subject')));
			$search_class= (trim($this->input->get('search_class')) == ''?null:trim($this->input->get('search_class')));
       
	   
		$config = array();
		$config['reuse_query_string'] = true;
		// $config['page_query_string'] = true;
		$config['use_page_numbers'] = TRUE;
		$config["base_url"] = base_url() . "/parent/book/index";
		$config["total_rows"] = $this->book_model->listbook($search_title,$search_book_no,$search_isbn,$search_author,$search_barcode,$search_subject,$search_class,true);
		$config ['uri_segment'] = 4;
		$config ['per_page'] = 50;
		$config ['num_links'] = 10;
		$config['full_tag_open'] = '<nav aria-label="Page navigation example">
		<ul class="pagination pg-blue">';
		$config['full_tag_close'] = ' </ul>
		</nav>';
		$config['first_link'] = 'First';
		$config['last_link'] = 'Last';
		$config['first_tag_open'] = '<li class="page-item">';
		$config['first_tag_close'] = '</li>';
		$config['prev_link'] = 'Previous';
		$config['prev_tag_open'] = '<li class="page-item">';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Next';
		$config['next_tag_open'] = '<li class="page-item">';
		$config['next_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li class="page-item">';
		$config['last_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li class="active"><a href="#">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_tag_open'] = '<li class="page-item">';
		$config['num_tag_close'] = '</li>';
		
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(4)) ? $this->uri->segment(4) :1; 
		$offset =($page -1) * $config['per_page'];
		$data["links"] = $this->pagination->create_links();
		
	    $data['listbook'] = $this->book_model->listbook($search_title,$search_book_no,$search_isbn,$search_author,$search_barcode,$search_subject,$search_class,false, $config["per_page"],$offset);
         

        $this->load->view('layout/parent/header');

        $this->load->view('parent/book/createbook', $data);

        $this->load->view('layout/parent/footer');

    }



    function create() {

        $data['title'] = 'Add Book';

        $data['title_list'] = 'Book Details';

        $this->form_validation->set_rules('book_title', 'Book Title', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $listbook = $this->book_model->listbook();

            $data['listbook'] = $listbook;

            $this->load->view('layout/header');

            $this->load->view('admin/book/createbook', $data);

            $this->load->view('layout/footer');

        } else {

            $data = array(

                'book_title' => $this->input->post('book_title'),

                'subject' => $this->input->post('subject'),

                'rack_no' => $this->input->post('rack_no'),

                'publish' => $this->input->post('publish'),

                'author' => $this->input->post('author'),

                'qty' => $this->input->post('qty'),

                'perunitcost' => $this->input->post('perunitcost'),

                'postdate' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('postdate'))),

                'description' => $this->input->post('description')

            );

            $this->book_model->addbooks($data);

            redirect('admin/book/index');

        }

    }



    function edit($id) {

        $data['title'] = 'Edit Book';

        $data['title_list'] = 'Book Details';

        $data['id'] = $id;

        $editbook = $this->book_model->get($id);

        $data['editbook'] = $editbook;

        $this->form_validation->set_rules('book_title', 'Book Title', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {

            $listbook = $this->book_model->listbook();

            $data['listbook'] = $listbook;

            $this->load->view('layout/header');

            $this->load->view('admin/book/editbook', $data);

            $this->load->view('layout/footer');

        } else {

            $data = array(

                'id' => $this->input->post('id'),

                'book_title' => $this->input->post('book_title'),

                'subject' => $this->input->post('subject'),

                'rack_no' => $this->input->post('rack_no'),

                'publish' => $this->input->post('publish'),

                'author' => $this->input->post('author'),

                'qty' => $this->input->post('qty'),

                'perunitcost' => $this->input->post('perunitcost'),

                'postdate' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('postdate'))),

                'description' => $this->input->post('description')

            );

            $this->book_model->addbooks($data);

            $this->session->set_flashdata('msg', '<div feemaster="alert alert-success text-center">book details added to Database!!!</div>');

            redirect('admin/book/index');

        }

    }



    function delete($id) {

        $data['title'] = 'Fees Master List';

        $this->book_model->remove($id);

        redirect('admin/book/index');

    }



}



?>