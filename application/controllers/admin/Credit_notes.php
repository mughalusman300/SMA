<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Credit_notes extends Admin_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model("setting_model");
	$this->load->model("feetype_model");
        $this->load->model("creditnotes_model");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('credit_notes', 'can_view')) {
            access_denied();
        }
        $guardian_id = '';
        $this->session->set_userdata('top_menu', 'credit_note');
        $this->session->set_userdata('sub_menu', 'admin/credit_notes/index');
        $data['title'] = 'Credit Notes';


        if ($this->input->server('REQUEST_METHOD') == "GET")
         {
            $guardian_id = null;
            $admission_no = null;
            $invoice_no = null;
            $date_from = null;
             $date_to = null;
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
	}
        $config = array();
        $config['reuse_query_string'] = true;
        // $config['page_query_string'] = true;
        $config['use_page_numbers'] = TRUE;
        $config["base_url"] = base_url() . "/admin/credit_notes/index";
        
        $config["total_rows"] = $this->creditnotes_model->getCreditnoteList($invoice_no,$guardian_id,$admission_no,$invoice_no,true, $date_from, $date_to); 
        
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
			$creditnotes = $this->creditnotes_model->getCreditnoteDetails($invoice_no,$guardian_id,$admission_no,$invoice_no,false, $date_from, $date_to);
			$this->exportCreditnotesData($creditnotes);
		} else if(trim($action) == 'export_all'){
			ini_set('display_errors', 0);
		 ini_set('display_startup_errors', 0);
		  error_reporting(0);
			$creditnotes = $this->creditnotes_model->getCreditnoteList($invoice_no,$guardian_id,$admission_no,$invoice_no,false, $date_from, $date_to,10,0,true);
			$this->exportCreditnotes($creditnotes);
		}
			
        $creditnotes = $this->creditnotes_model->getCreditnoteList($invoice_no,$guardian_id,$admission_no,$invoice_no,false, $date_from, $date_to,$config["per_page"],$offset);
      
        $data['creditNotesList'] = $creditnotes;
        $data['guardian_id'] = $guardian_id;
        $data['invoice_no'] = $invoice_no;
        $data['admission_no'] = $admission_no;
	
        $this->load->view('layout/header', $data);
        $this->load->view('admin/credit_notes/index', $data);
        $this->load->view('layout/footer', $data);
    }
	
	function exportCreditnotes($invoicesDetails){
		
		 $fee_tax = $this->setting_model->getFeeTax();
		 $filename = 'studentfee_'.date('YmdHis').'.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
 
        // file creation
        $file = fopen('php://output', 'w');
 
		$csvHeader = array("Credit Notes ID",
						"Credit Notes Date",
						"Admission Number",
						"Parent Id",
						"Total Amount",
						"Invoice Status"
						);
		fputcsv($file, $csvHeader);
						 if (isset($invoicesDetails)) {
                 
                                $count = 1;
                                foreach ($invoicesDetails as $invoice) {
                                    $csvBody = array($invoice['creditnote_number'],
                                            $invoice['creditnote_date'],
                                            $invoice['admission_no'],
                                            $invoice['guardian_id'],
                                            $currency_symbol." ".$invoice['creditnote_amount'],
                                            $invoice['status']
                                            );
                                    fputcsv($file, $csvBody);		
                                }
                                $count++;
                                
                }
					fclose($file);
        exit;
						
		}
		
    function exportCreditnotesData($invoicesDetails){
		
		 $fee_tax = $this->setting_model->getFeeTax();
		 $filename = 'studentfee_'.date('YmdHis').'.csv';
        header("Content-Description: File Transfer");
        header("Content-Disposition: attachment; filename=$filename");
        header("Content-Type: application/csv; ");
 
        // file creation
        $file = fopen('php://output', 'w');
 
		$csvHeader = array("Credit Note ID",
						"Credit Note Date",
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
							  
							  $csvBody = array($feeList['creditnote_number'],
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
    function print_credit_notes() {
        if (!$this->rbac->hasPrivilege('invoices', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoice_id =  $this->input->post('invoice_id');
        $invoicesList = $this->creditnotes_model->get($invoice_id);
        
        
        if($invoicesList['inv_type'] == 'book'){
            $invoicesDetails = $this->creditnotes_model->getCreditNoteBookDetails($invoice_id);
        }else{
            $invoicesDetails = $this->creditnotes_model->getCreditnoteDetails($invoice_id);
        }
        $data["sales_tax"] = $this->setting_model->getSalesTax();
//      
//      
//      
//            echo "<pre>";
//           print_r($invoicesDetails);
//           die;
        $data['invoiceslist'] = $invoicesList;
        $data['invoicesDetails'] = $invoicesDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/credit_notes/print_credit_notes", $data);
    }
    function print_invoices_set_ar(){
        $this->load->helper('lang');
        set_language(5);
    }
    
    function delete_credit_notes(){
        $invoice_id =  $this->input->post('invoice_id');
        $this->db->where('creditnote_number', $invoice_id);
        $this->db->update('student_fees_master', [ "creditnote_number"=>null]);
        
        $this->db->where('id', $invoice_id);
        $this->db->update('credit_note', ["status"=>"deleted"]);
        
    }
    
    
    
    function print_credit_notes_ar() {
        $this->load->helper('lang');
        if (!$this->rbac->hasPrivilege('receipts', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoice_id =  $this->input->post('invoice_id');
        $invoicesList = $this->creditnotes_model->get($invoice_id);
       if($invoicesList['inv_type'] == 'book'){
            $invoicesDetails = $this->creditnotes_model->getCreditNoteBookDetails($invoice_id);
        }else{
            $invoicesDetails = $this->creditnotes_model->getCreditnoteDetails($invoice_id);
        }
        $data["sales_tax"] = $this->setting_model->getSalesTax();
//            echo "<pre>";
//           print_r($invoicesDetails);
//           die;
        $data['invoiceslist'] = $invoicesList;
        $data['invoicesDetails'] = $invoicesDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/credit_notes/print_credit_notes_ar", $data);
        set_language(4);
    }

    
    
    function pdf() {
        $this->load->helper('pdf_helper');
    }

    function print_student_invoices() {
        if (!$this->rbac->hasPrivilege('invoices', 'can_view')) {
            access_denied();
        }
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoices_id =  $this->input->post('invoices_id');
        $invoicesList = $this->invoices_model->get($invoices_id);
        $invoicesDetails = $this->invoices_model->getStudentFeeDetails($invoices_id);
            echo "<pre>";
           print_r($receiptsDetails);
           die;
        $data['invoiceslist'] = $invoicesList;
        $data['invoicesDetails'] = $invoicesDetails;
        $data["fee_tax"] = $this->setting_model->getFeeTax();
        $data["previous_session_balance_tax"] = $this->setting_model->getPreviousSessionBalanceTax();
        
        $this->load->view("admin/invoices/print_invoices", $data);
    }

    
    
    
}

?>