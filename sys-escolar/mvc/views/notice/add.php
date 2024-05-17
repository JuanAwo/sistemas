
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-calendar"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("notice/index")?>"><?=$this->lang->line('menu_notice')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_notice')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form class="form-horizontal" role="form" method="post">
                    <?php 
                        if(form_error('title')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="title" class="col-sm-1 control-label">
                            <?=$this->lang->line("notice_title")?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="title" name="title" value="<?=set_value('title')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('title'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('date')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="date" class="col-sm-1 control-label">
                            <?=$this->lang->line("notice_date")?>
                        </label>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="date" name="date" value="<?=set_value('date')?>" >
                        </div>
                        <span class="col-sm-4 control-label">
                            <?php echo form_error('date'); ?>
                        </span>
                    </div>

                    <?php 
                        if(form_error('notice')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="notice" class="col-sm-1 control-label">
                            <?=$this->lang->line("notice_notice")?>
                        </label>
                        <div class="col-sm-8">
                            <textarea class="form-control" id="notice" name="notice" ><?=set_value('notice')?></textarea>
                        </div>
                        <span class="col-sm-3 control-label">
                            <?php echo form_error('notice'); ?>
                        </span>
                    </div>

                    <div class="form-group">
                        <div class="col-sm-offset-1 col-sm-8">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_class")?>" >
                        </div>
                    </div>

                </form>
                <?php if ($siteinfos->note==1) { ?>
                    <div class="col-md-9">
                        <div class="panel-group" id="accordion"> 
                            <div class="panel panel-default"> 
                                <div class="panel-heading"> 
                                    <h4 class="panel-title"> 
                                    <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne"> 
                                    <span class="fa fa-send"></span> Más información</a> 
                                    </h4> 
                                </div> 
                                <div id="collapseOne" class="panel-collapse collapse"> 
                                    <div class="panel-body">
                                        <div class="col-md-12 text-justify">
                                            <div class="alert alert-dismissible alert-info">
                                                <p>
                                                    Cuando agregue una nueva noticia todos los usuarios registrados en el sistema visualizaran estos avisos en sus cuentas.
                                                </p>
                                            </div>
                                        </div>
                                    </div> 
                                </div> 
                            </div> 
                        </div>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
$('#date').datepicker();
$('#notice').jqte();
</script>
