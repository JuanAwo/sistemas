
<div class="box">
    <div class="box-header">
        <h3 class="box-title">
            <i class="fa fa-calendar-plus-o"></i> 
            <?php 
                echo $this->lang->line('panel_title');
            ?>
        </h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_schoolyear')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                
                    <h5 class="page-header">
                        <?php
                            $usertype = $this->session->userdata("usertype");
                            if(permissionChecker('schoolyear_add')) {
                        ?>
                            <a class="btn btn-success" href="<?php echo base_url('schoolyear/add') ?>">
                                <i class="fa fa-plus"></i>
                                <?=$this->lang->line('add_title')?>
                            </a>
                        <?php } ?>
                    </h5>
                

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('schoolyear_schoolyear')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('schoolyear_schoolyeartitle')?></th>
                                <?php if(($siteinfos->school_type == 'semesterbase' && $set == 'semesterbase') || $set == 'semesterbase') { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('schoolyear_semestercode')?></th>
                                    <?php } ?>
                                <?php if(permissionChecker('schoolyear_edit') || permissionChecker('schoolyear_delete')) {
                                ?>
                                <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if(count($schoolyears)) {$i = 1; foreach($schoolyears as $schoolyear) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('schoolyear_schoolyear')?>">
                                        <?php echo $schoolyear->schoolyear; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('schoolyear_schoolyeartitle')?>">
                                        <?php echo $schoolyear->schoolyeartitle; ?>
                                    </td>
                                    <?php if(($siteinfos->school_type == 'semesterbase' && $set == 'semesterbase') || $set == 'semesterbase') { ?>
                                    <td data-title="<?=$this->lang->line('schoolyear_semestercode')?>">
                                        <?php echo $schoolyear->semestercode; ?>
                                    </td>
                                    <?php } ?>
                                    <?php if(permissionChecker('schoolyear_edit') || permissionChecker('schoolyear_delete')) {
                                    ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php echo btn_edit('schoolyear/edit/'.$schoolyear->schoolyearID, $this->lang->line('edit')) ?>
                                        <?php if($schoolyear->schoolyearID !=1) { echo btn_delete('schoolyear/delete/'.$schoolyear->schoolyearID, $this->lang->line('delete')); }?>
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