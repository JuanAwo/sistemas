
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-eattendance"></i> <?=$this->lang->line('panel_title')?></h3>
        
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_eattendance')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                

                <?php if(permissionChecker('eattendance_add') || $this->session->userdata('usertypeID') == 3) { ?>
                    <h5 class="page-header">
                        <?php if(permissionChecker('eattendance_add')) { ?>
                            <a class="btn btn-success" href="<?php echo base_url('eattendance/add') ?>">
                                <i class="fa fa-plus"></i> 
                                <?=$this->lang->line('add_title')?>
                            </a>
                        <?php } ?>
                        <?php if($this->session->userdata('usertypeID') == 3) { ?>
                            <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                                <?php
                                    $array = array("0" => $this->lang->line("eattendance_select_exam"));
                                    if(count($exams)) {
                                        foreach ($exams as $exam) {
                                            $array[$exam->examID] = $exam->exam;
                                        }
                                    }
                                    echo form_dropdown("examID", $array, set_value("examID", $set), "id='examID' class='form-control select2'");
                                ?>
                            </div>
                        <?php } ?>
                    </h5>
                <?php } ?>
                

                <?php if($this->session->userdata('usertypeID') == 4) { ?>
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>">
                                            <label for="examID" class="control-label">
                                                <?=$this->lang->line('eattendance_exam')?>
                                            </label>

                                            <?php
                                                $array = array("0" => $this->lang->line("eattendance_select_exam"));
                                                if(count($exams)) {
                                                    foreach ($exams as $exam) {
                                                        $array[$exam->examID] = $exam->exam;
                                                    }
                                                }
                                                echo form_dropdown("examID", $array, set_value("examID"), "id='examID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="<?php echo form_error('studentID') ? 'form-group has-error' : 'form-group'; ?>" >
                                            <label for="studentID" class="control-label">
                                                <?=$this->lang->line('eattendance_student')?>
                                            </label>

                                            <?php
                                                $array = array("0" => $this->lang->line("eattendance_select_student"));
                                                if($parentsStudents) {
                                                    foreach ($parentsStudents as $parentsStudent) {
                                                        $array[$parentsStudent->studentID] = $parentsStudent->name;
                                                    }
                                                }
                                                echo form_dropdown("studentID", $array, set_value("studentID"), "id='studentID' class='form-control select2'");
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group" >
                                            <input type="submit" class="btn btn-success col-md-12" style="margin-top:20px" value="<?=$this->lang->line("view_attendance")?>" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php } ?>

                <?php if(count($students) > 0 ) { ?>
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                    <th class="col-sm-1"><?=$this->lang->line('eattendance_photo')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_name')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_roll')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_classes')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_subject')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_status')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($students)) {$i = 1; foreach($students as $student) { ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('eattendance_photo')?>">
                                            <?php $array = array(
                                                    "src" => base_url('uploads/images/'.$student->photo),
                                                    'width' => '35px',
                                                    'height' => '35px',
                                                    'class' => 'img-rounded'

                                                );
                                                echo img($array); 
                                            ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_name')?>">
                                            <?php echo $student->name; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_roll')?>">
                                            <?php echo $student->roll; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_classes')?>">
                                            <?php echo $student->classes; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_subject')?>">
                                            <?php echo $student->subject; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_status')?>">
                                            <?php
                                                if($student->eattendance == "Presente") {
                                                    echo "<button class='btn btn-success btn-xs'>" . $this->lang->line('eattendance_present') . "</button>";
                                                } elseif($student->eattendance == "") {
                                                    echo "<button class='btn btn-danger btn-xs'>" . $this->lang->line('eattendance_absent') . "</button>";
                                                } else {
                                                    echo "<button class='btn btn-danger btn-xs'>" . $this->lang->line('eattendance_absent') . "</button>";
                                                }
                                            ?>
                                        </td>
                                   </tr>
                                <?php $i++; }} ?>
                            </tbody>
                        </table>
                    </div>
                <?php } else { ?>
                    <div id="hide-table">
                        <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                            <thead>
                                <tr>
                                    <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                    <th class="col-sm-1"><?=$this->lang->line('eattendance_photo')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_name')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_roll')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_classes')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_subject')?></th>
                                    <th class="col-sm-2"><?=$this->lang->line('eattendance_status')?></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if(count($students)) {$i = 1; foreach($students as $student) { ?>
                                    <tr>
                                        <td data-title="<?=$this->lang->line('slno')?>">
                                            <?php echo $i; ?>
                                        </td>

                                        <td data-title="<?=$this->lang->line('eattendance_photo')?>">
                                            <?php $array = array(
                                                    "src" => base_url('uploads/images/'.$student->photo),
                                                    'width' => '35px',
                                                    'height' => '35px',
                                                    'class' => 'img-rounded'

                                                );
                                                echo img($array); 
                                            ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_name')?>">
                                            <?php echo $student->name; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_roll')?>">
                                            <?php echo $student->roll; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_classes')?>">
                                            <?php echo $student->classes; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_subject')?>">
                                            <?php echo $student->subject; ?>
                                        </td>
                                        <td data-title="<?=$this->lang->line('eattendance_status')?>">
                                            <?php
                                                if($student->eattendance == "Present") {
                                                    echo "<button class='btn btn-success btn-xs'>" . $this->lang->line('eattendance_present') . "</button>";
                                                } elseif($student->eattendance == "") {
                                                    echo "<button class='btn btn-danger btn-xs'>" . $this->lang->line('eattendance_absent') . "</button>";
                                                } else {
                                                    echo "<button class='btn btn-danger btn-xs'>" . $this->lang->line('eattendance_absent') . "</button>";
                                                }
                                            ?>
                                        </td>
                                   </tr>
                                <?php $i++; }} ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?> 
            </div> <!-- col-sm-12 -->
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">
$('.select2').select2();
<?php if($this->session->userdata('usertypeID') == 3) { ?> 
    $('#examID').change(function() {
        var examID = $(this).val();
        if(examID == 0) {
            $('#hide-table').hide();
            $('.nav-tabs-custom').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('eattendance/exam_list')?>",
                data: "id=" + examID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
<?php } ?>
</script>