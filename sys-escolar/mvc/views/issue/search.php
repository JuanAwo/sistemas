
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
                            Para emitir un nuevo préstamo debe ingresar el id, el id del miembro es aquel que se asigna cuando el estudiante es miembro de la biblioteca, ejemplo "id de la biblioteca."
                        </p>
                    </div>
                </div>
            </div> 
        </div> 
    </div> 
</div>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-issue"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_issue')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <?php if(permissionChecker('issue_add')) { ?>
                    <h5 class="page-header">
                        <a class="btn btn-success" href="<?php echo base_url('issue/add') ?>">
                            <i class="fa fa-plus"></i> 
                            <?=$this->lang->line('add_title')?>
                        </a>
                    </h5>
                <?php } ?>

                <div class="col-lg-6 col-lg-offset-3 list-group">
                    <div class="list-group-item list-group-item-warning">
                        <form style="" class="form-horizontal" role="form" method="post" enctype="multipart/form-data">  
                              <div class='form-group' >
                                <label for="lid" class="col-sm-3 control-label">
                                    <?=$this->lang->line("issue_lid")?>
                                </label>
                                <div class="col-sm-6">
                                    <input type="text" class="form-control" id="lid" name="lid" value="<?=set_value('lid')?>" >
                                </div>
                                <div class="col-sm-3">
                                    <input type="submit" class="btn btn-success iss-mar" value="<?=$this->lang->line('issue_search')?>" >
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
</div>