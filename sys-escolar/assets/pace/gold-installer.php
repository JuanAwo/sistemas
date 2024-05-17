<?php

class GOLD_MEDIA_Installer {
	var $templateName='default';
	public function getCurrentTemplatePath()
	{
		return 'gold-skins/'.$this->templateName.'';
	}
	public function form_error() {
		return $_POST['error'];
	}
	public function installer() {
		$Installer = new Installer();
		if(GOLD_USERNAME == '') {
		return '<html>
  <head>
  	<meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
  	<title>Gold MOVIES Installer</title>
  	<link rel="stylesheet" href="'.$this->getCurrentTemplatePath().'/installer.css" />
  	<script src="//use.edgefonts.net/source-sans-pro:n3,n4,n6,i3,i4;source-code-pro:n4.js"></script>
  </head>
  <body id="home">

	<div id="banner-wrapper">
	    <div id="banner" class="row">
	        <a id="banner-button" href="" class="rounded radius button"><img src="'.$this->getCurrentTemplatePath().'/images/logo.png" class="animated zoomIn"></a>
	    </div>
	</div>

	<div id="hero-wrapper">
	    <div id="hero" class="row">
	        <div class="large-12 columns">
	        	<h1 class="animated bounce"><span>Gold MOVIES Installation</span></h1>
	        	<h2 class="error"><span>'.$this->form_error().'</span></h2>
	        	<form method="POST" action="" class="forms">
	        		<h2><span>Database Information:</span></h2>
					<div class="input-groups width-50">
						'.$Installer->form_input('title', 'Database Hostname', 'text', 'database_host', '', '').'
						'.$Installer->form_input('input', 'Database Hostname', 'text', 'database_host', '', '').'
					</div>
					<div class="input-groups width-50">
						'.$Installer->form_input('title', 'Database Name', 'text', 'database_name', '', '').'
						'.$Installer->form_input('input', 'Database Name', 'text', 'database_name', '', '').'
					</div>
					<div class="input-groups width-50">
						'.$Installer->form_input('title', 'Database User', 'text', 'database_user', '', '').'
						'.$Installer->form_input('input', 'Database User', 'text', 'database_user', '', '').'
					</div>
					<div class="input-groups width-50">
						'.$Installer->form_input('title', 'Database Password', 'password', 'database_password', '', '').'
						'.$Installer->form_input('input', 'Database Password', 'password', 'database_password', '', '').'
					</div>

					<h2 style="margin-top: 20px;"><span>Admin Information:</span></h2>
					<div class="input-groups width-50">
						'.$Installer->form_input('title', 'Admin Username', 'text', 'admin_username', '', '').'
						'.$Installer->form_input('input', 'Admin Username', 'text', 'admin_username', '', '').'
					</div>
					<div class="input-groups width-50">
						'.$Installer->form_input('title', 'Admin Email', 'text', 'admin_email', '', '').'
						'.$Installer->form_input('input', 'Admin Email', 'text', 'admin_email', '', '').'
					</div>
					<div class="input-groups width-50">
						'.$Installer->form_input('title', 'Admin Password', 'password', 'admin_password', '', '').'
						'.$Installer->form_input('input', 'Admin Password', 'password', 'admin_password', '', '').'
					</div>
					<div class="input-groups width-50">
						<div class="checkbox">
							<label>
						    	<input type="checkbox" name="import_posts" checked="checked" value="1" style="float: left;"> <span style="float: left; margin-top: -3px; margin-left: 5px;">Pre-Import Media</span>
							</label>
						</div>
					</div>
					<div class="input-groups width-50">
						<div style="width: 210px; margin:0 auto;">
							'.$Installer->form_input('input', 'Submit', 'submit', 'submit', 'Install Gold MOVIES !', 'gold_submit').'
						</div>
					</div>
				</form>
	        </div>
	    </div>
  	</div>
  </body>
  </html>
		';
	} else {
		if($subfolder != '') { $redirect_uri = "/".$subfolder."/index.php?gold=admin"; } else { $redirect_uri = "/index.php?gold=admin"; }
		header("Location: ".$redirect_uri);
	}
	}
}