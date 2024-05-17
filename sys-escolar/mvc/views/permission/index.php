
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-permission"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_permission')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <form style="" action="#" class="form-horizontal" role="form" method="post" id="usertype">
                    <div class="<?php if(form_error('usertypeID')) {echo 'form-group has-error';} else {echo 'form-group';} ?>" >
                        <label for="usertypeID" class="col-sm-2 col-md-offset-2 control-label">
                            <?=$this->lang->line("select_usertype")?>
                        </label>

                        <div class="col-sm-4">
                           <?php
                                $array = array("0" => $this->lang->line("permission_select_usertype"));
                                if (isset($set)) {
                                    $set = $set;
                                } else {
                                    $set = null;
                                }
                                foreach ($usertypes as $usertype) {
                                    $array[$usertype->usertypeID] = $usertype->usertype;
                                }
                                echo form_dropdown("usertypeID", $array, set_value("usertypeID", $set), "id='usertypeID' class='form-control select2'");
                            ?>
                        </div>

                        <!-- <div class="col-sm-1 rep-mar">
                            <input type="submit" class="btn btn-success" value="<?=$this->lang->line("permission_table")?>" >
                        </div> -->
                    </div>
                </form>
            </div>
        </div><!-- row -->
        <?php if (isset($set)): ?>
            <div class="row">
                <div class="col-sm-12">
                    <form action="<?=base_url('permission/save/'.$set)?>" class="form-horizontal" role="form" method="post" id="usertype">
                        <div id="hide-table">
                            <table id="" class="table table-striped table-bordered table-hover dataTable no-footer">
                                <thead>
                                    <tr>
                                        <th class="col-lg-1"><?=$this->lang->line('slno')?></th>
                                        <th class="col-lg-3"><?=$this->lang->line('module_name')?></th>
                                        <th class="col-lg-1"><?=$this->lang->line('permission_add')?></th>
                                        <th class="col-lg-1"><?=$this->lang->line('permission_edit')?></th>
                                        <th class="col-lg-1"><?=$this->lang->line('permission_delete')?></th>
                                        <th class="col-lg-1"><?=$this->lang->line('permission_view')?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                        $permissionTable    = array();
                                        $permissionCheckBox = array();
                                        $permissionCheckBoxVal = array();
                                        foreach ($permissions as $data) {
                                            if(strpos($data->name, '_edit') == false && strpos($data->name, '_view') == false && strpos($data->name, '_delete') == false && strpos($data->name, '_add') == false) {
                                                $push['name'] = $data->name;
                                                $push['description'] = $data->description;
                                                $push['status'] = $data->active;

                                                array_push($permissionTable, $push);

                                            }
                                            $permissionCheckBox[ $data->name ] = $data->active;
                                            $permissionCheckBoxVal[ $data->name ] = $data->permissionID;

                                        }
                                    ?>
                                    <?php
                                    $i = 1;
                                    foreach($permissionTable as $data) { ?>
                                        <tr>
                                            <td data-title="<?=$this->lang->line('slno')?>">
                                                <?php
                                                    //echo $i;
                                                    $status = "";
                                                    if(isset($permissionCheckBox[$data['name']])) {
                                                        if ($permissionCheckBox[$data['name']]=="yes") {
                                                            if ($permissionCheckBoxVal[$data['name']]) {
                                                                echo "<input type='checkbox' name=".$data['name']." value=".$permissionCheckBoxVal[$data['name']]." checked='checked' id=".$data['name']." onClick='$(this).processCheck();'>";
                                                            }
                                                        } else {
                                                            if ($permissionCheckBoxVal[$data['name']]) {
                                                                $status = "disabled";
                                                                echo "<input type='checkbox' name=".$data['name']." value=".$permissionCheckBoxVal[$data['name']]." id=".$data['name']."  onClick='$(this).processCheck();' >";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('permission_description')?>">
                                                <?php echo $data['description']; ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('permission_add')?>">
                                                <?php
                                                    if(isset($permissionCheckBox[$data['name'].'_add'])) {
                                                        if ($permissionCheckBox[$data['name'].'_add']=="yes") {
                                                            if ($permissionCheckBoxVal[$data['name'].'_add']) {
                                                                echo "<input type='checkbox' name='".$data['name'].'_add'."' value=".$permissionCheckBoxVal[$data['name'].'_add']." checked='checked' id='".$data['name'].'_add'."' ".$status.">";
                                                            }
                                                        } else {
                                                            if ($permissionCheckBoxVal[$data['name'].'_add']) {
                                                                echo "<input type='checkbox' name='".$data['name'].'_add'."' value=".$permissionCheckBoxVal[$data['name'].'_add']." id='".$data['name'].'_add'."' ".$status.">";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('permission_edit')?>">
                                                <?php
                                                    if(isset($permissionCheckBox[$data['name'].'_edit'])) {
                                                        if ($permissionCheckBox[$data['name'].'_edit']=="yes") {
                                                            if ($permissionCheckBoxVal[$data['name'].'_edit']) {
                                                                echo "<input type='checkbox' name='".$data['name'].'_edit'."' value=".$permissionCheckBoxVal[$data['name'].'_edit']." checked='checked' id='".$data['name'].'_edit'."' ".$status.">";
                                                            }
                                                        } else {
                                                            if ($permissionCheckBoxVal[$data['name'].'_edit']) {
                                                                echo "<input type='checkbox' name='".$data['name'].'_edit'."' value=".$permissionCheckBoxVal[$data['name'].'_edit']." id='".$data['name'].'_edit'."' ".$status.">";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('permission_delete')?>">
                                                <?php
                                                    if(isset($permissionCheckBox[$data['name'].'_delete'])) {
                                                        // echo "delete";
                                                        if ($permissionCheckBox[$data['name'].'_delete']=="yes") {
                                                            if ($permissionCheckBoxVal[$data['name'].'_delete']) {
                                                                echo "<input type='checkbox' name='".$data['name'].'_delete'."' value=".$permissionCheckBoxVal[$data['name'].'_delete']." checked='checked' id='".$data['name'].'_delete'."' ".$status.">";
                                                            }
                                                        } else {
                                                            if ($permissionCheckBoxVal[$data['name'].'_delete']) {
                                                                echo "<input type='checkbox' name='".$data['name'].'_delete'."' value=".$permissionCheckBoxVal[$data['name'].'_delete']." id='".$data['name'].'_delete'."' ".$status.">";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                            <td data-title="<?=$this->lang->line('permission_view')?>">
                                                <?php
                                                    if(isset($permissionCheckBox[$data['name'].'_view'])) {
                                                        if ($permissionCheckBox[$data['name'].'_view']=="yes") {
                                                            if ($permissionCheckBoxVal[$data['name'].'_view']) {
                                                                echo "<input type='checkbox' name='".$data['name'].'_view'."' value=".$permissionCheckBoxVal[$data['name'].'_view']." checked='checked' id='".$data['name'].'_view'."' ".$status.">";
                                                            }
                                                        } else {
                                                            if ($permissionCheckBoxVal[$data['name'].'_view']) {
                                                                echo "<input type='checkbox' name='".$data['name'].'_view'."' value=".$permissionCheckBoxVal[$data['name'].'_view']." id='".$data['name'].'_view'."' ".$status.">";
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                    <?php $i++; } ?>
                                    <tr>
                                        <td colspan="6" rowspan="2">
                                            <input class="btn btn-success" type="submit" name="" value="Guardar cambios">
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div><!-- row -->
        <?php endif ?>
    </div><!-- Body -->
</div><!-- /.box -->

<script type="text/javascript">

var usertypeID = $("#usertypeID").val();

$('#usertypeID').change(function(event) {
    var usertypeID = $(this).val();
    $.ajax({
        type: 'POST',
        url: "<?=base_url('permission/permission_list')?>",
        data: "usertypeID=" + usertypeID,
        dataType: "html",
        success: function(data) {
            console.log(data);
           window.location.href = data;
        }
    });
});
$.fn.processCheck = function() {
    var id = $(this).attr('id');
    if ($('input#'+id).is(':checked')) {
        if ($('input#'+id+"_add").length) {
            $('input#'+id+"_add").prop('disabled', false);
            $('input#'+id+"_add").prop('checked', true);
        }
        if ($('input#'+id+"_edit").length) {
            $('input#'+id+"_edit").prop('disabled', false);
            $('input#'+id+"_edit").prop('checked', true);
        }
        if ($('input#'+id+"_delete").length) {
            $('input#'+id+"_delete").prop('disabled', false);
            $('input#'+id+"_delete").prop('checked', true);
        }
        if ($('input#'+id+"_view").length) {
            $('input#'+id+"_view").prop('disabled', false);
            $('input#'+id+"_view").prop('checked', true);
        }
    } else {
        if ($('input#'+id+"_add").length) {
            $('input#'+id+"_add").prop('disabled', true);
            $('input#'+id+"_add").prop('checked', false);
        }
        if ($('input#'+id+"_edit").length) {
            $('input#'+id+"_edit").prop('disabled', true);
            $('input#'+id+"_edit").prop('checked', false);
        }
        if ($('input#'+id+"_delete").length) {
            $('input#'+id+"_delete").prop('disabled', true);
            $('input#'+id+"_delete").prop('checked', false);
        }
        if ($('input#'+id+"_view").length) {
            $('input#'+id+"_view").prop('disabled', true);
            $('input#'+id+"_view").prop('checked', false);
        }
    }
};


</script>
