<!DOCTYPE html>
<html lang="es">
<head>
<title><?php echo $panel_title; ?></title>

<style type="text/css">
    #page-wrap {
        width: 1000px;
        margin: 0 auto;
    }
    .center-justified {
        text-align: justify;
        margin: 0 auto;
        width: 30em;
    }
    /*ini starts here*/
    .list-group {
      padding-left: 0;
      margin-bottom: 15px;
      width: auto;
    }
    .list-group-item {
      position: relative;
      display: block;
      padding: 7.5px 10px;
      margin-bottom: -1px;
      background-color: #fff;
      border: 1px solid #ddd;
      /*margin: 2px;*/
    }
    table {
      border-spacing: 0;
      border-collapse: collapse;
      font-size: 12px;
    }
    td,
    th {
      padding: 0;
    }
    @media print {
      * {
        color: #000 !important;
        text-shadow: none !important;
        background: transparent !important;
        box-shadow: none !important;
      }
      a,
      a:visited {
        text-decoration: underline;
      }
      a[href]:after {
        content: " (" attr(href) ")";
      }
      abbr[title]:after {
        content: " (" attr(title) ")";
      }
      a[href^="javascript:"]:after,
      a[href^="#"]:after {
        content: "";
      }
      pre,
      blockquote {
        border: 1px solid #999;

        page-break-inside: avoid;
      }
      thead {
        display: table-header-group;
      }
      tr,
      img {
        page-break-inside: avoid;
      }
      img {
        max-width: 100% !important;
      }
      p,
      h2,
      h3 {
        orphans: 3;
        widows: 3;
      }
      h2,
      h3 {
        page-break-after: avoid;
      }
      select {
        background: #fff !important;
      }
      .navbar {
        display: none;
      }
      .table td,
      .table th {
        background-color: #fff !important;
      }
      .btn > .caret,
      .dropup > .btn > .caret {
        border-top-color: #000 !important;
      }
      .label {
        border: 1px solid #000;
      }
      .table {
        border-collapse: collapse !important;
      }
      .table-bordered th,
      .table-bordered td {
        border: 1px solid #ddd !important;


      }
    }
    table {
      max-width: 100%;
      background-color: transparent;
      font-size: 12px;
    }
    th {
      text-align: left;
    }
    /*td {
      text-align: center;
      background-color: red;
    }*/
    .table {
      width: 100%;
      margin-bottom: 20px;
    }
    .table h4 {
      font-size: 15px;
      padding: 0px;
      margin: 0px;
    }
    .head {
       border-top: 0px solid #e2e7eb;
       border-bottom: 0px solid #e2e7eb;
    }
    .table > thead > tr > th,
    .table > tbody > tr > th,
    .table > tfoot > tr > th,
    .table > thead > tr > td,
    .table > tbody > tr > td,
    .table > tfoot > tr > td {
      padding: 8px;
      line-height: 1.428571429;
      vertical-align: top;
      text-align: left;
      /*border-top: 1px solid #e2e7eb; */
    }
    /*ini edit default value : border top 1px to 0 px*/
    .table > thead > tr > th {
      font-size: 15px;
      font-weight: 500;
      vertical-align: bottom;
      /*border-bottom: 2px solid #e2e7eb;*/
      color: #242a30;


    }

    .table > caption + thead > tr:first-child > th,
    .table > colgroup + thead > tr:first-child > th,
    .table > thead:first-child > tr:first-child > th,
    .table > caption + thead > tr:first-child > td,
    .table > colgroup + thead > tr:first-child > td,
    .table > thead:first-child > tr:first-child > td {
      border-top: 0;
    }
    .table > tbody + tbody {
      border-top: 2px solid #e2e7eb;
    }
    .table .table {
      background-color: #fff;
    }
    .table-condensed > thead > tr > th,
    .table-condensed > tbody > tr > th,
    .table-condensed > tfoot > tr > th,
    .table-condensed > thead > tr > td,
    .table-condensed > tbody > tr > td,
    .table-condensed > tfoot > tr > td {
      padding: 5px;
    }
    .table-bordered {
      border: 1px solid #e2e7eb;
      text-align: center;
    }
    .table-bordered > thead > tr > th,
    .table-bordered > tbody > tr > th,
    .table-bordered > tfoot > tr > th,
    .table-bordered > thead > tr > td,
    .table-bordered > tbody > tr > td,
    .table-bordered > tfoot > tr > td {
      border: 1px solid #e2e7eb;
    }
    .table-bordered > thead > tr > th,
    .table-bordered > thead > tr > td {
      border-bottom-width: 2px;
    }
    .table-striped > tbody > tr:nth-child(odd) > td,
    .table-striped > tbody > tr:nth-child(odd) > th {
      background-color: #f0f3f5;
    }
    .panel-title {
      margin-top: 0;
      margin-bottom: 0;
      font-size: 20px;
      color: #fff;
      padding: 0;
    }
    .panel-title > a {
      color: #707478;
      text-decoration: none;
    }
    a {
      background: transparent;
      color: #707478;
      text-decoration: none;
    }
    strong {
        color: #707478;
    }
