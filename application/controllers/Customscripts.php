<?php



class Customscripts extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('smsgateway');
        $this->load->library('mailsmsconf');
        $this->load->model("setting_model");
	$this->load->model("feetype_model");
        $this->load->model("invoices_model");
        $this->load->model("creditnotes_model");
        $this->load->model('book_store');
        $this->load->model('store_orders');
        $this->load->model('student_model');
        $this->load->library('session');
        $this->load->library('encoding_lib');
        $this->load->model('class_model');
         $this->load->model('studentsession_model');
        
    }

    public function  print_student_invoices() {
        $setting_result = $this->setting_model->get();
        $data['settinglist'] = $setting_result;
        
        $invoices_id =  $this->input->post('invoices_id');
        $invoicesList = $this->invoices_model->get($invoices_id);
        $invoicesDetails = $this->invoices_model->getStudentFeeDetails($invoices_id);
        if(count($invoicesDetails)>0){
        $to      = 'mtahir.nusrat@gmail.com, hasan.akhter@pisjes.edu.sa ';
        $subject = 'invoices genearted from PISJES CRON JOB';
        $message =  print_r($invoicesDetails, true);
        $headers = 'From: hasan.akhter@pisjes.edu.sa ' . "\r\n" .
            'Reply-To: hasan.akhter@pisjes.edu.sa' . "\r\n" .
            'X-Mailer: PHP/' . phpversion();
        mail($to, $subject, $message, $headers);
       
        }
       die; 
    }

    public function generateAllBookOrderInvoice(){
        $hetml = [];
        $orderIds = $this->store_orders->getOrderIdNotInvoiced();
        foreach($orderIds as $orderId){
            $order_id = $orderId["order_id"];
            $msgStatus = $this->generateinvoice($order_id,false);
            if(trim($msgStatus) !="")
                $hetml[] = $order_id." ".$this->generateinvoice($order_id,false)."<br>";
              
        }
        if(count($hetml)>0){
            $to      = 'mtahir.nusrat@gmail.com, hasan.akhter@pisjes.edu.sa ';
            $subject = 'Book Invoices genearted from PISJES CRON JOB';
            $message =  print_r($hetml, true);
            $headers = 'From: hasan.akhter@pisjes.edu.sa ' . "\r\n" .
                'Reply-To: hasan.akhter@pisjes.edu.sa' . "\r\n" .
                'X-Mailer: PHP/' . phpversion();
            mail($to, $subject, $message, $headers);
        }
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
        
        return "Successfully generated";   
        }
         
        
    }
    
    
    
    public function updatestaffpwds() {
        echo "tahir";

die;
            $this->load->model("staff_model");
              $this->load->library('Enc_lib');
            
             $allstaff = $this->staff_model->getDataForUpdatePassword();
             
             if(count($allstaff)>0){
                 echo '<table><tr><th>ID</th><th>employee_id</th><th>Name</th><th>user_type</th><th>Password</th><th>Pass Key</th><th>Password Updated</th></tr><tbody>';
                 
                 foreach($allstaff as $staff){
                   
                    echo '<tr><td>'.$staff['id'].'</td><td>'.$staff['employee_id'].'</td><td>'.$staff['name'].'</td><td>'.$staff['user_type'].'</td><td>'.$staff['password'].'</td><td>'.$staff['pass_key']."</td>";
                    $pass_key = $staff['pass_key'];

                                $NewPasswordEnc = $this->enc_lib->passHashEnc($pass_key);

                                echo "<td>" . $NewPasswordEnc . "</td>";
                                        
                                          $update_record = array(
                                          'id' => $staff['id'],
                                          'password' =>$NewPasswordEnc,
                                          );
                                          $change = $this->staff_model->update($update_record);
                                          if ($change) {
                                          //if the password was successfully changed
                                          } else {
                                          //if the password was not updated successfully
                                          }
                                        
                
                echo "</tr>";
                 }
                 
                 echo '</tbody></table>';
             }
             
             
              
            exit;

    }



}



?>