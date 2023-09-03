<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class BookStore extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('book_store');
        $this->load->model('store_orders');
        $this->load->model('student_model');
        $this->load->library('session');
        $this->load->library('encoding_lib');
        $this->load->model('setting_model');
        $this->load->model('class_model');
         $this->load->model('studentsession_model');
         $this->load->model("invoices_model");
    }

    public function add_book(){

         $this->session->set_userdata('top_menu', 'Inventory');
         $this->session->set_userdata('sub_menu', 'admin/BookStore/add_book');

         $data['classes'] = $this->class_model->getClassesTag();

        
        $this->load->view('layout/header');
        $this->load->view('book-store/add-book', $data);
        $this->load->view('layout/footer');
    }

    public function post_book(){

        $id = $this->input->post('id');
        $check = $this->book_store->check_book($id);

        $classes = $this->input->post('classs');

        $tags = implode(', ', $classes);


        $data = array(
            'book_id' => $this->input->post('id'),
            'title' => $this->input->post('title'),
            'brand' => $this->input->post('brand'),
            'price' => $this->input->post('price'),
            'author' => $this->input->post('author'),
            'quantity' => $this->input->post('quantity'),
            'class' => $tags
        );

        if($this->book_store->add_book($data) > 0){
            $this->session->set_userdata('book_success', 'Book Added Successfully!');
        }else{
            $this->session->set_userdata('book_error', 'Book Already Exists!');
        }
        return $this->add_book();

    }

    public function update_stock(){

        $this->load->view('layout/header');
        $this->load->view('book-store/update-stock');
        $this->load->view('layout/footer');
    }

    public function view_stock(){
        
          $this->session->set_userdata('top_menu', 'Inventory');
          $this->session->set_userdata('sub_menu', 'admin/BookStore/view_stock');

        $data['books'] = $this->book_store->get_stocks();
        //echo "<pre>";  print_r($data['books']);exit;
        $this->load->view('layout/header');
        $this->load->view('book-store/view-stock',$data);
        $this->load->view('layout/footer');
    }


    public function place_orders(){
        
         $this->session->set_userdata('top_menu', 'store_orders');
          $this->session->set_userdata('sub_menu', 'admin/Store/order');
        
        $this->load->view('layout/header');
        $this->load->view('book-store/place-orders-view');
        $this->load->view('layout/footer');
    }



    public function searchParent(){
        $parentName = $this->input->post('parent_name');
     //   echo $parentName;
        $data['students'] = $this->student_model->searchStudent($parentName);
     // echo "<pre>";  print_r( $data['students']);exit;
        $this->load->view('layout/header');
        $this->load->view('book-store/place-orders-view', $data);
        $this->load->view('layout/footer');

    }


    public function orderItems(){
        $data['books'] = $this->book_store->get_stock_by_ids($this->input->post('checkedBooks'));
        $data["parent_id"] = $this->input->post('parent_id');
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/order-stock', $data);
        $this->load->view('layout/footer');
    }

    public function orderItemss(){
        $data['books'] = $this->book_store->get_stock_by_ids($this->input->post('checkedBooks'));
        //echo "<pre>";  print_r($data['books']);exit;
        $data["id"] = $this->input->post('id');
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/order-stock', $data);
        $this->load->view('layout/footer');
    }


    public function placeOrder(){
        $booksId = $this->input->post('id');
        $quantity = $this->input->post('book_quantity');
        $studentId = $this->input->post('studetn_id');
        $price =  $this->input->post('price');
        $user_id = $this->customlib->getUserData()['name'];


        if($this->store_orders->insert($user_id, $booksId, $quantity, $studentId, $price) > 0){
            $this->session->set_userdata('success', 'Order Placed Successfully!');
        }else{
            $this->session->set_userdata('error', 'Error in Placing Order!');
        }
        return redirect(site_url('admin/BookStore/placeOrderByStudent').'/'.$studentId);
    }


    public function editOrder($orderId){
        $data["orders"] = $this->store_orders->getDetails($orderId);
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/edit_order', $data);
        $this->load->view('layout/footer');
    }



    public function updateOrder(){
        $orderId = $this->input->post('order_id');
        $book_id = $this->input->post('books_id');
        $prevOrderQty = $this->input->post('prev_order_quantity');
        $newOrderQty = $this->input->post('new_order_qty');
        $prevOrdersold = $this->input->post('prev_sold_qty');
        $newOrdersold = $this->input->post('new_qty_sold');


        if($this->store_orders->update($orderId, $book_id, $prevOrderQty, $newOrderQty, $prevOrdersold, $newOrdersold) > 0){
            $this->session->set_userdata('success', 'Order updated Successfully!');
        }else{
            $this->session->set_userdata('error', 'Error in updating Order!');
        }

        return $this->pending_orders();
    }



    public function placeOrderByParent($parent_id){
        $data["previous_orders"] = $this->store_orders->getOrderList($parent_id);


        $data["stock"] = $this->book_store->get_stock();

      
        $data["parent_id"] = $parent_id;
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data["classes"] = $this->class_model->getClassesTag();
          //$data["classes"]=$this->studentsession_model->getStudentClass();
        //$data["classes"] = $this->book_store->getClasses();
        $this->load->view('layout/header');
        $this->load->view('book-store/place-orders-by-parent', $data);
        $this->load->view('layout/footer');

    }



   public function placeOrderByStudent($id){
        
        $data["previous_orders"] = $this->store_orders->getOrderList($id);
        $data["stock"] = $this->book_store->get_stock($id);
        //echo "<pre>";  print_r($data["stock"]);exit;


        $data["id"] = $id;
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data["classes"] = $this->class_model->getClassesTag();
        $this->load->view('layout/header');
        $this->load->view('book-store/place-orders-by-parent', $data);
        $this->load->view('layout/footer');


    }





    public function cancelled_orders(){
        $data["search_order_id"] =  $search_order_id    =   (trim($this->input->get('search_order_id')) == ''?null:trim($this->input->get('search_order_id')));
        $data["search_admission_no"] =  $search_admission_no    =   (trim($this->input->get('search_admission_no')) == ''?null:trim($this->input->get('search_admission_no')));
        $data["search_parent_id"] =  $search_parent_id   =   (trim($this->input->get('search_parent_id')) == ''?null:trim($this->input->get('search_parent_id')));
        $data["search_order_placed"] =  $search_order_placed    =   (trim($this->input->get('search_order_placed')) == ''?null:trim($this->input->get('search_order_placed')));
        $data["url"]    =   site_url('admin/BookStore/cancelled_orders');
        $data['search_filter'] = $this->load->view('book-store/search_filter', $data, true);
        
        //$orders = $this->store_orders->getCancelledOrders();
        $config = array();
        $config['reuse_query_string'] = true;
        // $config['page_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "/admin/BookStore/cancelled_orders";
        
        $config["total_rows"] = $this->store_orders->getCancelledOrders(true,10,0,$search_order_id, $search_admission_no, $search_parent_id, $search_order_placed); 
        
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
        $orders = $this->store_orders->getCancelledOrders(false,$config["per_page"],$offset,$search_order_id, $search_admission_no, $search_parent_id, $search_order_placed); 

        

        $data['orders'] = $orders;

        $this->load->view('layout/header');
        $this->load->view('book-store/cancelled-orders', $data);
        $this->load->view('layout/footer');
    }

   public function pending_orders(){
       $data["search_order_id"] =  $search_order_id    =   (trim($this->input->get('search_order_id')) == ''?null:trim($this->input->get('search_order_id')));
        $data["search_admission_no"] =  $search_admission_no    =   (trim($this->input->get('search_admission_no')) == ''?null:trim($this->input->get('search_admission_no')));
        $data["search_parent_id"] =  $search_parent_id   =   (trim($this->input->get('search_parent_id')) == ''?null:trim($this->input->get('search_parent_id')));
        $data["search_order_placed"] =  $search_order_placed    =   (trim($this->input->get('search_order_placed')) == ''?null:trim($this->input->get('search_order_placed')));
        $data["url"]    =   site_url('admin/BookStore/pending_orders');
        $data['search_filter'] = $this->load->view('book-store/search_filter', $data, true);
        
        //$orders = $this->store_orders->getPendingOrders();
        // var_dump($orders);die();
        $config = array();
        $config['reuse_query_string'] = true;
        // $config['page_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "/admin/BookStore/pending_orders";
        
        $config["total_rows"] = $this->store_orders->getPendingOrders(true,10,0,$search_order_id, $search_admission_no, $search_parent_id, $search_order_placed); 
        
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
        $orders = $this->store_orders->getPendingOrders(false,$config["per_page"],$offset,$search_order_id, $search_admission_no, $search_parent_id, $search_order_placed); 

        

        $data['orders'] = $orders;

        //$data['orders'] = $pendingOrders;


        $this->load->view('layout/header');
        $this->load->view('book-store/pending-orders', $data);
        $this->load->view('layout/footer');
    }


    public function partially_completed_orders(){

        $data["search_order_id"] =  $search_order_id    =   (trim($this->input->get('search_order_id')) == ''?null:trim($this->input->get('search_order_id')));
        $data["search_admission_no"] =  $search_admission_no    =   (trim($this->input->get('search_admission_no')) == ''?null:trim($this->input->get('search_admission_no')));
        $data["search_parent_id"] =  $search_parent_id   =   (trim($this->input->get('search_parent_id')) == ''?null:trim($this->input->get('search_parent_id')));
        $data["search_order_placed"] =  $search_order_placed    =   (trim($this->input->get('search_order_placed')) == ''?null:trim($this->input->get('search_order_placed')));
        $data["url"]    =   site_url('admin/BookStore/partially_completed_orders');
        $data['search_filter'] = $this->load->view('book-store/search_filter', $data, true);
        
       // $orders = $this->store_orders->getPartiallyCompletedOrders();
        $config = array();
        $config['reuse_query_string'] = true;
        // $config['page_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "/admin/BookStore/partially_completed_orders";
        
        $config["total_rows"] = $this->store_orders->getPartiallyCompletedOrders(true,10,0,$search_order_id, $search_admission_no, $search_parent_id, $search_order_placed); 
        
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
        $orders = $this->store_orders->getPartiallyCompletedOrders(false,$config["per_page"],$offset,$search_order_id, $search_admission_no, $search_parent_id, $search_order_placed); 

        

        $data['orders'] = $orders;

        $this->load->view('layout/header');
        $this->load->view('book-store/partially_completed_orders', $data);
        $this->load->view('layout/footer');
    }




    public function completed_orders(){
        
        //$orders = $this->store_orders->getCompleteOrders();
     
        $data["search_order_id"] =  $search_order_id    =   (trim($this->input->get('search_order_id')) == ''?null:trim($this->input->get('search_order_id')));
        $data["search_admission_no"] =  $search_admission_no    =   (trim($this->input->get('search_admission_no')) == ''?null:trim($this->input->get('search_admission_no')));
        $data["search_parent_id"] =  $search_parent_id   =   (trim($this->input->get('search_parent_id')) == ''?null:trim($this->input->get('search_parent_id')));
        $data["search_order_placed"] =  $search_order_placed    =   (trim($this->input->get('search_order_placed')) == ''?null:trim($this->input->get('search_order_placed')));
$data["url"]    =   site_url('admin/BookStore/completed_orders');
        $data['search_filter'] = $this->load->view('book-store/search_filter', $data, true);
        
        $config = array();
        $config['reuse_query_string'] = true;
        // $config['page_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "/admin/BookStore/completed_orders";
        
        $config["total_rows"] = $this->store_orders->getCompleteOrders(true,10,0,$search_order_id, $search_admission_no, $search_parent_id, $search_order_placed); 
        
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
        $orders = $this->store_orders->getCompleteOrders(false,$config["per_page"],$offset,$search_order_id, $search_admission_no, $search_parent_id, $search_order_placed); 

        
     //print_r($orders); die();

        // var_dump($orders); die();
        $completedOrders = array();
        foreach ($orders as $order){
            if($order['max_status'] == '1' && $order['min_status'] == '1'){
                $completedOrders[] = $order;
            }
        }

        $data['orders'] = $completedOrders;
        $this->load->view('layout/header');
        $this->load->view('book-store/completed-orders', $data);
        $this->load->view('layout/footer');
    }

    public function edit_book($id){
        $data['book'] = $this->book_store->edit_book($id);
        $data['classes'] = $this->class_model->getClassesTag();
        $this->load->view('layout/header');
        $this->load->view('book-store/edit_book', $data);
        $this->load->view('layout/footer');
    }


    public function update_book(){

        $classes = $this->input->post('classs');

        $tags = implode(', ', $classes);


        $data = array(
            'id' => $this->input->post('id'),
            'title' => $this->input->post('title'),
            'brand' => $this->input->post('brand'),
            'price' => $this->input->post('price'),
            'author' => $this->input->post('author'),
            'quantity' => $this->input->post('quantity'),
            'class' => $tags
        );

        if($this->book_store->update_book($data)){
            $this->load->library('session');
            $this->session->set_userdata('book_success', 'Book Updated Successfully!');
            return $this->view_stock();
        }else{
            $this->load->library('session');
            $this->session->set_userdata('book_error', 'Error in book updation!');
            return $this->edit_book($data['id']);
        }

    }


    public function disable($id){

        $data = array(
            'id' => $id,
            'is_disabled' => 1
        );
        if($this->book_store->disable_book($data)){
            $this->session->set_userdata('book_success', 'Book Deleted Successfully!');
            return $this->view_stock();
        }else{
            $this->session->set_userdata('book_error', 'Error in Book Deletion!');
            return $this->view_stock();
        }
    }


    public function viewCompleteOrder($orderId){
        $data["orders"] = $this->store_orders->getDetails($orderId);
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/complete_order_detail', $data);
        $this->load->view('layout/footer');
    }



    public function viewOrder($orderId){
        $data["orders"] = $this->store_orders->getDetails($orderId);
   //var_dump($data["orders"] ); die();

        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $this->load->view('layout/header');
        $this->load->view('book-store/order_detail', $data);
        $this->load->view('layout/footer');
    }

    public function cancelOrder($orderId){
        if($this->store_orders->cancelOrder($orderId)){
            $this->session->set_userdata('success', 'Order Cancelled Successfully!');
        }else{
            $this->session->set_userdata('error', 'Error in Order Cancellation!');
        }
        //;

        redirect_back();
    }



    public function import(){

        $fields = array('bluk_class', 'bluk_author', 'bluk_ISBN', 'bluk_title', 'bluk_price', 'bluk_quantity', 'bluk_brand');
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



                        $isbn = $data[$i]["bluk_ISBN"];


                        // if ($this->book_store->check_book_exists($isbn)) {
                        //     $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center">Record already exists.</div>');
                        //   } else {

                        $data_new = array(
                            'book_id' =>  $data[$i]["bluk_ISBN"],
                            'title' =>  $data[$i]["bluk_title"],
                            'author' =>  $data[$i]["bluk_author"],
                            'quantity' =>  $data[$i]["bluk_quantity"],
                            'brand' => $data[$i]['bluk_brand'],
                            'price' => $data[$i]["bluk_price"],
                            'class' => $data[$i]["bluk_class"]
                        );

                        $array []=  $data_new;
                        $rowcount++;

                    }

                    if($this->book_store->add_book_stock($array) > 0){
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
        $this->load->view('book-store/import', $data);
        $this->load->view('layout/footer');
    }


    public function exportformat(){
        $this->load->helper('download');
        $filepath = "./backend/import/import_book_sample_file.csv";
        $data = file_get_contents($filepath);
        $name = 'import_book_sample_file.csv';

        force_download($name, $data);
    }



    public function printReceipt($orderId){
        $orders = $this->store_orders->getReceiptDetails($orderId);

           //echo "<pre>";  print_r( $orders);exit;
        
        $data["parent_details"] = $this->student_model->getStudentDetail($orders[0]["std_id"]);
		
        $data["orders"] = $orders;
        //echo "<pre>";  print_r($data["parent_details"] );exit;
        $data["sales_tax"] = $this->setting_model->getSalesTax();

        $this->load->view('layout/header');
        $this->load->view('book-store/receipt_view', $data);
        $this->load->view('layout/footer');
    }

    public function generateinvoice($orderId,$requestType=true){
        $orders = $this->store_orders->getReceiptDetails($orderId);
        $invoiceDetails = $this->invoices_model->checkOrderInvoices($orderId);
        
        $data["parent_details"] = $this->student_model->getStudentDetail($orders[0]["std_id"]);
	if(count($invoiceDetails)<=0){
        $parentId = $data["parent_details"][0]->parent_id;
        $admission_no = $data["parent_details"][0]->admission_no;
        $amount = $orders[0]["price"];
        $date = date("Y-m-d", strtotime( $orders[0]["created_at"]));
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        extract($data["sales_tax"]);
//        echo $sales_tax;
//        die;
        $dataInvoices = [
            "invoice_amount" => $amount,
            "parent_id" => $parentId,
            "admission_no" => $admission_no,
            "invoice_date" => $date,
            "status" => "active",
            "inv_type"=>"book"
        ];
        $lastInsertId = $this->invoices_model->add_invoices($dataInvoices);
       
        $amountTotal = 0;
        $orderInvoiceData = [];
        if(count($orders)>0){
            foreach($orders as $orderItems){
                $saleTax = number_format(($sales_tax*$orderItems["price"])/100 , 2);
                $totalAmount = number_format( $orderItems["price"] + $saleTax , 2);
                $orderInvoiceData[]=[
                    "book_order_id" => $orderItems["order_id"],
                    "book_id"=> $orderItems["book_id"],
                    "amount"=> $orderItems["price"],
                    "tax_percent"=> $sales_tax,
                    "tax"=> $saleTax,
                    "total_amount"=> $totalAmount,
                    
                    "invoice_id"=> $lastInsertId,
                ];
                $amountTotal += $totalAmount;
            }
            $this->invoices_model->db->insert_batch("invoices_details",$orderInvoiceData);
        }
        $dataInvoicesNumber = [
                    "invoice_number" => $lastInsertId,
                    "invoice_amount" => $amountTotal,
                ];
        $this->invoices_model->update_invoices_number($lastInsertId, $dataInvoicesNumber);
        }
        
        if($requestType)
            return redirect(site_url('admin/invoices/index'));
        else
            return "Successfully generated";    
        /*$data["orders"] = $orders;
        //echo "<pre>";  print_r($data["parent_details"] );exit;
        $data["sales_tax"] = $this->setting_model->getSalesTax();

        $this->load->view('layout/header');
        $this->load->view('book-store/receipt_view', $data);
        $this->load->view('layout/footer');*/
        
    }
    
    
    
    public function generateAllBookOrderInvoice(){
        
        $orderIds = $this->store_orders->getOrderId();
        foreach($orderIds as $orderId){
            $order_id = $orderId["order_id"];
            echo $order_id." ".$this->generateinvoice($order_id,false)."<br>";
           
        }
    }
    
    
    
    public function completeOrder(){
        if(($this->store_orders->markOrderComplete($this->input->post('order_id'), $this->input->post('sold_book_qty'), $this->input->post('books_id'), $this->input->post('ordered_quantity'), $this->input->post('taken_quantity'))> 0)){
            $order = $this->store_orders->getDetails($this->input->post('order_id'));
            $this->book_store->updateStockOnOrderCompltion($order);

            $this->session->set_userdata('success', 'Order Completed Successfully!');
        }else{
            $this->session->set_userdata('error', 'Error in order completion!');
        }
        return redirect(site_url('admin/BookStore/printReceipt').'/'.$this->input->post('order_id'));
    }

}
?>