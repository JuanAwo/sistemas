<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-sattendance"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("sattendance/index")?>"><?=$this->lang->line('menu_sattendance')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_sattendance')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if($setting->attendance=="subject"){ ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_classes')?></label>

                                            <?php
                                                $array = array("0" => $this->lang->line("attendance_select_classes"));
                                                foreach ($classes as $classa) {
                                                    $array[$classa->classesID] = $classa->classes;
                                                }
                                                echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                                            ?>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_section')?></label>

                                            <?php
                                                $arraysection = array('0' => $this->lang->line("attendance_select_section"));
                                                if($sections != "empty") {
                                                    foreach ($sections as $section) {
                                                        $arraysection[$section->sectionID] = $section->section;
                                                    }
                                                }

                                                $secID = 0;
                                                if($sectionID != 0) {
                                                    $secID = $sectionID;
                                                }

                                                echo form_dropdown("sectionID", $arraysection, set_value("sectionID", $secID), "id='sectionID' class='form-control select2'");
                                            ?>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('subjectID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_subject')?></label>

                                            <?php
                                                $array = array('0' => $this->lang->line("attendance_select_subject"));
                                                if($subjects != "empty") {
                                                    foreach ($subjects as $subject) {
                                                        $array[$subject->subjectID] = $subject->subject;
                                                    }
                                                }

                                                $sID = 0;
                                                if($subjectID != 0) {
                                                    $sID = $subjectID;
                                                }

                                                echo form_dropdown("subjectID", $array, set_value("subjectID", $sID), "id='subjectID' class='form-control select2'");
                                            ?>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="<?php echo form_error('date') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_date')?></label>


                                            <input type="text" class="form-control" name="date" id="date" value="<?=set_value("date", $date)?>" >

                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <button type="submit" class="btn btn-success col-md-12" style="margin-top: 20px;"><?=$this->lang->line('add_attendance')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } else { ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label">Class</label>


                                            <?php
                                                $array = array("0" => $this->lang->line("attendance_select_classes"));
                                                foreach ($classes as $classa) {
                                                    $array[$classa->classesID] = $classa->classes;
                                                }
                                                echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                                            ?>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                              <label class="control-label"><?=$this->lang->line('attendance_section')?></label>

                                              <?php
                                                  $arraysection = array('0' => $this->lang->line("attendance_select_section"));
                                                  if($sections != "empty") {
                                                      foreach ($sections as $section) {
                                                          $arraysection[$section->sectionID] = $section->section;
                                                      }
                                                  }

                                                  $secID = 0;
                                                  if($sectionID != 0) {
                                                      $secID = $sectionID;
                                                  }

                                                  echo form_dropdown("sectionID", $arraysection, set_value("sectionID", $secID), "id='sectionID' class='form-control select2'");
                                              ?>

                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="<?php echo form_error('date') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label class="control-label"><?=$this->lang->line('attendance_date')?></label>


                                            <input type="text" class="form-control" name="date" id="date" value="<?=set_value("date", $date)?>" >

                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <button type="submit" class="btn btn-success col-md-12" style="margin-top: 20px;"><?=$this->lang->line('add_attendance')?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </form>
                <?php } ?>

                <?php if ($siteinfos->note==1) { ?>
                    <div class="panel-group" id="accordion"> 
                        <div class="panel panel-default"> 
                            <div class="panel-heading"> 
                                <h4 class="panel-title"> 
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> 
                                <span class="fa fa-send"></span> Más información</a> 
                                </h4> 
                            </div> 
                            <div id="collapseOne" class="panel-collapse collapse"> 
                                <div class="panel-body">
                                    <div class="col-md-12 text-justify">
                                        <div class="alert alert-dismissible alert-info">
                                            <p>
                                                Existen dos tipos de asistencias para el estudiante, por materia o día estos mismos los puede modificar desde <strong>Ajustes generales</strong> en la opción <strong>"Asistencia Predeterminada"</strong>
                                            </p>
                                        </div>
                                    </div>
                                </div> 
                            </div> 
                        </div> 
                    </div><!-- accordion -->
                <?php } ?>


                <?php if(count($sattendanceinfo)) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">
                        <?php
                            echo '<h5><center>'.$this->lang->line('attendance_details').'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('attendance_classes').' : '. $sattendanceinfo['class'].'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('attendance_section').' : '. $sattendanceinfo['section'].'</center></h5>';
                            if($setting->attendance == "subject") {
                                echo '<h5><center>'.$this->lang->line('attendance_subject').' : '. $sattendanceinfo['subject'].'</center></h5>';
                            }
                            echo '<h5><center>'.$this->lang->line('attendance_day').' : '. $sattendanceinfo['day'].'</center></h5>';
                             echo '<h5><center>'.$this->lang->line('attendance_date').' : '. $sattendanceinfo['date'].'</center></h5>';
                        ?>
                    </div>
                <?php } ?>

                <?php if(count($students)) { ?>

                <div id="hide-table">
                    <table class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('attendance_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('attendance_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('attendance_email')?></th>

                                <th class="col-sm-2"><?=$this->lang->line('attendance_roll')?></th>

                                <th class="col-sm-2"><?=btn_attendance('', '', 'all_attendance', $this->lang->line('add_all_attendance')).$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody id="list">
                            <?php if(count($students)) {$i = 1; foreach($students as $student) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('attendance_photo')?>">
                                        <?php $array = array(
                                                "src" => base_url('uploads/images/'.$student->photo),
                                                'width' => '35px',
                                                'height' => '35px',
                                                'class' => 'img-rounded'

                                            );
                                            echo img($array);
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('attendance_name')?>">
                                        <?php echo $student->name; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('attendance_email')?>">
                                        <?php echo $student->email; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('attendance_roll')?>">
                                        <?php echo $student->roll; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php
                                            $aday = "a".abs($day);

                                            foreach ($attendances as $attendance) {
                                                if($setting->attendance=="subject") {
                                                    if($monthyear == $attendance->monthyear && $attendance->studentID == $student->studentID && $attendance->classesID == $student->classesID && $attendance->subjectID == $sID) {
                                                        $method = '';
                                                        if($attendance->$aday == "P") {$method = "checked";}
                                                        echo  btn_attendance($attendance->attendanceID, $method, 'attendance btn btn-warning', $this->lang->line('add_title'));
                                                        break;
                                                    }
                                                } else {

                                                        if ($monthyear == $attendance->monthyear && $attendance->studentID == $student->studentID && $attendance->classesID == $student->classesID) {
                                                            $method = '';
                                                            if($attendance->$aday == "P") {$method = "checked";}
                                                            echo  btn_attendance($attendance->attendanceID, $method, 'attendance btn btn-warning', $this->lang->line('add_title'));
                                                            break;
                                                        }
                                                }
                                            }
                                        ?>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>

                        </tbody>
                    </table>
                </div>
                <?php } ?>

                <script type="text/javascript">
                    $('.attendance').click(function() {
                        var id = $(this).attr("id");
                        var day = "<?=$day?>";
                        if(parseInt(id) && parseInt(day)) {
                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('sattendance/singl_add')?>",
                                data: {"id" : id, "day" : day},
                                dataType: "html",
                                success: function(data) {
                                    toastr["success"](data)
                                    toastr.options = {
                                      "closeButton": true,
                                      "debug": false,
                                      "newestOnTop": false,
                                      "progressBar": false,
                                      "positionClass": "toast-top-right",
                                      "preventDuplicates": false,
                                      "onclick": null,
                                      "showDuration": "500",
                                      "hideDuration": "500",
                                      "timeOut": "5000",
                                      "extendedTimeOut": "1000",
                                      "showEasing": "swing",
                                      "hideEasing": "linear",
                                      "showMethod": "fadeIn",
                                      "hideMethod": "fadeOut"
                                    }

                                }
                            });
                        }
                    });

                    $('.all_attendance').click(function() {
                        var day = "<?=$day?>";
                        var classes = "<?=$set?>";
                        var section = "<?=$secID?>";
                        var monthyear = "<?=$monthyear?>";
                        <?php if($setting->attendance=="subject"){ ?>
                        var subjectID = "<?=$sID?>";
                        <?php } else { ?>
                        var subjectID = 0;
                        <?php } ?>

                        var status = "";

                        if($(".all_attendance").prop('checked')) {
                            status = "checked";
                            $('.attendance').prop("checked", true);
                        } else {
                            status = "unchecked";
                            $('.attendance').prop("checked", false);
                        }

                        if(parseInt(classes) && parseInt(day)) {
                            $.ajax({
                                type: 'POST',
                                url: "<?=base_url('sattendance/all_add')?>",
                                data: {"day" : day, "classes" : classes, "section" : section, "subject" : subjectID , "monthyear" : monthyear , "status" : status },
                                dataType: "html",
                                success: function(data) {
                                    toastr["success"](data)
                                    toastr.options = {
                                      "closeButton": true,
                                      "debug": false,
                                      "newestOnTop": false,
                                      "progressBar": false,
                                      "positionClass": "toast-top-right",
                                      "preventDuplicates": false,
                                      "onclick": null,
                                      "showDuration": "500",
                                      "hideDuration": "500",
                                      "timeOut": "5000",
                                      "extendedTimeOut": "1000",
                                      "showEasing": "swing",
                                      "hideEasing": "linear",
                                      "showMethod": "fadeIn",
                                      "hideMethod": "fadeOut"
                                    }

                                }
                            });
                        }
                    });
                </script>

            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
<script type="text/javascript">
    $('.select2').select2();
    $("#date").datepicker();
    $("#classesID").change(function() {
    var id = $(this).val();
    if(parseInt(id)) {

        <?php if($setting->attendance=="subject"){ ?>
        if(id === '0') {
            $('#subjectID').val(0);
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('sattendance/subjectall')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#subjectID').html(data);
                }
            });
        }
        <?php } ?>

        if(id === '0') {
            $('#sectionID').val(0);
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('sattendance/sectionall')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#sectionID').html(data);
                }
            });
        }
    }
    });
</script>
