
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-subject"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_subject')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">


                <h5 class="page-header">
                    <?php if(permissionChecker('subject_add')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('subject/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?>

                    <?php if($this->session->userdata('usertypeID') != 3) { ?>
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                            <?php
                                $array = array("0" => $this->lang->line("subject_select_student"));
                                if($students) {
                                    foreach ($students as $student) {
                                        $array[$student->studentID] = $student->name;
                                    }
                                }
                                echo form_dropdown("studentID", $array, set_value("studentID", $set), "id='studentID' class='form-control select2'");
                            ?>
                        </div>
                    <?php } ?>
                </h5>


              <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('subject_name')?></th>
                                <th><?=$this->lang->line('subject_author')?></th>
                                <th><?=$this->lang->line('subject_code')?></th>
                                <th><?=$this->lang->line('subject_teacher')?></th>
                                <th><?=$this->lang->line('subject_passmark')?></th>
                                <th><?=$this->lang->line('subject_finalmark')?></th>
                                <th><?=$this->lang->line('subject_type')?></th>
                                <?php if(permissionChecker('subject_edit') || permissionChecker('subject_delete')) { ?>
                                <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if(count($subjects)) {$i = 1; foreach($subjects as $subject) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('subject_name')?>">
                                        <?php echo $subject->subject; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('subject_author')?>">
                                        <?php echo $subject->subject_author; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('subject_code')?>">
                                        <?php echo $subject->subject_code; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('subject_teacher')?>">
                                        <?php echo $subject->teacher_name; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('subject_passmark')?>">
                                        <?php echo $subject->passmark; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('subject_finalmark')?>">
                                        <?php echo $subject->finalmark; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('subject_type')?>">
                                        <?php if($subject->type == 1) { ?>
                                            <button type="button" class="btn btn-primary btn-xs"><?php echo $this->lang->line('subject_mandatory'); ?></button>
                                        <?php } elseif($subject->type == 0) { ?>
                                            <button type="button" class="btn btn-warning btn-xs"><?php echo $this->lang->line('subject_optional'); ?></button>
                                        <?php } ?>
                                    </td>

                                    <?php if(permissionChecker('subject_edit') || permissionChecker('subject_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php echo btn_edit('subject/edit/'.$subject->subjectID."/".$set, $this->lang->line('edit')) ?>
                                        <?php echo btn_delete('subject/delete/'.$subject->subjectID."/".$set, $this->lang->line('delete')) ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('.select2').select2();
    $('#studentID').change(function() {
        var studentID = $(this).val();
        if(studentID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('subject/student_list')?>",
                data: "id=" + studentID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
