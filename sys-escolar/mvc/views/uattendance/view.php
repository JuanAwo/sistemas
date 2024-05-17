

    <div class="well">
        <div class="row">
            <div class="col-sm-6">
                <button class="btn-cs btn-sm-cs" onclick="javascript:printDiv('printablediv')"><span class="fa fa-print"></span> <?=$this->lang->line('print')?> </button>
                <?php
                 echo btn_add_pdf('uattendance/print_preview/'.$user->userID, $this->lang->line('pdf_preview')) 
                ?>
                <button class="btn-cs btn-sm-cs" data-toggle="modal" data-target="#mail"><span class="fa fa-envelope-o"></span> <?=$this->lang->line('mail')?></button>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li><a href="<?=base_url("dashboard/index")?>"><i class="fa fa-laptop"></i> <?=$this->lang->line('menu_dashboard')?></a></li>
                    <li><a href="<?=base_url("uattendance/index")?>"><?=$this->lang->line('menu_uattendance')?></a></li>
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
                <p><?=isset($usertypes[$user->usertypeID]) ? $usertypes[$user->usertypeID] : '' ?></p>

            </div>
            <div class="panel-body profile-view-dis">
                <h1><?=$this->lang->line("personal_information")?></h1>
                <div class="row">
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("uattendance_dob")?> </span>: <?=date("d M Y", strtotime($user->dob))?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("uattendance_jod")?> </span>: <?=date("d M Y", strtotime($user->jod))?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("uattendance_sex")?> </span>: <?=$user->sex?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("uattendance_email")?> </span>: <?=$user->email?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("uattendance_phone")?> </span>: <?=$user->phone?></p>
                    </div>
                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("uattendance_address")?> </span>: <?=$user->address?></p>
                    </div>

                    <div class="profile-view-tab">
                        <p><span><?=$this->lang->line("uattendance_username")?> </span>: <?=$user->username?></p>
                    </div>

                </div>

                <h1><?=$this->lang->line("uattendance_information")?></h1>

                <div class="row">
                    <div class="col-sm-12">
                        <div id="hide-table">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <?php
                                            for($i=1; $i<=31; $i++){
                                               echo  "<th>".$this->lang->line('uattendance_'.$i)."</th>";
                                            }
                                        ?>
                                    </tr>
                                </thead>
                                <?php 
                                    $year = date("Y");
                                    if($uattendances) { 
                                        foreach ($uattendances as $key => $uattendance) {
                                            $monthyear_ex = explode('-', $uattendance->monthyear);
                                            if($monthyear_ex[0] === '01' && $monthyear_ex[1] == $year ) {
                                ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_jan')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '02' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_feb')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '03' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_mar')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '04' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_apr')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '05' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_may')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '06' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_june')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '07' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_jul')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '08' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_aug')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '09' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_sep')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '10' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_oct')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '11' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_nov')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php } elseif($monthyear_ex[0] === '12' && $monthyear_ex[1] == $year) { ?>
                                    <tr>
                                        <th><?=$this->lang->line('uattendance_dec')?></th>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_1')?>' ><?=$uattendance->a1?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_2')?>' ><?=$uattendance->a2?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_3')?>' ><?=$uattendance->a3?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_4')?>' ><?=$uattendance->a4?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_5')?>' ><?=$uattendance->a5?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_6')?>' ><?=$uattendance->a6?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_7')?>' ><?=$uattendance->a7?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_8')?>' ><?=$uattendance->a8?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_9')?>' ><?=$uattendance->a9?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_10')?>' ><?=$uattendance->a10?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_11')?>' ><?=$uattendance->a11?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_12')?>' ><?=$uattendance->a12?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_13')?>' ><?=$uattendance->a13?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_14')?>' ><?=$uattendance->a14?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_15')?>' ><?=$uattendance->a15?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_16')?>' ><?=$uattendance->a16?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_17')?>' ><?=$uattendance->a17?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_18')?>' ><?=$uattendance->a18?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_19')?>' ><?=$uattendance->a19?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_20')?>' ><?=$uattendance->a20?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_21')?>' ><?=$uattendance->a21?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_22')?>' ><?=$uattendance->a22?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_23')?>' ><?=$uattendance->a23?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_24')?>' ><?=$uattendance->a24?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_25')?>' ><?=$uattendance->a25?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_26')?>' ><?=$uattendance->a26?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_27')?>' ><?=$uattendance->a27?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_28')?>' ><?=$uattendance->a28?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_29')?>' ><?=$uattendance->a29?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_30')?>' ><?=$uattendance->a30?></td>
                                        <td class="att-bg-color" data-title='<?=$this->lang->line('uattendance_31')?>' ><?=$uattendance->a31?></td>
                                    </tr>
                                <?php
                                            }
                                        }
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </div>
    <!-- inicio ventana modal -->
    <form class="form-horizontal" role="form" action="<?=base_url('user/send_mail');?>" method="post">
        <div class="modal fade" id="mail">
          <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?=$this->lang->line('mail')?></h4>
                </div>
                <div class="modal-body">
                
                    <?php 
                        if(form_error('to')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="to" class="col-sm-2 control-label">
                            <?=$this->lang->line("to")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="email" class="form-control" id="to" name="to" value="<?=set_value('to')?>" >
                        </div>
                        <span class="col-sm-4 control-label" id="to_error">
                        </span>
                    </div>

                    <?php 
                        if(form_error('subject')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="subject" class="col-sm-2 control-label">
                            <?=$this->lang->line("subject")?>
                        </label>
                        <div class="col-sm-6">
                            <input type="text" class="form-control" id="subject" name="subject" value="<?=set_value('subject')?>" >
                        </div>
                        <span class="col-sm-4 control-label" id="subject_error">
                        </span>

                    </div>

                    <?php 
                        if(form_error('message')) 
                            echo "<div class='form-group has-error' >";
                        else     
                            echo "<div class='form-group' >";
                    ?>
                        <label for="message" class="col-sm-2 control-label">
                            <?=$this->lang->line("message")?>
                        </label>
                        <div class="col-sm-6">
                            <textarea class="form-control" id="message" style="resize: vertical;" name="message" value="<?=set_value('message')?>" ></textarea>
                        </div>
                    </div>

                
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" style="margin-bottom:0px;" data-dismiss="modal"><?=$this->lang->line('close')?></button>
                    <input type="button" id="send_pdf" class="btn btn-success" value="<?=$this->lang->line("send")?>" />
                </div>
            </div>
          </div>
        </div>
    </form>
    <!-- fin de ventana modal-->    

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

        function check_email(email) {
            var status = false;     
            var emailRegEx = /^[A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4}$/i;
            if (email.search(emailRegEx) == -1) {
                $("#to_error").html('');
                $("#to_error").html("<?=$this->lang->line('mail_valid')?>").css("text-align", "left").css("color", 'red');
            } else {
                status = true;
            }
            return status;
        }


        $("#send_pdf").click(function(){
            var to = $('#to').val();
            var subject = $('#subject').val();
            var message = $('#message').val();
            var id = "<?=$user->userID;?>";
            var error = 0;

            if(to == "" || to == null) {
                error++;
                $("#to_error").html("");
                $("#to_error").html("<?=$this->lang->line('mail_to')?>").css("text-align", "left").css("color", 'red');
            } else {
                if(check_email(to) == false) {
                    error++
                }
            } 

            if(subject == "" || subject == null) {
                error++;
                $("#subject_error").html("");
                $("#subject_error").html("<?=$this->lang->line('mail_subject')?>").css("text-align", "left").css("color", 'red');
            } else {
                $("#subject_error").html("");
            }

            if(error == 0) {
                $.ajax({
                    type: 'POST',
                    url: "<?=base_url('uattendance/send_mail')?>",
                    data: 'to='+ to + '&subject=' + subject + "&id=" + id+ "&message=" + message,
                    dataType: "html",
                    success: function(data) {
                        location.reload();
                    }
                });
            }
        });
    </script>

