
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-syllabus"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i><?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_syllabus')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('syllabus_add') || $this->session->userdata('usertypeID') != 3) { ?>
                <h5 class="page-header">
                    <?php if(permissionChecker('syllabus_add')) { ?>
                        <a class="btn btn-success" href="<?php echo base_url('syllabus/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?>

                    <?php if($this->session->userdata('usertypeID') != 3) { ?>
                    <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                        <?php
                            $array = array("0" => $this->lang->line("syllabus_select_student"));
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
                                <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('syllabus_title')?></th>
                                <th><?=$this->lang->line('syllabus_description')?></th>
                                <th><?=$this->lang->line('syllabus_date')?></th>
                                <th><?=$this->lang->line('syllabus_uploder')?></th>
                                <th><?=$this->lang->line('syllabus_file')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('action')?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($syllabuss)) {$i = 1; foreach($syllabuss as $syllabus) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('syllabus_title')?>">
                                        <?php echo $syllabus->title; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('syllabus_description')?>">
                                        <?php echo $syllabus->description; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('syllabus_date')?>">
                                        <?php echo $syllabus->date; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('syllabus_uploder')?>">
                                        <?php echo getNameByUsertypeIDAndUserID($syllabus->usertypeID, $syllabus->userID); ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('syllabus_file')?>">
                                        <?php echo $syllabus->originalfile; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php echo btn_download('syllabus/download/'.$syllabus->syllabusID, $this->lang->line('download')) ?>

                                        <?php echo btn_edit('syllabus/edit/'.$syllabus->syllabusID.'/'.$set, $this->lang->line('edit')) ?>
                                        <?php echo btn_delete('syllabus/delete/'.$syllabus->syllabusID.'/'.$set, $this->lang->line('delete')) ?>
                                    </td>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<?php if($this->session->userdata('usertypeID') !=3) { ?>
<script type="text/javascript">
    $(".select2").select2();
</script>
<?php } ?>


<script type="text/javascript">
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
            $('.nav-tabs-custom').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('syllabus/student_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
