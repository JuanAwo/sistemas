
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-object-group"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("studentgroup/index")?>"><?=$this->lang->line('menu_studentgroup')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_studentgroup')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                    if(form_error('group'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="group" class="col-sm-1 control-label">
                            <?=$this->lang->line("studentgroup_group")?> <span class="text-red">*</span>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="group" name="group" value="<?=set_value('group')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('group'); ?>
                        </span>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_studentgroup")?>" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
