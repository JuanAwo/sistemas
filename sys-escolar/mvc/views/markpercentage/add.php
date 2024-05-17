
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-markpercentage"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("markpercentage/index")?>"></i> <?=$this->lang->line('menu_markpercentage')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_markpercentage')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post">

                    <?php 
                        if(form_error('markpercentagetype')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="markpercentagetype" class="col-sm-2 control-label">
                            <?=$this->lang->line("markpercentage_markpercentagetype")?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="markpercentagetype" name="markpercentagetype" value="<?=set_value('markpercentagetype')?>" >
                        </div>
                        <span class="col-sm-6 control-label">
                            <?php echo form_error('markpercentagetype'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('percentage')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="percentage" class="col-sm-2 control-label">
                            <?=$this->lang->line("markpercentage_percentage")?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="percentage" name="percentage" value="<?=set_value('percentage')?>" >
                        </div>
                        <span class="col-sm-6 control-label">
                            <?php echo form_error('percentage'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_markpercentage")?>" >
                        </div>
                    </div>

                </form>


            </div>
        </div>
    </div>
</div>
