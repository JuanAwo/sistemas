
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-payment"></i> <?=$this->lang->line('panel_title')?></h3>

        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_paymenthistory')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('paymenthistory_student')?></th>
                                <th><?=$this->lang->line('paymenthistory_classes')?></th>
                                <th><?=$this->lang->line('paymenthistory_feetype')?></th>
                                <th><?=$this->lang->line('paymenthistory_method')?></th>
                                <th><?=$this->lang->line('paymenthistory_amount')?></th>
                                <th><?=$this->lang->line('paymenthistory_date')?></th>
                                <th><?=$this->lang->line('paymenthistory_payment_by')?></th>
                                 <?php if(permissionChecker('paymenthistory_edit') || permissionChecker('paymenthistory_delete')) { ?>
                                <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($payments)) {$i = 1; foreach($payments as $payment) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_student')?>">
                                        <?php echo $payment->srname; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_classes')?>">
                                        <?php echo $payment->srclasses; ?>
                                    </td>
                                   
                                    <td data-title="<?=$this->lang->line('paymenthistory_feetype')?>">
                                        <?php echo $payment->feetype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_method')?>">
                                        <?php echo $payment->paymenttype; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_amount')?>">
                                        <?php echo $payment->paymentamount; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_date')?>">
                                        <?php echo date("d M Y", strtotime($payment->paymentdate));  ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('paymenthistory_payment_by')?>">
                                        <?php echo $payment->uname;  ?>
                                    </td>
                                    <?php if(permissionChecker('paymenthistory_edit') || permissionChecker('paymenthistory_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <?php if($payment->paymenttype != 'Paypal' && $payment->paymenttype != 'Stripe' && $payment->paymenttype != 'PayUmoney') { ?>
                                        <?php echo btn_edit('paymenthistory/edit/'.$payment->paymentID, $this->lang->line('edit')) ?>
                                        <?php echo btn_delete('paymenthistory/delete/'.$payment->paymentID, $this->lang->line('delete')) ?>
                                        <?php } ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>
