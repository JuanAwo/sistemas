
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-paymentsettings"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_paymentsettings')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">

                <div class="col-sm-12">

                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="<?php if($paypal == 1) echo 'active'; ?>"><a data-toggle="tab" href="#paypal" aria-expanded="true"><?=$this->lang->line('tab_paypal')?></a></li>
                            <li class="<?php if($stripe == 1) echo 'active'; ?>"><a data-toggle="tab" href="#stripe" aria-expanded="true"><?=$this->lang->line('tab_stripe')?></a></li>
                            <li class="<?php if($payumoney == 1) echo 'active'; ?>"><a data-toggle="tab" href="#payumoney" aria-expanded="true"><?=$this->lang->line('tab_payumoney')?></a></li>
                            <li class="<?php if($voguepay == 1) echo 'active'; ?>"><a data-toggle="tab" href="#voguepay" aria-expanded="true"><?=$this->lang->line('tab_voguepay')?></a></li>
                        </ul>

                        <div class="tab-content">
                            <div id="voguepay" class="tab-pane <?php if($voguepay == 1) echo 'active';?> ">
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                            <?php echo form_hidden('type', 'voguepay'); ?>

                                            <div class="form-group <?= form_error('voguepay_merchant_id') ?'has-error':'' ?>" >
                                                <label for="voguepay_merchant_id" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("voguepay_merchant_id")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="voguepay_merchant_id" name="voguepay_merchant_id" value="<?=set_value('voguepay_merchant_id', $set_key['voguepay_merchant_id'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('voguepay_merchant_id'); ?>
                                                </span>
                                            </div>
                                            <div class="form-group <?= form_error('voguepay_merchant_ref') ?'has-error':'' ?>" >
                                                <label for="voguepay_merchant_ref" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("voguepay_merchant_ref")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="voguepay_merchant_ref" name="voguepay_merchant_ref" value="<?=set_value('voguepay_merchant_ref', $set_key['voguepay_merchant_ref'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('voguepay_merchant_ref'); ?>
                                                </span>
                                            </div>
                                            <div class="form-group <?= form_error('voguepay_developer_code') ?'has-error':'' ?>" >
                                                <label for="voguepay_developer_code" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("voguepay_developer_code")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="voguepay_developer_code" name="voguepay_developer_code" value="<?=set_value('voguepay_developer_code', $set_key['voguepay_developer_code'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('voguepay_developer_code'); ?>
                                                </span>
                                            </div>
                                            <div class="form-group <?= form_error('voguepay_demo') ?'has-error':'' ?>" >
                                                <label for="voguepay_demo" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("voguepay_demo")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="voguepay_demo" class="onoffswitch-checkbox" id="myonoffswitchvoguepay" <?=($set_key['voguepay_demo']=="TRUE"?"checked":"")?>>
                                                        <label class="onoffswitch-label" for="myonoffswitchvoguepay">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('voguepay_demo'); ?>
                                                </span>
                                            </div>
                                            <?php
                                                if(form_error('voguepay_status'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="voguepay_status" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("voguepay_status")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="radio" name="voguepay_status" value="1" <?=set_value('voguepay_status', $set_key['voguepay_status']) == 1 ? "checked" : ""; ?> /> <?=$this->lang->line('enable')?>
                                                    <input type="radio" name="voguepay_status" value="0" <?=set_value('voguepay_status', $set_key['voguepay_status']) == 0 ? "checked" : ""; ?> /> <?=$this->lang->line('disable')?>
                                                </div>


                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('voguepay_status'); ?>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                    <input type="submit" class="btn btn-success" value="<?=$this->lang->line("save")?>" >
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="payumoney" class="tab-pane <?php if($payumoney == 1) echo 'active';?> ">
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                            <?php echo form_hidden('type', 'payumoney'); ?>

                                            <div class="form-group <?= form_error('payumoney_key') ?'has-error':'' ?>" >
                                                <label for="payumoney_key" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("payumoney_key")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="payumoney_key" name="payumoney_key" value="<?=set_value('payumoney_key', $set_key['payumoney_key'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('payumoney_key'); ?>
                                                </span>
                                            </div>
                                            <div class="form-group <?= form_error('payumoney_salt') ?'has-error':'' ?>" >
                                                <label for="payumoney_salt" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("payumoney_salt")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="payumoney_salt" name="payumoney_salt" value="<?=set_value('payumoney_salt', $set_key['payumoney_salt'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('payumoney_salt'); ?>
                                                </span>
                                            </div>
                                            <div class="form-group <?= form_error('payumoney_demo') ?'has-error':'' ?>" >
                                                <label for="payumoney_secret" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("payumoney_demo")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="payumoney_demo" class="onoffswitch-checkbox" id="myonoffswitch" <?=($set_key['payumoney_demo']=="TRUE"?"checked":"")?>>
                                                        <label class="onoffswitch-label" for="myonoffswitch">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('payumoney_demo'); ?>
                                                </span>
                                            </div>
                                            <?php
                                                if(form_error('payumoney_status'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                                ?>
                                                <label for="payumoney_status" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("payumoney_status")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="radio" name="payumoney_status" value="1" <?=set_value('payumoney_status', $set_key['payumoney_status']) == 1 ? "checked" : ""; ?> /> <?=$this->lang->line('enable')?>
                                                    <input type="radio" name="payumoney_status" value="0" <?=set_value('payumoney_status', $set_key['payumoney_status']) == 0 ? "checked" : ""; ?> /> <?=$this->lang->line('disable')?>
                                                </div>


                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('payumoney_status'); ?>
                                                </span>
                                            </div>
                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                    <input type="submit" class="btn btn-success" value="<?=$this->lang->line("save")?>" >
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="stripe" class="tab-pane <?php if($stripe == 1) echo 'active';?> ">
                                <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                            <?php echo form_hidden('type', 'stripe'); ?>

                                            <div class="form-group <?= form_error('stripe_secret') ?'has-error':'' ?>" >
                                                <label for="stripe_secret" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("stripe_secret")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="stripe_secret" name="stripe_secret" value="<?=set_value('stripe_secret', $set_key['stripe_secret'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('stripe_secret'); ?>
                                                </span>
                                            </div>
                                            <div class="form-group <?= form_error('stripe_demo') ?'has-error':'' ?>" >
                                                <label for="stripe_secret" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("stripe_demo")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="stripe_demo" class="onoffswitch-checkbox" id="myonoffswitchstripe" <?=($set_key['stripe_demo']=="TRUE"?"checked":"")?>>
                                                        <label class="onoffswitch-label" for="myonoffswitchstripe">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('stripe_demo'); ?>
                                                </span>
                                            </div>

                                            <?php
                                                if(form_error('stripe_status'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                                ?>
                                                <label for="stripe_status" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("stripe_status")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="radio" name="stripe_status" value="1" <?=set_value('stripe_status', $set_key['stripe_status']) == 1 ? "checked" : ""; ?> /> <?=$this->lang->line('enable')?>
                                                    <input type="radio" name="stripe_status" value="0" <?=set_value('stripe_status', $set_key['stripe_status']) == 0 ? "checked" : ""; ?> /> <?=$this->lang->line('disable')?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('stripe_status'); ?>
                                                </span>
                                            </div>

                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                    <input type="submit" class="btn btn-success" value="<?=$this->lang->line("save")?>" >
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div id="paypal" class="tab-pane <?php if($paypal == 1) echo 'active';?> ">
                            <br>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <form class="form-horizontal" role="form" method="post" enctype="multipart/form-data">
                                            <?php echo form_hidden('type', 'paypal'); ?> 
                                            
                                            <?php 
                                                if(form_error('paypal_api_username')) 
                                                    echo "<div class='form-group has-error' >";
                                                else     
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="paypal_api_username" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("paypal_api_username")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="paypal_api_username" name="paypal_api_username" value="<?=set_value('paypal_api_username', $set_key['paypal_api_username'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('paypal_api_username'); ?>
                                                </span>
                                            </div>

                                            <?php 
                                                if(form_error('paypal_api_password')) 
                                                    echo "<div class='form-group has-error' >";
                                                else     
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="paypal_api_password" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("paypal_api_password")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="paypal_api_password" name="paypal_api_password" value="<?=set_value('paypal_api_password', $set_key['paypal_api_password'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('paypal_api_password'); ?>
                                                </span>
                                            </div>

                                            <?php 
                                                if(form_error('paypal_api_signature')) 
                                                    echo "<div class='form-group has-error' >";
                                                else     
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="paypal_api_signature" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("paypal_api_signature")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="text" class="form-control" id="paypal_api_signature" name="paypal_api_signature" value="<?=set_value('paypal_api_signature', $set_key['paypal_api_signature'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('paypal_api_signature'); ?>
                                                </span>
                                            </div>

                                            <?php 
                                                if(form_error('paypal_email')) 
                                                    echo "<div class='form-group has-error' >";
                                                else     
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="paypal_email" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("paypal_email")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="email" class="form-control" id="paypal_email" name="paypal_email" value="<?=set_value('paypal_email', $set_key['paypal_email'])?>" >
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('paypal_email'); ?>
                                                </span>
                                            </div>

                                            <?php 
                                                if(form_error('paypal_demo')) 
                                                    echo "<div class='form-group has-error' >";
                                                else     
                                                    echo "<div class='form-group' >";
                                            ?>
                                                <label for="paypal_demo" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("paypal_demo")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <div class="onoffswitch">
                                                        <input type="checkbox" name="paypal_demo" class="onoffswitch-checkbox" id="myonoffswitchpaypal" <?=($set_key['paypal_demo']=="TRUE"?"checked":"")?>>
                                                        <label class="onoffswitch-label" for="myonoffswitchpaypal">
                                                            <span class="onoffswitch-inner"></span>
                                                            <span class="onoffswitch-switch"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('paypal_demo'); ?>
                                                </span>
                                            </div>

                                            <?php
                                                if(form_error('paypal_status'))
                                                    echo "<div class='form-group has-error' >";
                                                else
                                                    echo "<div class='form-group' >";
                                                ?>
                                                <label for="paypal_status" class="col-sm-2 control-label">
                                                    <?=$this->lang->line("paypal_status")?>
                                                </label>
                                                <div class="col-sm-5">
                                                    <input type="radio" name="paypal_status" value="1" <?=set_value('paypal_status', $set_key['paypal_status']) == 1 ? "checked" : ""; ?> /> <?=$this->lang->line('enable')?>
                                                    <input type="radio" name="paypal_status" value="0" <?=set_value('paypal_status', $set_key['paypal_status']) == 0 ? "checked" : ""; ?> /> <?=$this->lang->line('disable')?>
                                                </div>
                                                <span class="col-sm-4 control-label">
                                                    <?php echo form_error('paypal_status'); ?>
                                                </span>
                                            </div>


                                            <div class="form-group">
                                                <div class="col-sm-offset-2 col-sm-8">
                                                    <input type="submit" class="btn btn-success" value="<?=$this->lang->line("save")?>" >
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div> <!-- nav-tabs-custom -->
                </div>
   

            </div> <!-- col-sm-12 -->
            
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->


<script>
    $(document).ready(function(){
      $('.now-check-type').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-green',
        increaseArea: '20%'
      });
    });

    $(document).ready(function(){
      $('.now-check-type').iCheck({
        checkboxClass: 'icheckbox_square-green',
        radioClass: 'iradio_square-red',
      });
    });

</script>