
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-invoice"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li><a href="<?=base_url("invoice/index")?>"><?=$this->lang->line('menu_invoice')?></a></li>
            <li class="active"><?=$this->lang->line('add_payment')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-10">
                <?php 
                    $usertypeID = $this->session->userdata("usertypeID"); 
                    if($usertypeID == 1 || $usertypeID == 5) { 
                ?>
                    <form class="form-horizontal" role="form" method="post">
                        <?php 
                            if(form_error('amount')) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                        ?>
                            <label for="amount" class="col-sm-2 control-label">
                                <?=$this->lang->line("invoice_amount")?>
                            </label>
                            <div class="col-sm-6">
                                <input type="text" class="form-control" id="amount" name="amount" value="<?=set_value('amount', $dueamount)?>" >
                            </div>
                            <span class="col-sm-4 control-label">
                                <?php echo form_error('amount'); ?>
                            </span>
                        </div>

                        <?php 
                            if(form_error('payment_method')) 
                                echo "<div class='form-group has-error' >";
                            else     
                                echo "<div class='form-group' >";
                        ?>
                            <label for="payment_method" class="col-sm-2 control-label">
                                <?=$this->lang->line("invoice_paymentmethod")?>
                            </label>
                            <div class="col-sm-6">
                                <?php
                                    $array = $array = array('0' => $this->lang->line("invoice_select_paymentmethod"));
                                    $array['Cash'] = $this->lang->line('Cash');
                                    $array['Cheque'] = $this->lang->line('Cheque');
                                    if ($payment_settings['paypal_status'] == true) {
                                        $array['Paypal'] = $this->lang->line('Paypal');
                                    }
                                    echo form_dropdown("payment_method", $array, set_value("payment_method"), "id='payment_method' class='form-control select2' onchange='CheckType()'");
                                ?>
                            </div>
                            <span class="col-sm-4 control-label">
                                <?php echo form_error('payment_method'); ?>
                            </span>
                        </div>

                            <span class="col-sm-4 control-label">
                                <?php echo form_error('payment_method'); ?>
                            </span>
                        </div>
                   
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <input type="submit" class="btn btn-success" value="<?=$this->lang->line("add_payment")?>" >
                            </div>
                        </div>
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>

