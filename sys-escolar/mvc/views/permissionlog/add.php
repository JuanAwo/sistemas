
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("permissionlog/index")?>"><?=$this->lang->line('panel_title')?></a></li>
            <li class="active"><?=$this->lang->line('permissionlog_add')?> <?=$this->lang->line('panel_title')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post">
                    <?php
                    if(form_error('name'))
                        echo "<div class='form-group has-error' >";
                    else
                        echo "<div class='form-group' >";
                    ?>
                        <label for="name" class="col-sm-1 control-label">
                            <?=$this->lang->line("permissionlog_name")?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="name" name="name" value="<?=set_value('name')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('name'); ?>
                        </span>
                </div>
                <?php
                if(form_error('description'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>
                    <label for="description" class="col-sm-1 control-label">
                        <?=$this->lang->line("permissionlog_description")?>
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="description" name="description" value="<?=set_value('description')?>" >
                    </div>
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('description'); ?>
                    </span>
                </div>
                <?php
                if(form_error('active'))
                    echo "<div class='form-group has-error' >";
                else
                    echo "<div class='form-group' >";
                ?>
                    <label for="active" class="col-sm-1 control-label">
                        <?=$this->lang->line("permissionlog_active")?>
                    </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" id="active" name="active" value="<?=set_value('active', 'yes')?>" >
                    </div>
                    <span class="col-sm-4 control-label">
                        <?php echo form_error('active'); ?>
                    </span>
                </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-1 col-sm-8">
            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_class")?>" >
        </div>
    </div>

    </form>

</div>
</div>
</div>
</div>
<script type="text/javascript">

</script>
