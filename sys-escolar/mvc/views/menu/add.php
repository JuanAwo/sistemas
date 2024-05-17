
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-signal"></i>Menu Management</h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("menu/index")?>">Manu Management</a></li>
            <li class="active">Add Menu</li>
        </ol>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <form class="form-horizontal" role="form" method="post">

                    <?php
                        if(form_error('menuName'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="menuName" class="col-sm-2 control-label">
                            Menu Name
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="menuName" name="menuName" value="<?=set_value('menuName')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('menuName'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('parentID'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="parentID" class="col-sm-2 control-label">
                            Parent
                        </label>
                        <div class="col-sm-6">

                            <?php
                                $array = array();
                                $array[0] = 'Select';

                                foreach ($menus as $menu) {
                                    $array[$menu->menuID] = $menu->menuName;
                                }
                                echo form_dropdown("parentID", $array, set_value("parentID"), "id='parentID' class='form-control select2'");
                            ?>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('parentID'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('link'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="link" class="col-sm-2 control-label">
                            Link
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="link" name="link" value="<?=set_value('link')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('link'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('icon'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="icon" class="col-sm-2 control-label">
                            Icon
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="icon" name="icon" value="<?=set_value('icon')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('icon'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('priority'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="priority" class="col-sm-2 control-label">
                            Priority
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="priority" name="priority" value="<?=set_value('priority')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('priority'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('status'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="status" class="col-sm-2 control-label">
                            Status
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="status" name="status" value="<?=set_value('status', 1)?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('status'); ?>
                        </span>
                    </div>

                    <?php
                        if(form_error('pullRight'))
                            echo "<div class='form-group has-error' >";
                        else
                            echo "<div class='form-group' >";
                    ?>
                        <label for="pullRight" class="col-sm-2 control-label">
                            Pull Right
                        </label>
                        <div class="col-sm-6">
                            <textarea style="resize:none;" class="form-control" id="pullRight" name="pullRight"><?=set_value('pullRight')?></textarea>
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('pullRight'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-8">
                            <input type="submit" class="btn btn-success" value="Submit" >
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $( ".select2" ).select2();
</script>
