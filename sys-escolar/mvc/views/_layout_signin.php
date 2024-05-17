<?php echo doctype("html5"); ?>
<html class="white-bg-login" lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    <title>Login</title>
    <link rel="SHORTCUT ICON" href="<?=base_url("uploads/images/$siteinfos->photo")?>" />
    <link href="<?php echo base_url('assets/bootstrap/bootstrap.min.css'); ?>" rel="stylesheet"  type="text/css">
    <link href="<?php echo base_url('assets/fonts/font-awesome.css'); ?>" rel="stylesheet"  type="text/css">
    <link href="<?php echo base_url('assets/lesson/style.css'); ?>" rel="stylesheet"  type="text/css">
    <link href="<?php echo base_url('assets/lesson/lesson.css'); ?>" rel="stylesheet"  type="text/css">
    <link href="<?php echo base_url('assets/lesson/responsive.css'); ?>" rel="stylesheet"  type="text/css">
</head>
<body class="white-bg-login">
    <div class="col-md-4 col-md-offset-4 marg" style="margin-top:30px;">
        <?php
            if(count($siteinfos->photo)) {
                echo "<center><img width='50' height='50' src=".base_url('uploads/images/'.$siteinfos->photo)." /></center>";
            }
        ?>
        <center><h4><?php echo namesorting($siteinfos->sname, 25); ?></h4></center>
    </div>
    <?php $this->load->view($subview); ?>
    <script type="text/javascript" src="<?php echo base_url('assets/lesson/jquery.js'); ?>"></script>
    <script type="text/javascript" src="<?php echo base_url('assets/bootstrap/bootstrap.min.js'); ?>"></script>
</body>
</html>
