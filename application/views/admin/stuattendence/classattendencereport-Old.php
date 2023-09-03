<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>
<div class="content-wrapper" style="min-height: 946px;">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            <i class="fa fa-calendar-check-o"></i> <?php echo $this->lang->line('attendance'); ?> 
        </h1>
    </section>
    <section class="content">
        <div class="row">   
            <div class="col-md-12">
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                    </div>
                    <form id='form1' action="<?php echo site_url('admin/stuattendence/classmonthlyattendencereport') ?>"  method="post" accept-charset="utf-8">
                        <div class="box-body">

                            <?php echo $this->customlib->getCSRF(); ?>
                            <div class="row">
                                <div id="class_container" class="col-md-3">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('class'); ?></label><small class="req"> *</small>
                                        <select autofocus="" id="class_id" name="class_id" class="form-control" >
                                            <option  value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($classlist as $class) {
                                                ?>
                                                <option  data-attendance_type="<?php echo $class['attendance_type']; ?>" value="<?php echo $class['id'] ?>" <?php
                                                if ($class_id == $class['id']) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $class['class'] ?></option>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>



                                <div id="section_container" <?php if($searchBySubject){ echo 'class="col-md-2"';}else{ echo 'class="col-md-3"';} ?>>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('section'); ?></label><small class="req"> *</small>
                                        <select  id="section_id" name="section_id" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('section_id'); ?></span>
                                    </div>
                                </div>

                                <div id="subject_container" class="col-md-3" <?php if(!$searchBySubject){ echo 'style="display:none"';} ?> >
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo "Subject"; ?></label><small class="req"> *</small>

                                        <select autofocus="" id="subject_select" name="subjectid" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>

                                        </select>
                                        <span class="text-danger"><?php echo form_error('class_id'); ?></span>
                                    </div>
                                </div>

                                <div id="month_container" <?php if($searchBySubject){ echo 'class="col-md-2"';}else{ echo 'class="col-md-3"';} ?>>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('month'); ?></label><small class="req"> *</small>
                                        <select  id="month" name="month" class="form-control" >
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($monthlist as $m_key => $month) {
                                                ?>
                                                <option value="<?php echo $m_key ?>" <?php
                                                if ($month_selected == $m_key) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $month; ?></option>
                                                        <?php
                                                        $count++;
                                                    }
                                                    ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('month'); ?></span>
                                    </div>
                                </div>
                                <div id="year_container" <?php if($searchBySubject){ echo 'class="col-md-2"';}else{ echo 'class="col-md-3"';} ?>>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('year'); ?></label>
                                        <select  id="year" name="year" class="form-control" >

                                            <?php
                                            // $yearlist  = array('2018' => '2018' );
                                            foreach ($yearlist as $y_key => $year) {
                                                ?>
                                                <option value="<?php echo $year["year"] ?>" <?php
                                                if ($year_selected == $year["year"]) {
                                                    echo "selected =selected";
                                                }
                                                ?>><?php echo $year["year"]; ?></option>
                                                        <?php
                                                    }
                                                    ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('year'); ?></span>
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
                if ($this->module_lib->hasActive('student_attendance')) {

                    if (isset($resultlist)) {
                        ?>
                        <div class="box box-info" id="attendencelist">
                            <div class="box-header with-border" >
                                <div class="row">
                                    <div class="col-md-4 col-sm-4">
                                        <h3 class="box-title"><i class="fa fa-users"></i> <?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('attendance'); ?> <?php echo $this->lang->line('register'); ?></h3>
                                    </div>
                                    <div class="col-md-8 col-sm-8">
                                        <div class="lateday">
                                            <b>Days:D</b>
                                            <?php
                                            foreach ($attendencetypeslist as $key_type => $value_type) {
                                                ?>
                                                &nbsp;&nbsp;
                                                <b>
                                                    <?php
                                                    $att_type = str_replace(" ", "_", strtolower($value_type['type']));
                                                    if (strip_tags($value_type["key_value"]) != "E") {
                                                        //for understanding change F TO E
                                                        if(strip_tags($value_type["key_value"]) == "F"){
                                                        $value_type["key_value"] = "E";
                                                    }//end

                                                        echo $this->lang->line($att_type) . ": " . $value_type['key_value'] . "";
                                                    }
                                                    ?>
                                                </b>
                                                <?php
                                            }
                                            ?>
                                        </div>

                                    </div>
                                </div></div>
                            <div class="box-body table-responsive">


                                <?php
                                if (!empty($resultlist)) {
                                    ?>
                                    <div class="mailbox-controls">
                                        <div class="pull-right">
                                        </div>
                                    </div>
                                    <div class="download_label"><?php echo $this->lang->line('student'); ?> <?php echo $this->lang->line('attendance'); ?> <?php echo $this->lang->line('register'); ?></div>
                                    <table class="table table-striped table-bordered table-hover example xyz">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <?php echo $this->lang->line('student'); ?> / <?php echo $this->lang->line('date'); ?>
                                                </th>
                                                

                                                <?php
                                                foreach ($attendencetypeslist as $key => $value) {
                                                    if (strip_tags($value["key_value"]) != "E") {
                                                        ?>
                                                        <th colspan="" ><br/><span data-toggle="tooltip" title="<?php echo "Total " . $value["type"]; ?>"><?php 
                                                        //for understanding change F TO E
                                                            if(strip_tags($value["key_value"]) == "F")
                                                              { $value["key_value"] = "E";}
                                                            //end 

                                                        echo strip_tags($value["key_value"]); ?>

                                                            </span></th>

                                                    <?php }
                                                }
                                                ?>
                                                <th><br/><span data-toggle="tooltip" title="<?php echo "Total  Days"; ?>">D</span></th>
                                                <?php 
                                                foreach ($attendence_array as $at_key => $at_value) {
                                                    if (date('D', $this->customlib->dateyyyymmddTodateformat($at_value)) ==  "Fri" OR date('D', $this->customlib->dateyyyymmddTodateformat($at_value)) =="Sat" ) {
                                                        ?>
                                                        <th></th>
                                                        <?php
                                                    } else {
                                                        ?>
                                                        <th class="tdcls text text-center">
                                                         <a href="<?php echo base_url(); ?>/admin/stuattendence/Edit/<?php echo $class_id;?>/<?php echo $section_id;?>/<?php echo $subject_id;?>/<?php echo $at_value;?>" target="_blank"> 
                                                            <?php
                                                            echo date('d', $this->customlib->dateyyyymmddTodateformat($at_value)) . "<br/>" .
                                                            date('D', $this->customlib->dateyyyymmddTodateformat($at_value))
                                                            ;
                                                            ?>
                                                         </a>
                                                        </th>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                              <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($student_array)) {
                                                ?>
                                                <tr>
                                                    <td colspan="32" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                </tr>
                                                <?php
                                            } else {
                                                $row_count = 1;
                                                $i = 0;
                                                //echo "<pre>";


                                                foreach ($student_array as $student_key => $student_value) {
                                                    //echo $i;
                                                    ?>
                                                    <tr>
                                                        <th class="tdclsname">
                                                            <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#" style="color:#333"><?php echo $student_value['firstname'] . " " . $student_value['lastname']; ?></a></span>
                                                            <div class="fee_detail_popover" style="display: none"><?php echo "Admission No: " . $student_value['admission_no']; ?></div> 
                                                        </th>

                                                        <th><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['present']); ?></th>
                                                        <!--th><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse']); ?></th-->
                                                        <th><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['late'] + $monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse']); ?></th>
                                                        <th><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['absent']); ?></th>
                                                        <th><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['holiday']); ?></th>
                                                        <th><?php print_r($monthAttendance[$i][$student_value['student_session_id']]['half_day']); ?></th>
                                                        <th>
                                                         <?php $total_school_attendenced_days = $monthAttendance[$i][$student_value['student_session_id']]['present'] + $monthAttendance[$i][$student_value['student_session_id']]['late_with_excuse'] + $monthAttendance[$i][$student_value['student_session_id']]['late'] + $monthAttendance[$i][$student_value['student_session_id']]['half_day'] + $monthAttendance[$i][$student_value['student_session_id']]['absent'];?>
                                                         <?php echo $total_school_attendenced_days;?></th>


                                                        <?php
                                                        $count= 0;
                                                        foreach ($attendence_array as $at_key => $at_value) {
                                                            ?>
                                                            <th class="tdcls text text-center">

                                                                <span data-toggle="popover" class="detail_popover" data-original-title="" title=""><a href="#" style="color:#333"><?php

                                                                 if (strip_tags($resultlist[$at_value][$student_value['student_session_id']]['key']) != "A") {

                                                                            $count+= 1;
                                                                        }
                                                                        if (strip_tags($resultlist[$at_value][$student_value['student_session_id']]['key']) == "L") {

                                                                            $attendence_key = "L";
                                                                            $remark = "Late With Excuse";
                                                                        } else {

          $attendence_key = $resultlist[$at_value][$student_value['student_session_id']]['key'];
                                                                            $remark = $resultlist[$at_value][$student_value['student_session_id']]['remark'];
                                                                        }

                                                                        print_r($attendence_key);
                                                                        ?></a></span>
                                                                <div class="fee_detail_popover" style="display: none"><?php echo $remark; ?></div>

                                                            </th>
                                                            
                                            


                                                        <?php }
                                                        ?>


                                                        <?php
                                                        $i++;
                                                        ?>


                                                    </tr>
                                                    <?php
                                                }
                                            }
                                            ?>

                                        </tbody>

                                    </table>
                                    <?php
                                } else {
                                    ?>
                                    <div class="alert alert-info">
                                    <?php echo $this->lang->line('no_attendance_prepare'); ?>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                ?>
                </section>
            </div>
            <script type="text/javascript">
                $(document).ready(function () {


                    $('#class_id').change(function(){
                        var attendanceType =  $(this).find(':selected').data('attendance_type');



                        if(attendanceType == '0'){
                            $("#section_container").removeClass("col-md-2");
                            $("#month_container").removeClass("col-md-2");
                            $("#year_container").removeClass("col-md-2");

                            $("#section_container").addClass("col-md-3");
                            $("#month_container").addClass("col-md-3");
                            $("#year_container").addClass("col-md-3");


                            $('#subject_container').hide();

                        }else{
                            $("#section_container").removeClass("col-md-3");
                            $("#month_container").removeClass("col-md-3");
                            $("#year_container").removeClass("col-md-3");

                            $("#section_container").addClass("col-md-2");
                            $("#month_container").addClass("col-md-2");
                            $("#year_container").addClass("col-md-2");


                            $('#subject_container').show();
                        }


                    });



                    $('.detail_popover').popover({
                        placement: 'right',
                        title: '',
                        trigger: 'hover',
                        container: 'body',
                        html: true,
                        content: function () {
                            return $(this).closest('th').find('.fee_detail_popover').html();
                        }
                    });

                    var section_id_post = '<?php echo $section_id; ?>';
                    var class_id_post = '<?php echo $class_id; ?>';
                    var subject_id = '<?php echo $subject_id; ?>';


        populateSection(section_id_post, class_id_post);
        function populateSection(section_id_post, class_id_post) {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php
                $userdata = $this->customlib->getUserData();
                if (($userdata["role_id"] == 2 or $userdata["role_id"] == 15 or $userdata["role_id"] == 16 or $userdata["role_id"] == 19)) {
                    echo "getClassTeacherSection";
                } else {
                    echo "getByClass";
                }
                ?>";
            $.ajax({
                type: "GET",
                url:base_url + "sections/" + url,
                data: {'class_id': class_id_post},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var select = "";
                        if (section_id_post == obj.section_id) {
                            var select = "selected=selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + select + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }

        if(subject_id != '-1'){

            populateSubjects(class_id_post,section_id_post, subject_id);


            function populateSubjects(class_id_post,section_id_post, subject_id){

                
                var base_url = '<?php echo base_url() ?>';
                var div_data ;
                var url = "<?php
                $userdata = $this->customlib->getUserData();
                if (($userdata["role_id"] == 2 or $userdata["role_id"] == 15 or $userdata["role_id"] == 16 or $userdata["role_id"] == 19)) {
                    echo "getClassTeacherSubjects";
                } else {
                    echo "getClassSectionSubjects";
                }
                ?>";
                $.ajax({
                    type: "GET",
                    url: base_url + "sections/"+ url,
                    data: {'c_id': class_id_post,
                        's_id': section_id_post
                       },
                    dataType: "json",
                    success: function (data) {
                        $.each(data, function (i, obj)
                        {
                            var select = "";
                            if (subject_id == obj.id) {
                                var select = "selected=selected";
                            }
                            div_data += "<option value=" + obj.id + " " + select + ">" + obj.name + "</option>";
                        });
                        $('#subject_select').append(div_data);
                    }
                });

            }
        
        }
                    $(document).on('change', '#class_id', function (e) {

            $('#section_id').html("");
            $('#subject_select').html("");

            var class_id = $(this).val();
            var section_id = $(this).val();


            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var div_data2 = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            var url = "<?php
                $userdata = $this->customlib->getUserData();
                if (($userdata["role_id"] == 2 or $userdata["role_id"] == 15 or $userdata["role_id"] == 16 or $userdata["role_id"] == 19)) {
                    echo "getClassTeacherSection";
                } else {
                    echo "getByClass";
                }
                ?>";
            $.ajax({
                type: "GET",
                url: base_url + "sections/" + url,
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });


            
            /*$('#section_id').change(function() {  

            $('#subject_select').html("");

            if($('#section_id').val()!="") {    
            $.ajax({
                type: "GET",
                url: base_url + "sections/getSubjects",
                data: {'class_id': class_id,
                        'section_id':section_id
             },
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data2 += "<option value=" + obj.id + ">" + obj.name + "</option>";
                    });

                    $('#subject_select').append(div_data2);
                }
            });
        }//if close

            });//change close*/


        });


      $('#section_id').change(function(){ 

                 
                var attendanceType =  $('#class_id').find(':selected').data('attendance_type');
                if(attendanceType == '1'){
                $('#subject_select').prop('required', true);  
                 }        
                $('#subject_select').html("");
                var base_url = '<?php echo base_url() ?>';
                var div_data2 = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
                var url = "<?php
                $userdata = $this->customlib->getUserData();
                if (($userdata["role_id"] == 2 or $userdata["role_id"] == 15 or $userdata["role_id"] == 16 or $userdata["role_id"] == 19)) {
                    echo "getClassTeacherSubjects";
                } else {
                    echo "getClassSectionSubjects";
                }
                ?>";
                var c_id  =$('#class_id').val();
                var s_id= $(this).val();
                //alert(c_id);

                $.ajax({
                    url: base_url + "sections/"+ url,
                    method : "GET",
                    data : {'c_id': c_id,
                        's_id':s_id},
                    dataType : 'json',
                    success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var select = "";
                            if (subject_id == obj.id) {
                                var select = "selected=selected";
                            }
                        div_data2 += "<option value=" + obj.id + ">" + obj.name + "</option>";
                    });

                    $('#subject_select').append(div_data2);
                   }

                });
                
            }); 









                });
            </script>
            <script type="text/javascript">
                var base_url = '<?php echo base_url() ?>';
                function printDiv(elem) {
                    Popup(jQuery(elem).html());
                }
                function Popup(data)
                {

                    var frame1 = $('<iframe />');
                    frame1[0].name = "frame1";
                    frame1.css({"position": "absolute", "top": "-1000000px"});
                    $("body").append(frame1);
                    var frameDoc = frame1[0].contentWindow ? frame1[0].contentWindow : frame1[0].contentDocument.document ? frame1[0].contentDocument.document : frame1[0].contentDocument;
                    frameDoc.document.open();
                    //Create a new HTML document.
                    frameDoc.document.write('<html>');
                    frameDoc.document.write('<head>');
                    frameDoc.document.write('<title></title>');
                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/bootstrap/css/bootstrap.min.css">');
                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/font-awesome.min.css">');
                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/ionicons.min.css">');
                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/AdminLTE.min.css">');
                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/dist/css/skins/_all-skins.min.css">');
                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/iCheck/flat/blue.css">');
                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/morris/morris.css">');


                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/jvectormap/jquery-jvectormap-1.2.2.css">');
                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/datepicker/datepicker3.css">');
                    frameDoc.document.write('<link rel="stylesheet" href="' + base_url + 'backend/plugins/daterangepicker/daterangepicker-bs3.css">');
                    frameDoc.document.write('</head>');
                    frameDoc.document.write('<body>');
                    frameDoc.document.write(data);
                    frameDoc.document.write('</body>');
                    frameDoc.document.write('</html>');
                    frameDoc.document.close();
                    setTimeout(function () {
                        window.frames["frame1"].focus();
                        window.frames["frame1"].print();
                        frame1.remove();
                    }, 500);


                    return true;
                }
            </script>