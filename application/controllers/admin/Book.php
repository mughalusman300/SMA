<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Book extends Admin_Controller {

    function __construct() {
        parent::__construct();
        
        $this->load->model('book_model');
        $this->load->model('bookissue_model');
        $this->load->library('session');
        $this->load->library('encoding_lib');
        $this->load->model('setting_model');
        //$this->load->library('Ajax_pagination');
        //$this->perPage = 2;
        
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/index');
        $data['title'] = 'Add Book';
        $data['title_list'] = 'Book Details';
        $listbook = $this->book_model->getlistbook();
        $data['listbook'] = $listbook;
        $this->load->view('layout/header');
        $this->load->view('admin/book/createbook', $data);
        $this->load->view('layout/footer');
    }

    public function getall() {
        if (!$this->rbac->hasPrivilege('books', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/getall');
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
		$config["base_url"] = base_url() . "/admin/book/getall";
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
         
        $this->load->view('layout/header');
        $this->load->view('admin/book/getall', $data);
        $this->load->view('layout/footer');
    }

    function create() {
        if (!$this->rbac->hasPrivilege('books', 'can_add')) {
            access_denied();
        }
        $data['title'] = 'Add Book';
        $data['title_list'] = 'Book Details';
        $this->form_validation->set_rules('book_title', 'Book Title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('book_no', 'Book Number', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $listbook = $this->book_model->getlistbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/book/createbook', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'book_title' => (trim($this->input->post('book_title'))),
                'book_no' => (trim($this->input->post('book_no'))),
                'isbn_no' => (trim($this->input->post('isbn_no'))),
                'subject' => (trim($this->input->post('subject'))),
                'location' => (trim($this->input->post('location'))),
                'publish' => (trim($this->input->post('publish'))),
                'author' => (trim($this->input->post('author'))),
                'class' => (trim($this->input->post('class'))),
                'tags' => (trim($this->input->post('tags'))),
                //'postdate' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('postdate'))),
                'other' => (trim($this->input->post('other'))),
                'description' => (trim($this->input->post('description')))
            );
            $this->book_model->addbooks($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book added successfully</div>');
            redirect('admin/book/index');
        }
    }

    function edit($id) {
        if (!$this->rbac->hasPrivilege('books', 'can_edit')) {
            access_denied();
        }
        $data['title'] = 'Edit Book';
        $data['title_list'] = 'Book Details';
        $data['id'] = $id;
        $editbook = $this->book_model->get($id);
        //echo '<pre>'; print_r(  $editbook); exit; 
        $checkIs_active =  $editbook['is_active'];
        //echo '<pre>'; print_r(  $checkIs_active); exit; 
        $data['editbook'] = $editbook;
        $status=$this->input->post('status');
        if ($status =="damage"){
            $available = "no";
            $is_active = "no";
        }
        else{
            $available = $this->input->post('status');
            $is_active = $checkIs_active;
        }
        $this->form_validation->set_rules('book_title', 'Book Title', 'trim|required|xss_clean');
        $this->form_validation->set_rules('book_no', 'Book Number', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            $listbook = $this->book_model->getlistbook();
            $data['listbook'] = $listbook;
            $this->load->view('layout/header');
            $this->load->view('admin/book/editbook', $data);
            $this->load->view('layout/footer');
        } else {
            $data = array(
                'id' => $this->input->post('id'),
                'book_title' => $this->input->post('book_title'),
                'book_no' => $this->input->post('book_no'),
                'isbn_no' => $this->input->post('isbn_no'),
                'subject' => $this->input->post('subject'),
                'location' => $this->input->post('location'),
                'publish' => $this->input->post('publish'),
                'author' => $this->input->post('author'),
                'class' => $this->input->post('class'),
                'tags' => $this->input->post('tags'),
                'other' => $this->input->post('other'),
                'description' => $this->input->post('description'),
                'available' => $available,
                'is_active' => $is_active

            );
            $this->book_model->addbooks($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book updated successfully</div>');
            redirect('admin/book/index');
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('books', 'can_delete')) {
            access_denied();
        }
        $data['title'] = 'Fees Master List';
        $this->book_model->remove($id);
        redirect('admin/book/getall');
    }

        public function import(){

        $fields = array('book_no', 'book_title', 'isbn_no', 'subject', 'location', 'publish', 'author', 'class', 'tags', 'other', 'description');
        $data["fields"] = $fields;



        if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            if ($ext == 'csv') {
                $file = $_FILES['file']['tmp_name'];
                $this->load->library('CSVReader');
                $result = $this->csvreader->parse_file($file);

                if (!empty($result)) {
                    $array = array();
                    $rowcount = 0;
                    for ($i = 1; $i <= count($result); $i++) {

                        $data[$i] = array();
                        $n = 0;
                        foreach ($result[$i] as $key => $value) {
                            $data[$i][$fields[$n]] = $this->encoding_lib->toUTF8($result[$i][$key]);
                            $n++;
                        }

                        $isbn = $data[$i]["book_no"];

                        $data_new = array(
                            'book_no' =>  $data[$i]["book_no"],
                            'book_title' =>  $data[$i]["book_title"],
                            'isbn_no' =>  $data[$i]["isbn_no"],
                            'subject' =>  $data[$i]["subject"],
                            'location' => $data[$i]['location'],
                            'publish' => $data[$i]["publish"],
                            'author' => $data[$i]["author"],
                            'class' =>  $data[$i]["class"],
                            'tags' => $data[$i]['tags'],
                            'other' => $data[$i]["other"],
                            'description' => $data[$i]["description"]
                        );

                        $array []=  $data_new;
                        $rowcount++;

                    }

                    if($this->book_model->add_book_stock($array) > 0){
                        $this->session->set_flashdata('msg', '<div class="alert alert-success text-center">Total ' . count($result) . " records found in CSV file. Total " . $rowcount . ' records imported successfully.</div>');
                    }

                } else {
                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">No Data was found.</div>');
                }
            } else {
                $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Please upload CSV file only.</div>');
            }
        }
        $this->load->view('layout/header');
        $this->load->view('admin/book/import', $data);
        $this->load->view('layout/footer');
    }

    
    public function exportformat(){
        $this->load->helper('download');
        $filepath = "./backend/import/import_book_library_sample_file.csv";
        $data = file_get_contents($filepath);
        $name = 'import_book_library_sample_file.csv';

        force_download($name, $data);
    }
    public function Booksreport(){

        if (!$this->rbac->hasPrivilege('book_reports', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/booksreport');
        $status= $this->input->get('status');       
        $data['status']=$status;
        //echo '<pre>'; print_r(  $status); exit;
        
        $data['title'] = "Book Reports"; 
        $data['start_date']= $this->input->get('start_date');
        $data['end_date']=$this->input->get('end_date'); 

        if($data['start_date']!="" && $data['end_date']!="" && $status=="" )
        {
        $books = $this->book_model->getAllBookReports();
        $data["books"]= $books;
         //echo '<pre>'; print_r(  $booksByStatus); exit;
        }
        if($data['start_date']!="" && $data['end_date']!="" && $status!=""  )
        {
        $books = $this->book_model->getAllBookReports($status);
        $data["books"]= $books;
         //echo '<pre>'; print_r(  $booksByStatus); exit;
        }
        if($data['start_date']!="" && $data['end_date']!="" && $status!="" && $status=="Damage" )
        {
        $is_active= "no";    
        $books = $this->book_model->getAllBookReports($status,$is_active);
        $data["books"]= $books;
         //echo '<pre>'; print_r(  $booksByStatus); exit;
        }
        if(isset($_GET['submit'])){
        if($data['start_date']=="" && $data['end_date']=="" && $status=="")
        {
        $books = $this->book_model->getAllBooks();
        $data["books"]= $books;
         //echo '<pre>'; print_r(  $booksByStatus); exit;
        }
        }
        if($data['start_date']=="" && $data['end_date']=="" && $status!="")
        {
        $books = $this->book_model->getAllBooks($status);
        $data["books"]= $books;
         //echo '<pre>'; print_r(  $booksByStatus); exit;
        }
        if($data['start_date']=="" && $data['end_date']=="" && $status!="" && $status=="Damage" )
        {
        $is_active= "no";
        $books = $this->book_model->getAllBooks($status,$is_active);
        $data["books"]= $books;
         //echo '<pre>'; print_r(  $booksByStatus); exit;
        }
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/books_report.php',$data);
        $this->load->view('layout/footer');
    }
    /*public function bookpages(){

        if (!$this->rbac->hasPrivilege('book_reports', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/booksreport');
         $data = array();
        
        //total rows count
        $totalRec = count($this->book_model->getRows());
        
        //pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = base_url().'admin/book/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
        $this->ajax_pagination->initialize($config);
        
        //get the posts data
        $data['posts'] = $this->book_model->getRows(array('limit'=>$this->perPage));
        
        //load the view
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/book_reportscopy.php',$data);
        $this->load->view('layout/footer');
    }
    
    function ajaxPaginationData(){
        $conditions = array();
        
        //calc offset number
        $page = $this->input->post('page');
        if(!$page){
            $offset = 0;
        }else{
            $offset = $page;
        }
        
        //set conditions for search
        $keywords = $this->input->post('keywords');
        $sortBy = $this->input->post('sortBy');
        if(!empty($keywords)){
            $conditions['search']['keywords'] = $keywords;
        }
        if(!empty($sortBy)){
            $conditions['search']['sortBy'] = $sortBy;
        }
        
        //total rows count
        $totalRec = count($this->book_model->getRows($conditions));
        
        //pagination configuration
        $config['target']      = '#postList';
        $config['base_url']    = base_url().'admin/book/ajaxPaginationData';
        $config['total_rows']  = $totalRec;
        $config['per_page']    = $this->perPage;
        $config['link_func']   = 'searchFilter';
        $this->ajax_pagination->initialize($config);
        
        //set start and limit
        $conditions['start'] = $offset;
        $conditions['limit'] = $this->perPage;
        
        //get posts data
        $data['posts'] = $this->book_model->getRows($conditions);
        
        //load the view
        //$this->load->view('posts/ajax-pagination-data', $data, false);
 
        //$this->load->view('layout/header');
        $this->load->view('admin/librarian/ajax-pagination-data',$data, false);
       //$this->load->view('layout/footer');
    }*/
    public function Booksissuereport(){
        //order by IssueDate
        if (!$this->rbac->hasPrivilege('book_issue_reports', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/booksissuereport');
        $status= $this->input->get('status');
       /* $data['status']=$status;
        $data['title'] = "Books Issue Reports"; 
        $data['start_date']= $this->input->get('start_date');
        $data['end_date']=$this->input->get('end_date'); 
        if($data['start_date']!="" && $data['end_date']!="")
        { 
        $books = $this->bookissue_model->getBooksIssueReport();
        $data["books"]= $books;
        //echo '<pre>'; print_r(  $books); exit;
        }
        if($data['start_date']!="" && $data['end_date']!="" && $data['status']!="")
        { 
        $books = $this->bookissue_model->getBooksIssueReport($status);
        $data["books"]= $books;
        }
        if(isset($_GET['submit'])){
        if($data['start_date']=="" && $data['end_date']=="" && $data['status']=="")
        {    
        $books = $this->bookissue_model->getBooks();
        //echo '<pre>'; print_r(  $books); exit;
        $data['books'] = $books;
         //echo '<pre>'; print_r(  $books); exit;
        }
        }
        if($data['start_date']=="" && $data['end_date']=="" && $data['status']!="")
        {    
        $books = $this->bookissue_model->getBooks($status);
        //echo '<pre>'; print_r(  $books); exit;
        $data['books'] = $books;
         //echo '<pre>'; print_r(  $books); exit;
        }*/
        
          $data['title'] = "Books Issue Reports"; 
        
        $status= $this->input->get('status');
        $data['status']=$status;
        $data['start_date']= $this->input->get('start_date');
        $data['end_date']=$this->input->get('end_date'); 
        
               
        
        $config = array();
        $config['reuse_query_string'] = true;
        // $config['page_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "admin/Book/Booksissuereport";
        $config["total_rows"] = $this->bookissue_model->getBooksIssueReport($data['status'], $data['start_date'], $data['end_date'] ,true);
        $config ['uri_segment'] = 4;
        $config ['per_page'] = 50;
        $config ['num_links'] = 10;
        $config['full_tag_open'] = '<nav aria-label="Page navigation example">
                                          <ul class="pagination pg-blue">';
        $config['full_tag_close'] = ' </ul>
                                        </nav>';
        $config['first_link'] = false;
        $config['last_link'] = false;
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
        $this->uri->segment(4);
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
        $offset = ($page - 1) * $config ['per_page'];
        $data["links"] = $this->pagination->create_links();

        $resultlist = $this->bookissue_model->getBooksIssueReport($data['status'], $data['start_date'], $data['end_date'],false , $config["per_page"], $offset);
        $data['books'] = $resultlist;
 
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/books_issue_report.php',$data);
        $this->load->view('layout/footer');
    }
    public function finereport(){

        if (!$this->rbac->hasPrivilege('fine_report', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'book/finereport');
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $status= $this->input->get('status');
        $data['status']=$status;
        $data['title'] = "Fine Reports"; 
        $data['start_date']= $this->input->get('start_date');
        $data['end_date']=$this->input->get('end_date'); 
        if($data['start_date']!="" && $data['end_date']!="")
        { 
        $finelist = $this->bookissue_model->getFineReport();
        $data["finelist"]= $finelist;
        }
        if($data['start_date']!="" && $data['end_date']!="" && $data['status']!="")
        { 
        $finelist = $this->bookissue_model->getFineReport($status);
        $data["finelist"]= $finelist;
        }
        if(isset($_GET['submit'])){
        if($data['start_date']=="" && $data['end_date']=="" && $data['status']=="")
        {    
        $finelist = $this->bookissue_model->getAllFine();
        $data['finelist'] = $finelist;
        }
        }
        if($data['start_date']=="" && $data['end_date']=="" && $data['status']!="")
        {    
        $finelist = $this->bookissue_model->getAllFine($status);
        //echo '<pre>'; print_r(  $finelist); exit;
        $data['finelist'] = $finelist;
         //echo '<pre>'; print_r(  $finelist); exit;
        }
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/finelist.php',$data);
        $this->load->view('layout/footer');
    }


}

?>