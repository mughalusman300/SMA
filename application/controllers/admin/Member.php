<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Member extends Admin_Controller {

    function __construct() {
        parent::__construct();
    }

    public function index() {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title'] = 'Member';
        $data['title_list'] = 'Members';
        $library_card_no= trim($this->input ->get('library_card_no'));
        /*$library_card_no= trim($library_card_no,"*");
        if($library_card_no==""){
        $memberList = $this->librarymember_model->get();
        $data['memberList'] = $memberList;
    }
        //echo "<pre>"; print_r($library_card_no); exit;
        if($library_card_no!=""){
        $memberList = $this->librarymember_model->getByLibraryCardNo($library_card_no);
        //echo "<pre>"; print_r($memberList); exit;
        $data['memberList'] = $memberList;
        }
        
        
        */
        $config = array();
        $config['reuse_query_string'] = true;
        // $config['page_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "admin/member/index";
        $config["total_rows"] = $this->librarymember_model->getByLibraryCardNoKiran($library_card_no,true);
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

        $resultlist = $this->librarymember_model->getByLibraryCardNoKiran($library_card_no,false , $config["per_page"], $offset);
                
        $data['memberList'] = $resultlist;
         
         
 
        
        
       // $memberList = $this->librarymember_model->get();
        //$data['memberList'] = $memberList;
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/index', $data);
        $this->load->view('layout/footer');
    }



    public function issue($id) {
        if (!$this->rbac->hasPrivilege('issue_return', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/index');
        $data['title'] = 'Member';
        $data['title_list'] = 'Members';
        $memberList = $this->librarymember_model->getByMemberID($id);
        $member_type=$memberList->member_type;
        $data['memberList'] = $memberList;
        $issued_books = $this->bookissue_model->getMemberBooks($id);
        $data['issued_books'] = $issued_books;
        $data['fineList'] = $this->bookissue_model->getMemberFine($id);
        $bookList = $this->book_model->get();
        $data['bookList'] = $bookList;

        $bookId = (trim($this->input->post('book_id')));
        $bookIds = (trim($bookId,"*"));
        $data['bok'] = $this->book_model->getBookCheck($bookIds);
        if($member_type=="student"){
        $totalborrowedbookchek= $this->bookissue_model->getborrowedbook($id);
        //echo '<pre>'; print_r(  $totalborrowedbookchek); exit;
        $data['totalborrowedbookchek']= $totalborrowedbookchek;
        }

        
        if($member_type=="teacher"){
        $totalborrowedbookchek= $this->bookissue_model->getborrowedbookteacher($id);
        //echo '<pre>'; print_r(  $totalborrowedbookchek); exit;
        $data['totalborrowedbookchek']= $totalborrowedbookchek;
        }

        $Availabecheck = $this->book_model->getBookChecksWithAvailable($bookIds);
        $Activecheck = $this->book_model->getBookCheckssWithIsActive($bookIds);

        if ($bookIds!="" && $Availabecheck == "" && $Activecheck== ""){
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Book barcode not found.</div>');
         }
        // $bookss['bok']= $this->book_model->getBookChecks();
         //var_dump($data['bok']);die();
         if ($bookIds!="" && $Availabecheck == "" && $Activecheck!= ""){
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">Sorry Book borrowed Already!.</div>');
         }
         if($bookIds!=""  && $totalborrowedbookchek==""){
        $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">You have borrowed two books already!</div>');
        }
         if($bookIds!=""  && $Availabecheck != "" && $Activecheck!= "" && $totalborrowedbookchek!=""){
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book Available! Click save if you want to borrow this.</div>');
        
        $this->form_validation->set_rules('book_id', 'Book', 'trim|required|xss_clean');
        $this->form_validation->set_rules('return_date', 'Return Date', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $member_id = $this->input->post('member_id');
            $bookId = $this->input->post('book_id');
            $bookValidation= $this->book_model->getBookCheck($bookId);
            //echo '<pre>'; print_r(  $bookValidation); exit;
           // $bookValidation= $this->book_model->getBookCheckss($bookId);
            if(count($bookValidation) > 0)
            {
                $data = array(
                    'book_id' => $bookValidation[0]['id'],
                    'return_date' => date('Y-m-d', $this->customlib->datetostrtotime($this->input->post('return_date'))),
                    'issue_date' => date('Y-m-d'),
                    'member_id' => $this->input->post('member_id'),
                    //'is_active' => 'no',
                );

                $barcode = $this->input->post('book_id');
                $available = "no";
                $this->book_model->update_book_by_id($barcode,$available);
                $this->bookissue_model->add($data);
                $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book issued successfully.</div>');
            }

            redirect('admin/member/issue/' . $member_id,'refresh');
        }

    } 
        $this->load->view('layout/header');
        $this->load->view('admin/librarian/issue', $data);
        $this->load->view('layout/footer');
    }


    public function bookreturn($id, $member_id) {
        //get book_issues table data by id
        $book_issue = $this->bookissue_model->get_bookissue_by_id($id);
        $member_type = $book_issue[0]['member_type'];
        $book_issue_id = $book_issue[0]['id'];
        $return_date = $book_issue[0]['return_date'];
        //$rt = strtotime($return_date);
        //echo"<pre>"; print_r($rt);exit;
      
        $date = strtotime($return_date);
        $return_increase_one= date("Y-m-d", strtotime("+1 day", $date)); 
        //echo"<pre>"; print_r($return_increase_one);exit;
        $timestamp1 = strtotime($return_increase_one);
         //echo"<pre>"; print_r($timestamp1);exit;
        $timestamp2 = strtotime(date('Y-m-d'));
        $fri    = array();
        $sat    = array();
        $oneDay = 60*60*24;
        $countfri = 0;
        $countsat = 0;

        for($i = $timestamp1; $i <= $timestamp2; $i += $oneDay) {
            $day = date('N', $i);

            // If friday
            if($day == 5 ) {
                // Save friday in format YYYY-MM-DD, if you need just timestamp
                // save only $i
                $fri[] = date('Y-m-d', $i);
                // Since we know it is friday, we can simply skip 
                // next 6 days so we get right to next friday
                $i += 6 * $oneDay;
                $countfri++;
            }
        }
        for($i = $timestamp1; $i <= $timestamp2; $i += $oneDay) {
            $day = date('N', $i);

            // If Saturday
            if($day == 6 ) {
                // Save Saturday in format YYYY-MM-DD, if you need just timestamp
                // save only $i
                $sat[] = date('Y-m-d', $i);
                // Since we know it is Saturday, we can simply skip 
                // next 6 days so we get right to next Saturday
                $i += 6 * $oneDay;
                $countsat++;
            }
        }
        //echo"<pre>"; print_r($countsat);exit;
        //echo"<pre>"; print_r($count);exit;

        $current_date= date("Y-m-d");
    if($member_type!="teacher" && $current_date > $return_date) 
        //if( $current_date > $return_date) 
         {   
            $a =strtotime(date('Y-m-d'));
            $time = new DateTime($return_date);
            $date = $time->format('Y-m-d');
            $b = strtotime($date);
            $diff = $a -$b;
            $days= floor($diff/(60*60*24));
            $balance=$days - $countfri - $countsat *1;
            $total_days = $balance;
            //echo"<pre>"; print_r($balance);exit;
            $total_fine=$balance;
            $this->bookissue_model->add_fine($book_issue_id,$total_days,$balance,$total_fine);
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left">You have fine SR'.$balance. '.00/ please pay this. Book Returned Successfully</div>');
         }
         else{
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book returned successfully.</div>');
         }

        //get book_issues table data by id
        $book_issue = $this->bookissue_model->get_book_data_by_bookissue_id($id);
        $other = $book_issue[0]['other'];
        //book table update column available
        $barcode_id = $other;
        $available = "yes";
        $this->book_model->update_book_by_id($barcode_id,$available);
        
               $data = array(
            'id' => $id,
            'is_returned' => 1
        );
        $this->bookissue_model->update($data);
        redirect('admin/member/issue/' . $member_id);

    }
    public function bookreissue($id, $member_id) {

        $book_issue = $this->bookissue_model->get_bookissue_by_id($id);
        $return_date = $book_issue[0]['return_date'];
        $date = strtotime("+7 day", strtotime($return_date));
        $new_date=date("Y-m-d", $date);
        //echo '<pre>'; print_r(  $return_date); exit; 
        $data = array(
            'id' => $id,
            'return_date' => $new_date
        );
        $this->bookissue_model->update($data);
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Book Re-issue Successfully</div>');
        redirect('admin/member/issue/' . $member_id);
    }
    public function payfine($fid, $member_id) {
              
        $data['memberList']  = $this->librarymember_model->getByMemberID($member_id);
               $data = array(
            'id' => $fid,
            'amount_paid' => 'Yes',
            'status'=> 1
           
        );
        $check=$this->bookissue_model->update_fine($data);
        if($check!=""){
        $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Fine Paid successfully.</div>');
         }
         

       redirect('admin/member/finedetail/' . $fid.'/'.$member_id);
    }
   public function finedetail($fid,$mid) {
              
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data['memberList']  = $this->librarymember_model->getByMemberID($mid);
        $data['title'] = "Fine Detail";
        $fine_detail = $this->bookissue_model->getFineById($fid);
        $data['fine_detail']=$fine_detail; 
        $id=$this->input->post('fid');
        $discount=$this->input->post('discount');
        $balance = $this->input->post('balance');
        $total_fine   = $balance-$discount;
            $data_array = array(
            'id' => $id,
            'discount' =>$discount,
            'total_fine'=>$total_fine,
            'amount_paid' => 'Yes',
            'remarks' =>  $this->input->post('remarks'),  
            'status'=> 1
           
        );
          
         if($discount!=""){
        $check=$this->bookissue_model->update_fine($data_array);
        redirect('admin/member/finedetail/'.$fid.'/'.$mid);
    }

        $this->load->view('layout/header');
        $this->load->view('admin/librarian/fine', $data);
        $this->load->view('layout/footer');
    }
    public function ReturnBooksByclass(){
        if (!$this->rbac->hasPrivilege('return_by_class', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/return_by_class');
        $data['title'] = 'Issued Return Class';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/librarian/book_issue_return_by_class', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->searchLibraryStudentMembers($class, $section);
                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";
                    $data['class_id'] = $this->input->post('class_id');
                    $data['section_id'] = $this->input->post('section_id');
                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text);
                    $data['resultlist'] = $resultlist;
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/librarian/book_issue_return_by_class', $data);
            $this->load->view('layout/footer', $data);
        }
    }
    public function issuedBooksByclass(){
        if (!$this->rbac->hasPrivilege('issue_by_class', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/issue_by_class');
        $data['title'] = 'Issued Return Class';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/librarian/book_issue_by_class', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $library_card_no = $this->input->post('library_card_no');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');
                        $data['library_card_no'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->searchLibraryStudent($class, $section);
                        //echo '<pre>';print_r($resultlist);exit;

                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {

                    $data['searchby'] = "text";
                    $data['class_id'] = $this->input->post('class_id');
                    $data['section_id'] = $this->input->post('section_id');
                    $library_card_no = trim($this->input->post('library_card_no'));
                    $library_card_no =(trim($library_card_no,"*"));
                    //$library_card_no= $data['library_card_no'];
                    //echo '<pre>';print_r($library_card_no);exit;
                    $resultlist = $this->student_model->searchLibraryStudentByLibraryCardNo($library_card_no);
                    //echo '<pre>';print_r($resultlist);exit;

                    $data['resultlist'] = $resultlist;
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/librarian/book_issue_by_class', $data);
            $this->load->view('layout/footer', $data);
        }
    }
    public function bookreturnbyclass($id= null, $member_id= null) {
        //get book_issues table data by id
        $id = $this->input->post('id');
        $book_issue = $this->bookissue_model->get_bookissue_by_id($id);
        $book_issue_id = $book_issue[0]['id'];
        $return_date = $book_issue[0]['return_date'];
        $member_id  =$book_issue[0]['member_id'];
        $link = base_url('admin/member/issue/'.$member_id);
        $current_date= date("Y-m-d");

        //off days calculation
        $date = strtotime($return_date);
        $return_increase_one= date("Y-m-d", strtotime("+1 day", $date)); 
        //echo"<pre>"; print_r($return_increase_one);exit;
        $timestamp1 = strtotime($return_increase_one);
         //echo"<pre>"; print_r($timestamp1);exit;
        $timestamp2 = strtotime(date('Y-m-d'));
        $fri    = array();
        $sat    = array();
        $oneDay = 60*60*24;
        $countfri = 0;
        $countsat = 0;

        for($i = $timestamp1; $i <= $timestamp2; $i += $oneDay) {
            $day = date('N', $i);

            // If friday
            if($day == 5 ) {
                // Save friday in format YYYY-MM-DD, if you need just timestamp
                // save only $i
                $fri[] = date('Y-m-d', $i);
                // Since we know it is friday, we can simply skip 
                // next 6 days so we get right to next friday
                $i += 6 * $oneDay;
                $countfri++;
            }
        }
        for($i = $timestamp1; $i <= $timestamp2; $i += $oneDay) {
            $day = date('N', $i);

            // If Saturday
            if($day == 6 ) {
                // Save Saturday in format YYYY-MM-DD, if you need just timestamp
                // save only $i
                $sat[] = date('Y-m-d', $i);
                // Since we know it is Saturday, we can simply skip 
                // next 6 days so we get right to next Saturday
                $i += 6 * $oneDay;
                $countsat++;
            }
        }
        //echo"<pre>"; print_r($countsat);exit;
        //echo"<pre>"; print_r($count);exit;
        
        if($current_date > $return_date) 
         {   
            $a =strtotime(date('Y-m-d'));
            $time = new DateTime($return_date);
            $date = $time->format('Y-m-d');
            $b = strtotime($date);
            $diff = $a -$b;
            $days= floor($diff/(60*60*24));
            $balance=$days - $countfri - $countsat *1;
            $total_days = $balance;
            $total_fine=$balance;
            $this->bookissue_model->add_fine($book_issue_id,$total_days,$balance,$total_fine);
            $this->session->set_flashdata('msg', '<div class="alert alert-danger text-left"><a  target= "_blank" href="'.$link. '"> <i class="btn btn-xs btn-success ">Pay Fine</i></a> You Have SR'.$balance. '.00/ Fine Please Pay Your Fine.</div>');

         }
         else{

         }
        //get book_issues table data by id
        $book_issue = $this->bookissue_model->get_book_data_by_bookissue_id($id);
        $other = $book_issue[0]['other'];
        //book table update column available
        $barcode_id = $other;
        $available = "yes";
        $this->book_model->update_book_by_id($barcode_id,$available);
        //book_issues table update
               $data = array(
            'id' => $id,
            'is_returned' => 1
        );
        $this->bookissue_model->update($data);
        $array = array('status' => 'success', 'error' => '', 'message' => 'Book Returned successfully');
            echo json_encode($array);
    }
    function student() {

        if (!$this->rbac->hasPrivilege('add_student', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/student');
        $data['title'] = 'Student Search';
        $class = $this->class_model->get();
        $data['classlist'] = $class;
        $button = $this->input->post('search');
        if ($this->input->server('REQUEST_METHOD') == "GET") {
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        } else {
            $class = $this->input->post('class_id');
            $section = $this->input->post('section_id');
            $search = $this->input->post('search');
            $search_text = $this->input->post('search_text');
            if (isset($search)) {
                if ($search == 'search_filter') {
                    $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');
                    if ($this->form_validation->run() == FALSE) {
                        
                    } else {
                        $data['searchby'] = "filter";
                        $data['class_id'] = $this->input->post('class_id');
                        $data['section_id'] = $this->input->post('section_id');
                        $data['search_text'] = $this->input->post('search_text');
                        $resultlist = $this->student_model->searchLibraryStudent($class, $section);
                        $data['resultlist'] = $resultlist;
                    }
                } else if ($search == 'search_full') {
                    $data['searchby'] = "text";
                    $data['class_id'] = $this->input->post('class_id');
                    $data['section_id'] = $this->input->post('section_id');
                    $data['search_text'] = trim($this->input->post('search_text'));
                    $resultlist = $this->student_model->searchFullText($search_text);
                    $data['resultlist'] = $resultlist;
                }
            }
            $this->load->view('layout/header', $data);
            $this->load->view('admin/member/studentSearch', $data);
            $this->load->view('layout/footer', $data);
        }
    }

    function add() {
        if ($this->input->post('library_card_no') != "") {

            $this->form_validation->set_rules('library_card_no', 'library Card No', 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student = $this->input->post('member_id');
                $data = array(
                    'member_type' => 'student',
                    'member_id' => $student,
                    'library_card_no' => $library_card_no
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array = array('status' => 'success', 'error' => '', 'message' => 'Member added successfully', 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student = $this->input->post('member_id');
            $data = array(
                'member_type' => 'student',
                'member_id' => $student,
                'library_card_no' => $library_card_no
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Member added successfully', 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    function check_cardno_exists() {
        $data['library_card_no'] = $this->security->xss_clean($this->input->post('library_card_no'));

        if ($this->librarymanagement_model->check_data_exists($data)) {
            $this->form_validation->set_message('check_cardno_exists', 'Card no already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function teacher() {
        $this->session->set_userdata('top_menu', 'Library');
        $this->session->set_userdata('sub_menu', 'member/teacher');
        $data['title'] = 'Add Teacher';
        $teacher_result = $this->teacher_model->getLibraryTeacher();
        $data['teacherlist'] = $teacher_result;

        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/member/teacher', $data);
        $this->load->view('layout/footer', $data);
    }

    function addteacher() {
        if ($this->input->post('library_card_no') != "") {

            $this->form_validation->set_rules('library_card_no', 'library Card No', 'required|trim|xss_clean|callback_check_cardno_exists');
            if ($this->form_validation->run() == false) {
                $data = array(
                    'library_card_no' => form_error('library_card_no'),
                );
                $array = array('status' => 'fail', 'error' => $data);
                echo json_encode($array);
            } else {
                $library_card_no = $this->input->post('library_card_no');
                $student = $this->input->post('member_id');
                $data = array(
                    'member_type' => 'teacher',
                    'member_id' => $student,
                    'library_card_no' => $library_card_no
                );

                $inserted_id = $this->librarymanagement_model->add($data);
                $array = array('status' => 'success', 'error' => '', 'message' => 'Member added successfully', 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
                echo json_encode($array);
            }
        } else {
            $library_card_no = $this->input->post('library_card_no');
            $student = $this->input->post('member_id');
            $data = array(
                'member_type' => 'teacher',
                'member_id' => $student,
                'library_card_no' => $library_card_no
            );

            $inserted_id = $this->librarymanagement_model->add($data);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Member added successfully', 'inserted_id' => $inserted_id, 'library_card_no' => $library_card_no);
            echo json_encode($array);
        }
    }

    public function surrender() {

        $this->form_validation->set_rules('member_id', 'Book', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            
        } else {
            $member_id = $this->input->post('member_id');
            $this->librarymember_model->surrender($member_id);
            $array = array('status' => 'success', 'error' => '', 'message' => 'Membership surrender successfully');
            echo json_encode($array);
        }
    }

}

?>