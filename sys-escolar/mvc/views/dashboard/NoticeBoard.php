      <div class="box">
        <div class="box-header" style="background-color: #fff;">
          <h3 class="box-title text-black">
              <?=$this->lang->line('dashboard_notice')?>
            </h3>
        </div>

        <div class="box-body" style="padding: 0px;">
          <table class="table table-hover">
              <tbody>
                <?php
                  if(count($notices)) {
                    $i =0;
                    $j = 1;
                    foreach ($notices as $key => $notice) {
                      if($i != $val) {
                        echo "<tr>";
                          echo "<td>";
                            echo $j;
                          echo "</td>";

                          echo "<td>";
                            if(strlen($notice->title) > $length) {
                               $title = substr($notice->title, 0,$length). "..";
                            } else {
                                $title = $notice->title;
                            }
                            echo strip_tags($title);
                          echo "</td>";

                          echo "<td>";
                            if(strlen($notice->notice) > $maxlength) {
                              $discription = substr($notice->notice, 0,$maxlength). "..";
                            } else {
                                $discription = $notice->notice;
                            }
                            echo strip_tags($discription);
                          echo "</td>";

                          echo "<td>";
                            echo btn_dash_view('notice/view/'.$notice->noticeID, $this->lang->line('view'), 'bg-maroon-light');
                          echo "</td>";
                        echo "</tr>";
                        $i++;
                        $j++;
                      } else {
                        break;
                      }

                    }
                  }


                ?>
              </tbody>
          </table>
        </div>
      </div>