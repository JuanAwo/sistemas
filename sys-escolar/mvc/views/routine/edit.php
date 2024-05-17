
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-routine"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("routine/index/$set")?>"><?=$this->lang->line('menu_routine')?></a></li>
            <li class="active"><?=$this->lang->line('menu_edit')?> <?=$this->lang->line('menu_routine')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">
                    <?php 
                        if(form_error('schoolyearID'))
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="schoolyearID" class="col-sm-2 control-label">
                            <?=$this->lang->line("routine_schoolyear")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $arrayschoolyear = array();
                                $arrayschoolyear[0] = $this->lang->line("routine_select_schoolyear");
                                $defaultschoolyear = $siteinfos->school_year;
                                if($siteinfos->school_type == 'classbase') {
                                    foreach ($schoolyears as $schoolyear) {
                                        if($schoolyear->schooltype == 'classbase') {
                                            if($siteinfos->school_year == $schoolyear->schoolyearID) {
                                                $arrayschoolyear[$schoolyear->schoolyearID] = $schoolyear->schoolyear . $this->lang->line('default');
                                            } else {
                                                $arrayschoolyear[$schoolyear->schoolyearID] = $schoolyear->schoolyear;
                                            }
                                        }
                                    }
                                } else {
                                    foreach ($schoolyears as $schoolyear) {
                                        if($schoolyear->schooltype == 'semesterbase') {
                                            if($siteinfos->school_year == $schoolyear->schoolyearID) {
                                                $arrayschoolyear[$schoolyear->schoolyearID] = $schoolyear->schoolyeartitle . ' - ' .  $schoolyear->schoolyear . $this->lang->line('default');
                                            } else {
                                                $arrayschoolyear[$schoolyear->schoolyearID] = $schoolyear->schoolyeartitle . ' - ' .  $schoolyear->schoolyear;
                                            }
                                        }
                                    }
                                }

                                echo form_dropdown("schoolyearID", $arrayschoolyear, set_value("schoolyearID", $defaultschoolyear), "id='schoolyearID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('schoolyearID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('classesID'))
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="classesID" class="col-sm-2 control-label">
                            <?=$this->lang->line("routine_classes")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                                $array = array();
                                $array[0] = $this->lang->line("routine_select_classes");
                                foreach ($classes as $classa) {
                                    $array[$classa->classesID] = $classa->classes;
                                }
                                echo form_dropdown("classesID", $array, set_value("classesID", $routine->classesID), "id='classesID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('classesID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('sectionID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="sectionID" class="col-sm-2 control-label">
                            <?=$this->lang->line("routine_section")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array();
                             $array[0] = $this->lang->line("routine_select_section");
                                foreach ($sections as $section) {
                                    $array[$section->sectionID] = $section->section;
                                }
                            
                            echo form_dropdown("sectionID", $array, set_value("sectionID", $routine->sectionID), "id='sectionID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('sectionID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('subjectID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subjectID" class="col-sm-2 control-label">
                            <?=$this->lang->line("routine_subject")?>
                        </label>
                        <div class="col-sm-6">
                            <?php
                            $array = array();
                             $array[0] = $this->lang->line("routine_subject_select");
                                foreach ($subjects as $subject) {
                                    $array[$subject->subjectID] = $subject->subject;
                                }
                            
                            echo form_dropdown("subjectID", $array, set_value("subjectID", $routine->subjectID), "id='subjectID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('subjectID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('day')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="day" class="col-sm-2 control-label">
                            <?=$this->lang->line("routine_day")?>
                        </label>
                        <div class="col-sm-6">
                             <?php
                                $array = array(
                                    $this->lang->line('sunday') => $this->lang->line('sunday'),
                                    $this->lang->line('monday') => $this->lang->line('monday'),
                                    $this->lang->line('tuesday') => $this->lang->line('tuesday'),
                                    $this->lang->line('wednesday') => $this->lang->line('wednesday'),
                                    $this->lang->line('thursday') => $this->lang->line('thursday'),
                                    $this->lang->line('friday') => $this->lang->line('friday'),
                                    $this->lang->line('saturday') => $this->lang->line('saturday')
                                );
                                echo form_dropdown("day", $array, set_value("day", $routine->day), "id='day' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('day'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('teacherID')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="teacherID" class="col-sm-2 control-label">
                            <?=$this->lang->line("routine_teacher")?>
                        </label>
                        <div class="col-sm-6">
                             <?php
                                $arrayteacher[0] = $this->lang->line('routine_select_teacher');
                                if(count($teachers)) {
                                    foreach ($teachers as $key => $teacher) {
                                        $arrayteacher[$teacher->teacherID] = $teacher->name;
                                    }
                                }
                                echo form_dropdown("teacherID", $arrayteacher, set_value("teacherID", $routine->teacherID), "id='teacherID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('teacherID'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('start_time')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="start_time" class="col-sm-2 control-label">
                            <?=$this->lang->line("routine_start_time")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="start_time" name="start_time" value="<?=set_value('start_time', $routine->start_time)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('start_time'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('end_time')) 
                            echo "<div class='form-group has-error'>";
                        else     
                            echo "<div class='form-group'>";
                    ?>
                        <label for="end_time" class="col-sm-2 control-label">
                            <?=$this->lang->line("routine_end_time")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="end_time" name="end_time" value="<?=set_value('end_time', $routine->end_time)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('end_time'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('room')) 
                            echo "<div class='form-group has-error'>";
                        else     
                            echo "<div class='form-group'>";
                    ?>
                        <label for="room" class="col-sm-2 control-label">
                            <?=$this->lang->line("routine_room")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="room" name="room" value="<?=set_value('room', $routine->room)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('room'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("update_routine")?>" >
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

$('.select2').select2();

$('#classesID').change(function() {
    var classesID = $(this).val();
    if(classesID == 0) {
        $('#sectionID').val(0);
        $('#subjectID').val(0);
    } else {
        $.ajax({
            type: 'POST',
            url: "<?=base_url('routine/subjectcall')?>",
            data: "id=" + classesID,
            dataType: "html",
            success: function(data) {
               $('#subjectID').html(data)
            }
        });

        $.ajax({
            type: 'POST',
            url: "<?=base_url('routine/sectioncall')?>",
            data: "id=" + classesID,
            dataType: "html",
            success: function(data) {
               $('#sectionID').html(data)
            }
        });
    }

});
$('#start_time').timepicker();
$('#end_time').timepicker();
</script>

