
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-assignment"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_assignment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
               

                <?php if(permissionChecker('assignment_add') || $this->session->userdata('usertypeID') != 3) { ?>
                <h5 class="page-header">
                    <?php if(permissionChecker('assignment_add')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('assignment/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?>

                    <?php if($this->session->userdata('usertypeID') != 3) { ?>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                        <?php
                            $array = array("0" => $this->lang->line("assignment_select_student"));
                            if(count($students)) {
                                foreach ($students as $student) {
                                    $array[$student->studentID] = $student->name;
                                }
                            }
                            echo form_dropdown("classesID", $array, set_value("classesID", $set), "id='classesID' class='form-control select2'");
                        ?>
                    </div>
                    <?php } ?>
                </h5>
                <?php } ?>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('assignment_title')?></th>
                                <th><?=$this->lang->line('assignment_description')?></th>
                                <th><?=$this->lang->line('assignment_deadlinedate')?></th>
                                <th><?=$this->lang->line('assignment_uploder')?></th>
                                <th><?=$this->lang->line('assignment_file')?></th>
                                <?php 
                                    // if($this->session->userdata('usertypeID') != 3) {
                                if(permissionChecker('assignment_edit') || permissionChecker('assignment_delete') || permissionChecker('assignment_view') || $this->session->userdata('usertypeID') == 3) { ?>
                                <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($assignments)) {$i = 1; foreach($assignments as $assignment) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('assignment_title')?>">
                                        <?php echo $assignment->title; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('assignment_description')?>">
                                        <?php echo $assignment->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('assignment_deadlinedate')?>">
                                        <?php echo date('d M Y', strtotime($assignment->deadlinedate)); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('assignment_uploder')?>">
                                        <?php echo getNameByUsertypeIDAndUserID($assignment->usertypeID, $assignment->userID); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('assignment_file')?>">
                                        <?php 
                                            if($assignment->originalfile) { echo btn_download_file('assignment/download/'.$assignment->assignmentID, $assignment->originalfile, $this->lang->line('download')); 
                                            }
                                        ?>
                                    </td>
                                    <?php if(permissionChecker('assignment_edit') || permissionChecker('assignment_delete') || permissionChecker('assignment_view') || $this->session->userdata('usertypeID') == 3) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php if($this->session->userdata('usertypeID') == 3) { ?> 
                                        <?php echo btn_upload('assignment/assignmentanswer/'.$assignment->assignmentID.'/'.$set, $this->lang->line('upload')); ?>
                                        <?php } ?>
                                        <?php echo btn_view('assignment/view/'.$assignment->assignmentID.'/'.$set, $this->lang->line('view')) ?>
                                        <?php echo btn_edit('assignment/edit/'.$assignment->assignmentID.'/'.$set, $this->lang->line('edit')) ?>
                                        <?php echo btn_delete('assignment/delete/'.$assignment->assignmentID.'/'.$set, $this->lang->line('delete')) ?>
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
    $(".select2").select2();
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
            $('.nav-tabs-custom').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('assignment/student_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
