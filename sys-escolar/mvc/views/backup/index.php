
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-download"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_backup')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form action="<?=base_url('backup/index');?>" class="form-horizontal" role="form" method="post">  
                    <label for="photo" class="col-sm-2 control-label col-xs-8 col-md-2">
                        <?=$this->lang->line("backup_title")?>
                    </label>
                    <div class="form-group">
                        <div class="col-md-1 rep-mar">
                            <input type="hidden" value="0" name="hidden">
                            <button type="submit" class="btn btn-primary">
                              <i class="fa fa-download"></i> <?=$this->lang->line("backup_submit")?>
                            </button>
                        </div>
                    </div>
                </form>
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

                                            <i class="fa fa-database fa-2x"></i>
                                            Al descargar el script de la base de datos está generando una copia de toda la información del sistema.
                                            Se recomienda guardar  las <strong>copias de seguridad </strong> en un lugar seguro, el sistema no realiza copias de seguridad automáticas.
                                        </div>
                                    </div>
                                </div>
                            </div> 
                        </div> 
                    </div> 
            </div>  
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->