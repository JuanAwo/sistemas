
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-promotion"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_promotion')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <form role="form" method="post" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-6">

                            <div class="col-sm-12 list-group-item list-group-item-warning">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="schoolyear" class="control-label">
                                            <?=$this->lang->line('promotion_school_year')?>
                                        </label>
                                        <?php
                                            $array = array();
                                            foreach ($schoolyears as $schoolyear) {
                                                $array[$schoolyear->schoolyearID] = $schoolyear->schoolyear;
                                            }

                                            $array[$siteinfos->school_year] = $array[$siteinfos->school_year].' (Default)';

                                            echo form_dropdown("schoolyear", $array, set_value("schoolyear", $siteinfos->school_year), "id='schoolyear' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="classesID" class="control-label">
                                            <?=$this->lang->line("promotion_classes")?>
                                        </label>

                                        <?php
                                            $array = array("0" => $this->lang->line("promotion_select_class"));
                                            foreach ($classes as $classa) {
                                                $array[$classa->classesID] = $classa->classes;
                                            }
                                            echo form_dropdown("classesID", $array, set_value("classesID"), "id='classesID' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </form>
        </div><!-- row -->
    </div><!-- Body -->
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
                                <h5>Opción normal.</h5>
                                La opción normal promocionará estudiantes directamente con tan solo elegirlos.
                                <h5>Opción avanzado.</h5>
                                La opción avanzada promocionará estudiantes de acuerdo a sus calificaciones.
                            </p>
                        </div>
                    </div>
                </div> 
            </div> 
        </div> 
    </div>
</div><!-- /.box -->

<script type="text/javascript">
    $('.select2').select2();
    $('#classesID').change(function() {
        var classesID = $(this).val();
        var schoolyearID = $('#schoolyear').val();
        if(classesID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('promotion/promotion_list')?>",
                data: {"id" : classesID, "year" : schoolyearID},
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>