</style>
</head>
  <body>
    <div id="page-wrap">
      <table width="100%" style="text-align:center">
        <tr>
          <td>
            <h2>
              <?php
                if($siteinfos->photo) {
                    $array = array(
                        "src" => base_url('uploads/images/'.$siteinfos->photo),
                        'width' => '50px',
                        'height' => '50px',
                        "style" => "margin-right:0px;"
                    );
                    echo img($array)."<br>";
                }
                echo $siteinfos->sname;
                ?>
            </h2>
          </td>
        </tr>
      </table>
      <table width="100%">
        <tbody>
          <tr>
            <td width="8%">
              <?php
                if(count($user)) {
                    $array = array(
                        "src" => base_url('uploads/images/'.$user->photo),
                        'width' => '80px',
                        'height' => '80px',
                        "style" => "margin-bottom:5px; border: 2px solid #707478;"
                    );
                    echo img($array);
                }
              ?>
            </td>
            <td width="80%">
              <table>
                <tr>
                  <td>
                      <h3 style="margin:0px;"> <strong><?php  echo $user->name; ?></strong></h3>
                  </td>
                </tr>
                <tr>
                  <td>
                    <h5 style="margin:0px;"> <strong><?=isset($usertypes[$user->usertypeID]) ? $usertypes[$user->usertypeID] : '' ?></strong>
                    </h5>
                  </td>
                </tr>
                <tr>
                  <td>
                    <h5 style="margin:0px;"> <strong><?php  echo $this->lang->line("uattendance_email")." ".$user->email; ?></strong>
                    </h5>
                  </td>
                </tr>
              </table>
            </td>
          </tr>
          <tr>
              <td colspan="2">&nbsp;</td>
          </tr>
        </tbody>
      </table>

      <table class="table table-bordered">
        <tbody>
          <tr>
            <th colspan="2"><h4><?=$this->lang->line("personal_information")?></h4></th>
          </tr>

          <tr>
              <th width="40%"><?=$this->lang->line("uattendance_dob")?></th>
              <td width="60%"><?php  echo $user->dob; ?></td>
          </tr>

          <tr>
              <th width="40%"><?=$this->lang->line("uattendance_sex")?></th>
              <td width="60%"><?php  echo $user->sex; ?></td>
          </tr>
          <tr>
              <th width="40%"><?=$this->lang->line("uattendance_email")?></th>
              <td width="60%"><?php  echo $user->email; ?></td>
          </tr>
          <tr>
              <th width="40%"><?=$this->lang->line("uattendance_phone")?></th>
              <td width="60%"><?php  echo $user->phone; ?></td>
          </tr>
          <tr>
              <th width="40%"><?=$this->lang->line("uattendance_address")?></th>
              <td width="60%"><?php  echo $user->address; ?></td>
          </tr>
        </tbody>
      </table>

      <table class="table table-striped table-bordered">
        <thead>
            <tr>
              <td colspan="32"><h4><?=$this->lang->line("uattendance_information")?></h4></td>
            </tr>
            <tr>
                <th>#</th>
                <th><?=$this->lang->line('uattendance_1')?></th>
                <th><?=$this->lang->line('uattendance_2')?></th>
                <th><?=$this->lang->line('uattendance_3')?></th>
                <th><?=$this->lang->line('uattendance_4')?></th>
                <th><?=$this->lang->line('uattendance_5')?></th>
                <th><?=$this->lang->line('uattendance_6')?></th>
                <th><?=$this->lang->line('uattendance_7')?></th>
                <th><?=$this->lang->line('uattendance_8')?></th>
                <th><?=$this->lang->line('uattendance_9')?></th>
                <th><?=$this->lang->line('uattendance_10')?></th>
                <th><?=$this->lang->line('uattendance_11')?></th>
                <th><?=$this->lang->line('uattendance_12')?></th>
                <th><?=$this->lang->line('uattendance_13')?></th>
                <th><?=$this->lang->line('uattendance_14')?></th>
                <th><?=$this->lang->line('uattendance_15')?></th>
                <th><?=$this->lang->line('uattendance_16')?></th>
                <th><?=$this->lang->line('uattendance_17')?></th>
                <th><?=$this->lang->line('uattendance_18')?></th>
                <th><?=$this->lang->line('uattendance_19')?></th>
                <th><?=$this->lang->line('uattendance_20')?></th>
                <th><?=$this->lang->line('uattendance_21')?></th>
                <th><?=$this->lang->line('uattendance_22')?></th>
                <th><?=$this->lang->line('uattendance_23')?></th>
                <th><?=$this->lang->line('uattendance_24')?></th>
                <th><?=$this->lang->line('uattendance_25')?></th>
                <th><?=$this->lang->line('uattendance_26')?></th>
                <th><?=$this->lang->line('uattendance_27')?></th>
                <th><?=$this->lang->line('uattendance_28')?></th>
                <th><?=$this->lang->line('uattendance_29')?></th>
                <th><?=$this->lang->line('uattendance_30')?></th>
                <th><?=$this->lang->line('uattendance_31')?></th>
            </tr>
        </thead>
        <tbody>
        <?php
            $year = date("Y");
            if($uattendances) {

                foreach ($uattendances as $key => $uattendance) {
                    $monthyear_ex = explode('-', $uattendance->monthyear);

                    if($monthyear_ex[0] === '01' && $monthyear_ex[1] == $year ){

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
        <?php break; } elseif($monthyear_ex[0] === '02' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '03' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '04' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '05' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '06' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '07' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '08' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '09' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '10' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '11' && $monthyear_ex[1] == $year) { ?>
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
        <?php break; } elseif($monthyear_ex[0] === '12' && $monthyear_ex[1] == $year) { ?>
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
              break; }
                }
            }
        ?>
        </tbody>
    </table>

    </div>
  </body>
</html>
