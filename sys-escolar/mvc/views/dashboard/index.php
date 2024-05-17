<div class="row">
<?php if(config_item('demo')) { ?>
    <div class="col-sm-12" id="resetDummyData">
        <div class="callout callout-danger">
            <h4>Reminder!</h4>
            <p>Dummy data will be reset in every <code>30</code> minutes</p>
        </div>
    </div>

    <script type="text/javascript"> 
      $(document).ready(function() {
          var count = 7;
          var countdown = setInterval(function(){
            $("p.countdown").html(count + " seconds remaining!");
            if (count == 0) {
              clearInterval(countdown);
              $('#resetDummyData').hide();
            }
            count--;
          }, 1000);
      });
    </script>

<?php } ?>
<?php
$arrayColor = array(
    'bg-orange-dark',
    'bg-teal-light',
    'bg-pink-light',
    'bg-purple-light'
);

function allModuleArray($usertypeID='1', $dashboardWidget) {
  $userAllModuleArray = array(
    $usertypeID => array(
        'student'   => $dashboardWidget['students'],
        'classes'   => $dashboardWidget['classes'],
        'teacher'   => $dashboardWidget['teachers'],
        'parents'   => $dashboardWidget['parents'],
        'subject'   => $dashboardWidget['subjects'],
        'book'     => $dashboardWidget['books'],
        'feetypes'  => $dashboardWidget['feetypes'],
        'lmember'   => $dashboardWidget['lmembers'],
        'event'     => $dashboardWidget['events'],
        'issue'     => $dashboardWidget['issues'],
        'holiday'   => $dashboardWidget['holidays'],
        'invoice'   => $dashboardWidget['invoices'],
    )
  );
  return $userAllModuleArray;
}

$userArray = array(
  '1' => array(
      'student'   => $dashboardWidget['students'],
      'teacher'   => $dashboardWidget['teachers'],
      'parents'   => $dashboardWidget['parents'],
      'subject'   => $dashboardWidget['subjects']
  ),
  '2' => array(
      'student'   => $dashboardWidget['students'],
      'teacher'   => $dashboardWidget['teachers'],
      'classes'   => $dashboardWidget['classes'],
      'subject'   => $dashboardWidget['subjects'],
  ),
  '3' => array(
      'teacher'   => $dashboardWidget['teachers'],
      'subject'   => $dashboardWidget['subjects'],
      'issue'     => $dashboardWidget['issues'],
      'invoice'   => $dashboardWidget['invoices'],
  ),
  '4' => array(
      'teacher'   => $dashboardWidget['teachers'],
      'book'     => $dashboardWidget['books'],
      'event'     => $dashboardWidget['events'],
      'holiday'   => $dashboardWidget['holidays'],
  ),
  '5' => array(
      'teacher'   => $dashboardWidget['teachers'],
      'parents'   => $dashboardWidget['parents'],
      'feetypes'  => $dashboardWidget['feetypes'],
      'invoice'   => $dashboardWidget['invoices'],
  ),
  '6' => array(
      'teacher'   => $dashboardWidget['teachers'],
      'lmember'   => $dashboardWidget['lmembers'],
      'book'      => $dashboardWidget['books'],
      'issue'     => $dashboardWidget['issues'],
  ),
  '7' => array(
      'teacher'       => $dashboardWidget['teachers'],
      'event'         => $dashboardWidget['events'],
      'holiday'       => $dashboardWidget['holidays'],
      //'visitorinfo'  => $dashboardWidget['visitors'],
  ),

);

$generateBoxArray = array();
$counter = 0;
$getActiveUserID = $this->session->userdata('usertypeID');
$getAllSessionDatas = $this->session->userdata('master_permission_set');
foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
    if($getAllSessionData == 'yes') {
        if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
            if($counter == 4) {
              break;
            }

            $generateBoxArray[$getAllSessionDataKey] = array(
                'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                'color' => $arrayColor[$counter],
                'link' => $getAllSessionDataKey,
                'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey],
            );
            $counter++;
        }

    }
}


