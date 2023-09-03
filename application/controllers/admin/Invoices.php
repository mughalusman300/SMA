<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoices extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model("setting_model");
	$this->load->model("feetype_model");
        $this->load->model("invoices_model");
        $this->load->model("creditnotes_model");
        
    }

    function index() {
        if (!$this->rbac->hasPrivilege('invoices', 'can_view')) {
            access_denied();
        }
        $guardian_id = '';
        $this->session->set_userdata('top_menu', 'invoices');
        $this->session->set_userdata('sub_menu', 'admin/invoices/index');
        $data['title'] = 'Invoices';

        $invoicesTypes = $this->invoices_model->getInvoiceTypes();
        $invoicesStatus = $this->invoices_model->getInvoiceStatus();
        if ($this->input->server('REQUEST_METHOD') == "GET")
         {
            $guardian_id = null;
            $admission_no = null;
            $invoice_no = null;
            $date_from = null;
            $date_to = null;
            $status = null;
            $invoice_type = null;
            $action = null;
        }
        else
        {
            $search_text = $this->input->post('search_text');
            if (!empty($search_text)) 
            {
                $guardian_id = $search_text;
            }
			
			 $action = $this->input->post('action');
             $admission_no = $this->input->post('admission_no');
             $invoice_no = $this->input->post('invoice_no');
			 $date_from = date('Y-m-d 00:00:00', $this->customlib->datetostrtotime($this->input->post('date_from')));
			 $date_to = date('Y-m-d 23:59:00', $this->customlib->datetostrtotime($this->input->post('date_to')));
             $status = $this->input->post('status');
             $invoice_type = $this->input->post('invoice_type');
        
        }
        $config = array();
        $config['reuse_query_string'] = true;
        // $config['page_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "/admin/invoices/index";
        $config["total_rows"] = $this->invoices_model->getInvoicesList($invoice_no,$guardian_id,$admission_no,$invoice_no,true, $date_from, $date_to,$status,$invoice_type); 
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
		if(trim($action) == 'export'){
			ini_set('display_errors', 0);
		 ini_set('display_startup_errors', 0);
		  error_reporting(0);
			$invoices = $this->invoices_model->getInvoiceDetails($invoice_no,$guardian_id,$admission_no,$invoice_no,false, $date_from, $date_to,$status,$invoice_type);
			$this->exportInvoicesData($invoices);
		} else if(trim($action) == 'export_all'){
			ini_set('display_errors', 0);
		 ini_set('display_startup_errors', 0);
		  error_reporting(0);
			$invoices = $this->invoices_model->getInvoicesList($invoice_no,$guardian_id,$admission_no,$invoice_no,false, $date_from, $date_to,$status,$invoice_type,10,0,true);
			$this->exportInvoices($invoices);
		}
			
        $invoices = $this->invoices_model->getInvoicesList($invoice_no,$guardian_id,$admission_no,$invoice_no,false, $date_from, $date_to,$status,$invoice_type,$config["per_page"],$offset);
      
        $data['invoiceslist'] = $invoices;
        $data['guardian_id'] = $guardian_id;
        $data['invoice_no'] = $invoice_no;
        $data['admission_no'] = $admission_no;
        $data['search_status'] = $status;
        $data['invoice_type'] = $invoice_type;
        $data['invoices_types'] = $invoicesTypes;//for dropDown
        $data['invoices_status'] = $invoicesStatus;//for dropDown
        //echo "<pre>";
	//	print_r($data);exit;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/invoices/index', $data);
        $this->load->view('layout/footer', $data);
    }
	
	function exportInvoices($invoicesDetails){
		
		 $fee_tax = $this->setting_model->getFeeTax();
		 $filename = 'invoices_'.date('YmdHis').'.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
 
        // file creation
        $file = fopen('php://output', 'w');
 
		$csvHeader = array("Invoice ID",
						"Invoice Date",
						"Admission Number",
						"Parent Id",
						"Total Amount",
						"Invoice Status",
                                                "Invoice Type"
						);
		fputcsv($file, $csvHeader);
						 if (isset($invoicesDetails)) {
                 
                                $count = 1;
                                foreach ($invoicesDetails as $invoice) {
									$csvBody = array($invoice['invoice_number'],
														$invoice['invoice_date'],
														$invoice['admission_no'],
														$invoice['guardian_id'],
														$currency_symbol." ".$invoice['invoice_amount'],
														$invoice['status'],
                                                                                                                $invoice['inv_type']
														);
												fputcsv($file, $csvBody);		
                                }
                                $count++;
                                
                }
					fclose($file);
        exit;
						
		}
		
    function exportInvoicesData($invoicesDetails){
		
		 $fee_tax = $this->setting_model->getFeeTax();
		 $filename = 'studentfee_'.date('YmdHis').'.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
 
        // file creation
        $file = fopen('php://output', 'w');
 
		$csvHeader = array("Invoice ID",
						"Invoice Date",
						"Guardian Id",
						"Admission Number",
						"Student Name",
						"Fee Group Name",
						"Fee Amount",
						"Discount Name",
						"Discount Amount",
						"Tax Amount",
						"Total Amount",
						"Invoice Status"
						);
		fputcsv($file, $csvHeader);
						if(!empty($invoicesDetails)){
							$total_amount = 0;
                            $total_deposite_amount = 0;
                            $total_fine_amount = 0;
                            $total_discount_amount = 0;
                            $total_balance_amount = 0;
                            $alot_fee_discount = 0;
                            $total_paid_amount = 0;
                            $total_paid_tax = 0;
                         

                                foreach ($invoicesDetails as $fee_key => $feeList) {
									
                                    $fee_discount = 0;
                                    $fee_paid = 0;
                                    $fee_fine = 0;
                                    if (!empty($feeList['amount_detail'])) {
                                        $fee_deposits = json_decode(($feeList['amount_detail']));

                                        foreach ($fee_deposits as $fee_deposits_key => $fee_deposits_value) {
                                            $fee_paid = $fee_paid + $fee_deposits_value->amount;
                                            $fee_discount = $fee_discount + $fee_deposits_value->amount_discount;
                                            $fee_fine = $fee_fine + $fee_deposits_value->amount_fine;
                                        }
                                    }
                                    $discount_percent = $feeList['students'][0]["discount_amount"];
                                    $discount_name  = $feeList['students'][0]["discount_name"];
                                    $discount_code = $feeList['students'][0]["discount_code"];
                                    $discount = $feeList['fee_groups_feetype'][0]['amount'] * $discount_percent  / 100;

                                    //Feelist->amount = 750
                                    //discount = 150
                                    
                                    $total_amount = $total_amount + $fee_paid;
                                    $total_discount_amount = $total_discount_amount + $discount;
                                    $total_fine_amount = $total_fine_amount + $fee_fine;
                                    $total_deposite_amount = $total_deposite_amount + $fee_paid;
                                    
                                    if(trim($feeList['fee_groups_feetype'][0]['feetype']['code']) == "Previous Session Balance" && $previous_session_balance_tax["previous_session_balance_tax"] == "disabled")
                                            $tax_amount = 0;	
                                    else if($feeList['fee_groups_feetype'][0]['feetype']['is_taxable'] == 'YES')
                                    {
                                        $tax_amount         = (((float)$fee_tax['fee_tax'] * ((float)$feeList['fee_groups_feetype'][0]['amount'] - $discount)) / 100);
                                        //$total_paid_tax += (($fee_paid * (float)$fee_tax['fee_tax'])/100);
                                        $total_paid_tax += ((($fee_paid /  (1+((float)$fee_tax['fee_tax']/100)))*(float)$fee_tax['fee_tax'])/100);
                                        
                                    }
                                    else 
                                            $tax_amount = 0;
                                   

                                    $feetype_balance = $feeList['fee_groups_feetype'][0]['amount'] - ($discount + $fee_paid) + $tax_amount;
                                    $total_balance_amount = $total_balance_amount + $feetype_balance;
							  
							  $csvBody = array($feeList['invoice_number'],
						date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($feeList['fee_groups_feetype'][0]['due_date'])),
						$feeList['students'][0]['guardian_id'],
						$feeList['students'][0]['admission_no'],
						$feeList['students'][0]['firstname'].' '.$feeList['students'][0]['lastname'],
						$feeList['fee_groups_feetype'][0]['fee_groups']['name'],
						$currency_symbol . $feeList['fee_groups_feetype'][0]['amount'],
						$discount_name,
						$currency_symbol . number_format($discount, 2, '.', ''),
						$currency_symbol . $tax_amount,
						$currency_symbol . number_format($feeList['fee_groups_feetype'][0]['amount'] - $discount + $tax_amount, 2, '.', ''),
						$feeList['status']
						);
						fputcsv($file, $csvBody );
						
                                }
				
								
					}
					fclose($file);
        exit;
						
		}
    function print_invoices() {
        
        if (!$this->rbac->hasPrivilege('invoices', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoice_id =  $this->input->post('invoice_id');
        
        $invoicesList = $this->invoices_model->get($invoice_id);
        if($invoicesList['inv_type'] == 'book'){
            $invoicesDetails = $this->invoices_model->getInvoiceBookDetails($invoice_id);
        }else{
            $invoicesDetails = $this->invoices_model->getInvoiceDetails($invoice_id);
        }
        $data["sales_tax"] = $this->setting_model->getSalesTax();
//            echo "<pre>";
//           print_r($invoicesDetails);
//           die;
        $data['invoiceslist'] = $invoicesList;
        $data['invoicesDetails'] = $invoicesDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/invoices/print_invoices", $data);
    }
    function print_invoices_set_ar(){
        $this->load->helper('lang');
        set_language(5);
    }
    
    function delete_invoices(){
        $invoice_id =  $this->input->post('invoice_id');
        
        
        $this->db->where('invoice_number', $invoice_id);
        $this->db->update('student_fees_master', ["is_invoiced"=>null, "invoice_number"=>null]);
        
        $this->db->where('id', $invoice_id);
        $this->db->update('invoices', ["status"=>"deleted"]);
        
    }
    
    
      function create_invoice_note(){
          
        $invoice_id =  $this->input->post('invoice_id');
        $invoiceData =  $this->invoices_model->get($invoice_id);
        
           if(count($invoiceData)>0){
            $dataReceipts = [
                "creditnote_amount"=> floatval($invoiceData["invoice_amount"]),
                "invoice_number"=> $invoiceData['id'],
                "parent_id"=>$invoiceData['parent_id'],
                "admission_no"=>$invoiceData['admission_no'],                
                "status"=>"active",
                "inv_type"=>$invoiceData['inv_type']                
            ];
            $lastInsertId = $this->creditnotes_model->add_creditnote_invoice($dataReceipts);
            
            $dataReceiptsNumber = [
                "creditnote_number"=>$lastInsertId,
            ];
            $this->creditnotes_model->update_creditnote_number($lastInsertId,$dataReceiptsNumber);
            }
        
    }
    
    
    
    function print_invoices_ar() {
        $this->load->helper('lang');
        if (!$this->rbac->hasPrivilege('receipts', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoice_id =  $this->input->post('invoice_id');
        $invoicesList = $this->invoices_model->get($invoice_id);
        if($invoicesList['inv_type'] == 'book'){
            $invoicesDetails = $this->invoices_model->getInvoiceBookDetails($invoice_id);
        }else{
            $invoicesDetails = $this->invoices_model->getInvoiceDetails($invoice_id);
        }
//            echo "<pre>";
//           print_r($invoicesDetails);
//           die;
        $data["sales_tax"] = $this->setting_model->getSalesTax();
        $data['invoiceslist'] = $invoicesList;
        $data['invoicesDetails'] = $invoicesDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/invoices/print_invoices_ar", $data);
        set_language(4);
    }

    
    
    function pdf() {
        $this->load->helper('pdf_helper');
    }

    function print_student_invoices() {
        /*$setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoices_id =  $this->input->post('invoices_id');
        $invoicesList = $this->invoices_model->get($invoices_id);
        $invoicesDetails = $this->invoices_model->getStudentFeeDetails($invoices_id);
            echo "<pre>";
          */
        
        $to      = 'mtahir.nusrat@gmail.com, hasan.akhter@pisjes.edu.sa ';
        $subject = 'invoices genearted from PISJES CRON JOB';
        $message =  print_r($receiptsDetails, true);
        $headers = 'From: hasan.akhter@pisjes.edu.sa ' . "\r\n" .
    'Reply-To: hasan.akhter@pisjes.edu.sa' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);

           die;
        
    }

    
    
    
}

?>