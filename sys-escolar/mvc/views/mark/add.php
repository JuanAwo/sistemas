<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-flask"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("mark/index")?>"><?=$this->lang->line('menu_mark')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_mark')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST">
                    <div class="row">
                        <div class="col-md-10">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('examID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="examID" class="control-label">
                                            <?=$this->lang->line('mark_exam')?>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line("mark_select_exam"));
                                            foreach ($exams as $exam) {
                                                $array[$exam->examID] = $exam->exam;
                                            }
                                            echo form_dropdown("examID", $array, set_value("examID", $set_exam), "id='examID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="<?php echo form_error('classesID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="classesID" class="control-label">
                                            <?=$this->lang->line('mark_classes')?>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line("mark_select_classes"));
                                            foreach ($classes as $classa) {
                                                $array[$classa->classesID] = $classa->classes;
                                            }
                                            echo form_dropdown("classesID", $array, set_value("classesID", $set_classes), "id='classesID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('sectionID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label class="control-label"><?=$this->lang->line('mark_section')?></label>

                                        <?php
                                            $arraysection = array('0' => $this->lang->line("mark_select_section"));
                                            if($sections != 0) {
                                                foreach ($sections as $section) {
                                                    $arraysection[$section->sectionID] = $section->section;
                                                }
                                            }

                                            echo form_dropdown("sectionID", $arraysection, set_value("sectionID", $set_section), "id='sectionID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="<?php echo form_error('subjectID') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="subjectID" class="control-label">
                                            <?=$this->lang->line('mark_subject')?>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line("mark_select_subject"));
                                            if($subjects != 0) {
                                                foreach ($subjects as $subject) {
                                                    $array[$subject->subjectID] = $subject->subject;
                                                }
                                            }
                                            echo form_dropdown("subjectID", $array, set_value("subjectID", $set_subject), "id='subjectID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-xs-12">
                            <div class="row">
                                <div class="col-md-12 col-xs-12">
                                    <div class="form-group" >
                                        <button type="submit" class="btn btn-success col-md-12 col-xs-12" style="margin-top: 20px;"><?=$this->lang->line('add_mark')?></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>


                <?php if(count($sendExam) && count($sendClasses) && count($sendSection) && count($sendSubject)) { ?>
                    <div class="col-sm-4 col-sm-offset-4 box-layout-fame">

                        <?php
                            echo '<h5><center>'.$this->lang->line('mark_details').'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('mark_exam').' : '.$sendExam->exam.'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('mark_classes').' : '. $sendClasses->classes.'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('mark_section').' : '. $sendSection->section.'</center></h5>';
                            echo '<h5><center>'.$this->lang->line('mark_subject').' : '. $sendSubject->subject.'</center></h5>';
                        ?>
                    </div>
                <?php } ?>
            </div>
            <div class="col-sm-12">

                <?php if(count($students)) { ?>
                <div id="hide-table">
                    <table class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('mark_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('mark_name')?></th>
                                <th class="col-sm-1"><?=$this->lang->line('mark_roll')?></th>
                                <?php
                                    foreach ($markpercentages as $data) {
                                        echo "<th class='col-sm-2'>$data->markpercentagetype <br/>( $data->percentage % )</th>";
                                    }
                                ?>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($students)) {$i = 1; foreach($students as $student) { foreach ($marks as $mark) { if($student->studentID == $mark->studentID) {   ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('mark_photo')?>">
                                        <?php $array = array(
                                                "src" => base_url('uploads/images/'.$student->photo),
                                                'width' => '35px',
                                                'height' => '35px',
                                                'class' => 'img-rounded'

                                            );
                                            echo img($array);
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('mark_name')?>">
                                        <?php echo $student->name; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('mark_roll')?>">
                                        <?php echo $student->roll; ?>
                                    </td>
                                    <?php
                                        foreach ($markpercentages as $data) {

                                            echo "<td data-title='$data->markpercentagetype'>";
                                                echo "<input class='form-control mark' type='number' name='mark-$data->markpercentageID-$student->studentID' value='".$markRelations[$student->studentID][$data->markpercentageID]."' />";
                                            echo "</td>";
                                        }
                                    ?>

                                </tr>
                            <?php $i++;  }}}} ?>
                        </tbody>
                    </table>
                </div>

                <input type="button" class="btn btn-success col-sm-2 col-xs-12" id="add_mark" name="add_mark" value="<?=$this->lang->line("add_sub_mark")?>" />

                <script type="text/javascript">
                    $("#add_mark").click(function() {
                        var inputs = "";
                        var inputs_value = "";
                        var mark = $('input[name^=mark]').map(function(){
                            return { mark: this.name , value: this.value };
                        }).get();

                        $.ajax({
                            type: 'POST',
                            url: "<?=base_url('mark/mark_send')?>",
                            data: {"examID" : "<?=$set_exam?>", "classesID" : "<?=$set_classes?>", "subjectID" : "<?=$set_subject?>", "inputs" : mark},
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

                    });
                </script>
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$('.select2').select2();
$("#classesID").change(function() {
    var id = $(this).val();
    if(parseInt(id)) {
        if(id === '0') {
            $('#subjectID').val(0);
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('mark/subjectcall')?>",
                data: {"id" : id},
                dataType: "html",
                success: function(data) {
                   $('#subjectID').html(data);
                }
            });

            $.ajax({
                type: 'POST',
                url: "<?=base_url('mark/sectioncall')?>",
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
