<!DOCTYPE html>
<html lang="es">
<head>
<title><?php echo $panel_title; ?></title>

<style type="text/css">
    #page-wrap {
        width: 700px;
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
      font-family: Arial;
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
      .table h4 {
        font-size: 15px;
        padding: 0px;
        margin: 0px;
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
            <td  width="12%">
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
                    <h3 style="margin:0px;"> <strong><?php  echo $user->dni; ?></strong></h3>
                  </td>
                </tr>
                <tr>
                  <td>
                      <h5 style="margin:0px;"> <strong><?=$user->usertype?> </strong>
                      </h5>
                  </td>
                </tr>
                <tr>
                  <td>
                    <h5 style="margin:0px;"> <strong><?php  echo $this->lang->line("user_email")." : ".$user->email; ?></strong>
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
              <th width="40%"><?=$this->lang->line("user_dni")?></th>
              <td width="60%"><?php  echo $user->dni; ?></td>
          </tr>
          <tr>
            <th width="40%"><?=$this->lang->line("user_dob")?></th>
            <td width="60%"><?php  echo date("d M Y", strtotime($user->dob)); ?></td>
          </tr>
          <tr>
            <th width="40%"><?=$this->lang->line("user_jod")?></th>
            <td width="60%"><?php  echo date("d M Y", strtotime($user->jod)); ?></td>
          </tr>
          <tr>
            <th width="40%"><?=$this->lang->line("user_sex")?></th>
            <td width="60%"><?php  echo $user->sex; ?></td>
          </tr>
          <tr>
            <th width="40%"><?=$this->lang->line("user_phone")?></th>
            <td width="60%"><?php  echo $user->phone; ?></td>
          </tr>
          <tr>
            <th width="40%"><?=$this->lang->line("user_address")?></th>
            <td width="60%"><?php  echo $user->address; ?></td>
          </tr>
          <tr>
            <th width="40%"><?=$this->lang->line("user_username")?></th>
            <td width="60%"><?php  echo $user->username; ?></td>
          </tr>
        </tbody>
      </table>

    </div>
  </body>
</html>
