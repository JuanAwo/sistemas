<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa fa-money"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_make_payment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <form method="POST">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4">
                                    <div class="<?php echo form_error('role') ? 'form-group has-error' : 'form-group'; ?>" >
                                        <label for="role" class="control-label">
                                            <?=$this->lang->line('make_payment_role')?>
                                        </label>
                                        <?php
                                            $array = array("0" => $this->lang->line('make_payment_select_role'));
                                            foreach ($roles as $role) {
                                                if($role->usertypeID != 3 && $role->usertypeID != 4) {
                                                    $array[$role->usertypeID] = $role->usertype;
                                                }
                                            }
                                            echo form_dropdown("role", $array, set_value("role", $setrole), "id='role' class='form-control select2'");
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <?php if(count($users)) { ?>
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('slno')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('make_payment_photo')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('make_payment_name')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('make_payment_email')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('make_payment_jod')?></th>
                                <?php if(permissionChecker('make_payment')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach($users as $user) { if($user->usertypeID == 1) { $userID = $user->systemadminID; } elseif($user->usertypeID == 2) { $userID = $user->teacherID; } else { $userID = $user->userID; }  if(in_array($userID, $managesalary)) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('make_payment_photo')?>">
                                        <?php $array = array(
                                                "src" => base_url('uploads/images/'.$user->photo),
                                                'width' => '35px',
                                                'height' => '35px',
                                                'class' => 'img-rounded'

                                            );
                                            echo img($array); 
                                        ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('make_payment_name')?>">
                                        <?=$user->name?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('make_payment_email')?>">
                                        <?=$user->email?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('make_payment_jod')?>">
                                        <?=date('d M Y', strtotime($user->jod))?>
                                    </td>

                                    <?php if(permissionChecker('make_payment')) { ?>
                                        <td data-title="<?=$this->lang->line('action')?>">
                                            <a href="<?=base_url("make_payment/add/$userID/$user->usertypeID")?>" class="btn btn-sm btn-success"><?=$this->lang->line('make_payment_make_payment')?></a>
                                        </td>
                                    <?php } ?>
                                    
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>
                <?php } ?>
               
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(".select2").select2();

    $('#role').change(function() {
        var role = $(this).val();
        if(role == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('make_payment/role_list')?>",
                data: "id=" + role,
                dataType: "html",
                success: function(data) {  
                    window.location.href = data;
                }
            });
        }
    });
</script>