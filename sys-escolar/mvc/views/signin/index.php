
<div class="form-box" id="login-box">
    <div class="header"><?=$this->lang->line('signin')?></div>
    <form method="post">

        <!-- style="margin-top:40px;" -->

        <div class="body white-bg">
        <?php
            if($form_validation == "No"){
            } else {
                if(count($form_validation)) {
                    echo "<div class=\"alert alert-danger alert-dismissable\">
                        <i class=\"fa fa-frown-o fa-2x\"></i>
                        <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                        $form_validation
                    </div>";
                }
            }
            if($this->session->flashdata('reset_success')) {
                $message = $this->session->flashdata('reset_success');
                echo "<div class=\"alert alert-success alert-dismissable\">
                    <i class=\"fa fa-smile-o fa-2x\"></i>
                    <button aria-hidden=\"true\" data-dismiss=\"alert\" class=\"close\" type=\"button\">×</button>
                    $message
                </div>";
            }
        ?>
            <div class="form-group">
                <input class="form-control" placeholder="Usuario" name="username" type="text" autofocus value="admin">
            </div>
            <div class="form-group">
                <input class="form-control" placeholder="Contraseña" name="password" type="password" value="admin">
            </div>


            <div class="checkbox">
                <label>
                    <input type="checkbox" value="Remember Me" name="remember">
                    <span> &nbsp; Recordar</span>
                </label>
                <span class="pull-right">
                    <label>
                        <a href="<?=base_url('reset/index')?>"> ¿Olvidé mi contraseña?</a>
                    </label>
                </span>
            </div>

            <input type="submit" class="btn btn-lg btn-success btn-block" value="INGRESAR" />
        </div>
    </form>
</div>
