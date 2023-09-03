

<style type="text/css">

    .radio {

        padding-left: 20px; }

    .radio label {

        display: inline-block;

        vertical-align: middle;

        position: relative;

        padding-left: 5px; }

    .radio label::before {

        content: "";

        display: inline-block;

        position: absolute;

        width: 17px;

        height: 17px;

        left: 0;

        margin-left: -20px;

        border: 1px solid #cccccc;

        border-radius: 50%;

        background-color: #fff;

        -webkit-transition: border 0.15s ease-in-out;

        -o-transition: border 0.15s ease-in-out;

        transition: border 0.15s ease-in-out; }

    .radio label::after {

        display: inline-block;

        position: absolute;

        content: " ";

        width: 11px;

        height: 11px;

        left: 3px;

        top: 3px;

        margin-left: -20px;

        border-radius: 50%;

        background-color: #555555;

        -webkit-transform: scale(0, 0);

        -ms-transform: scale(0, 0);

        -o-transform: scale(0, 0);

        transform: scale(0, 0);

        -webkit-transition: -webkit-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);

        -moz-transition: -moz-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);

        -o-transition: -o-transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33);

        transition: transform 0.1s cubic-bezier(0.8, -0.33, 0.2, 1.33); }

    .radio input[type="radio"] {

        opacity: 0;

        z-index: 1; }

    .radio input[type="radio"]:focus + label::before {

        outline: thin dotted;

        outline: 5px auto -webkit-focus-ring-color;

        outline-offset: -2px; }

    .radio input[type="radio"]:checked + label::after {

        -webkit-transform: scale(1, 1);

        -ms-transform: scale(1, 1);

        -o-transform: scale(1, 1);

        transform: scale(1, 1); }

    .radio input[type="radio"]:disabled + label {

        opacity: 0.65; }

    .radio input[type="radio"]:disabled + label::before {

        cursor: not-allowed; }

    .radio.radio-inline {

        margin-top: 0; }

    .radio-primary input[type="radio"] + label::after {

        background-color: #337ab7; }

    .radio-primary input[type="radio"]:checked + label::before {

        border-color: #337ab7; }

    .radio-primary input[type="radio"]:checked + label::after {

        background-color: #337ab7; }

    .radio-danger input[type="radio"] + label::after {

        background-color: #d9534f; }

    .radio-danger input[type="radio"]:checked + label::before {

        border-color: #d9534f; }

    .radio-danger input[type="radio"]:checked + label::after {

        background-color: #d9534f; }

    .radio-info input[type="radio"] + label::after {

        background-color: #5bc0de; }

    .radio-info input[type="radio"]:checked + label::before {

        border-color: #5bc0de; }

    .radio-info input[type="radio"]:checked + label::after {

        background-color: #5bc0de; }

    </style>



    <div class="content-wrapper" style="min-height: 946px;">

    <!-- Content Header (Page header) -->

    <section class="content-header">

        <h1>

            <i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?> </h1>

    </section>

    <!-- Main content -->

    <section class="content">

        <div class="row">  

            <div class="col-md-12">

                <div class="box box-primary">

                    <div class="box-header with-border">

                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>

                    </div>

                    <form id='form1' action="<?php echo site_url('admin/stuattendence/attendencereport') ?>"  method="post" accept-charset="utf-8">

                        <div class="box-body">

                            <?php echo $this->customlib->getCSRF(); ?>

                            <div class="row">

                            

                                <div class="col-md-offset-9 col-md-3">

                                    <div class="form-group">

                                        <label for="exampleInputEmail1"><small class="req"> *</small>

                                            <?php echo $this->lang->line('attendance'); ?>

                                            <?php echo $this->lang->line('date'); ?>

                                        </label>

                                        <input id="date" name="date" placeholder="" type="text" class="form-control"  value="<?php echo set_value('date', date($this->customlib->getSchoolDateFormat())); ?>" readonly="readonly"/>

                                        <span class="text-danger"><?php echo form_error('date'); ?></span>

                                    </div>

                                </div>

                            </div>

                        </div>

                        <div class="box-footer">

                            <button type="submit" name="search" value="search" class="btn btn-primary btn-sm pull-right checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>

                        </div>

                    </form>

                </div>



                <?php

                if (isset($resultlist)) {

                    ?>

                    <div class="box box-info">

                        <div class="box-header with-border">

                            <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('attendance'); ?> <?php echo $this->lang->line('by_date'); ?> </h3>

                            <div class="box-tools pull-right">

                            </div>

                        </div>

                        <div class="box-body">

                            <?php

                            if (!empty($resultlist)) {

                                ?>

                                <div class="mailbox-controls">

                                    <div class="pull-right">

                                    </div>

                                </div>

                                

                                <div class="download_label"><?php echo $this->lang->line('attendance'); ?> <?php echo $this->lang->line('by_date'); ?></div>

                                <div class="table-responsive">    

                                    <table class="table table-hover table-striped example">

                                        <thead>

                                            <tr>

                                                <th>#</th>

                                                <th><?php echo $this->lang->line('class'); ?></th>

                                                <th><?php echo $this->lang->line('section'); ?></th>
                                                <th><?php echo $this->lang->line('subject'); ?></th>

                                                <th><?php echo $this->lang->line('total_students'); ?></th>

                                                <th ><?php echo $this->lang->line('present'); ?></th>

                                            </tr>

                                        </thead>

                                        <tbody>

                                            <?php

                                            $row_count = 1;
                                            $sum_total_students = 0;
                                            $sum_present_students =0;

                                            foreach ($resultlist as $key => $value) {

                                                ?>

                                                <tr>

                                                <!--   <td>

                                                  <input type="hidden" name="student_session[]" value="<?php echo $value['student_session_id']; ?>">

                                                  <input  type="hidden" value="<?php echo $value['attendence_id']; ?>"  name="attendendence_id<?php echo $value['student_session_id']; ?>">



                                                  </td> -->

                                                    <td> <?php echo $row_count; ?></td>

                                                    <td>     <?php echo $value['class']; ?>   </td>

                                                    <td>     <?php echo $value['section']; ?>   </td>
                                                    <td>
                                                         <?php echo $value['name']; ?>   </td>

                                                    <td>

                                                        <?php echo $value['total_attendances']; ?>
                                                        <?php 
                                                        $sum_total_students += $value['total_attendances']; ?>
                                                        

                                                    </td>
                                                     <td >

                                                        <?php echo $value['present_count']; ?>
                                                        <?php 
                                                        $sum_present_students += $value['present_count']; ?>

                                                    </td>

                                              <?php

                                                $row_count++;

                                            }

                                            ?>

                                        </tbody>
                                      <tr>
                                       
                                            
                                        
                                    </tr>
                                    <tr>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th><?php echo  $sum_total_students;?></th>
                                        <th ><?php echo $sum_present_students; ?></th>
                                    </tr>  

                                    </table>
                                   


                                    <?php

                                }else {

                                    ?>

                                    <div class="alert alert-info">

                                        <?php echo $this->lang->line('no_attendance_prepare'); ?>

                                    </div>

                                    <?php

                                }

                                ?>

                            </div>

                        </div></div> 

                    <?php

                }

                ?>

                </section>

            </div>

            <script type="text/javascript">

                $(document).ready(function () {

                   

                    var date_format = '<?php echo $result = strtr($this->customlib->getSchoolDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

                    $('#date').datepicker({

                        format: date_format,

                        autoclose: true

                    });

                });

            </script>