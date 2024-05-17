
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-star"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_section')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php 
                    if(permissionChecker('section_add')) {
                ?>
                    <h5 class="page-header">
                        <a class="btn btn-success" href="<?php echo base_url('section/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>

                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right margin-top-bottom">
                            <?php
                                if($siteinfos->school_type == 'classbase') {
                                    $array = array("0" => $this->lang->line("section_select_class"));    
                                } else {
                                    $array = array("0" => $this->lang->line("section_select_department"));
                                }
                                
                                if(count($classes)) {
                                    foreach ($classes as $classa) {
                                        $array[$classa->classesID] = $classa->classes;
                                    }
                                }

                                echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='pull-right form-control select2'");
                            ?>
                        </div>

                    </h5>
                <?php } ?> 

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('section_name')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('section_category')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('section_capacity')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('section_teacher_name')?></th>
                                <th class="col-lg-2"><?=$this->lang->line('section_note')?></th>
                                <?php if(permissionChecker('section_edit') || permissionChecker('section_delete')) { ?>
                                <th class="col-lg-1"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td data-title="<?=$this->lang->line('slno')?>">&nbsp;</td>

                                <td data-title="<?=$this->lang->line('section_name')?>">&nbsp;</td>

                                <td data-title="<?=$this->lang->line('section_category')?>">&nbsp;</td>

                                <td data-title="<?=$this->lang->line('section_capacity')?>">&nbsp;</td>

                                <td data-title="<?=$this->lang->line('section_teacher_name')?>">&nbsp;</td>

                                <td data-title="<?=$this->lang->line('section_note')?>">&nbsp;</td>
                                <?php if(permissionChecker('section_edit') || permissionChecker('section_delete')) { ?>
                                <td data-title="<?=$this->lang->line('action')?>">&nbsp;</td>
                                <?php } ?>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div> <!-- col-sm-12 -->
            
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
<script type="text/javascript">
    $('#classesID').change(function() {
        var classesID = $(this).val();
        if(classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('section/section_list')?>",
                data: "id=" + classesID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>

<script>
$( ".select2" ).select2( { placeholder: "", maximumSelectionSize: 6 } );
</script>