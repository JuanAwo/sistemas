
<div class="well">
    <div class="row">
        <div class="col-sm-6">
            <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                <li><a href="<?=base_url("make_payment/add/$userID/$usertypeID")?>"><?=$this->lang->line('menu_manage_salary')?></a></li>
                <li class="active"><?=$this->lang->line('view')?></li>
            </ol>
        </div>
    </div>
</div>

<div id="printablediv">
    <section class="panel">

        <div class="profile-view-head">
            <a href="#">
                <?=img(base_url('uploads/images/'.$user->photo))?>
            </a>

            <h1><?=$user->name?></h1>
            <p><?=$usertype->usertype?></p>
        </div>

        <div class="panel-body bio-graph-info">
            <div id="printablediv" class="box-body">
                <div class="row">
                    <div class="col-sm-12">

                            <div class="row">
                                <div class="col-sm-6" style="margin-bottom: 10px;">
                                    <div class="info-box">

                                        <?php if(isset($make_payment->total_hours)) { ?>
                                            <p style="margin:0 0 20px">
                                                <span><?=$this->lang->line("make_payment_salary_grades")?></span>
                                                <?=$persent_salary_template->hourly_grades?>
                                            </p>
                                            <p style="margin:0 0 20px">
                                                <span><?=$this->lang->line("make_payment_hourly_rate")?></span>
                                                <?=number_format($persent_salary_template->hourly_rate, 2)?>
                                            </p>
                                        <?php } else { ?>
                                     
                                            <?php if($make_payment->salaryID == 1) { ?>
                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("make_payment_salary_grades")?></span>
                                                    <?=$persent_salary_template->salary_grades?>
                                                </p>
                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("make_payment_basic_salary")?></span>
                                                    <?=number_format($persent_salary_template->basic_salary, 2)?>
                                                </p>
                                                <p style="margin:0 0 20px">
                                                    <span><?=$this->lang->line("make_payment_overtime_rate")?></span>
                                                    <?=number_format($persent_salary_template->overtime_rate, 2)?>
                                                </p>
                                            <?php } ?>
                                            

                                        <?php } ?>

                                        <p style="margin:0 0 20px">
                                            <span><?=$this->lang->line("make_payment_month")?></span>
                                            <?=date('M Y', strtotime('1-'.$make_payment->month))?>
                                        </p>

                                        <p style="margin:0 0 20px">
                                            <span><?=$this->lang->line("make_payment_date")?></span>
                                            <?=date('d M Y', strtotime($make_payment->create_date))?>
                                        </p>

                                        <p style="margin:0 0 20px">
                                            <span><?=$this->lang->line("make_payment_payment_method")?></span>
                                            <?=isset($paymentMethod[$make_payment->payment_method]) ? $paymentMethod[$make_payment->payment_method] : ''?>
                                        </p>

                                        <p style="margin:0 0 20px">
                                            <span><?=$this->lang->line("make_payment_comments")?></span>
                                            <?=$make_payment->comments?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <?php if($make_payment->salaryID == 1) { ?>
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="box" style="border: 1px solid #eee">
                                        <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                            <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('make_payment_allowances')?></h3>
                                        </div>
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-sm-12" id="allowances">
                                                    <div class="info-box">
                                                        <?php 
                                                            if(count($salaryoptions)) { 
                                                                foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                    if($salaryoption->option_type == 1) {
                                                        ?>
                                                            <p>
                                                                <span><?=$salaryoption->label_name?></span>
                                                                <?=number_format($salaryoption->label_amount, 2)?>
                                                            </p>
                                                        <?php        
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="box" style="border: 1px solid #eee;">
                                        <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                            <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('make_payment_deductions')?></h3>
                                        </div><!-- /.box-header -->
                                        <!-- form start -->
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-sm-12" id="deductions">
                                                    <div class="info-box">
                                                        <?php 
                                                            if(count($salaryoptions)) { 
                                                                foreach ($salaryoptions as $salaryoptionkey => $salaryoption) {
                                                                    if($salaryoption->option_type == 2) {
                                                        ?>
                                                            <p>
                                                                <span><?=$salaryoption->label_name?></span>
                                                                <?=number_format($salaryoption->label_amount, 2)?>
                                                            </p>
                                                        <?php        
                                                                    }
                                                                }
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>

                            <div class="row">
                                <div class="col-sm-8 col-sm-offset-4">
                                    <div class="box" style="border: 1px solid #eee;">
                                        <div class="box-header" style="background-color: #fff;border-bottom: 1px solid #eee;color: #000;">
                                            <h3 class="box-title" style="color: #1a2229"><?=$this->lang->line('make_payment_total_salary_details')?></h3>
                                        </div>
                                        <div class="box-body">
                                            <table class="table table-bordered">
                                                <tr>
                                                    <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_gross_salary')?></td>

                                                    <td class="col-sm-4" style="line-height: 36px"><?=number_format($make_payment->gross_salary, 2)?></td>
                                                </tr>

                                                <tr>
                                                    <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_total_deduction')?></td>

                                                    <td class="col-sm-4" style="line-height: 36px"><?=number_format($make_payment->total_deduction, 2)?></td>
                                                </tr>

                                                <?php 
                                                    if(isset($make_payment->total_hours)) {
                                                       $net_hourly_rate = ($make_payment->total_hours * $make_payment->net_salary);
                                                       $net_hourly_rate_grp = '('.$make_payment->total_hours. 'X' . $make_payment->net_salary .')';
                                                ?>
                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_total_houres')?></td>

                                                        <td class="col-sm-4" style="line-height: 36px">
                                                        <?=number_format($make_payment->total_hours, 2)?></td>
                                                    </tr>
                                                <?php } ?>

                                                <?php if(isset($make_payment->total_hours)) { ?>
                                                <tr>
                                                    <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_net_hourly_rate')?> <span class="text-red"><?=$net_hourly_rate_grp?></span></td>

                                                    <td class="col-sm-4" style="line-height: 36px"><b>
                                                    <?=number_format($net_hourly_rate, 2)?></b></td>
                                                </tr>
                                                <?php } else { ?>
                                                    <tr>
                                                        <td class="col-sm-8" style="line-height: 36px"><?=$this->lang->line('make_payment_net_salary')?></td>

                                                        <td class="col-sm-4" style="line-height: 36px"><b><?=number_format($make_payment->net_salary, 2)?></b></td>
                                                    </tr>
                                                <?php  } ?>

                                                <tr>
                                                    <td class="col-sm-8" style="line-height: 36px"><b><?=$this->lang->line('make_payment_payment_amount')?></b></td>

                                                    <td class="col-sm-4" style="line-height: 36px"><b><?=number_format($make_payment->payment_amount, 2)?></b></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>




<script language="javascript" type="text/javascript">
    function printDiv(divID) {
        //Get the HTML of div
        var divElements = document.getElementById(divID).innerHTML;
        //Get the HTML of whole page
        var oldPage = document.body.innerHTML;

        //Reset the page's HTML with div's HTML only
        document.body.innerHTML =
          "<html><head><title></title></head><body>" +
          divElements + "</body>";

        //Print Page
        window.print();

        //Restore orignal HTML
        document.body.innerHTML = oldPage;
    }
    function closeWindow() {
        location.reload();
    }
</script>

