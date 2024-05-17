      <?php if(count($user)) { ?>
        <section class="panel">
          <div class="profile-db-head bg-maroon-light">
            <a href="<?=base_url('profile/index')?>">
              <?=img(base_url('uploads/images/'.$user->photo));?>
            </a>

            <h1><?=$user->name?></h1>
            <p><?=$usertype?></p>

          </div>
          <table class="table table-hover">
              <tbody>
                  <tr>
                    <td>
                      <i class="glyphicon glyphicon-user text-maroon-light" ></i>
                    </td>
                    <td><?=$this->lang->line('dashboard_username')?></td>
                    <td><?=$user->username?></td>
                  </tr>
                  <tr>
                      <td>
                        <i class="fa fa-envelope text-maroon-light"></i>
                      </td>
                      <td><?=$this->lang->line('dashboard_email')?></td>
                    <td><?=$user->email?></td>
                  </tr>
                  <tr>
                    <td>
                      <i class="fa fa-phone text-maroon-light"></i>
                    </td>
                    <td><?=$this->lang->line('dashboard_phone')?></td>
                    <td><?=$user->phone?></td>
                  </tr>
                  <tr>
                    <td>
                      <i class=" fa fa-globe text-maroon-light"></i>
                    </td>
                    <td><?=$this->lang->line('dashboard_address')?></td>
                    <td><?=$user->address?></td>
                  </tr>
              </tbody>
          </table>
        </section>
      <?php } ?>