$icon = '';
$menu = '';
if($counter < 4) {
  $userArray = allModuleArray($getActiveUserID, $dashboardWidget);
  foreach ($getAllSessionDatas as $getAllSessionDataKey => $getAllSessionData) {
      if($getAllSessionData == 'yes') {
          if(isset($userArray[$getActiveUserID][$getAllSessionDataKey])) {
              if($counter == 4) {
                break;
              }

              if(!isset($generateBoxArray[$getAllSessionDataKey])) {
                  $generateBoxArray[$getAllSessionDataKey] = array(
                      'icon' => $dashboardWidget['allmenu'][$getAllSessionDataKey],
                      'color' => $arrayColor[$counter],
                      'link' => $getAllSessionDataKey,
                      'count' => $userArray[$getActiveUserID][$getAllSessionDataKey],
                      'menu' => $dashboardWidget['allmenulang'][$getAllSessionDataKey]
                  );
                  $counter++;
              }
          }

      }
  }
}

if(count($generateBoxArray)) {
    foreach ($generateBoxArray as $generateBoxArrayKey => $generateBoxValue) {
?>
  <div class="col-lg-3 col-xs-6">
      <div class="small-box ">
          <a class="small-box-footer <?=$generateBoxValue['color']?>" href="<?=base_url($generateBoxValue['link'])?>">
              <div class="icon <?=$generateBoxValue['color']?>" style="padding: 9.5px 18px 8px 18px;">
                  <i class="fa <?=$generateBoxValue['icon']?>"></i>
              </div>
              <div class="inner ">
                  <h3 class="text-white">
                      <?=$generateBoxValue['count']?>
                  </h3 class="text-white">
                  <p class="text-white">
                      <?=$this->lang->line('menu_'.$generateBoxValue['menu'])?>
                  </p>
              </div>
          </a>
      </div>
  </div>

<?php
    }
}

?>
</div>




<!-- <php if($getActiveUserID == 1 || $getActiveUserID == 5) { ?> -->
<!-- <div class="row">
    <div class="col-sm-4">
        <?php $this->load->view('dashboard/ProfileBox'); ?>
    </div>
    <div class="col-sm-8">
        <div class="box">
            <div class="box-body" style="padding: 0px;">
                <div id="attendanceGraph"></div>
            </div>
        </div>
    </div>
</div> -->

<div class="row">
    <?php if(permissionChecker('notice')) { ?>
    <div class="col-sm-6">
        <?php $this->load->view('dashboard/NoticeBoard', array('val' => 5, 'length' => 15, 'maxlength' => 45)); ?>
    </div>
    <?php } ?>
    <div class="col-sm-6">
        <div class="box">
            <div class="box-body" style="padding: 0px;">
                <div id="visitor"></div>
            </div>
        </div>
    </div>
</div>
<?php if($getActiveUserID == 1 || $getActiveUserID == 5) { ?>
<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <!-- <div class="box-header">
                <h3 class="box-title" id="earning_top_right_graph_title">
                    <?=$this->lang->line('dashboard_per_day_attendance')?>
                </h3>
                <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" type="button" id="earning_top_right_graph_back_btn">
                        <i class="fa fa-arrow-left"></i>
                    </button>
                </div>
            </div> -->

            <div class="box-body" style="padding: 0px;">
                <div id="earningGraph"></div>
            </div>
        </div>
    </div>
</div>
<?php } ?>
<!-- <php } ?> -->
<div class="row">
  <div class="col-sm-12">
      <div class="box">
          <div class="box-body">
              <!-- THE CALENDAR -->
              <div id="calendar"></div>
          </div>
      </div>
  </div>
</div><!-- /.row -->


<?php
    if($attendanceSystem != 'subject') {
        $this->load->view("dashboard/AttendanceHighChartJavascript");
    } else {
        $this->load->view("dashboard/SubjectWiseAttendanceHighChartJavascript");
    }
?>
<?php $this->load->view("dashboard/EarningHighChartJavascript.php"); ?>
<?php $this->load->view("dashboard/CalenderJavascript"); ?>
<?php $this->load->view("dashboard/VisitorHighChartJavascript"); ?>
