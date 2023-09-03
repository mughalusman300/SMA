<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transaction extends Admin_Controller {

    function __construct() {

        parent::__construct();
    }

    function searchtransaction() {
        $this->session->set_userdata('top_menu', 'Reports');
        $this->session->set_userdata('sub_menu', 'transaction/searchtransaction');
        $data['title'] = 'Search Expense';

        $search = $this->input->get('search');
        $action = $this->input->get('action');
        if ($action == "search" || $action == "export") {
            $data["date_from"] = $this->input->get('date_from');
            $data["date_to"] = $this->input->get('date_to');

            $date_from = date('Y-m-d', $this->customlib->datetostrtotime($this->input->get('date_from')));
            $date_to = date('Y-m-d', $this->customlib->datetostrtotime($this->input->get('date_to')));

            $config = array();
            $config['reuse_query_string'] = true;
            // $config['page_query_string'] = true;
            $config['use_page_numbers'] = TRUE;
            $config["base_url"] = base_url() . "/admin/transaction/searchtransaction";
            $config["total_rows"] = $this->studentfeemaster_model->getFeeBetweenDate($date_from, $date_to, true);
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
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
            $offset = ($page - 1) * $config['per_page'];
            $data["links"] = $this->pagination->create_links();

            $data['exp_title'] = 'Transaction From ' . $this->input->get('date_from') . " To " . $this->input->get('date_to');


            if ($action == "export") {
                $feeList = $this->studentfeemaster_model->getFeeBetweenDate($date_from, $date_to, false, $config["per_page"], $offset, true);
                $this->exportTransaction($feeList);
            }
            $feeList = $this->studentfeemaster_model->getFeeBetweenDate($date_from, $date_to, false, $config["per_page"], $offset);
            $data['feeList'] = $feeList;
        }

        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/searchtransaction', $data);
        $this->load->view('layout/footer', $data);
    }

    function searchincome() {

        $this->session->set_userdata('top_menu', 'Reports');

        $this->session->set_userdata('sub_menu', 'transaction/searchincome');

        $data['title'] = 'Search Income';


        $search = $this->input->get('search');
        $action = $this->input->get('action');

        if ($action == "search" || $action == "export") {

            $data['exp_title'] = 'Income From ' . $this->input->get('date_from') . " To " . $this->input->get('date_to');

            $date_from = date('Y-m-d', $this->customlib->datetostrtotime($this->input->get('date_from')));
            $date_to = date('Y-m-d', $this->customlib->datetostrtotime($this->input->get('date_to')));
            $data["date_from"] = $this->input->get('date_from');
            $data["date_to"] = $this->input->get('date_to');
            $config = array();
            $config['reuse_query_string'] = true;
            // $config['page_query_string'] = true;
            $config['use_page_numbers'] = TRUE;
            $config["base_url"] = base_url() . "/admin/transaction/searchincome";
            $config["total_rows"] = $this->income_model->search("", $date_from, $date_to, true);
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
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
            $offset = ($page - 1) * $config['per_page'];
            $data["links"] = $this->pagination->create_links();



            if ($action == "export") {
                $incomeList = $this->income_model->search("", $date_from, $date_to, false, $config["per_page"], $offset, true);
                if(!empty($incomeList))
                $this->exportIncome($incomeList);
            }
            $incomeList = $this->income_model->search("", $date_from, $date_to, false, $config["per_page"], $offset);
            $data['incomeList'] = $incomeList;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/searchincome', $data);
        $this->load->view('layout/footer', $data);
    }

    function searchexpense() {

        $this->session->set_userdata('top_menu', 'Reports');

        $this->session->set_userdata('sub_menu', 'transaction/searchexpanse');

        $data['title'] = 'Search Expense';


        $search = $this->input->get('search');
        $action = $this->input->get('action');

        if ($action == "search" || $action == "export") {

            $data['exp_title'] = 'Expenses From ' . $this->input->get('date_from') . " To " . $this->input->get('date_to');

            $date_from = date('Y-m-d', $this->customlib->datetostrtotime($this->input->get('date_from')));
            $date_to = date('Y-m-d', $this->customlib->datetostrtotime($this->input->get('date_to')));
            $data["date_from"] = $this->input->get('date_from');
            $data["date_to"] = $this->input->get('date_to');
            $config = array();
            $config['reuse_query_string'] = true;
            // $config['page_query_string'] = true;
            $config['use_page_numbers'] = TRUE;
            $config["base_url"] = base_url() . "/admin/transaction/searchexpense";
            $config["total_rows"] = $this->expense_model->search("", $date_from, $date_to, true);
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
            $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 1;
            $offset = ($page - 1) * $config['per_page'];
            $data["links"] = $this->pagination->create_links();



            if ($action == "export") {
                $expenseList = $this->expense_model->search("", $date_from, $date_to, false, $config["per_page"], $offset, true);
                if(!empty($expenseList))
                    $this->exportExpense($expenseList);
            }
            $expenseList = $this->expense_model->search("", $date_from, $date_to, false, $config["per_page"], $offset);
            $data['expenseList'] = $expenseList;
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/transaction/searchexpense', $data);
        $this->load->view('layout/footer', $data);
    }

    function exportTransaction($incomeList) {

        if (count($incomeList) > 0) {
            $filename = 'income_' . date('YmdHis') . '.csv';
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");
            $grand_total = 0;
            // file creation
            $file = fopen('php://output', 'w');
            $csvHeader = array("Payment ID",
                "Date",
                "Registration Number",
                "Name",
                "Description",
                "Class",
                "Fees Type",
                "Mode",
                "Total (SR)"
            );
            fputcsv($file, $csvHeader);
            if (!empty($incomeList)) {
                $count = 1;
                $amount = 0;
                $discount = 0;
                $fine = 0;
                $tax = 0;
                foreach ($incomeList as $key => $value) {
                    $amount = $amount + $value['amount'];
                    $discount = $discount + $value['amount_discount'];
                    $fine = $fine + $value['amount_fine'];
                    $tax = $tax + $value["tax"];
                    $total = ($amount + $fine) - $discount + $tax;
                    $t = ($value['amount'] + $value['amount_fine']) - $value['amount_discount'] + $value['tax'];
                    $csvBotdy = array(
                        $value['id'] . "/" . $value['inv_no'],
                        date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($value['date'])),
                        $value['admission_no'],
                        $value['firstname'] . " " . $value['lastname'],
                        $value['description'],
                        $value['class'] . " (" . $value['section'] . ")",
                        $value['name'],
                        $value['payment_mode'],
                        number_format($value['amount'], 2, '.', '')
                            /* number_format($value['tax'], 2, '.', ''),
                              number_format($value['amount_discount'], 2, '.', ''),
                              (number_format($value['amount_fine'], 2, '.', '')),
                              (number_format($t, 2, '.', '')) */                            );
                    fputcsv($file, $csvBotdy);
                    $count++;
                }

                $csvBotdy = array(
                    '',
                    '', '', '', '', '', '', $this->lang->line('grand_total'), (number_format($amount, 2, '.', '')));
                fputcsv($file, $csvBotdy);
            }
            fclose($file);
            exit;
        }
        exit;
    }

    function exportExpense($incomeList) {
        
    }

    function exportIncome($incomeList) {
        if (count($incomeList) > 0) {
            $filename = 'income_' . date('YmdHis') . '.csv';
            header("Content-Description: File Transfer");
            header("Content-Disposition: attachment; filename=$filename");
            header("Content-Type: application/csv; ");
            $grand_total = 0;
            // file creation
            $file = fopen('php://output', 'w');

            $csvHeader = array("Income ID",
                "Date",
                "Income Head",
                "Name",
                "Description",
                "Invoice No",
                "Amount(SR)"
            );
            fputcsv($file, $csvHeader);
            if (!empty($incomeList)) {
                foreach ($incomeList as $key => $values) {
                    $grand_total = $grand_total + $values['amount'];
                    $csvBody = [
                        $values['id'],
                        date($this->customlib->getSchoolDateFormat(), $this->customlib->dateyyyymmddTodateformat($values['date'])),
                        $values['income_category'],
                        $values['name'],
                        $values['note'],
                        $values['invoice_no'],
                        $values['amount']];
                    fputcsv($file, $csvBody);
                }
                $csvBody = [
                    '',
                    '',
                    '',
                    '',
                    '',
                    'Total',
                    $grand_total];
                fputcsv($file, $csvBody);
            }
            fclose($file);
            exit;
        }
        exit;
    }

    function studentacademicreport() {

        if (!$this->rbac->hasPrivilege('balance_fees_report', 'can_view')) {

            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Fees Collection');

        $this->session->set_userdata('sub_menu', 'transaction/studentacademicreport');

        $data['title'] = 'student fee';

        $data['title'] = 'student fee';

        $class = $this->class_model->get();

        $data['classlist'] = $class;

        $fee_tax = $this->setting_model->getFeeTax();

        if ($this->input->server('REQUEST_METHOD') == "GET") {

            $this->load->view('layout/header', $data);

            $this->load->view('admin/transaction/studentAcademicReport', $data);

            $this->load->view('layout/footer', $data);
        } else {

            $this->form_validation->set_rules('section_id', 'Section', 'trim|required|xss_clean');

            $this->form_validation->set_rules('class_id', 'Class', 'trim|required|xss_clean');

            if ($this->form_validation->run() == FALSE) {

                $this->load->view('layout/header', $data);

                $this->load->view('admin/transaction/studentAcademicReport', $data);

                $this->load->view('layout/footer', $data);
            } else {

                $class_id = $this->input->post('class_id');

                $section_id = $this->input->post('section_id');

                $feetype = $this->input->post('feetype');

                $feetype_arr = $this->input->post('feetype_arr');

                $student_Array = array();

                $studentlist = $this->student_model->searchByClassSection($class_id, $section_id);

                if (!empty($studentlist)) {

                    foreach ($studentlist as $key => $eachstudent) {

                        $obj = new stdClass();

                        $obj->name = $eachstudent['firstname'] . " " . $eachstudent['lastname'];

                        $obj->class = $eachstudent['class'];

                        $obj->section = $eachstudent['section'];

                        $obj->admission_no = $eachstudent['admission_no'];

                        $obj->roll_no = $eachstudent['roll_no'];

                        $obj->father_name = $eachstudent['father_name'];

                        $student_session_id = $eachstudent['student_session_id'];

                        $student_total_fees = $this->studentfeemaster_model->getStudentFees($student_session_id);



                        if (!empty($student_total_fees)) {





                            $totalfee = 0;

                            $deposit = 0;

                            $discount = 0;

                            $balance = 0;

                            $fine = 0;
                            $tax_amount = 0;

                            foreach ($student_total_fees as $student_total_fees_key => $student_total_fees_value) {

                                $discount_percent = $student_total_fees_value->discount_amount;
                                $discount_name = $student_total_fees_value->discount_name;
                                $discount_code = $student_total_fees_value->discount_code;
                                if (!empty($student_total_fees_value->fees)) {

                                    foreach ($student_total_fees_value->fees as $each_fee_key => $each_fee_value) {
                                        $totalfee = $totalfee + $each_fee_value->amount;
                                        $amount_detail = json_decode($each_fee_value->amount_detail);
                                        // echo "<pre/>";
                                        // var_dump($amount_detail);
// print_r($amount_detail);
                                        $discount = $discount + ((($each_fee_value->amount * $discount_percent) / 100));
                                        if (is_object($amount_detail)) {
                                            foreach ($amount_detail as $amount_detail_key => $amount_detail_value) {
                                                if(trim(@$amount_detail_value->status) != "deleted"){
                                                    $deposit = $deposit + $amount_detail_value->amount;
                                                    $fine = $fine + $amount_detail_value->amount_fine;
                                                    $discount = $discount + $amount_detail_value->amount_discount;
                                                }
                                            }
                                        }
                                    }
                                }
                            }

                            $obj->totalfee = $totalfee;

                            $obj->payment_mode = "N/A";

                            $obj->deposit = $deposit;

                            $obj->fine = $fine;


                            $obj->discount = $discount;

                            $tax_amount = (($fee_tax["fee_tax"] * ($totalfee + $fine - $discount)) / 100);

                            $obj->tax_amount = $tax_amount;
                            $obj->balance = $totalfee - ($deposit + $discount) + $tax_amount;
                        } else {



                            $obj->totalfee = "N/A";

                            $obj->payment_mode = "N/A";

                            $obj->deposit = "N/A";

                            $obj->fine = "N/A";
                            $obj->tax_amount = "N/A";

                            $obj->balance = "N/A";

                            $obj->discount = "N/A";
                        }

                        $student_Array[] = $obj;
                    }
                }





                $data['student_due_fee'] = $student_Array;



                // exit();

                $data['class_id'] = $class_id;

                $data['section_id'] = $section_id;

                $data['feetype'] = $feetype;

                $data['feetype_arr'] = $feetype_arr;

                $this->load->view('layout/header', $data);

                $this->load->view('admin/transaction/studentAcademicReport', $data);

                $this->load->view('layout/footer', $data);
            }
        }
    }

}
?>