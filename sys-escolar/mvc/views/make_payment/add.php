

<div class="row">
    <div class="col-sm-4">
        
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-money"></i> <?=$this->lang->line('panel_title')?></h3>
            </div>

            <div class="box-body box-profile">
                <center>
                <?php $array = array(
                        "src" => base_url('uploads/images/'.$user->photo),
                        'width' => '100px',
                        'height' => '100px',
                        'class' => 'profile-user-img img-responsive img-circle',
                        'style' => 'border:6px solid #d2d6de'
                    );
                    echo img($array);
                ?>
                </center>

                <h3 class="profile-username text-center"><?=$user->name?></h3>

                <p class="text-muted text-center"><?=$usertype->usertype?></p>

                <ul class="list-group list-group-unbordered">
                    <li class="list-group-item" style="background-color: #FFF">
                        <b><?=$this->lang->line('make_payment_dob')?></b> <a class="pull-right"><?=date('d M Y', strtotime($user->dob))?></a>
                    </li>
                    <li class="list-group-item" style="background-color: #FFF">
                        <b><?=$this->lang->line('make_payment_jod')?></b> <a class="pull-right"><?=date('d M Y', strtotime($user->jod))?></a>
                    </li>
                    <li class="list-group-item" style="background-color: #FFF">
                        <b><?=$this->lang->line('make_payment_email')?></b> <a class="pull-right"><?=$user->email?></a>
                    </li>
                </ul>
            </div>

        </div>

        <div class="box" style="margin-bottom:40px">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-money"></i> <?=$this->lang->line('panel_title')?></h3>
            </div><!-- /.box-header -->
            <!-- form start -->
            <div class="box-body">
                <form role="form" method="post">
                    <div class="form-group <?=form_error('gross_salary') ? 'has-error' : '' ?>">
                        <label for="gross_salary"><?=$this->lang->line('make_payment_gross_salary')?></label>
                        <input type="text" name="gross_salary" class="form-control" id="gross_salary" value="<?=set_value('gross_salary', $grosssalary)?>" disabled>
                    </div>

                    <div class="form-group <?=form_error('total_deduction') ? 'has-error' : '' ?>">
                        <label for="total_deduction"><?=$this->lang->line('make_payment_total_deduction')?></label>
                        <input type="text" name="total_deduction" class="form-control" id="total_deduction" value="<?=set_value('total_deduction', $totaldeduction)?>" disabled>
                    </div>

                    <div class="form-group <?=form_error('net_salary') ? 'has-error' : '' ?>">
                        <label for="net_salary"><?=$this->lang->line('make_payment_net_salary')?></label>
                        <input type="text" name="net_salary" class="form-control" id="net_salary" value="<?=set_value('net_salary', $netsalary)?>" disabled>
                    </div>

                    <div class="form-group <?=form_error('month') ? 'has-error' : '' ?>">
                        <label for="month"><?=$this->lang->line('make_payment_month')?></label>
                        <input type="type" name="month" class="form-control" id="month" value="<?=set_value('month', date('m-Y'))?>">
                        <span class="text-red">
                            <?=form_error('month')?>
                        </span>
                    </div>

                    <?php if($manage_salary->salary == 2) { ?>
                        <div class="form-group <?=form_error('total_hours') ? 'has-error' : '' ?>">
                            <label for="total_hours"><?=$this->lang->line('make_payment_total_houres')?></label>
                            <input type="text" name="total_hours" class="form-control" id="total_hours" value="<?=set_value('total_hours')?>">
                            <span class="text-red">
                                <?=form_error('total_hours')?>
                            </span>
                        </div>
                    <?php } ?>

                    <div class="form-group <?=form_error('payment_amount') ? 'has-error' : '' ?>">
                        <label for="payment_amount"><?=$this->lang->line('make_payment_payment_amount')?></label> <?php if($manage_salary->salary == 2) { echo '<span id="hourdis"></span>'; } ?>
                        <input type="text" name="payment_amount" class="form-control" id="payment_amount" value="<?=set_value('payment_amount', $netsalary)?>">
                        <span class="text-red">
                            <?=form_error('payment_amount')?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('payment_method') ? 'has-error' : '' ?>">
                        <label for="payment_method"><?=$this->lang->line('make_payment_payment_method')?></label>
                        <?php
                            $paymentArray = array(
                                '0' => $this->lang->line('make_payment_select_payment_method'),
                                '1' => $this->lang->line('make_payment_payment_cash'),
                                '2' => $this->lang->line('make_payment_payment_cheque'),
                            );

                            echo form_dropdown("payment_method", $paymentArray, set_value("payment_method"), "id='payment_method' class='form-control'");
                        ?>
                        <span class="text-red">
                            <?=form_error('payment_method')?>
                        </span>
                    </div>

                    <div class="form-group <?=form_error('comments') ? 'has-error' : '' ?>">
                        <label for="comments"><?=$this->lang->line('make_payment_comments')?></label>
                        <input type="text" name="comments" class="form-control" id="comments">
                    </div>

                    <button type="submit" class="btn btn-success"><?=$this->lang->line('add_payment')?></button>
                </form>
            </div>
        </div>

    </div>

    <div class="col-sm-8">
        
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa icon-payment"></i> <?=$this->lang->line('make_payment_payment_history')?></h3>
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("make_payment/index/$usertype->usertypeID")?>"><?=$this->lang->line('menu_make_payment')?></a></li>
                    <li class="active"><?=$this->lang->line('menu_add')?> <?=$this->lang->line('menu_make_payment')?></li>
                </ol>
            </div><!-- /.box-header -->
            <div class="box-body">

                <div id="hide-table">
                    <table id="example1" class="table table-striped table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="col-sm-2"><?=$this->lang->line('make_payment_month')?></th>
                                <th class="col-sm-2"><?=$this->lang->line('make_payment_date')?></th>
                                <th class="col-sm-3"><?php if($manage_salary->salary == 2) { echo $this->lang->line('make_payment_net_salary_hourly'); } else { echo $this->lang->line('make_payment_net_salary'); } ?></th>
                                <th class="col-sm-3"><?=$this->lang->line('make_payment_payment_amount')?></th>
                                <?php if(permissionChecker('make_payment')) { ?>
                                    <th class="col-sm-2"><?=$this->lang->line('action')?></th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if(count($make_payments)) { $i = 1; foreach($make_payments as $make_payment) { ?>
                                <tr>
                                    <td data-title="<?=$this->lang->line('make_payment_month')?>">
                                        <?php echo date("M Y", strtotime('1-'.$make_payment->month)); ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('make_payment_date')?>">
                                        <?php echo date("d M Y", strtotime($make_payment->create_date)); ?>
                                    </td>

                                    <td data-title="<?php if($manage_salary->salary == 2) { echo $this->lang->line('make_payment_net_salary_hourly'); } else { echo $this->lang->line('make_payment_net_salary'); } ?>">
                                        <?php
                                            if(isset($make_payment->total_hours)) {
                                                echo '('.$make_payment->total_hours. 'X' . $make_payment->net_salary .') = '. ($make_payment->total_hours * $make_payment->net_salary); 
                                            } else {
                                                echo $make_payment->net_salary; 
                                            }
                                        ?>
                                    </td>

                                    <td data-title="<?=$this->lang->line('make_payment_amount')?>">
                                        <?php echo $make_payment->payment_amount; ?>
                                    </td>

                                    <?php if(permissionChecker('make_payment')) { ?>
                                    <td data-title="<?=$this->lang->line('action')?>">
                                        <a href="<?=base_url("make_payment/view/$make_payment->make_paymentID")?>" class="btn btn-xs btn-success" data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('view')?>"><i class='fa fa-check-square-o'></i></a>

                                        <a href="<?=base_url("make_payment/delete/$make_payment->make_paymentID")?>" class="btn btn-xs btn-danger" data-placement="top" data-toggle="tooltip" data-original-title="<?=$this->lang->line('delete')?>"><i class='fa fa fa-trash-o'></i></a>
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

<script type="text/javascript">
    $("#month").datepicker( {
        format: "mm-yyyy",
        startView: "months", 
        minViewMode: "months"
    });

    jQuery("#total_hours").focus(function(){   

    })
    .blur(function(){
        var net_salary = "<?=$netsalary?>";
        var total_hours = $(this).val();

        if(parseFloat(total_hours)) {
            $('#hourdis').html('('+total_hours+' X '+net_salary+')').addClass('text-red');
            $('#payment_amount').val(parseFloat(total_hours*net_salary));
        }
    });

    $(document).ready(function() {
        var net_salary = "<?=$netsalary?>";
        var total_hours = $('#total_hours').val();

        if(total_hours != '' || total_hours != null) {
            if(parseFloat(total_hours)) {
                $('#hourdis').html('('+total_hours+' X '+net_salary+')').addClass('text-red');
            }
        }
    });
</script>





