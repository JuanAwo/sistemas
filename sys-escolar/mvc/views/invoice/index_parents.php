
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><i class="fa icon-invoice"></i> <?=$this->lang->line('panel_title')?></h3>

       
        <ol class="breadcrumb">
            <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
            <li class="active"><?=$this->lang->line('menu_invoice')?></li>
        </ol>
    </div><!-- /.box-header -->
    <!-- form start -->
    <div class="box-body">
        <div class="row">
            <div class="col-sm-12">
                <h5 class="page-header">
                    <?php if(permissionChecker('invoice_add')) { ?>
                        <a href="<?php echo base_url('invoice/add') ?>">
                            <i class="fa fa-plus"></i>
                            <?=$this->lang->line('add_title')?>
                        </a>
                    <?php } ?>

                    <?php if($this->session->userdata('usertypeID') != 3) { ?>
                        <div class="col-lg-2 col-sm-2 col-md-2 col-xs-12 pull-right drop-marg">
                            <?php
                                $array = array("0" => $this->lang->line("invoice_select_student"));
                                if($students) {
                                    foreach ($students as $student) {
                                        $array[$student->studentID] = $student->name;
                                    }
                                }
                                echo form_dropdown("studentID", $array, set_value("studentID", $set), "id='studentID' class='form-control select2'");
                            ?>
                        </div>
                    <?php } ?>
                </h5>

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover dataTable no-footer">
                        <thead>
                            <tr>
                                <th><?=$this->lang->line('slno')?></th>
                                <th><?=$this->lang->line('invoice_feetype')?></th>
                                <th><?=$this->lang->line('invoice_amount')?></th>
                                <th><?=$this->lang->line('invoice_due')?></th>
                                <th><?=$this->lang->line('invoice_status')?></th>
                                <th><?=$this->lang->line('invoice_date')?></th>
                                <?php if(permissionChecker('invoice_view') || permissionChecker('invoice_edit') || permissionChecker('invoice_delete')) { ?>
                                <th><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($invoices)) {$i = 1; foreach($invoices as $invoice) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('slno')?>">
                                        <?php echo $i; ?>
                                    </td>
                                    <td data-title="<?=$this->lang->line('invoice_feetype')?>">
                                        <?php echo $invoice->feetype; ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('invoice_amount')?>">
                                        <?php echo number_format($invoice->amount, 2); ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('invoice_due')?>">
                                        <?php echo number_format(($invoice->amount - $payments[$invoice->invoiceID]), 2); ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('invoice_status')?>">
                                        <?php 
                                            $status = $invoice->paidstatus;
                                            $setButton = '';
                                            if($status == 0) {
                                                $status = $this->lang->line('invoice_notpaid');
                                                $setButton = 'btn-danger';
                                            } elseif($status == 1) {
                                                $status = $this->lang->line('invoice_partially_paid');
                                                $setButton = 'btn-warning';
                                            } elseif($status == 2) {
                                                $status = $this->lang->line('invoice_fully_paid');
                                                $setButton = 'btn-success';
                                            }

                                            echo "<button class='btn ".$setButton." btn-xs'>".$status."</button>";
                                        ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('invoice_date')?>">
                                        <?php echo date("d M Y", strtotime($invoice->date)) ; ?>
                                    </td>

                                    <?php if(permissionChecker('invoice_view') || permissionChecker('invoice_edit') || permissionChecker('invoice_delete')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                         <?php echo btn_view('invoice/view/'.$invoice->invoiceID, $this->lang->line('view')) ?>
                                        <?php echo btn_edit('invoice/edit/'.$invoice->invoiceID, $this->lang->line('edit')) ?>
                                        <?php echo btn_delete('invoice/delete/'.$invoice->invoiceID, $this->lang->line('delete'))?>
                                        <?php if(permissionChecker('invoice_view')) { if($invoice->paidstatus != 2) { echo btn_invoice('invoice/payment/'.$invoice->invoiceID, $this->lang->line('payment')); }} ?>
                                    </td>
                                    <?php } ?>
                                </tr>
                            <?php $i++; }} ?>
                        </tbody>
                    </table>
                </div>

            </div> <!-- col-sm-12 -->
            
        </div><!-- row -->
    </div><!-- Body -->
</div><!-- /.box -->
<script type="text/javascript">
    $('.select2').select2();
    $('#studentID').change(function() {
        var studentID = $(this).val();
        if(studentID == 0) {
            $('#hide-table').hide();
        } else {
            $.ajax({
                type: 'POST',
                url: "<?=base_url('invoice/student_list')?>",
                data: "id=" + studentID,
                dataType: "html",
                success: function(data) {
                    window.location.href = data;
                }
            });
        }
    });
</script>