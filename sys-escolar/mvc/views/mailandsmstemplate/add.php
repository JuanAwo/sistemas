
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-template"></i> <?=$this->lang->line('panel_title')?></h3>


        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("mailandsmstemplate/index")?>"><?=$this->lang->line('menu_mailandsmstemplate')?></a></li>
            <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_mailandsmstemplate')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="<?php if($email == 1) echo 'active'; ?>"><a data-toggle="tab" href="#email" aria-expanded="true"><?=$this->lang->line('mailandsmstemplate_email')?></a></li>
                        <li class="<?php if($sms == 1) echo 'active'; ?>"><a data-toggle="tab" href="#sms" aria-expanded="true"><?=$this->lang->line('mailandsmstemplate_sms')?></a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="email" class="tab-pane <?php if($email == 1) echo 'active';?> ">
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <form class="form-horizontal" role="form" method="post">
                                        <?php echo form_hidden('type', 'email'); ?>
                                        <?php
                                            if(form_error('email_name'))
                                                echo "<div class='form-group has-error' >";
                                            else
                                                echo "<div class='form-group' >";
                                        ?>
                                            <label for="email_name" class="col-sm-1 control-label">
                                                <?=$this->lang->line("mailandsmstemplate_name")?>
                                            </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="email_name" name="email_name" value="<?=set_value('email_name')?>" >
                                            </div>
                                            <span class="col-sm-4 control-label">
                                                <?php echo form_error('email_name'); ?>
                                            </span>
                                        </div>

                                        <?php
                                            if(form_error('email_user'))
                                                echo "<div class='form-group has-error' >";
                                            else
                                                echo "<div class='form-group' >";
                                        ?>
                                            <label for="email_user" class="col-sm-1 control-label">
                                                <?=$this->lang->line("mailandsmstemplate_user")?>
                                            </label>
                                            <div class="col-sm-4">
                                                <?php
                                                    $array = array('select' => $this->lang->line('mailandsmstemplate_select_user'));

                                                    if(count($usertypes)) {
                                                        foreach ($usertypes as $key => $usertype) {
                                                            $array[$usertype->usertypeID] = $usertype->usertype;
                                                        }
                                                    }

                                                    echo form_dropdown("email_user", $array, set_value("email_user"), "id='email_user' class='form-control'");
                                                ?>
                                            </div>
                                            <span class="col-sm-4 control-label">
                                                <?php echo form_error('email_user'); ?>
                                            </span>
                                        </div>

                                        <?php
                                            if(form_error('email_tags'))
                                                echo "<div class='form-group has-error' >";
                                            else
                                                echo "<div class='form-group' >";
                                        ?>
                                            <label for="email_tags" class="col-sm-1 control-label">
                                                <?=$this->lang->line("mailandsmstemplate_tags")?>
                                            </label>
                                            <div class="col-sm-8" >
                                                <div class="col-sm-12 border" id="email_tags">

                                            <?php
                                            if(count($usertypes)) {
                                                foreach ($usertypes as $key => $usertype) {
                                                    if($usertype->usertypeID == 2) {
                                                        echo '<div class="emailtagdiv" id="'."email_".$usertype->usertype.'">';
                                                            echo $teachers;
                                                        echo '</div>';

                                                    } elseif($usertype->usertypeID == 3) {
                                                        echo '<div class="emailtagdiv" id="'."email_".$usertype->usertype.'">';
                                                            echo $students;
                                                        echo '</div>';

                                                    } elseif($usertype->usertypeID == 4) {
                                                        echo '<div class="emailtagdiv" id="'."email_".$usertype->usertype.'">';
                                                            echo $parents;
                                                        echo '</div>';
                                                    }  else {
                                                        echo '<div class="emailtagdiv" id="'."email_".str_replace(' ', '', $usertype->usertype).'">';
                                                            echo $users;
                                                        echo '</div>';

                                                    }
                                                }
                                            }
                                            ?>


                                                </div>
                                            </div>
                                            <span class="col-sm-3 control-label">
                                                <?php echo form_error('email_tags'); ?>
                                            </span>
                                        </div>

                                        <?php
                                            if(form_error('email_template'))
                                                echo "<div class='form-group has-error' >";
                                            else
                                                echo "<div class='form-group' >";
                                        ?>
                                            <label for="email_template" class="col-sm-1 control-label">
                                                <?=$this->lang->line("mailandsmstemplate_template")?>
                                            </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" id="email_template" name="email_template" ><?=set_value('email_template')?></textarea>
                                            </div>
                                            <span class="col-sm-3 control-label">
                                                <?php echo form_error('email_template'); ?>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-1 col-sm-8">
                                                <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_template")?>" >
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>



                        <div id="sms" class="tab-pane <?php if($sms == 1) echo 'active'; ?>">
                            <br>
                            <div class="row">
                                <div class="col-sm-12">
                                    <form class="form-horizontal" role="form" method="post">
                                        <?php echo form_hidden('type', 'sms'); ?>
                                        <?php
                                            if(form_error('sms_name'))
                                                echo "<div class='form-group has-error' >";
                                            else
                                                echo "<div class='form-group' >";
                                        ?>
                                            <label for="sms_name" class="col-sm-1 control-label">
                                                <?=$this->lang->line("mailandsmstemplate_name")?>
                                            </label>
                                            <div class="col-sm-4">
                                                <input type="text" class="form-control" id="sms_name" name="sms_name" value="<?=set_value('sms_name')?>" >
                                            </div>
                                            <span class="col-sm-4 control-label">
                                                <?php echo form_error('sms_name'); ?>
                                            </span>
                                        </div>

                                        <?php
                                            if(form_error('sms_user'))
                                                echo "<div class='form-group has-error' >";
                                            else
                                                echo "<div class='form-group' >";
                                        ?>
                                            <label for="sms_user" class="col-sm-1 control-label">
                                                <?=$this->lang->line("mailandsmstemplate_user")?>
                                            </label>
                                            <div class="col-sm-4">
                                                <?php
                                                    $array = array('select' => $this->lang->line('mailandsmstemplate_select_user'));

                                                    if(count($usertypes)) {
                                                        foreach ($usertypes as $key => $usertype) {
                                                            $array[$usertype->usertypeID] = $usertype->usertype;
                                                        }
                                                    }

                                                    echo form_dropdown("sms_user", $array, set_value("sms_user"), "id='sms_user' class='form-control'");
                                                ?>
                                            </div>
                                            <span class="col-sm-4 control-label">
                                                <?php echo form_error('sms_user'); ?>
                                            </span>
                                        </div>

                                        <?php
                                            if(form_error('sms_tags'))
                                                echo "<div class='form-group has-error' >";
                                            else
                                                echo "<div class='form-group' >";
                                        ?>
                                            <label for="sms_tags" class="col-sm-1 control-label">
                                                <?=$this->lang->line("mailandsmstemplate_tags")?>
                                            </label>
                                            <div class="col-sm-8" >
                                                <div class="col-sm-12 border" id="sms_tags">
                                                <?php
                                                if(count($usertypes)) {
                                                    foreach ($usertypes as $key => $usertype) {
                                                        if($usertype->usertypeID == 2) {
                                                            echo '<div class="smstagdiv" id="'."sms_".$usertype->usertype.'">';
                                                                echo $teachers;
                                                            echo '</div>';

                                                        } elseif($usertype->usertypeID == 3) {
                                                            echo '<div class="smstagdiv" id="'."sms_".$usertype->usertype.'">';
                                                                echo $students;
                                                            echo '</div>';

                                                        } elseif($usertype->usertypeID == 4) {
                                                            echo '<div class="smstagdiv" id="'."sms_".$usertype->usertype.'">';
                                                                echo $parents;
                                                            echo '</div>';
                                                        }  else {
                                                            echo '<div class="smstagdiv" id="'."sms_".str_replace(' ', '', $usertype->usertype).'">';
                                                                echo $users;
                                                            echo '</div>';

                                                        }
                                                    }
                                                }
                                                ?>
                                                </div>
                                            </div>
                                            <span class="col-sm-3 control-label">
                                                <?php echo form_error('sms_tags'); ?>
                                            </span>
                                        </div>

                                        <?php
                                            if(form_error('sms_template'))
                                                echo "<div class='form-group has-error' >";
                                            else
                                                echo "<div class='form-group' >";
                                        ?>
                                            <label for="sms_template" class="col-sm-1 control-label">
                                                <?=$this->lang->line("mailandsmstemplate_template")?>
                                            </label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control" style="resize: vertical;" id="sms_template" name="sms_template" ><?=set_value('sms_template')?></textarea>
                                            </div>
                                            <span class="col-sm-3 control-label">
                                                <?php echo form_error('sms_template'); ?>
                                            </span>
                                        </div>

                                        <div class="form-group">
                                            <div class="col-sm-offset-1 col-sm-8">
                                                <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_template")?>" >
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div> <!-- nav-tabs-custom -->
                <?php if ($siteinfos->note==1) { ?>
                    <div class="col-md-8">
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
                                                Cuando seleccione un usuario aparecerán etiquetas las cuales representan los datos de cada usuario.
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
$(document).ready(function() {

    var email_setuser = "<?=$email_user?>";
    if(email_setuser !='select') {
        <?php
            if(count($usertypes)) {
                foreach ($usertypes as $key => $usertype) {
                    echo 'if('.$usertype->usertypeID." == email_setuser) {"."\n";
                        echo '$("#email_'.str_replace(' ', '', $usertype->usertype).'").show();'."\n";
                    echo '} else {'."\n";
                        echo '$("#email_'.str_replace(' ', '', $usertype->usertype).'").hide();'."\n";
                    echo '}'."\n";
                }
            }
        ?>
    } else {
        <?php
            if(count($usertypes)) {
                foreach ($usertypes as $key => $usertype) {
                    echo '$("#email_'.str_replace(' ', '', $usertype->usertype).'").hide();'."\n";
                }
            }
        ?>
    }

    var sms_setuser = "<?=$sms_user?>";
    if(sms_setuser !='select') {
        <?php
            if(count($usertypes)) {
                foreach ($usertypes as $key => $usertype) {
                    echo 'if('.$usertype->usertypeID." == sms_setuser) {"."\n";
                        echo '$("#sms_'.str_replace(' ', '', $usertype->usertype).'").show();'."\n";
                    echo '} else {'."\n";
                        echo '$("#sms_'.str_replace(' ', '', $usertype->usertype).'").hide();'."\n";
                    echo '}'."\n";
                }
            }

        ?>
    } else {
        <?php
            if(count($usertypes)) {
                foreach ($usertypes as $key => $usertype) {
                    echo '$("#sms_'.str_replace(' ', '', $usertype->usertype).'").hide();'."\n";
                }
            }
        ?>

    }


    $('#email_user').change(function() {
        var email_user = $(this).val();
        if(email_user !='select') {
            <?php
                if(count($usertypes)) {
                    foreach ($usertypes as $key => $usertype) {
                        echo 'if('.$usertype->usertypeID." == email_user) {"."\n";
                            echo '$("#email_'.str_replace(' ', '', $usertype->usertype).'").show();'."\n";
                        echo '} else {'."\n";
                            echo '$("#email_'.str_replace(' ', '', $usertype->usertype).'").hide();'."\n";
                        echo '}'."\n";
                    }
                }

            ?>
        } else {
            <?php
                if(count($usertypes)) {
                    foreach ($usertypes as $key => $usertype) {
                        echo '$("#email_'.str_replace(' ', '', $usertype->usertype).'").hide();'."\n";
                    }
                }
            ?>
        }

    });

    $('#sms_user').change(function() {
        var sms_user = $(this).val();
        if(sms_user !='select') {
            <?php
                if(count($usertypes)) {
                    foreach ($usertypes as $key => $usertype) {
                        echo 'if('.$usertype->usertypeID." == sms_user) {"."\n";
                            echo '$("#sms_'.str_replace(' ', '', $usertype->usertype).'").show();'."\n";
                        echo '} else {'."\n";
                            echo '$("#sms_'.str_replace(' ', '', $usertype->usertype).'").hide();'."\n";
                        echo '}'."\n";
                    }
                }
            ?>
        } else {
            <?php
                if(count($usertypes)) {
                    foreach ($usertypes as $key => $usertype) {
                        echo '$("#sms_'.str_replace(' ', '', $usertype->usertype).'").hide();'."\n";
                    }
                }
            ?>
        }
    });

    $('.emailtagdiv > .email_alltag').click(function() {
        var value = $(this).val();
        $(".jqte_editor").append(value);
    });

    $('.smstagdiv > .sms_alltag').click(function() {
        var value = $(this).val();
        var template = $('#sms_template').val();
        console.log(value);
        $('#sms_template').val(template+value);
    });

$('#email_template').jqte();
});

</script>
