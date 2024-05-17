<?php
// --- GOLD MEDIA --- //

define('GOLD_BASE', dirname(empty($_SERVER['SCRIPT_FILENAME']) ? __FILE__ : $_SERVER['SCRIPT_FILENAME']).'/');

class GOLD_MEDIA extends SkinFunctions {
	//All CMS template management related functions will be here.
    var $templateName='default';
	public function getCurrentTemplatePath()
	{
		return $this->GOLD_ROOT().'gold-skins/'.$this->templateName.'';
	}
	
		function output_array($elements)
		{
			foreach ($elements as $element) {
				$delta=substr_count($element, '<')-substr_count($element, '<!')-2*substr_count($element, '</')-substr_count($element, '/>');
				
				echo str_repeat("\t", max(0, $this->indent)).str_replace('/>', '>', $element)."\n";
				
					
				$this->lines++;
			}
		}

		
		function output()
		{
			$args=func_get_args();
			$this->output_array($args);
		}
		
		function smilies( $text ) {
    		$smilies = array(
     		   ':D' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/laugh.png" />',
			   ':)' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/happy.png" />',
			   ':(' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/bored.png" />',
			   ';)' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/wink.png" />',
			   ':P' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/tongue.png" />',
			   ':X' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/not_even.png" />',
			   ':O' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/agape.png" />',
			   ':grin:' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/grin.png" />',
			   ':shocked:' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/shocked.png" />',
			   ':cry:' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/cry.png" />',
			   ':sunglasses:' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/sunglasses.png" />',
			   ':wink:' => ' <img src="'.$this->getCurrentTemplatePath().'/images/smilies/wink.png" />'
   			);
			return str_replace( array_keys( $smilies ), array_values( $smilies ), $text );
		}

	
  public function GOLD_html() {
		if($this->GOLD_REQUEST("xml") == 'rss') {
			$this->GOLD_rss();
		} elseif($this->GOLD_REQUEST("xml") == 'sitemap') {
			$this->GOLD_sitemap();
		} elseif($this->GOLD_REQUEST("gold") == 'movies') {
			$this->GOLD_movies($this->GOLD_REQUEST("sub_gold"));
		} elseif($this->GOLD_REQUEST("xml") == '' || $this->GOLD_REQUEST("gold") != 'movies') {
			$GOLD_html .= $this->output('<!DOCTYPE html>
<html lang="ge">');
			
			$GOLD_html .= $this->GOLD_head();
			$GOLD_html .= $this->GOLD_body();
		}

    return $GOLD_html;
  }
  
  public function GOLD_movies($id) {
	$query = mysql_query("SELECT * FROM gold_posts WHERE post_id='".$id."'");
	$row = mysql_fetch_array($query);
	$file = $row['movie_flv'];
	session_start();
	$_SESSION['VIDEO'] = $file;
	header ('Location:'.$_SESSION['VIDEO']);
	session_unset('VIDEO'); 
  }
  
  public function GOLD_rss() {
	  
		header('Content-Type: text/xml');

		echo '<?xml version="1.0" encoding="UTF-8"?>
<rss version="2.0">
<channel>
<title>'.$this->set('gold_website_title').'</title>
<description>'.$this->set('gold_website_description').'</description>
<link>'.$this->GOLD_ROOT().'</link>';
$get_articles = "SELECT * FROM gold_posts WHERE post_status='1' ORDER BY post_id DESC";

$articles = mysql_query($get_articles) or die(mysql_error());

while ($article = mysql_fetch_array($articles)){
        $GOLD_CHECK_CATEGORY = mysql_query("SELECT * FROM gold_categories WHERE category_id='".$article['category_id']."'");
		$GOLD_CHECK_USERS = mysql_query("SELECT * FROM gold_users WHERE user_id='".$article['user_id']."'");
		$GOLD_CATEGORY = mysql_fetch_array($GOLD_CHECK_CATEGORY);
		$GOLD_USER = mysql_fetch_array($GOLD_CHECK_USERS);
		if($GOLD_USER['user_type'] == '') { $avatar = $this->GOLD_ROOT().'gold-app/gold-uploads/avatars/'.$GOLD_USER['user_avatar']; } else { $avatar = $GOLD_USER['user_avatar']; }
		if($media['post_type'] == '0') {
			$post_type = '<img src="'.$this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$media['post_thumb'].'" style="border-radius: 2px; width: 282px; margin-bottom: -6px;"'.$this->GOLD_ROOT().'>';
		}
    	elseif($media['post_type'] == '1') {
			$post_type = '<img src="'.$media['post_thumb'].'" style="border-radius: 2px; width: 282px; margin-bottom: -6px;"'.$this->GOLD_ROOT().'>';
     	}
    echo '
<item>
	<title>'.str_replace('&', '&amp;', $article['post_title']).'</title>
	<link>'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$article['post_name'].'</link>
	<description>'.str_replace('&', '&amp;', $article['post_content']).'</description>
	<category>'.str_replace('&', '&amp;', $GOLD_CATEGORY['title']).'</category>
	<guid isPermaLink="true">'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$article['post_name'].'</guid>
	<pubDate>'.$article['post_created'].'</pubDate>
</item>';
}
echo "
</channel>
</rss>";

  }
  
  public function GOLD_sitemap() {
	  
		header('Content-Type: text/xml');

		echo '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">';

echo '
<url>
	<loc>'.$this->GOLD_ROOT().'</loc>
	<lastmod>'.date('Y-m-d').'</lastmod>
	<priority>1.0</priority>
</url>';



$get_articles = "SELECT * FROM gold_posts WHERE post_status='1' ORDER BY post_id DESC";
$articles = mysql_query($get_articles) or die(mysql_error());
while ($article = mysql_fetch_array($articles)){
        $GOLD_CHECK_CATEGORY = mysql_query("SELECT * FROM gold_categories WHERE category_id='".$article['category_id']."'");
		$GOLD_CHECK_USERS = mysql_query("SELECT * FROM gold_users WHERE user_id='".$article['user_id']."'");
		$GOLD_CATEGORY = mysql_fetch_array($GOLD_CHECK_CATEGORY);
		$GOLD_USER = mysql_fetch_array($GOLD_CHECK_USERS);
		if($GOLD_USER['user_type'] == '') { $avatar = $this->GOLD_ROOT().'gold-app/gold-uploads/avatars/'.$GOLD_USER['user_avatar']; } else { $avatar = $GOLD_USER['user_avatar']; }
		if($media['post_type'] == '0') {
			$post_type = '<img src="'.$this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$media['post_thumb'].'" style="border-radius: 2px; width: 282px; margin-bottom: -6px;"'.$this->GOLD_ROOT().'>';
		}
    	elseif($media['post_type'] == '1') {
			$post_type = '<img src="'.$media['post_thumb'].'" style="border-radius: 2px; width: 282px; margin-bottom: -6px;"'.$this->GOLD_ROOT().'>';
     	}
    echo '
<url>
	<loc>'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$article['post_name'].'</loc>
	<lastmod>'.date('Y-m-d', strtotime($article['post_created'])).'</lastmod>
	<priority>0.7</priority>
</url>';
}

$get_categories = "SELECT * FROM gold_categories WHERE status='1' ORDER BY category_id ASC";
$cat_query = mysql_query($get_categories) or die(mysql_error());
while ($cat = mysql_fetch_array($cat_query)){
    echo '
<url>
	<loc>'.$this->GOLD_ROOT().'genre/'.$cat['name'].'</loc>
	<priority>0.5</priority>
</url>';
}

echo "
</urlset>";

  }

  public function GOLD_head() {
	if($this->GOLD_REQUEST('gold') != 'post') {
		if($this->GOLD_REQUEST('gold') == '') {
			$title = $this->set('gold_website_title');
		}
		elseif($this->GOLD_REQUEST('gold') == 'genre') {
			if($this->GOLD_REQUEST('sub_gold') != '') {
				$cat = mysql_fetch_array(mysql_query("SELECT * FROM gold_categories WHERE name='".$this->GOLD_REQUEST('sub_gold')."'"));
				$cat_title = $cat['title'];
			}
			elseif($this->GOLD_REQUEST('sub_gold') == '') {
				$cat_title = 'Genres';
			}
			$title = $cat_title." &raquo; ".$this->set('gold_website_title');
		}
		elseif($this->GOLD_REQUEST('gold') == 'search') {
			if($this->GOLD_REQUEST('q') != '') {
				$title = $this->GOLD_REQUEST('q')." &raquo; ".$this->set('gold_website_title');
			} else {
				$title = "Tags &raquo; ".$this->set('gold_website_title');
			}
		}
		elseif($this->GOLD_REQUEST('gold') == 'login') {
			$title = "Login"." &raquo; ".$this->set('gold_website_title');
		}
		elseif($this->GOLD_REQUEST('gold') == 'register') {
			$title = "Registration"." &raquo; ".$this->set('gold_website_title');
		}
		elseif($this->GOLD_REQUEST('gold') == 'forgot') {
			$title = "Forgot Password ?"." &raquo; ".$this->set('gold_website_title');
		}
		elseif($this->GOLD_REQUEST('gold') == 'submit') {
			$title = "Add Movie"." &raquo; ".$this->set('gold_website_title');
		}
		elseif($this->GOLD_REQUEST('gold') == 'admin') {
			$title = "Admin"." &raquo; ".$this->set('gold_website_title');
		}
		elseif($this->GOLD_REQUEST('gold') == 'pages' && $this->GOLD_REQUEST('sub_gold') == '') {
			$title = "Pages"." &raquo; ".$this->set('gold_website_title');
		}
		elseif($this->GOLD_REQUEST('gold') == 'pages' && $this->GOLD_REQUEST('sub_gold') != '') {
			$page = mysql_fetch_array(mysql_query("SELECT * FROM gold_pages WHERE name='".$this->GOLD_REQUEST('sub_gold')."'"));
			$title = $page['title']." &raquo; Pages &raquo; ".$this->set('gold_website_title');
		}
		elseif($this->GOLD_REQUEST('gold') == 'sort') {
			if($this->GOLD_REQUEST('sub_gold') == 'title') {
				$title = "Title"." &raquo; Filter &raquo; ".$this->set('gold_website_title');
			}
			elseif($this->GOLD_REQUEST('sub_gold') == 'rating') {
				$title = "Rating"." &raquo; Filter &raquo; ".$this->set('gold_website_title');
			}
			elseif($this->GOLD_REQUEST('sub_gold') == 'year') {
				$title = "Year"." &raquo; Filter &raquo; ".$this->set('gold_website_title');
			}
			elseif($this->GOLD_REQUEST('sub_gold') == 'views') {
				$title = "Views"." &raquo; Filter &raquo; ".$this->set('gold_website_title');
			}
			elseif($this->GOLD_REQUEST('sub_gold') == 'imdb') {
				$title = "IMDB"." &raquo; Filter &raquo; ".$this->set('gold_website_title');
			}
		}
		$GOLD_html .= $this->output('<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# video: http://ogp.me/ns/video#">
	<base href="'.$this->GOLD_ROOT().'">
	<title lang="ge">'.$title.'</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="index,follow,all">
	<meta name="revisit-after" content="1 days">
	<meta name="generator" content="ThemesGold" />
 	<link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
	<link rel="icon" type="image/x-icon" href="favicon.ico" />
	<link rel="alternate" type="application/rss+xml" title="'.$this->set('gold_website_title').'" href="'.$this->GOLD_ROOT().'rss.xml" />
	<meta property="og:type" content="video.movie" />
	<meta property="og:title" content="'.$this->set('gold_website_title').'" />
	<meta property="og:image" content="'.$this->getCurrentTemplatePath().'/images/logo.png" />
	<meta property="og:site_name" content="SheniKino.com" />
	<meta property="og:description" content="'.$this->set('gold_website_description').'" />
	<meta property="og:url" content="'.$this->GOLD_ROOT().'" />
	<meta property="title" content="'.$this->set('gold_website_title').'" />
	<meta property="type" content="website" />
	<meta property="image" content="'.$this->getCurrentTemplatePath().'/images/logo.png" />
	<meta property="site_name" content="'.$this->set('gold_website_title').'" />
	<meta property="description" content="'.$this->set('gold_website_description').'" />
	<meta property="url" content="'.$this->GOLD_ROOT().'" />
	<link rel="stylesheet" href="'.$this->getCurrentTemplatePath().'/gold-styles.css">
	<link href="'.$this->getCurrentTemplatePath().'/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="'.$this->getCurrentTemplatePath().'/font-awesome.min.css">
</head>
		');
	} else {
		$query = mysql_query("SELECT * FROM gold_posts WHERE post_name='".$this->GOLD_REQUEST('sub2_gold')."' ORDER BY post_id LIMIT 1");
		while($row = mysql_fetch_array($query)) {
		// Movie Information
		$image = ''.$this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$row['post_thumb'];
		$category = mysql_fetch_array(mysql_query("SELECT * FROM gold_categories WHERE category_id='".$row['category_id']."'"));
		$categories = $row['category_id'];
		$cats = explode(",", $categories);
		foreach($cats as &$cat) {
			$row_cat = mysql_fetch_array(mysql_query("SELECT * FROM gold_categories WHERE category_id='".$cat."'"));
			$cat = $row_cat['title'];
		}
		$genre = implode(", ", $cats);
		
		$GOLD_html .= $this->output('<head prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb# video: http://ogp.me/ns/video#">
        <base href="'.$this->GOLD_ROOT().'">
        <title lang="ge">'.$row['post_title'].' &raquo; '.$this->set('gold_website_title').'</title>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="robots" content="index,follow,all">
        <meta name="revisit-after" content="1 days">
        <meta name="generator" content="ThemesGold" />
        <link rel="shortcut icon" type="image/x-icon" href="favicon.ico" />
        <link rel="icon" type="image/x-icon" href="favicon.ico" />
        <link rel="alternate" type="application/rss+xml" title="'.$this->set("website_title").'" href="'.$this->GOLD_ROOT().'rss.xml" />
        <meta property="og:type" content="video.movie" />
        <meta property="og:title" content="'.$row['post_title'].'" />
        <meta property="og:image" content="'.$image.'" />
        <meta property="og:site_name" content="'.$this->set('gold_website_title').'" />
        <meta property="og:description" content="★ '.$this->LANG('year_title').':'.str_replace('"', '', $row['year']).'. ★ '.$this->LANG('genre_title').': '.str_replace('"', '', $genre).'. ★ '.$this->LANG('description_title').': '.str_replace('"', '', $row['post_content']).' ..." />
        <meta property="og:url" content="'.$this->GOLD_ROOT().$category['name'].'/'.$row['post_name'].'" />
        <meta property="title" content="'.$row['post_title'].'" />
        <meta property="type" content="website" />
        <meta property="image" content="'.$image.'" />
        <meta property="site_name" content="'.$this->set('gold_website_title').'" />
        <meta property="description" content="★ '.$this->LANG('year_title').':'.str_replace('"', '', $row['year']).'. ★ '.$this->LANG('genre_title').': '.str_replace('"', '', $genre).'. ★ '.$this->LANG('description_title').': '.str_replace('"', '', $row['post_content']).' ..." />
        <meta property="url" content="'.$this->GOLD_ROOT().$category['name'].'/'.$row['post_name'].'" />
        <link rel="stylesheet" href="'.$this->getCurrentTemplatePath().'/gold-styles.css">
        <link href="'.$this->getCurrentTemplatePath().'/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="'.$this->getCurrentTemplatePath().'/font-awesome.min.css">
</head>
		');
		}
	}
    return $GOLD_head;
  }
  
  
  public function GOLD_body() {
	    $GOLD_html .= $this->output('<body>
		
		
		');
	
		$GOLD_html .= $this->output('
		
		
		
		<div class="page-container">');
		$GOLD_html .= $this->output('<div class="mp-pusher" id="mp-pusher">');
		
		
		$GOLD_body .= $this->GOLD_header();
		$GOLD_body .= $this->GOLD_aside_sidebar();
		
		
		
		
		$GOLD_body .= $this->output('<div id="waterfall-container" class="waterfall-container" style="margin: 114px auto 0;">');
		if($this->GOLD_REQUEST('gold') == 'pages' || $this->GOLD_REQUEST('gold') == 'submit') {
			$width_920 = ' style="max-width: 920px;"';
		}
		if($this->GOLD_REQUEST('gold') == 'genre' && $this->GOLD_REQUEST('sub_gold') == '') { $width_920 = ' style="max-width: 920px;"'; }
		if($this->GOLD_REQUEST('gold') == 'submit') {
			$GOLD_body .= $this->output('<div class="full_wrap cf"'.$width_920.'>');
		} else {
			if($this->GOLD_REQUEST('gold') != 'post') {
				$GOLD_body .= $this->output('<div class="wrap cf"'.$width_920.'>'); 
			}
		}
		$this->GOLD_appOutput();
		$GOLD_body .= $this->output('</div></div></div>');
		$GOLD_body .= $this->output('</div></div>');
		
		$GOLD_html .= $this->output(''.$this->footer().'
		
		</div>');
if(GOLD_SUB_FOLDER != '') { $sub_folder = GOLD_SUB_FOLDER; $root = 'http://'.$_SERVER['SERVER_NAME'].'/'; $sub_folder_script = "<script type='text/javascript'>var sub_folder='".$root.$sub_folder."';</script>"; } else { $sub_folder = GOLD_SUB_FOLDER; $sub_folder_script = '<script type="text/javascript">var sub_folder="";</script>'; }

		$GOLD_html .= $this->output('</div>');
		$GOLD_body .= $this->output('
  '.$this->widget_echo("Analytics").'
<div id="fb-root"></div>
								<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
  fjs.parentNode.insertBefore(js, fjs);
}(document, "script", "facebook-jssdk"));</script>
'.$sub_folder_script.'');
		$GOLD_html .= $this->output('<script src="'.$this->getCurrentTemplatePath().'/jquery-1.8.0.min.js" type="text/javascript"></script>');

	
		$GOLD_html .= $this->output('<script src="'.$this->getCurrentTemplatePath().'/jquery.autocomplete.js" type="text/javascript"></script>');
		$GOLD_html .= $this->output('<script src="'.$this->getCurrentTemplatePath().'/jquery.form.js" type="text/javascript"></script>');
		$GOLD_html .= $this->output('<script src="'.$this->getCurrentTemplatePath().'/gold.min.js" type="text/javascript"></script>');
		$GOLD_html .= $this->output('<script src="'.$this->getCurrentTemplatePath().'/gold.js" type="text/javascript"></script>');
		$GOLD_html .= $this->output('</body>');
		$GOLD_html .= $this->output('</html>');
		
		$GOLD_html .= $this->output($this->poweredby());

    return $GOLD_body;
  }
  
  public function GOLD_error() {
	  
	  if($this->GOLD_REQUEST('gold') == 'post') {
		$Fetch = mysql_fetch_array(mysql_query("SELECT * FROM gold_posts WHERE post_name='".$this->GOLD_REQUEST('sub2_gold')."' LIMIT 1"));
		if($Fetch['post_status'] == '0') {
		  $GOLD_html .= $this->output('<style>.waterfall-container { margin: 104px auto 0; }</style>');
      	  $GOLD_html .= $this->output('<div id="sub-bar-container" style="position: fixed;">');
	  	  $GOLD_html .= $this->output('<div id="sub-bar" class="headline-bar wrap cf" style="text-align: center;">');
	  	  $GOLD_html .= $this->output('<h1 class="h2 solo-title" style="color: #000; font-size: 14px; font-weight: normal; margin-bottom: 7px;">');
	  	  $GOLD_html .= $this->output(''.$this->LANG('media_will_be_checked').'');
	  	  $GOLD_html .= $this->output('</h1>');
	  	  $GOLD_html .= $this->output('</div>');
	  	  $GOLD_html .= $this->output('</div>');
		}
	  }
	  elseif($_SESSION['user_id'] != '') {
		$Fetch = mysql_fetch_array(mysql_query("SELECT * FROM gold_users WHERE user_id='".$_SESSION['user_id']."' LIMIT 1"));
		if($Fetch['user_active'] == '0') {
		  $GOLD_html .= $this->output('<style>.waterfall-container { margin: 104px auto 0; }</style>');
      	  $GOLD_html .= $this->output('<div id="sub-bar-container" style="position: fixed;">');
	  	  $GOLD_html .= $this->output('<div id="sub-bar" class="headline-bar wrap cf" style="text-align: center;">');
	  	  $GOLD_html .= $this->output('<h1 class="h2 solo-title" style="color: #000; font-size: 14px; font-weight: normal; margin-bottom: 7px; font-family: Arial;">');
	  	  $GOLD_html .= $this->output(''.$this->LANG('confirm_the_email_adress').'');
	  	  $GOLD_html .= $this->output('</h1>');
	  	  $GOLD_html .= $this->output('</div>');
	  	  $GOLD_html .= $this->output('</div>');
		}
	  }
	  elseif($this->GOLD_REQUEST('gold') == 'forgot' && $this->GOLD_REQUEST('action') == 'sent') {
	  	  $GOLD_html .= $this->output('<style>.waterfall-container { margin: 104px auto 0; }</style>');
      	  $GOLD_html .= $this->output('<div id="sub-bar-container" style="position: fixed;">');
	  	  $GOLD_html .= $this->output('<div id="sub-bar" class="headline-bar wrap cf" style="text-align: center;">');
	  	  $GOLD_html .= $this->output('<h1 class="h2 solo-title" style="color: #000; font-size: 14px; font-weight: normal; margin-bottom: 7px; font-family: Arial;">');
	  	  $GOLD_html .= $this->output(''.$this->LANG('emailed_your_reset_password').'');
	  	  $GOLD_html .= $this->output('</h1>');
	  	  $GOLD_html .= $this->output('</div>');
	  	  $GOLD_html .= $this->output('</div>');
	  }
	  
    return $GOLD_html;
  }
	
  public function GOLD_header() {

		$GOLD_html .= $this->output('<header id="header" role="banner">');
		$GOLD_html .= $this->output('<div class="full_wrap cf"><div style="padding-left: 30px; padding-right: 30px;">');
		$GOLD_html .= $this->output('<a id="guest-logo" href="'.$this->GOLD_ROOT().'" >');
		$GOLD_html .= $this->output('<img src="'.$this->getCurrentTemplatePath().'/images/'.$this->set('gold_logo').'">');
		$GOLD_html .= $this->output('</a>
			<span id="main-nav-toggle" class="menu-trigger" data-icon="" style=""></span>');
		if($this->GOLD_REQUEST('gold') == 'login' || $this->GOLD_REQUEST('gold') == 'register' || $this->GOLD_REQUEST('gold') == 'forgot' || $this->GOLD_REQUEST('gold') == 'submit') { $gold_login = $this->GOLD_ROOT().'login'; } else { $gold_login = '#loginmodal'; }

$GOLD_html .= $this->output('<div class="search" style="text-align: left; position: relative; right: 7px;">');
if ($this->GOLD_logged_in()) {
	$GOLD_html .= $this->output('<a href="'.$this->GOLD_ROOT().'admin" style="text-decoration: none; background: #353535; color: #fff; display: inline-block; position: absolute; margin-left: -90px; margin-top: 15px; padding: 4px 15px;">Admin</a>');
	$GOLD_html .= $this->output('<a href="'.$this->GOLD_ROOT().'logout" style="text-decoration: none; background: #353535; color: #fff; display: inline-block; position: absolute; margin-left: -185px; margin-top: 15px; padding: 4px 15px;">Logout</a>');
}

$GOLD_html .= $this->output('<form method="GET" action="'.$this->GOLD_ROOT().'search" style="display: inline-block;"><input type="image" src="'.$this->getCurrentTemplatePath().'/images/search.png"><input type="text" name="q" class="search" value="'.$_GET['q'].'" placeholder="'.$this->LANG('search').'"></form></div>');

		$GOLD_html .= $this->output('</div> <!-- end wrap --></div>');

		$Fetch = mysql_fetch_array(mysql_query("SELECT * FROM gold_users WHERE user_id='".$_SESSION['user_id']."' LIMIT 1"));
		
		if($this->GOLD_REQUEST('gold') == 'forgot' && $this->GOLD_REQUEST('action') == 'sent') {
			$GOLD_body .= $this->GOLD_error();
		} else {
		if($Fetch['user_active'] == '0') {
			$GOLD_body .= $this->GOLD_error();
		} else {
			if($this->GOLD_REQUEST('gold') == '' || $this->GOLD_REQUEST('gold') == 'sort' || $this->GOLD_REQUEST('category') == '' || $this->GOLD_REQUEST('category') != '') {
				$GOLD_body .= $this->output('

<div id="sub-bar-container" class="sub-bar-sort" style="top: 0px;">
	<div id="sub-bar" class="headline-bar wrap cf">
		<div style="padding: 0px 30px; margin-bottom: 35px;">
					<div class="select-movie-genre" id="select-genre" style="left:30px; display:block">
                        <div class="select-genre-title">
                            <a>Choose Movies Genre</a>
                        </div>
                        <div id="select-movie-genre" class="select-genre-categories" style="display: none;">
                            <div class="select-genre-categories-inner">
                                <ul>');
				$genre[query] = mysql_query("SELECT * FROM gold_categories WHERE status='1'");
				while($genre[row] = mysql_fetch_array($genre[query])) {
					$GOLD_body .= $this->output('<li><a href="'.$this->GOLD_ROOT().'genre/'.$genre[row]['name'].'" class="'.$genre[row]['title'].'">'.$genre[row]['title'].'</a></li>');
				}
				if($this->GOLD_REQUEST('gold') == 'genre' && $this->GOLD_REQUEST('sub_gold') != '') { $cat_sort = $this->GOLD_REQUEST('sub_gold'); }
				if($this->GOLD_REQUEST('gold') == 'sort' && $this->GOLD_REQUEST('sub_gold') != '' && $this->GOLD_REQUEST('sub2_gold') != '') { $cat_sort = $this->GOLD_REQUEST('sub2_gold'); }
        $GOLD_body .= $this->output('
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="sort-by">
                        <div id="filter-by">
                        	<span>Filter By:</span>
                        	<a href="'.$this->GOLD_ROOT().'sort/title/'.$cat_sort.'" class="title-sort-btn" data-order="asc" title="Title">Title</a> <span class="filter-line"></span>
                        	<a href="'.$this->GOLD_ROOT().'sort/rating/'.$cat_sort.'" class="rating-sort-btn" data-order="desc" title="Rating">Rating</a> <span class="filter-line"></span>
                        	<a href="'.$this->GOLD_ROOT().'sort/year/'.$cat_sort.'" class="date-sort-btn" data-order="desc" title="Year">Year</a> <span class="filter-line"></span>
                        	<a href="'.$this->GOLD_ROOT().'sort/views/'.$cat_sort.'" class="views-sort-btn" data-order="desc" title="Views">Views</a> <span class="filter-line"></span>
                        	<div class="imdb-sort"><a href="'.$this->GOLD_ROOT().'sort/imdb/'.$cat_sort.'" class="imdb"><i class="icon"></i>IMDB</a></div>
                        </div>
                    </div>
        </div>
	</div>
</div>');
			} else {
				$GOLD_body .= $this->GOLD_error();
			}
		}
		}

		$GOLD_html .= $this->output('</header>');

    return $GOLD_header;
  }
  
  public function GOLD_aside_sidebar() {

		$GOLD_html .= $this->output('<aside class="sidebar">
			<div class="aside-header">
				<div class="aside">
					<h2 style="margin-left: 20px;">menu</h2>
					<a class="close-aside" id="close-aside" style="margin-right: 20px;">Close</a>
				</div>
			</div>
			');
		$GOLD_html .= $this->output('<ul class="clearfix">');
		$GOLD_html .= $this->output('<li><a href="'.$this->GOLD_ROOT().'genre" class="icon-newest"><span class="filter genre"></span>GENRES</a></li>');
		$GOLD_html .= $this->output('<li><a href="'.$this->GOLD_ROOT().'pages" class="icon-pages"><span class="filter pages"></span>PAGES</a></li>');
		$GOLD_html .= $this->output('<li><a href="'.$this->GOLD_ROOT().'sort/hot/" class="icon-topuser"><span class="filter popular_movies"></span>POPULAR MOVIES</a></li>');
		$GOLD_html .= $this->output('<li><a href="'.$this->GOLD_ROOT().'pages/feedback" class="icon-feedback"><span class="filter feedback"></span>FEEDBACK</a></li>');
		
		$menu_sql = mysql_query("SELECT * FROM gold_menu WHERE menu_status='1' ORDER BY menu_id ASC");		
		while($block_media = mysql_fetch_array($menu_sql)) {
			$block_position = $block_media['menu_name'];
			echo $block[$block_position];
		}
		
		$GOLD_html .= $this->output('</ul>');

		$GOLD_html .= $this->output('</div></aside>');

    return $GOLD_html;
  }
  
  public function GOLD_login() {
	$GOLD_html .= $this->output('<div class="full_wrap cf" style="width: 100%; display:inline-block;">
		<div class="wrap_high" style="background: #fff; border: 1px solid #E7E7E7; border-radius: 5px; margin: 50px auto; margin-bottom: 100px;">
			<div class="full_title">'.$this->LANG('login').'</div>
			<div class="wrap_normal" style="padding: 0px 10px;">');
	
	$GOLD_html .= $this->output('
		<form action="" method="post" class="cd-form" style="width: 93%;">
			<div style="width: 256px; margin: 0 auto;"><center style="width: 297px; margin: 0 auto;"><span id="cd-error-message" style="color: #ed0016; font-size: 15px; text-align: center;">Error !</span></center></div>
			<p class="fieldset">
				<label class="image-replace cd-email" for="signin-email">'.$this->LANG('email_or_username').'</label>
				<input type="email" name="signin-email" id="signin-email" class="full-width has-padding has-border" placeholder="'.$this->LANG('email_or_username').'" autocomplete="off">
			</p>
			<p class="fieldset">
				<label class="image-replace cd-password" for="signin-password">'.$this->LANG('password').'</label>
				<input type="password" name="signin-password" id="signin-password" class="full-width has-padding has-border" placeholder="'.$this->LANG('password').'" autocomplete="off">
			</p>
			<p class="cd-form-bottom-message" style="position: relative; text-align: left; bottom: 0; font-family: Arial;"><a href="'.$this->GOLD_ROOT().'forgot" style="color: #000; margin-bottom: 25px; font-family: Arial; text-shadow: 0 1px 0 rgba(0,0,0,0.34); margin-top: 19px; text-decoration: none;">'.$this->LANG('forgot_your_password').'</a></p>
			<p class="fieldset" style="padding-bottom: 20px;margin-bottom: 20px;">
				<input type="checkbox" id="remember-me" checked="" style="float: left;">
				<label for="remember-me" style="float: left; margin-top: -7px; margin-left: 5px; font-size: 13px;">'.$this->LANG('remember_me').'</label>
			</p>
			<div style="width: 256px; margin: 0 auto;">
				<p class="fieldset" style="width: 296px; margin: 0 auto; display: inline-block;">
					<input id="login_button" type="submit" name="submit" value="'.$this->LANG('login').'" class="full-width" style="margin-bottom: 30px;">
				</p>
			</div>
         </form>
         </div></div>');
    return $GOLD_html;
  }

  // Shares Number
	public function getTwitterCount($url){
		$twittercount = json_decode( file_get_contents( 'http://urls.api.twitter.com/1/urls/count.json?url='.$url ) );
		return $twittercount->count;
	}
	public function getFacebookCount($url){
		$facebookcount = json_decode( file_get_contents( 'http://graph.facebook.com/'.$url ) );
		return $facebookcount->shares;
	}
	public function getSocialCount($url){
		return $this->getTwitterCount($url) + $this->getFacebookCount($url);
	}
  
  public function GOLD_full_post($q) {
  
  while($media = mysql_fetch_array($q)) {
  	$post_views = mysql_query("UPDATE gold_posts SET post_views = post_views + 1 WHERE post_id='".$media['post_id']."'");
		$GOLD_CHECK_CATEGORY = mysql_query("SELECT * FROM gold_categories WHERE category_id='".$media['category_id']."'");
		$GOLD_CATEGORY = mysql_fetch_array($GOLD_CHECK_CATEGORY);
	    $shares_count = $this->getSocialCount("".$this->GOLD_ROOT().$GOLD_CATEGORY['name']."/".$media['post_name']."");
		if(strpos($media['category_id'],',22') !== false || strpos($media['category_id'],'22,') !== false) { $movie_title = 'Movie'; } else { $movie_title = 'Movie'; }
	  if($media['movie_iframe'] == '' && $media['movie_flv'] == '') {
	  	$select_episodes = mysql_query("SELECT * FROM gold_episodes WHERE movie_id='".$media['post_name']."' LIMIT 1");
	  	$check_episodes = mysql_num_rows($select_episodes);
	  	if($check_episodes >= '1') {
	  		while($episode = mysql_fetch_array($select_episodes)) {
	  			if($episode['movie_link'] != '') {
	  				$post_type .= '
	  				<div id="EPISODE_PLAYER" style="max-height: 470px;">
						<object class="fixed_player" type="application/x-shockwave-flash" name="player" data="'.$this->getCurrentTemplatePath().'/player/GOLDPLAYER.swf" width="100%" height="100%" id="player" style="visibility: visible;">
							<param name="allowFullScreen" value="true">
							<param name="allowScriptAccess" value="always">
							<param name="wmode" value="opaque">
							<param name="flashvars" value="src='.$episode['movie_link'].'&controlBarAutoHide=true&loop=false&skin='.$this->getCurrentTemplatePath().'/player/GOLDPLAYER.xml">
						</object>
					</div>
					';
	  			}
	  			elseif($episode['movie_iframe'] != '') {
	  				$post_type .= '
					<div id="EPISODE_PLAYER">
						<iframe src="'.$episode['movie_iframe'].'" frameborder="0" class="fixed_player"></iframe>
					</div>
					';
	  			}

	  			$select_all_episodes = mysql_query("SELECT (@row:=@row+1) AS ROW_ID, e.* FROM gold_episodes e, (SELECT @row := 0) r WHERE movie_id='".$media['post_name']."' ORDER BY id ASC");
	  			while($all_episode = mysql_fetch_array($select_all_episodes)) {
	  				$episodes_list .= '<div class="episode-item clearfix" data-index="0">
							<div class="episode-number">
								<span>'.$all_episode['ROW_ID'].'</span>
							</div>
							<div class="episode-title">
								<span class="episode-name">'.$all_episode['episode_name'].'</span>
							</div>
							<a href="javascript:" onclick="show_episode('.$all_episode['id'].');" class="tip" title=""></a>
						</div>';
	  			}

	  			$post_type .= '
	  			<div id="episodes">
	  				<a href="javascript:" class="prev-episode" id="prev-episode" title="Prev Episode">Prev Episode</a>
	  				<a href="javascript:" class="next-episode" id="next-episode" title="Next Episode">Next Episode</a>
	  				<div class="full_episodes" style="width:855px;float:left;height:40px">
		  				<div class="episodes-list">
		  					'.$episodes_list.'
		  				</div>
	  				</div>
	  			</div>

	  			';
	  		}
	  	} else {
			$post_type .= '<div class="soon"><div class="soon-title">'.$movie_title.' will be soon !</div></div>';
		}
	  }
	 elseif($media['post_type'] == '0') {

$post_type .= '
<object type="application/x-shockwave-flash" name="player" data="'.$this->getCurrentTemplatePath().'/player/GOLDPLAYER.swf" width="100%" height="100%" id="player" style="visibility: visible;">
	<param name="allowFullScreen" value="true">
	<param name="allowScriptAccess" value="always">
	<param name="wmode" value="opaque">
	<param name="flashvars" value="src='.$media['movie_flv'].'&controlBarAutoHide=true&loop=false&skin='.$this->getCurrentTemplatePath().'/player/GOLDPLAYER.xml">
</object>
';

		$multiple_sql = mysql_query("SELECT * FROM gold_multiple WHERE post_name = '".$media['post_name']."' ORDER BY media_id ASC LIMIT 1,100000000");
		if(mysql_num_rows($multiple_sql) >= 2) {
			while($multiple = mysql_fetch_array($multiple_sql)) {
				$post_type .= '<img src="'.$this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$multiple['file_name'].'" style="width: 101%;margin-top: 5px;margin-bottom: -5px;">';
			}
		}
	  }
	  if($this->GOLD_REQUEST('remove') == '1') {
		mysql_query("DELETE FROM gold_posts WHERE post_id='".$media['post_id']."' LIMIT 1");
		header("Location: ".$this->GOLD_ROOT()."");
	  }
      
	  
	  if($media['post_content'] != '') {
	  	$post_content = '<h5 style="font-weight: normal; font-size: 14px; display: block; margin: 0px 0px 14px 0; clear: both;">'.$media['post_content'].'</h5>';
	  }
	  
	  if($this->prev_media($media['post_id']) != '') { $prev_url = 'href="'.$this->prev_media($media['post_id']).'"'; }
	  if($this->next_media($media['post_id']) != '') { $next_url = 'href="'.$this->next_media($media['post_id']).'"'; }

	// TMDb API Connection
    if($this->widget_echo("TMDB_API_KEY") != '') {
    $tmdb = new TMDb($this->widget_echo('TMDB_API_KEY'));
    $tmdbConfig['config'] = $tmdb->getConfiguration();
    $title = $media['post_title'];
    $row = $tmdb->searchMovie($title, '1', FALSE, $media['year']);
	foreach ($row['results'] as $key => $movie) {
	      if($key >= 1){
	        break; // Get out after 10 movies.
	      }
	      $searchMovieId = $movie['id'];
  	}

  	$poster = $tmdb->getMovieImages($searchMovieId);
	foreach ($poster['posters'] as $key_2 => $posters) {
	      if($key_2 >= 1){
	        break; // Get out after 10 movies.
	      }
	      $searchMoviePoster = $tmdb->getImageUrl($posters['file_path'], 'poster', "original");
  	}

  	$image = $tmdb->getMovieImages($searchMovieId);
	foreach ($image['backdrops'] as $key_3 => $images) {
	      if($key_3 >= 1){
	        break; // Get out after 10 movies.
	      }
	      $searchMovieImage = $tmdb->getImageUrl($images['file_path'], 'poster', "original");
  	}

  	$trailer = $tmdb->getMovieTrailers($searchMovieId);
	foreach ($trailer['youtube'] as $key_3 => $trailers) {
	      if($key_3 >= 1){
	        break; // Get out after 10 movies.
	      }
	      $searchMovieTrailer = "http://www.youtube.com/embed/".$trailers['source'];
  	}
  }

  	if($searchMovieImage != '') {
		$GOLD_html .= $this->output('
			<div class="post_preview" style="background: #565558 url('.$searchMovieImage.'); background-size: 960px;">
				<div id="left-wrapper"></div>
				<div id="right-wrapper"></div>');
  	} else {
		$GOLD_html .= $this->output('<div class="post_preview">');
  	}
  	if($media['movie_iframe'] != '') { $post_type='<iframe src="'.$media['movie_iframe'].'" frameborder="0" style="width: 920px; height: 510px;"></iframe>'; }
	$GOLD_html .= $this->output('
		<div class="player">
		'.$post_type.'
				
		</div>
	</div>

	<div class="wrap cf" style="max-width: 920px;">

		<div class="full_wrap cf">'); 
	  
	  
	  $GOLD_html .= $this->output('<div class="wrap_high" style="float: left; padding-bottom: 100px;">
	  <div class="wrap_normal" style="width: 590px; margin-bottom: 20px; overflow: hidden; background: #fff; border: 1px solid #E7E7E7; border-radius: 5px;">

<div class="post_description" style="display: block; padding: 4px 8px 0px 8px;">
');

		if($media['post_type'] == '0') {
			$post_type = ''.$this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$media['post_thumb'].'';
		}
    	elseif($media['post_type'] == '1') {
			$post_type = ''.$media['post_img'].'';
     	}
		$directed_by = $media['directed_by'];
		$directed = explode(", ", $directed_by);
		foreach($directed as &$rejisori) {
			$rejisori = "<a href='".$this->GOLD_ROOT()."producer/".$rejisori."'>".str_replace(".", "", $rejisori)."</a>";
		}
		$rejisorebi = implode(", ", $directed);
		
		$casts_by = $media['casts'];
		$casts_explode = explode(", ", $casts_by);
		foreach($casts_explode as &$casts_to) {
			$casts_to = "<a href='".$this->GOLD_ROOT()."actor/".$casts_to."'>".str_replace(".", "", $casts_to)."</a>";
		}
		$casts = implode(", ", $casts_explode);
		
		$categories = $media['category_id'];
		$cats = explode(",", $categories);
		foreach($cats as &$cat) {
			$row = mysql_fetch_array(mysql_query("SELECT * FROM gold_categories WHERE category_id='".$cat."'"));
			$cat = "<a href='".$this->GOLD_ROOT()."genre/".$row['name']."'>".$row['title']."</a>";
		}
		$genre = implode(", ", $cats);
		
	  if($media['post_title'] != '') {
	  
	    $GOLD_html .= $this->output('
				<div class="movie_container" style="padding-bottom: 3px;">
					<div style="padding: 0px 5px; margin-top: 3px;"><a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$media['post_name'].'" class="movie_title" style="max-height: 53px; font-size: 14px;">'.$media['post_title'].'</a></div>
					<div class="movie_image">
						<img src="'.$post_type.'" alt="'.$media['post_title'].'" title="'.$media['post_title'].'" class="image" height="323" width="220">
						');
	    if ($this->GOLD_logged_in()) {
	    	if($dss != '') {
		    $GOLD_html .= '
							<div id="movie-poster-overlay">
								<div class="MovieButtons">
				   					<div class="add-to-watchlist">
				   						<img src="'.$this->getCurrentTemplatePath().'/images/eyes.png">
				   						<span>Watch Later</span>
				   					</div>
				   					<div class="add-to-favorites">
				   						<img src="'.$this->getCurrentTemplatePath().'/images/add-to-list.png">
				   						<span>Add Favorite</span>
				   					</div>                             
					            </div>
							</div>
			';
			}
		}
		$GOLD_html .= $this->output('
					</div>
					<div class="movie_information" style="margin-left: 5px;">
						<div class="movie_description" style="border-top: 0px; border-top-style: none; padding-top: 0px;">
							<div class="movie_desc_row" style="margin-bottom: -2px;"><div class="movie_desc_title">'.$this->LANG('year_title').':</div><div class="movie_desc_content"><a href="'.$this->GOLD_ROOT().'year/'.$media['year'].'">'.$media['year'].'</a></div></div>
							<div class="movie_desc_row" style="line-height: 15px; padding-top: 0px; max-height: 100px; display: inline-block; max-height: 70px; overflow: hidden;"><div class="movie_desc_title">'.$this->LANG('genre_title').':</div><font style="float: none; font-family: Arial; font-size: 10px; font-weight: bold; color: #000; position: relative; top: -1px;">'.$genre.'</font></div>
							<div class="movie_desc_row"><div class="movie_desc_title">'.$this->LANG('language_title').':</div><div class="movie_desc_content"><a>'.$media['language'].'</a></div></div>
							<div class="movie_desc_row"><div class="movie_desc_title">'.$this->LANG('producer_title').':</div><div class="movie_desc_content">'.$rejisorebi.'</div></div>
							<div class="movie_desc_row" style="line-height: 15px; padding-top: 3px; padding-bottom: 8px; border-bottom: 1px solid rgb(202, 202, 202); border-bottom-style: dotted;"><div class="movie_desc_title">'.$this->LANG('actors_title').':</div> <font style="float: none; font-family: Arial; font-size: 10px; font-weight: bold; color: #000; position: relative; top: -1px;">'.$casts.'</font></div>
							<div class="movie_desc_row" style="line-height: 15px; padding-top: 7px; max-height: 100px; display: inline-block; max-height: 70px; overflow: hidden;"><div class="movie_desc_title" style="text-decoration: underline; font-weight: bold; color: rgb(173, 34, 34); font-size: 11px;">'.$this->LANG('description_title').':</div> <font style="float: none; position: relative; top: -1px; font-family: Arial; font-size: 11px; color: rgb(88, 88, 88);">'.$media['post_content'].'</font></div>
							<div style="display: inline-block; position: relative; bottom: -10px; width: 350px;">
								<div style="display: inline-block;position: absolute;bottom: 0;width: 100%;">
									<div class="imdb-badge arrow" style="display: inline-block;"><span>'.$media['imdb'].'</span></div>
								</div>
							</div>
						</div>
					</div>
				</div>


<aside class="shares social" style="float: left;">

<div class="share-buttons v2" style="float: left;"><div class="share-container">
  <div class="share-count"><span class="share-num">'.$shares_count.'</span><div class="caption">'.$this->LANG('shares').'</div></div>
  <div class="primary-shares nowhatsapp">
    <a href="http://www.facebook.com/share.php?u='.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$media['post_name'].'" class="social-share facebook" style="text-decoration: none;">
	  <svg style="fill: #fff; width: 23px; position: relative; top: 9px; margin: 0px 7px 0px 5px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
                    <path d="M27.825,4.783c0-2.427-2.182-4.608-4.608-4.608H4.783c-2.422,0-4.608,2.182-4.608,4.608v18.434
                        c0,2.427,2.181,4.608,4.608,4.608H14V17.379h-3.379v-4.608H14v-1.795c0-3.089,2.335-5.885,5.192-5.885h3.718v4.608h-3.726
                        c-0.408,0-0.884,0.492-0.884,1.236v1.836h4.609v4.608h-4.609v10.446h4.916c2.422,0,4.608-2.188,4.608-4.608V4.783z"></path>
      </svg>
      <span class="expanded-text">Facebook</span>
    </a>
    <a href="http://twitter.com/share?url='.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$media['post_name'].'&amp;text='.$media['post_title'].'" class="social-share twitter" style="text-decoration: none;">
	  <svg style="fill: #fff; width: 23px; position: relative; top: 9px; margin: 0px 7px 0px 5px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
                <path d="M24.253,8.756C24.689,17.08,18.297,24.182,9.97,24.62c-3.122,0.162-6.219-0.646-8.861-2.32
                    c2.703,0.179,5.376-0.648,7.508-2.321c-2.072-0.247-3.818-1.661-4.489-3.638c0.801,0.128,1.62,0.076,2.399-0.155
                    C4.045,15.72,2.215,13.6,2.115,11.077c0.688,0.275,1.426,0.407,2.168,0.386c-2.135-1.65-2.729-4.621-1.394-6.965
                    C5.575,7.816,9.54,9.84,13.803,10.071c-0.842-2.739,0.694-5.64,3.434-6.482c2.018-0.623,4.212,0.044,5.546,1.683
                    c1.186-0.213,2.318-0.662,3.329-1.317c-0.385,1.256-1.247,2.312-2.399,2.942c1.048-0.106,2.069-0.394,3.019-0.851
                    C26.275,7.229,25.39,8.196,24.253,8.756z"></path>
      </svg>
      <span class="expanded-text">Twitter</span>
    </a>
    <a href="https://plus.google.com/share?url='.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$media['post_name'].'" class="social-share google" style="text-decoration: none;">
        <svg style="fill: #fff; width: 23px; position: relative; top: 9px; margin: 0px 5px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
                                    <g>
                                        <g>
                                            <path d="M14.703,15.854l-1.219-0.948c-0.372-0.308-0.88-0.715-0.88-1.459c0-0.748,0.508-1.223,0.95-1.663
                                                c1.42-1.119,2.839-2.309,2.839-4.817c0-2.58-1.621-3.937-2.399-4.581h2.097l2.202-1.383h-6.67c-1.83,0-4.467,0.433-6.398,2.027
                                                C3.768,4.287,3.059,6.018,3.059,7.576c0,2.634,2.022,5.328,5.604,5.328c0.339,0,0.71-0.033,1.083-0.068
                                                c-0.167,0.408-0.336,0.748-0.336,1.324c0,1.04,0.551,1.685,1.011,2.297c-1.524,0.104-4.37,0.273-6.467,1.562
                                                c-1.998,1.188-2.605,2.916-2.605,4.137c0,2.512,2.358,4.84,7.289,4.84c5.822,0,8.904-3.223,8.904-6.41
                                                c0.008-2.327-1.359-3.489-2.829-4.731H14.703z M10.269,11.951c-2.912,0-4.231-3.765-4.231-6.037c0-0.884,0.168-1.797,0.744-2.511
                                                c0.543-0.679,1.489-1.12,2.372-1.12c2.807,0,4.256,3.798,4.256,6.242c0,0.612-0.067,1.694-0.845,2.478
                                                c-0.537,0.55-1.438,0.948-2.295,0.951V11.951z M10.302,25.609c-3.621,0-5.957-1.732-5.957-4.142c0-2.408,2.165-3.223,2.911-3.492
                                                c1.421-0.479,3.25-0.545,3.555-0.545c0.338,0,0.52,0,0.766,0.034c2.574,1.838,3.706,2.757,3.706,4.479
                                                c-0.002,2.073-1.736,3.665-4.982,3.649L10.302,25.609z"></path>
                                            <polygon points="23.254,11.89 23.254,8.521 21.569,8.521 21.569,11.89 18.202,11.89 18.202,13.604 21.569,13.604 21.569,17.004
                                                23.254,17.004 23.254,13.604 26.653,13.604 26.653,11.89"></polygon>
                                        </g>
                                    </g>
        </svg>
      <span class="expanded-text">Google</span>
    </a>
  </div>
  
</div></div>

</aside>
<div style="float: right; display: inline-block;">
    <span class="" style="color: #333; font-size: 27px; font-weight: bold; max-width: 90px; overflow: hidden; text-align: center; display: block;">'.$media['post_views'].'</span>
    <div class="caption" style="font-weight: bold; padding-top: 2px;">'.$this->LANG('views').'</div>
</div>

		');
	}


		
if($media['user_id'] == $_SESSION['user_id']) {
$GOLD_html .= $this->output('<div style="display:block;clear: both;">
  <a href="?remove=1"><img src="'.$this->getCurrentTemplatePath().'/images/icon-picture-remove.png'.'" style="width: 30px;"><span style="font-size: 16px;position: relative;top: -9px;margin-left: 8px;"><span style="color: #F00;">Remove Post</span></span></a>
</div>');
}
elseif($_SESSION['user_id']) {
$GOLD_html .= $this->output('
<style> .comments-div-flag a.flag { color:#F80000; } </style>
	<div class="flag '.$comments_div_flag.'" id="flag_buttons'.$media['post_id'].'">
		<a href="javascript:;" class="flag" type="post" id="'.$media['post_id'].'" user_id="'.$_SESSION['user_id'].'"><i aria-hidden="true" class="icon icon-flag" style="font-size: 27px; left: 10px; position: relative; top: 4px;"></i></a>
	</div>');
}

$GOLD_html .= $this->output('
</div>

</div>');
$fb_comments_sql = mysql_query("SELECT * FROM gold_blocks WHERE block_type='post' AND block_name='fb_comments' AND block_status='1' AND block_position='1'");
if(mysql_num_rows($fb_comments_sql) == '1') { $fbcom_active = "active"; } else { $fbcom_hidden = "style='display: block;'"; }
$GOLD_html .= $this->output('
<div style="background: #fff; border: 1px solid #E7E7E7; border-radius: 3px;">
    <ul class="comments_switch">
        <li><a class="comments-switch '.$fbcom_active.'" data-comments="#tab_fb_comments" href="javascript:void(0);">
			<svg style="fill: #fff; width: 23px; position: relative; top: 5px; margin: 0px 10px 0px 0px;" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28px" height="28px" viewBox="0 0 28 28" enable-background="new 0 0 28 28" xml:space="preserve">
                    <path d="M27.825,4.783c0-2.427-2.182-4.608-4.608-4.608H4.783c-2.422,0-4.608,2.182-4.608,4.608v18.434
                        c0,2.427,2.181,4.608,4.608,4.608H14V17.379h-3.379v-4.608H14v-1.795c0-3.089,2.335-5.885,5.192-5.885h3.718v4.608h-3.726
                        c-0.408,0-0.884,0.492-0.884,1.236v1.836h4.609v4.608h-4.609v10.446h4.916c2.422,0,4.608-2.188,4.608-4.608V4.783z"></path>
      		</svg>
			<span style="position: relative; top: -3px;">'.$this->LANG('comments').'</span>
		</a></li>
    </ul>
	<div class="comments_tab">
		<section id="tab_fb_comments" class="comments_section" '.$fbcom_hidden.'>
			<div class="fb-comments" data-href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$media['post_name'].'" data-numposts="20" data-width="570"></div>
		</section>
    </div>
</div>


</div>
');

$blocks_sql = mysql_query("SELECT * FROM gold_blocks WHERE block_type='post' AND block_status='1' ORDER BY block_position ASC");
			while($block_media = mysql_fetch_array($blocks_sql)) {
				$block_position = $block_media['block_name'];
				echo $block[$block_position];
			}
	
	$GOLD_html .= $this->GOLD_sidebar($media['post_id'], $media['user_id'], $media['post_title'], $media['category_id']);
		$GOLD_html .= $this->output('</div>'); 
	}
    return $GOLD_html;
  }
  
  
  public function GOLD_sort_page_category($sort, $category) {
  
	$GOLD_html .= $this->output('<div class="wrap cf">');
	
	if (!isset($_REQUEST['content']) or !is_numeric($_REQUEST['content'])) { $content = 0; } else { $content = (int)$_REQUEST['content']; }
	$limit = $content.", ".$this->set('gold_rows');

	if($sort == '') {
		$q = mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '%".$category."%' AND post_status='1' ORDER BY post_id DESC LIMIT ".$limit."");
	} elseif($sort == 'recent') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '%".$category."%' AND post_status='1' ORDER BY post_id DESC LIMIT ".$limit."");
	} elseif($sort == 'title') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '%".$category."%' AND post_status='1' ORDER BY post_title ASC LIMIT ".$limit."");
	} elseif($sort == 'rating') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '%".$category."%' AND post_created > DATE_SUB(NOW(), INTERVAL 1 YEAR) AND post_status='1' ORDER BY post_views DESC LIMIT 100");
	} elseif($sort == 'year') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '%".$category."%' AND post_status='1' ORDER BY year ASC LIMIT ".$limit."");
	} elseif($sort == 'imdb') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '%".$category."%' AND post_status='1' ORDER BY imdb DESC LIMIT ".$limit."");
	} elseif($sort == 'hot') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '%".$category."%' AND post_created > DATE_SUB(NOW(), INTERVAL 3 YEAR) AND post_status='1' ORDER BY post_views DESC LIMIT ".$limit."");
	} elseif($sort == 'votes') {
	    $q = mysql_query("SELECT p.* , COUNT(votes.post_id) AS votes_post_id FROM gold_votes votes LEFT JOIN gold_posts p ON votes.post_id = p.post_id WHERE p.category_id LIKE '%".$category."%' GROUP BY votes.post_id ORDER BY votes_post_id DESC LIMIT ".$limit."");
	} elseif($sort == 'comments') {
	    $q = mysql_query("SELECT p.* , COUNT(c.post_id) AS c_post_id FROM gold_comments c LEFT JOIN gold_posts p ON c.post_id = p.post_id WHERE p.category_id LIKE '%".$category."%' GROUP BY p.post_id ORDER BY c_post_id DESC LIMIT ".$limit."");
	} elseif($sort == 'views') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '%".$category."%' AND post_status='1' ORDER BY post_views DESC LIMIT ".$limit."");
	}
	echo $this->GOLD_box($q, $content);
	
	$GOLD_html .= $this->output('</div>'); 
    return $GOLD_html;
  }
  
  
  public function GOLD_sort_page($sort) {
  
	$GOLD_html .= $this->output('<div class="wrap cf">');
	
	if (!isset($_REQUEST['content']) or !is_numeric($_REQUEST['content'])) { $content = 0; } else { $content = (int)$_REQUEST['content']; }
	$limit = $content.", ".$this->set('gold_rows');
	
	if($sort == '') {
		$q = mysql_query("SELECT * FROM gold_posts WHERE post_status='1' ORDER BY post_id DESC LIMIT ".$limit."");
	} elseif($sort == 'recent') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE post_status='1' ORDER BY post_id DESC LIMIT ".$limit."");
	} elseif($sort == 'title') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE post_status='1' ORDER BY post_title ASC LIMIT ".$limit."");
	} elseif($sort == 'rating') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE post_created > DATE_SUB(NOW(), INTERVAL 1 YEAR) AND post_status='1' ORDER BY post_views DESC LIMIT 100");
	} elseif($sort == 'year') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE post_status='1' ORDER BY year ASC LIMIT ".$limit."");
	} elseif($sort == 'imdb') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE post_status='1' ORDER BY imdb DESC LIMIT ".$limit."");
	} elseif($sort == 'hot') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE post_created > DATE_SUB(NOW(), INTERVAL 1 YEAR) AND post_status='1' ORDER BY post_views DESC LIMIT 100") or die(mysql_error());
	} elseif($sort == 'votes') {
	    $q = mysql_query("SELECT p.* , COUNT(votes.post_id) AS votes_post_id FROM gold_votes votes LEFT JOIN gold_posts p ON votes.post_id = p.post_id GROUP BY votes.post_id ORDER BY votes_post_id DESC LIMIT ".$limit."");
	} elseif($sort == 'comments') {
	    $q = mysql_query("SELECT p.* , COUNT(c.post_id) AS c_post_id FROM gold_comments c LEFT JOIN gold_posts p ON c.post_id = p.post_id GROUP BY p.post_id ORDER BY c_post_id DESC LIMIT ".$limit."");
	} elseif($sort == 'views') {
	    $q = mysql_query("SELECT * FROM gold_posts WHERE post_status='1' ORDER BY post_views DESC LIMIT ".$limit."");
	}

	echo $this->GOLD_box($q, $content);
	
	$GOLD_html .= $this->output('</div>'); 
    return $GOLD_html;
  }
  
  public function GOLD_tags_page($question_tags) {
  
	$GOLD_html .= $this->output('<div class="full_wrap cf">
		<div class="wrap_high" style="overflow: hidden; background: #fff; border: 1px solid #E7E7E7; border-radius: 5px;float: left; margin-bottom: 100px;">
			<div class="wrap_normal" style="padding: 10px;">');
	$GOLD_html .= $this->output('<ul class="ul_category">');
	
	$page = $_GET['sub2_gold'];
	if($page == '') { 
		$query_1 = mysql_query('SELECT * FROM gold_posts ORDER BY post_id DESC');
		while($tags = mysql_fetch_array($query_1)) {
			$GOLD_CHECK_CATEGORY = mysql_query("SELECT * FROM `gold_categories` WHERE category_id = '".$tags['category_id']."%'");
			$GOLD_CATEGORY = mysql_fetch_array($GOLD_CHECK_CATEGORY);
			
			$GOLD_html .= $this->output('<a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$tags['post_name'].'" alt="'.$tags['post_title'].'" title="'.$tags['post_title'].'" class="tag_name" style="text-decoration: none;"><b style="font-size: 15px; color: #F5FF00;font-weight: normal;position: relative;top: -1px;left: -1px;">'.$tags['post_title'].'</b></a>');
		}
	} elseif($page != '') { 
		
		if($page == '1') { $website_keywords = $this->set("gold_website_keywords"); } else { $website_keywords = $this->set("gold_website_keywords_".$page); } 
		$tags = explode(",", $website_keywords);
		foreach($tags as &$tag) {
			$tag = '<a href="'.$this->GOLD_ROOT().'" class="tag_name" style="text-decoration: none;"><b style="font-size: 15px; color: #F5FF00;font-weight: normal;position: relative;top: -1px;left: -1px;">'.$tag.'</b></a>';
		}
		echo implode(" ", $tags);
	}
	
	$GOLD_html .= $this->output('</ul>');
	$GOLD_html .= $this->output('</div></div>'); 
	$GOLD_html .= $this->GOLD_sidebar('', '', '', '');
	$GOLD_html .= $this->output('</div>'); 
    return $GOLD_html;
  }
  
  public function GOLD_pages($q) {

  	if($this->GOLD_REQUEST('sub_gold') == '') {

  	$q = mysql_query("SELECT * FROM gold_pages ORDER BY page_id ASC");
	$GOLD_html .= $this->output('<div class="full_wrap cf">
		<div class="wrap_high" style="overflow: hidden; background: #fff; border: 1px solid #E7E7E7; border-radius: 5px;float: left; margin-bottom: 100px;">
			<div class="wrap_normal" style="padding: 10px;">');
	$GOLD_html .= $this->output('<ul class="ul_category">');
	
	while($media = mysql_fetch_array($q)) {
		$GOLD_html .= $this->output('<li><a href="'.$this->GOLD_ROOT().'pages/'.$media['name'].'" class="category">'.$media['title'].'</a></li>');
	}
	
	$GOLD_html .= $this->output('</ul>');
	$GOLD_html .= $this->output('</div></div>'); 
	$GOLD_html .= $this->GOLD_sidebar('', '', '', '');
	$GOLD_html .= $this->output('</div>');

	} else {

  	if($this->GOLD_REQUEST('error') == '1') {
		if($this->GOLD_REQUEST('full_name') == '') {
  			echo '<style>em#err_full_name { display: inline-block; color: #FF0000; margin-left: 5px; }</style>';
		}
		if($this->GOLD_REQUEST('email') == '') {
  			echo '<style>em#err_email { display: inline-block; color: #FF0000; margin-left: 5px; }</style>';
		}
		if($this->GOLD_REQUEST('comments') == '') {
  			echo '<style>em#err_comments { display: inline-block; color: #FF0000; margin-left: 5px; }</style>';
		}
	}
	elseif($this->GOLD_REQUEST('success') == '1') {
		echo '<style>b.success { display: block; }</style>';
	}
	$GOLD_html .= $this->output('<div class="full_wrap cf">
		<div class="wrap_high" style="overflow: hidden; background: #fff; border: 1px solid #E7E7E7; border-radius: 5px;float: left; margin-bottom: 100px;">
			<div class="wrap_normal" style="padding: 10px;">');
	while($pages = mysql_fetch_array($q)) {
			$GOLD_html .= $this->output('<span class="pages_title">'.$pages['title'].'</span>');
			$GOLD_html .= $this->output('<span class="pages_content">'.$pages['content'].'</span>');
	}
	
	$GOLD_html .= $this->output('</div></div>'); 
	$GOLD_html .= $this->GOLD_sidebar('', '', '', '');
	$GOLD_html .= $this->output('</div>'); 

	}
    return $GOLD_html;
  }
  
  public function GOLD_categories_page() {
    $q = mysql_query("SELECT * FROM gold_categories WHERE parent_id='' ORDER BY category_id ASC");
	$GOLD_html .= $this->output('<div class="full_wrap cf">
		<div class="wrap_high" style="overflow: hidden; background: #fff; border: 1px solid #E7E7E7; border-radius: 5px;float: left; margin-bottom: 100px;">
			<div class="wrap_normal" style="padding: 10px;">');
	$GOLD_html .= $this->output('<ul class="ul_category">');
	
	while($media = mysql_fetch_array($q)) {
		$media_post_num = mysql_num_rows(mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '".$media['category_id']."' AND post_status='1'"));
		$GOLD_html .= $this->output('<li><a href="'.$this->GOLD_ROOT().'genre/'.$media['name'].'" class="category">'.$media['title'].'</a>');
		
		$q2 = mysql_query("SELECT * FROM gold_categories WHERE parent_id='".$media['category_id']."' ORDER BY category_id ASC");
			$GOLD_html .= $this->output('<ul class="ul_category">');
		while($media2 = mysql_fetch_array($q2)) {
			$media2_post_num = mysql_num_rows(mysql_query("SELECT * FROM gold_posts WHERE category_id LIKE '".$media2['category_id'].",' AND post_status='1'"));
    			$GOLD_html .= $this->output('<li style="list-style-type: square;"><a href="'.$this->GOLD_ROOT().'genre/'.$media2['name'].'" class="category">'.$media2['title'].'</a></li>');
		}
			$GOLD_html .= $this->output('</ul>');
    	$GOLD_html .= $this->output('</li>');
	}
	
	$GOLD_html .= $this->output('</ul>');
	$GOLD_html .= $this->output('</div></div>'); 
	$GOLD_html .= $this->GOLD_sidebar('', '', '', '');
	$GOLD_html .= $this->output('</div>'); 
    return $GOLD_html;
  }
  
  public function GOLD_top_users_page() {
    $q = mysql_query("SELECT * FROM gold_categories WHERE parent_id='' ORDER BY category_id ASC");
	$GOLD_html .= $this->output('<div class="full_wrap cf">
		<div class="wrap_high" style="overflow: hidden; background: #fff; border: 1px solid #E7E7E7; border-radius: 5px;float: left; margin-bottom: 100px;">
			<div class="wrap_normal" style="padding: 10px;">');
		
		$per_page = "50";
	    $page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
	    if ($page <= 0) $page = 1;
	    $startpoint = ($page * $per_page) - $per_page;
 
	$top_level_users_sql = mysql_query("SELECT @rownum:=@rownum+1 'user_rank', u.* from gold_users u, (SELECT @rownum:=0) r ORDER BY user_points DESC LIMIT {$startpoint} , {$per_page};");
		$GOLD_html .= $this->output('<ol class="top_users_list" style="margin-top: 20px;">');
		while($top_level_users = mysql_fetch_array($top_level_users_sql)) {
					$GOLD_html .= $this->output('
								<li>
								<a href="'.$this->GOLD_ROOT().'user/'.$top_level_users['user_username'].'">
									<div class="top_user_avatar">
										<div class="top_user_avatar_box">
											<img src="'.$this->GOLD_ROOT().'gold-app/gold-uploads/avatars/'.$top_level_users['user_avatar'].'" class="rounded responsiveImg">
                          					<span class="top_user_rank">'.$top_level_users['user_rank'].'</span>
										</div>
										<h3 class="top_user_name">
											<span class="username">'.ucfirst($top_level_users['user_username']).'</span>
                      						<span class="top_user_points">
												<span id="icon-trophy" class="icon-trophy" style="font-size: 17px; margin-top: -7px; display: block; padding-left: 1px;"></span>
												<span style="margin-left: 4px;position: relative;top: 2px;"><b style="color: #C00500;">'.$top_level_users['user_points'].'</b> '.$this->LANG('points').'</span>
											</span>
										</h3>
									</div>
								</a>
								</li>');
		}
		$GOLD_html .= $this->output('</ol>');
	
	  // displaying paginaiton.
	  $GOLD_html .= $this->pagination("gold_users", 'user_id', $per_page);
	  
	$GOLD_html .= $this->output('</div></div>'); 
	$GOLD_html .= $this->GOLD_sidebar('', '', '', '');
	$GOLD_html .= $this->output('</div>'); 
    return $GOLD_html;
  }
  
  public function GOLD_sidebar($media_id, $user_id, $media_title, $media_category_id) {
		$user_sql = mysql_query("SELECT * FROM gold_users WHERE user_id='".$user_id."'");
		$user = mysql_fetch_array($user_sql);

		$GOLD_html .= $this->output('<div class="sidebar_main" style="float: left;"><div class="post_sidebar">');
		if($this->set('gold_boxtype') == '2' || $this->GOLD_REQUEST('gold') == 'post' || $this->GOLD_REQUEST('gold') == 'submit' || $this->GOLD_REQUEST('gold') == 'top_users' || $this->GOLD_REQUEST('gold') == 'category' || $this->GOLD_REQUEST('sub_gold') == 'tag' || $this->GOLD_REQUEST('gold') == 'pages' || $this->GOLD_REQUEST('gold') == 'login' || $this->GOLD_REQUEST('gold') == 'register' || $this->GOLD_REQUEST('gold') == 'forgot') {
			
			$block['top_media'] .= '<div class="side_box" style="clear: both;margin-bottom: 20px;">';
				$block['top_media'] .= '<span class="title">'.$this->LANG('interesting_movies').'</span>';
				$block['top_media'] .= '<span class="content">';
				
				$related_media_sql = mysql_query("SELECT * FROM gold_posts WHERE post_created > DATE_SUB(NOW(), INTERVAL 1 YEAR) ORDER BY post_views DESC LIMIT ".$this->set("gold_max_related_media")."");
				while($related_media = mysql_fetch_array($related_media_sql)) {
					
					if($related_media['post_type'] == '0') {
						$post_type = '<img class="related_img" src="'.$this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$related_media['post_thumb'].'" alt="'.$related_media['post_title'].'" title="'.$related_media['post_title'].'">';
					}
    				elseif($related_media['post_type'] == '1') {
						
     				}
					$GOLD_CHECK_CATEGORY = mysql_query("SELECT * FROM `gold_categories` WHERE category_id = '".$related_media['category_id']."%'");
					$GOLD_CATEGORY = mysql_fetch_array($GOLD_CHECK_CATEGORY);
					
					$block['top_media'] .= '<div class="related-media">';
						$block['top_media'] .= '<a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$related_media['post_name'].'" style="text-decoration: none;">'.$post_type.'</a>';
						$block['top_media'] .= '<div class="related_info">';
							$block['top_media'] .= '<a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$related_media['post_name'].'" style="text-decoration: none;">';
								$block['top_media'] .= '<div class="relatedTitle" style="text-decoration: none;">'.$related_media['post_title'].'</div>';
							$block['top_media'] .= '</a>';
							$block['top_media'] .= '<a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$related_media['post_name'].'">';
								$block['top_media'] .= '<a href="'.$this->GOLD_ROOT().'genre/'.$GOLD_CATEGORY['name'].'" class="relatedCategoryType" style="text-decoration: none;">'.$GOLD_CATEGORY['title'].'</a>';
							$block['top_media'] .= '</a>';
						$block['top_media'] .= '</div>';
					$block['top_media'] .= '</div>';
				}
				if(mysql_num_rows($related_media_sql) == '0') {
					$block['top_media'] .= '<span class="no_related_media">';
						$block['top_media'] .= ''.$this->LANG('no_top_media').'.';
					$block['top_media'] .= '</span>';
				}
				$block['top_media'] .= '</span>';
			$block['top_media'] .= '</div>';
			
			$block['categories'] .= '
			
			<div class="side_box" style="margin-bottom: 20px;width: 144px;float: left;margin-right: 10px;">
				<span class="title" style="width: 144px;">'.$this->LANG('movie_genres').'</span>
				<span class="content" style="margin-top: 5px; margin-bottom: 0px;width: 144px;">
					<div style="margin-bottom: -1px;">
						<ul class="categories">';
							$query_of_categories = mysql_query("SELECT * FROM gold_categories WHERE status='1'");
							while($category = mysql_fetch_array($query_of_categories)) {
								if($this->GOLD_REQUEST("sub_gold") == $category['name']) { $active = "style='color: rgb(255, 0, 0); text-decoration: none;'"; } elseif($this->GOLD_REQUEST("sub_gold") != $category['name']) { $active = ''; }
								$block['categories'] .= "<li><a href='".$this->GOLD_ROOT().'genre/'.$category['name']."' ".$active.">".$category['title']."</a></li>";
							}
			
			$block['categories'] .= '

						</ul>
					</div>
				</span>
			</div>

			<div class="side_box" style="margin-bottom: 20px;width: 144px;float: left;">
				<span class="title" style="width: 144px;">'.$this->LANG('random_movies').'</span>
				<span class="content" style="margin-top: 5px; margin-bottom: 0px;width: auto;">
					<div style="margin-bottom: -1px;">';

						$POPULAR_MOVIES_QUERY = mysql_query("SELECT * FROM gold_posts WHERE post_status='1' ORDER BY rand() DESC LIMIT 3");
						while($popular_movies = mysql_fetch_array($POPULAR_MOVIES_QUERY)) {
							$GOLD_CHECK_CATEGORY_second = mysql_query("SELECT * FROM `gold_categories` WHERE category_id = '".$popular_movies['category_id']."%'");
							$GOLD_CATEGORY_second = mysql_fetch_array($GOLD_CHECK_CATEGORY_second);
							$block['categories'] .= '<div class="pop_img"><a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY_second['name'].'/'.$popular_movies['post_name'].'"><img src="'.$this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$popular_movies['post_thumb'].'" alt="'.$popular_movies['post_title'].'" title="'.$popular_movies['post_title'].'" style="width: 130px; height: 185px;"></div>';
						}
			$block['categories'] .= '
					</div>
				</span>
			</div>
';
			
			
			$block['sidebar_advert'] .= '<div class="side_box" style="clear: both; margin-bottom: 20px;">';
				$block['sidebar_advert'] .= '<span class="title">'.$this->LANG('advert').'</span>';
				$block['sidebar_advert'] .= '<span class="content">';
					$block['sidebar_advert'] .= $this->widget_echo('SidebarAdvert');
				$block['sidebar_advert'] .= '</span>';
			$block['sidebar_advert'] .= '</div>';
			
		
			
			}
			$block['facebook_box'] .= '<div class="side_box" style="clear: both;margin-bottom: 20px;">';
				$block['facebook_box'] .= '<span class="title">'.$this->LANG('facebook_box').'</span>';
				$block['facebook_box'] .= '<span class="content">';
				
				$block['facebook_box'] .= '<iframe src="//www.facebook.com/plugins/likebox.php?href='.$this->widget_echo('FacebookBox').'&amp;width=285&amp;height=320&amp;colorscheme=light&amp;show_faces=true&amp;header=true&amp;stream=false&amp;show_border=false" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width: 285px; height: 320px;padding: 0px 7px;" allowtransparency="true"></iframe>';
				
				$block['facebook_box'] .= '</span>';
			$block['facebook_box'] .= '</div>';
			
			$block['twitter_box'] .= '<div class="side_box" style="clear: both;margin-bottom: 20px;">';
				$block['twitter_box'] .= '<span class="title">'.$this->LANG('twitter_box').'</span>';
				$block['twitter_box'] .= '<span class="content">';
				
				$block['twitter_box'] .= '<div style="padding:0px 10px;"><a class="twitter-timeline" data-dnt="true" href="'.$this->widget_echo('TwitterBox').'" data-widget-id="508489276149870592">Твиты пользователя @envato</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script></div>';
				
				$block['twitter_box'] .= '</span>';
			$block['twitter_box'] .= '</div>';
			
			$blocks_sql = mysql_query("SELECT * FROM gold_blocks WHERE block_type='main' AND block_status='1' ORDER BY block_position ASC");
			while($block_media = mysql_fetch_array($blocks_sql)) {
				$block_position = $block_media['block_name'];
				echo $block[$block_position];
			}
			
		
		$GOLD_html .= $this->output('</div></div>');
		$GOLD_html .= $this->output('<div class="counters">
			</div>');
	return $GOLD_html;
  }
  
  public function GOLD_profile($q) {
	if (!isset($_REQUEST['content']) or !is_numeric($_REQUEST['content'])) { $content = 0; } else { $content = (int)$_REQUEST['content']; }
	$limit = $content.", ".$this->set('gold_rows');
	
    if($this->GOLD_REQUEST('sub2_gold') == '') {
		$selected_main = ' class="selected"';
	} elseif($this->GOLD_REQUEST('sub2_gold') == 'details') {
		$selected_details = ' class="selected"';
	} elseif($this->GOLD_REQUEST('sub2_gold') == 'history') {
		$selected_history = ' class="selected"';
	} elseif($this->GOLD_REQUEST('sub2_gold') == 'favorites') {
		$selected_favorites = ' class="selected"';
	}
	$GOLD_html .= $this->output('<div class="full_wrap cf">'); 
		while($media = mysql_fetch_array($q)) {
			if($_SESSION['user_id'] != '') { $details_info = '<a href="'.$this->GOLD_ROOT().'user/'.$media['user_username'].'/details" '.$selected_details.'><i class="edit"></i>'.$this->LANG('edit_details').'</a>'; }
			$GOLD_html .= $this->output('
			<div id="article-tabs">
				<a href="'.$this->GOLD_ROOT().'user/'.$media['user_username'].'" '.$selected_main.'><i class="home"></i>'.$this->LANG('information').'</a>
				'.$details_info.'
				<a href="'.$this->GOLD_ROOT().'user/'.$media['user_username'].'/favorites" '.$selected_favorites.'><i class="favorited"></i>'.$this->LANG('favorites').'</a>
				<a href="'.$this->GOLD_ROOT().'user/'.$media['user_username'].'/watchlater" '.$selected_watchlater.'><i class="watchlater"></i>'.$this->LANG('watchlater').'</a>
				<a href="'.$this->GOLD_ROOT().'user/'.$media['user_username'].'/history" '.$selected_history.' style="border-right: 1px solid rgba(232, 232, 232, 0);"><i class="contributed"></i>'.$this->LANG('history').'</a>
			</div>
			<div id="profile_page">');
			
			if($this->GOLD_REQUEST('sub2_gold') == '') {
				if(date("Y-m-d", strtotime($media['user_created'])) != '') { $member_registered = '<span class="row_size_block">'.date("Y-m-d", strtotime($media['user_created'])).'</span>'; }
				if($this->Group($media['user_group']) != '') { $user_group = '<span class="row_size_block">'.$this->Group($media['user_group']).'</span>'; }
				if(ucfirst($media['user_username']) != '') { $username = '<span class="row_size_block" style="background: #d74440;">'.ucfirst($media['user_username']).'</span>'; }
				if(ucfirst($media['user_fullname']) != '') { $fullname = '<span class="row_size_block" style="background: #d74440;">'.ucfirst($media['user_fullname']).'</span>'; }
				if($media['user_email'] != '') { $email = '<span class="row_size_block" style="background: #d74440;">'.$media['user_email'].'</span>'; }
				if(ucfirst($media['user_location']) != '') { $location = '<span class="row_size_block">'.ucfirst($media['user_location']).'</span>'; }
				if($media['user_website'] != '') { $website = '<span class="row_size_block">'.$media['user_website'].'</span>'; }
				$GOLD_html .= $this->output('<div class="wrap_high container_page" style="float: left; background-color: #fff; border-radius: 5px; -webkit-box-shadow: 0px 0px 15px 0px rgba(50, 50, 50, 1); -moz-box-shadow: 0px 0px 15px 0px rgba(50, 50, 50, 1); box-shadow: 0px 0px 15px 0px rgba(187, 187, 187, 1);">');
					$GOLD_html .= $this->output('<div class="wrap_normal profile_content" style="width: 592px; padding: 20px 0px 10px 0px;">');
						$GOLD_html .= $this->output('
							<div id="information_table" style="margin-bottom: 60px;">
								<div class="information_row"><div class="row_size_60">'.$this->LANG('member_registered').':</div><div class="row_size_40">'.$member_registered.'</div></div>
								<div class="information_row"><div class="row_size_60">'.$this->LANG('user_group').':</div><div class="row_size_40">'.$user_group.'</div></div>
								<div class="information_row"><div class="row_size_60">'.$this->LANG('username').':</div><div class="row_size_40">'.$username.'</div></div>
								<div class="information_row"><div class="row_size_60">'.$this->LANG('fullname').':</div><div class="row_size_40">'.$fullname.'</div></div>
								<div class="information_row"><div class="row_size_60">'.$this->LANG('location').':</div><div class="row_size_40">'.$location.'</div></div>
								<div class="information_row"><div class="row_size_60">'.$this->LANG('website').':</div><div class="row_size_40">'.$website.'</div></div>
								<div class="information_row"><div class="row_size_60">'.$this->LANG('about').':</div><div class="row_size_40" style="font-size: 12px; margin-left: 1px;">'.ucfirst($media['user_about']).'</div></div>
							</div>
							<div class="information_table_hr">'.$this->LANG('statistics_of').' <span style="color: #F00; padding-left: 3px;">'.ucfirst($media['user_username']).'</span></div>
							<div id="information_table">
								<div class="information_row"><div class="row_size_60">'.$this->LANG('uploaded_media').':</div><div class="row_size_40"><span class="row_size_block" style="background: #d74440;"><b>'.$this->Uploaded_Media($media['user_id']).'</b></span></div></div>
							</div>
							');
					$GOLD_html .= $this->output('</div>');
				$GOLD_html .= $this->output('</div>');
			} elseif($this->GOLD_REQUEST('sub2_gold') == 'details') {
			    if($_SESSION['user_username'] == $media['user_username']) { } else {
					header('Location: '.$this->GOLD_ROOT().'');
				}
				if($this->GOLD_REQUEST('error_username') != '') {
					$already_taken = '<span style="font-size: 14px;margin-left: 10px;top: -1px;position: relative;">'.$this->LANG('already_taken').'</span>';
				}
				
				$GOLD_html .= $this->output('<div class="wrap_high container_page" style="float: left; background-color: #fff; border-radius: 5px; -webkit-box-shadow: 0px 0px 15px 0px rgba(50, 50, 50, 1); -moz-box-shadow: 0px 0px 15px 0px rgba(50, 50, 50, 1); box-shadow: 0px 0px 15px 0px rgba(187, 187, 187, 1);">');
					$GOLD_html .= $this->output('<div class="wrap_normal profile_content" style="width: 592px; padding: 20px 0px 10px 0px;">');
						$GOLD_html .= $this->output('<style>.container_class input[type=submit] {display: inline-block; color: #fff; background-color: #EC3A39; margin-top: 10px; padding: 9px 20px; border: 1px solid #EC3A39; border-radius: 3px; box-shadow: 0 1px 0 rgba(0,0,0,0.08); font: bold 14px/20px "Helvetica Neue",Arial,Helvetica,Geneva,sans-serif; cursor: pointer; text-decoration: none; display: inline-block; text-align: center; -webkit-appearance: none; }</style>
				<div class="container_class" style="width: 80%; padding: 20px 0px;">
				<form id="upload-form" action="'.$this->GOLD_ROOT().'gold-app/gold-includes/GOLD.php" method="POST" enctype="multipart/form-data">
					<div class="field">
						<label>'.$this->LANG('username').' <span style="color:#EC3A39;">* '.$already_taken.'</span></label>
						<input type="text" required="" name="user_username" value="'.$media['user_username'].'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
					</div>
					<div class="field">
						<label>'.$this->LANG('email').' <span style="color:#EC3A39;">*</span></label>
						<input type="text" required="" name="user_email" value="'.$media['user_email'].'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
					</div>
					<div id="drag-upload-container" style="margin-top: 0px; margin-bottom: 22px;">
						<input id="add-file" type="file" class="field" name="avatar" accept="image/gif,image/jpeg,image/jpg,image/png">
						<span id="file-upload-input">'.$this->LANG('upload_avatar').'</span>
					</div>
					<div class="field">
						<label>'.$this->LANG('fullname').' <span style="color:#EC3A39;">*</span></label>
						<input type="text" name="user_fullname" value="'.$media['user_fullname'].'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
					</div>
					<div class="field">
						<label>'.$this->LANG('location').' <span style="color:#EC3A39;">*</span></label>
						<input type="text" name="user_location" value="'.$media['user_location'].'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
					</div>
					<div class="field">
						<label>'.$this->LANG('website').' <span style="color:#EC3A39;">*</span></label>
						<input type="text" name="user_website" value="'.$media['user_website'].'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
					</div>
					<div class="field">
						<label>'.$this->LANG('about').' <span style="color:#EC3A39;">*</span></label>
						<textarea name="user_about" style="width: 100%;height:100px;outline:0;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">'.$media['user_about'].'</textarea>
					</div>
					<input type="submit" name="submit_edit_profile" value="'.$this->LANG('save_changes').'">
				</form>
				</div>
				
				
				<div class="information_table_hr">'.$this->LANG('change_password').'</div>
				
				
				<div class="container_class" style="width: 80%; padding: 20px 0px;">
				<form id="upload-form" action="'.$this->GOLD_ROOT().'gold-app/gold-includes/GOLD.php" method="POST" enctype="multipart/form-data">
					<div class="field">
						<label>'.$this->LANG('password').' <span style="color:#EC3A39;">* '.$already_taken.'</span></label>
						<input type="password" name="user_password" value="" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
					</div>
					<input type="submit" name="submit_edit_password" value="'.$this->LANG('save_changes').'">
				</form>
				</div>
							');
					$GOLD_html .= $this->output('</div>');
				$GOLD_html .= $this->output('</div>');
			} elseif($this->GOLD_REQUEST('sub2_gold') == 'wall') {
				$GOLD_html .= $this->output('<div class="wrap_normal container_page" style="float: left;">');
					$no_sql = mysql_query("SELECT * FROM gold_posts WHERE user_id='".$media['user_id']."' AND post_status='1' ORDER BY post_id DESC");
					if(mysql_num_rows($no_sql) == '0') {
						$GOLD_html .= $this->output('<div class="no_results">');
							$GOLD_html .= $this->output('<img src="'.$this->getCurrentTemplatePath().'/images/icon-results.png" style="width: 111px;">');
							$GOLD_html .= $this->output('<span style="font-size: 25px;font-weight: normal;display: block;">'.$this->LANG('no_results').'</span>');
						$GOLD_html .= $this->output('</div>');
					} else {
						$GOLD_html .= $this->GOLD_box(mysql_query("SELECT * FROM gold_posts WHERE user_id='".$media['user_id']."' AND post_status='1' ORDER BY post_id DESC LIMIT ".$limit.""), $content, "wall");
					}
				$GOLD_html .= $this->output('</div>');
			} elseif($this->GOLD_REQUEST('sub2_gold') == 'favorites') {
				$GOLD_html .= $this->output('<div class="wrap_normal container_page" style="width: 592px; float: left;">');
					$no_sql = mysql_query("SELECT posts.*, votes.* FROM gold_posts posts, gold_favorites votes WHERE posts.post_id=votes.post_id AND votes.user_id='".$media['user_id']."' AND posts.post_status='1' ORDER BY posts.post_id DESC");
					if(mysql_num_rows($no_sql) == '0') {
						$GOLD_html .= $this->output('<div class="no_results">');
							$GOLD_html .= $this->output('<img src="'.$this->getCurrentTemplatePath().'/images/icon-results.png" style="width: 111px;">');
							$GOLD_html .= $this->output('<span style="font-size: 25px;font-weight: normal;display: block;">'.$this->LANG('no_results').'</span>');
						$GOLD_html .= $this->output('</div>');
					} else {
						$GOLD_html .= $this->GOLD_box(mysql_query("SELECT posts.*, votes.* FROM gold_posts posts, gold_favorites votes WHERE posts.post_id=votes.post_id AND votes.user_id='".$media['user_id']."' AND posts.post_status='1' ORDER BY posts.post_id DESC LIMIT ".$limit.""), $content, "favorites");
					}
				$GOLD_html .= $this->output('</div>');
			}
			
			if($this->set('gold_boxtype') == '2' && $GET != 'wall' && $GET != 'favorites') {
				$GOLD_html .= $this->output(''.$this->GOLD_sidebar('', $media['user_id'], '', '').'</div></div></div></div>');
			}
			
	
			$GOLD_html .= $this->output('</div>
			</div>
  ');
		}
	$GOLD_html .= $this->output('</div>');
	
    return $GOLD_html;
  }
  
  public function GOLD_submit() {
	  
	if($this->GOLD_REQUEST('sub_gold') == '' || $this->GOLD_REQUEST('sub_gold') == 'image') {
		  
	  if($_REQUEST['error'] == '1') {
			$error['form'] = '<font style="color: #000; color: rgb(253, 3, 3); padding-bottom: 10px; display: inline-block; font-weight: bold;">Fill out all of fields !</font><br />';
	  }
	  $GOLD_html .= $this->output('<style>@media only screen and (max-width:1170px){ .full_wrap{width: 90%;} } #back_to { text-decoration: none;
  color: #FFF;
  background-color: #26a69a;
  text-align: center;
  letter-spacing: 0.5px;
  -webkit-transition: 0.2s ease-out;
  -moz-transition: 0.2s ease-out;
  -o-transition: 0.2s ease-out;
  -ms-transition: 0.2s ease-out;
  transition: 0.2s ease-out;
  cursor: pointer; }</style>
<div class="wrap_high" id="site_page" style="width: 100%!important; display: inline-block;">
<div style="width: 592px; margin: 0 auto;">
<a href="/admin" id="back_to" class="back_to btn" style="margin-top: -16px; position: absolute; right: 178px;">
	<font style="margin-top: 1px; display: inline-block;">Back to Admin</font>
</a>
    <p class="h3 modal-title" id="add-title">'.$this->LANG('add_image').'</p>
	<form class="wrap_normal" id="upload-form" action="'.$this->GOLD_ROOT().'gold-app/gold-includes/GOLD.php" method="POST" enctype="multipart/form-data">
		'.$error['form'].'
		<label>Movie Title</label><br />
		<input type="text" id="upload-title" name="title" class="field" pattern=".{3,128}" value="'.$_POST['title'].'" placeholder="Movie Title" required="required" style="margin-right: 8px;">
		
		<label>Movie Year & IMDb Rating</label><br />
		<input type="text" id="upload-title" name="year" class="field" pattern=".{3,128}" value="'.$_POST['year'].'" placeholder="Movie Year" required="required" style="width: 265px; float: left; margin-right: 8px;">
		<input type="text" id="upload-title" name="imdb" class="field" pattern=".{3,128}" value="'.$_POST['imdb'].'" placeholder="IMDb Rating" required="required" style="width: 265px; float: left;">
		
		<a id="FETCH_MOVIE" onclick="FETCH_MOVIE()">Fetch Movie</a>

		<label>Choose Movie Genre </label>
		<p style="height: 140px; overflow: auto; border: 5px solid #eee; background: #eee; color: #000; padding-left: 5px; margin-bottom: 1.5em; -webkit-margin-before: 0.2em; -webkit-margin-after: 1em;">');
		$categories_query = mysql_query("SELECT * FROM gold_categories WHERE status='1'");
		while($cat = mysql_fetch_array($categories_query)) {
			$GOLD_html .= $this->output('<label style="line-height: 0px;"><input type="checkbox" name="genre[]" value="'.$cat['category_id'].'"> '.$cat['title'].'</label><br>');
		}
		
	$GOLD_html .= $this->output('

		</p>
		
		<input type="text" id="upload-title" name="directed_by" class="field" pattern=".{3,128}" value="'.$_POST['directed_by'].'" placeholder="Directed By" required="required">
		
		<input type="text" id="upload-title" name="casts" class="field" pattern=".{3,128}" value="'.$_POST['casts'].'" placeholder="Actors" required="required">
		
		<textarea id="upload-desc" name="description" class="field" placeholder="Movie Description" style="width: 565px;outline: 0px;height: 50px;" required="required">'.$_POST['description'].'</textarea>
		
		<span id="add-seperator" class="or-seperator" style="margin: 24px auto 34px;"><em>Upload Movie Cover</em></span>
		
		<div id="drag-upload-container">
			<input id="add-file" required="" type="file" class="field" name="file" accept="image/gif,image/jpeg,image/jpg,image/png" required="required">
			<span id="file-upload-input">'.$this->LANG('upload').'</span>
		</div>
		<output id="result" style="margin-bottom: -20px; display: inline-block;" /></output>
		<span id="add-seperator" class="or-seperator" style="margin: 24px auto 34px;"><em>Upload Movie</em></span>
		<div style="margin-top: -24px;">
			<label for="movie_flv">Movie Link (flv, mp4 and etc.)</label>
			<div class="add-url-input cf"><input id="add-url" type="url" required="required" name="movie_flv" class="field" placeholder="http://" value="'.$_POST['movie_flv'].'"></div>
			<label for="movie_iframe">Movie Iframe Link</label>
			<div class="add-url-input cf"><input id="add-url" type="url" required="required" name="movie_iframe" class="field" placeholder="http://" value="'.$_POST['movie_flv'].'"></div>
		</div>
		<span id="add-seperator" class="or-seperator" style="margin: 24px auto 34px;"><em>Episode <span id="EPISODE_NUM">1</span> (ONLY TV SHOWS)</em></span>
		<div style="margin-top: -24px;">
			<div class="add-url-input cf"><input id="add-url" type="url" required="required" name="episode_name[]" class="field" placeholder="Episode Name"></div>
			<div class="add-url-input cf"><input id="add-url" type="url" required="required" name="episodes_movie_flv[]" class="field" placeholder="Episode Link (flv, mp4 and etc.)"></div>
			<div class="add-url-input cf"><input id="add-url" type="url" required="required" name="episodes_movie_iframe[]" class="field" placeholder="Episode Iframe Link"></div>
		</div>
		<div id="episodes_result">

		</div>
		<input type="button" value="Add Episode" onclick="add_episode(1)" style="float: right!important; margin-top: 10px; margin-bottom: 10px; background-color: #4CAF50 !important; text-decoration: none; border: none; font-size: 16px; padding: 6px 18px; border-radius: 40px; color: #FFF; text-align: center; letter-spacing: 0.5px; position: relative; cursor: pointer; display: inline-block; overflow: hidden; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; vertical-align: middle; z-index: 1;">
		<div id="upload-data" style="padding-top: 55px; padding-bottom: 40px;">
			<div id="upload-details" style="margin: 0 auto; width: 240px;">
				<h7 id="error-message" style="display: block; text-align: center; padding-bottom: 10px;"></h7>
				<input id="submit_button" type="submit" name="submit_image" class="light-blue-btn right-btn" value="'.$this->LANG('add_image').'" formnovalidate="" style="padding: 0px 84px">
			</div>
		</div>
	</form>
</div></div>

');

	}
	
	
  }
  
  public function GET_GOLD_VOTES($id) {
	$query = "SELECT * FROM gold_votes WHERE vote_type='post' AND post_id = $id";
	$result = mysql_query($query);
	$vote = mysql_num_rows($result);
	return $vote;
  }
  
  public function time_ago($postedDateTime, $systemDateTime, $typeOfTime) {
		$changePostedTimeDate=strtotime($postedDateTime);
		$changeSystemTimeDate=strtotime($systemDateTime);
		$timeCalc=$changeSystemTimeDate-$changePostedTimeDate;
		if ($typeOfTime == "second") {
			if ($timeCalc > 0) {
				$typeOfTime = "second";
			}
			if ($timeCalc > 60) {
				$typeOfTime = "minute";
			}
			if ($timeCalc > (60*60)) {
				$typeOfTime = "hour";
			}
			if ($timeCalc > (60*60*24)) {
				$typeOfTime = "day";
			}
			if ($timeCalc > (60*60*24*7)) {
				$typeOfTime = "week";
			}
			if ($timeCalc > (60*60*24*30)) {
				$typeOfTime = "month";
			}
			if ($timeCalc > (60*60*24*365)) {
				$typeOfTime = "year";
			}
		}
		if ($typeOfTime == "second") {
			$timeCalc .= " second ago";
		}
		if ($typeOfTime == "minute") {
			$timeCalc = round($timeCalc/60) . " minute ago";
		}
		if ($typeOfTime == "hour") {
			$timeCalc = round($timeCalc/60/60) . " hour ago";
		}
		if ($typeOfTime == "day") {
			$timeCalc = round($timeCalc/60/60/24) . " days ago";
		}
		if ($typeOfTime == "week") {
			$timeCalc = round($timeCalc/60/60/24/7) . " week ago";
		}
		if ($typeOfTime == "month") {
			$timeCalc = round($timeCalc/60/60/24/30) . " month ago";
		}
		if ($typeOfTime == "year") {
			$timeCalc = round($timeCalc/60/60/24/365) . " year ago";
		}
	return $timeCalc;
	}

  public function GOLD_box($q, $content, $GET, $statement) {
	$GOLD_html .= $this->output('<div id="feed" class="wrap cf">'); 
		
	if($GET != 'wall') {
		$GOLD_html .= $this->output('<style>.full_wrap{width:100%; } #loading-indicator { width: 590px; }</style>');
		$GOLD_html .= $this->output('<div class="full_wrap cf"><div style="float: left; width: 100%;">');
	} else {
		$GOLD_html .= $this->output('<div class="full_wrap cf">');
	}
	$GOLD_html .= $this->output($this->widget('CenterAdvert'));
	
	$GOLD_html .= $this->output('<div id="container" style="visibility: visible;">');
	if(mysql_num_rows($q) == '0') {
		$GOLD_html .= $this->output('<div class="no_results">');
			$GOLD_html .= $this->output('<img src="'.$this->getCurrentTemplatePath().'/images/icon-results.png" style="width: 111px;">');
			$GOLD_html .= $this->output('<span style="font-size: 14px; font-weight: normal; display: block;">'.$this->LANG('no_results').'</span>');
		$GOLD_html .= $this->output('</div>');
	}
	$i = 1;
	$ii = 0;$count = 0;
	while($media = mysql_fetch_array($q)) {
		$directed_by = $media['directed_by'];
		$directed = explode(", ", $directed_by);
		foreach($directed as &$rejisori) {
			$rejisori = "<a href='".$this->GOLD_ROOT()."producer/".$rejisori."'>".str_replace(".", "", $rejisori)."</a>";
		}
		$rejisorebi = implode(", ", $directed);
		
		$casts_by = $media['casts'];
		$casts_explode = explode(", ", $casts_by);
		foreach($casts_explode as &$casts_to) {
			$casts_to = "<a href='".$this->GOLD_ROOT()."actor/".$casts_to."'>".str_replace(".", "", $casts_to)."</a>";
		}
		$casts = implode(", ", $casts_explode);
		
		$categories = $media['category_id'];
		$cats = explode(",", $categories);
		foreach($cats as &$cat) {
			$row = mysql_fetch_array(mysql_query("SELECT * FROM gold_categories WHERE category_id='".$cat."'"));
			$cat = "<a href='".$this->GOLD_ROOT()."genre/".$row['name']."'>".$row['title']."</a>";
		}
		$genre = implode(", ", $cats);
		$GOLD_CHECK_CATEGORY = mysql_query("SELECT * FROM gold_categories WHERE category_id='".$media['category_id']."'");
		$GOLD_CHECK_USERS = mysql_query("SELECT * FROM gold_users WHERE user_id='".$media['user_id']."'");
		$GOLD_CATEGORY = mysql_fetch_array($GOLD_CHECK_CATEGORY);
		$GOLD_USER = mysql_fetch_array($GOLD_CHECK_USERS);
	if($this->set('gold_boxtype') == '1' || $GET == 'wall' && $GET == 'favorites') {
		if($GOLD_USER['user_type'] == '') { $avatar = $this->GOLD_ROOT().'gold-app/gold-uploads/avatars/'.$GOLD_USER['user_avatar']; } else { $avatar = $GOLD_USER['user_avatar']; }
		if($media['post_type'] == '0') {
			$post_type = $this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$media['post_thumb'];
		}
    	elseif($media['post_type'] == '1') {
			$post_type = $media['post_thumb'];
     	}
	  if($media['post_title'] != '') {
	  	$ii++;
	  	if($this->widget_echo('PostAdvert') != '') {
		  	if ($count % 35 == 5) {
	        	// display ads
		  		$GOLD_html .= $this->output('
		        <div class="movie-element movie-element-size-1" data-id="9452">
		        	<div class="adverts" style="height: 291px; width: 217px; overflow: hidden; border-radius: 3px; background: #EC3A39;">
		        		<span style="padding-bottom: 5px; padding-top: 5px; color: #fff; font-size: 13px; font-family: Arial; display: inline-block;">ADVERT</span>
		        		'.$this->widget_echo('PostAdvert').'
		        	</div>
		        </div>
		        ');
		  	}
	  	}

		$GOLD_html .= $this->output('
	        <div class="movie-element movie-element-size-1" data-id="9452">
	        	<a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$media['post_name'].'" class="movie-image" style="background-image: url('.$post_type.');">
	        		<div class="movie-overlay clearfix"><div class="movie-overlay-title">'.$media['post_title'].'</div></div>
	        	</a>
	        </div>
	        ');

		$count++;

	  }
	}
	elseif($this->set('gold_boxtype') == '2') {
		if($GOLD_USER['user_type'] == '') { $avatar = $this->GOLD_ROOT().'gold-app/gold-uploads/avatars/'.$GOLD_USER['user_avatar']; } else { $avatar = $GOLD_USER['user_avatar']; }
		if($media['post_type'] == '0') {
			$post_type = ''.$this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$media['post_thumb'].'';
		}
    	elseif($media['post_type'] == '1') {
			$post_type = ''.$media['post_img'].'';
     	}
		
	  if($media['post_title'] != '') {
	  
	    $GOLD_html .= $this->output('
		<div id="box-object'.$media['post_id'].'" class="box-object" style="width: 590px; text-align: left; margin-bottom: 10px; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; border: 1px solid rgb(223, 223, 223); position: relative; left: 0px; top: 0px;">
			<div class="stream-view clearfix">
            	<div class="movie_container">
					<div class="movie_image">
						<a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$media['post_name'].'"><img src="'.$post_type.'" alt="'.$media['post_title'].'" title="'.$media['post_title'].'" class="image" height="323" width="220"></a>
		');
		if ($this->GOLD_logged_in()) {
		    $GOLD_html .= $this->output('
							<div id="movie-poster-overlay">
								<div class="MovieButtons">
				   					<div class="add-to-watchlist">
				   						<img src="'.$this->getCurrentTemplatePath().'/images/eyes.png">
				   						<span>Watch Later</span>
				   					</div>
				   					<div class="add-to-favorites">
				   						<img src="'.$this->getCurrentTemplatePath().'/images/add-to-list.png">
				   						<span>Add Favorite</span>
				   					</div>                             
					            </div>
							</div>
			');
		}
		$GOLD_html .= $this->output('
					</div>
					<div class="movie_information">
						<a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$media['post_name'].'" class="movie_title">'.$media['post_title'].'</a>
						<div class="movie_description">
							<div class="movie_desc_row"><div class="movie_desc_title">'.$this->LANG('year_title').':</div><div class="movie_desc_content"><a href="'.$this->GOLD_ROOT().'year/'.$media['year'].'">'.$media['year'].'</a></div></div>
							<div class="movie_desc_row" style="line-height: 15px; padding-top: 0px; max-height: 100px; display: inline-block; max-height: 70px; overflow: hidden;"><div class="movie_desc_title">'.$this->LANG('genre_title').':</div><font style="float: none; font-family: Arial; font-size: 10px; font-weight: bold; color: #000; position: relative; top: -1px;">'.$genre.'</font></div>
							<div class="movie_desc_row"><div class="movie_desc_title">'.$this->LANG('language_title').':</div><div class="movie_desc_content"><a>'.$media['language'].'</a></div></div>
							<div class="movie_desc_row"><div class="movie_desc_title">'.$this->LANG('producer_title').':</div><div class="movie_desc_content">'.$rejisorebi.'</div></div>
							<div class="movie_desc_row" style="line-height: 15px; padding-top: 3px; padding-bottom: 8px; border-bottom: 1px solid rgb(202, 202, 202); border-bottom-style: dotted;"><div class="movie_desc_title">'.$this->LANG('actors_title').':</div> <font style="float: none; font-family: Arial; font-size: 10px; font-weight: bold; color: #000; position: relative; top: -1px;">'.$casts.'</font></div>
							<div class="movie_desc_row" style="line-height: 15px; padding-top: 7px; max-height: 100px; display: inline-block; max-height: 70px; overflow: hidden;"><div class="movie_desc_title" style="text-decoration: underline; font-weight: bold; color: rgb(173, 34, 34); font-size: 11px;">'.$this->LANG('description_title').':</div> <font style="float: none; position: relative; top: -1px; font-family: Arial; font-size: 11px; color: rgb(88, 88, 88);">'.$media['post_content'].'</font></div>
							<div style="position: absolute; bottom: 5px; display: inline-block; width: 350px;">
								<div class="imdb-badge arrow" style="float: left;"><span>'.$media['imdb'].'</span></div>
								<a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$media['post_name'].'" id="srulad_naxva" href="" style="float: right;">'.$this->LANG('show_details').'</a>
							</div>
						</div>
					</div>
				</div>
            </div>
		</div>
		');
	
	if ($i % 2 == 0) {
		if($i == 2) {
			$GOLD_html .= $this->output("<div class='box-object' style='width: 590px; text-align: left; margin-bottom: 10px; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; background: none; position: relative; left: 0px; top: 0px;'>".$this->widget_echo("CenterAdvert2")."</div>");
		}
		elseif($i == 6) {
			$GOLD_html .= $this->output("<div class='box-object' style='width: 590px; text-align: left; margin-bottom: 10px; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; background: none; position: relative; left: 0px; top: 0px;'>".$this->widget_echo("CenterAdvert3")."</div>");
		}
		elseif($i == 10) {
			$GOLD_html .= $this->output("<div class='box-object' style='width: 590px; text-align: left; margin-bottom: 10px; border-top-left-radius: 3px; border-top-right-radius: 3px; border-bottom-right-radius: 3px; border-bottom-left-radius: 3px; background: none; position: relative; left: 0px; top: 0px;'>".$this->widget_echo("CenterAdvert4")."</div>");
		}
	}
	$i++;
		
	  }
	}
	}
	
	if($this->GOLD_REQUEST('gold') != 'sort') {

	$page = (int) (!isset($_GET["page"]) ? 1 : $_GET["page"]);
	$limit = $this->set('gold_rows');
	
	if(mysql_num_rows($q) != '0' && mysql_num_rows($q) >= $limit) {
		$GOLD_html .= $this->output(''.$this->pagination($statement,"post_id",$this->set("gold_rows")).'');
	}

	}
	
	$GOLD_html .= $this->output('<nav id="page-nav">');
		if($this->GOLD_REQUEST('gold') == '') { $gold = 'index'; } else { $gold = $this->GOLD_REQUEST('gold'); }
		if($this->GOLD_REQUEST('sub_gold') == '') { $sub_gold = ''; } else { $sub_gold = $this->GOLD_REQUEST('sub_gold'); }
		if($this->GOLD_REQUEST('sub2_gold') == '') { $sub2_gold = ''; } else { $sub2_gold = $this->GOLD_REQUEST('sub2_gold'); }
		if($this->GOLD_REQUEST('q') == '') { $q = ''; } else { $q = $this->GOLD_REQUEST('q'); }
		$content_row = $content+$this->set('gold_rows');
		$GOLD_html .= $this->output('<a href="'.$this->GOLD_ROOT().'?gold='.$gold.'&sub_gold='.$sub_gold.'&sub2_gold='.$sub2_gold.'&q='.$q.'&content='.$content_row.'"></a>');
	$GOLD_html .= $this->output('</nav>');
	
	if($this->set('gold_boxtype') == '1') {
		$GOLD_html .= $this->output('</div>');
	}
	
	
	
	if($this->set('gold_boxtype') == '2') {
		$GOLD_html .= $this->output('</div></div>');
		if($GET != 'wall' && $GET != 'favorites') {
			$GOLD_html .= $this->GOLD_sidebar('', '', '', '');
		}
		$GOLD_html .= $this->output('</div></div>');
	} else {
		
	}
	
	
    
	
    return $GOLD_html;
  }
  
  public function GOLD_INNER_category($parent) {
	if($parent == 'parent') {
		$q = mysql_query("SELECT * FROM gold_categories WHERE parent_id='' AND status='1' ORDER BY category_id ASC");
	} else {
		$q = mysql_query("SELECT * FROM gold_categories WHERE status='1' ORDER BY category_id ASC");
	}
	while($cat = mysql_fetch_array($q)) {
	  $GOLD_html .= $this->output('<option value="'.$cat['category_id'].'">'.$cat['title'].'</option>');
	  
	}
    return $GOLD_html;
  }
  
  public function GOLD_INNER_cat() {
	  
	$GOLD_html .= '<nav class="dropdown">
		<div class="dropdown-wrapper arrow-left"><ul>';
	$q = mysql_query("SELECT * FROM gold_categories WHERE parent_id='' AND status='1' ORDER BY category_id ASC"); 
	while($cat = mysql_fetch_array($q)) {
		
	  $GOLD_html .= '<li><a href="'.$this->GOLD_ROOT().'category/'.$cat['name'].'">'.$cat['title'].'</a></li>';
	  
	}
	$GOLD_html .= '</ul></div>
		</nav>'; 
	
    return $GOLD_html;
  }
  
  public function GOLD_INNER_pages2() {
  
	$q = mysql_query("SELECT * FROM gold_pages WHERE status='1' ORDER BY page_id ASC LIMIT 4"); 
	while($page = mysql_fetch_array($q)) {
	
	  $GOLD_html .= $this->output('<li><a href="'.$this->GOLD_ROOT().'pages/'.$page['name'].'">'.$page['title'].'</a></li>');
	  
	}
	
    return $GOLD_html;
  }
  
  public function GOLD_INNER_pages() {
	
	$GOLD_html .= '<nav class="dropdown">
		<div class="dropdown-wrapper arrow-left"><ul>';
	$q = mysql_query("SELECT * FROM gold_pages WHERE status='1' ORDER BY page_id ASC"); 
	while($page = mysql_fetch_array($q)) {
		
	  $GOLD_html .= '<li><a href="'.$this->GOLD_ROOT().'pages/'.$page['name'].'">'.$page['title'].'</a></li>';
	  
	}
	$GOLD_html .= '</ul></div>
		</nav>'; 
	
    return $GOLD_html;
  }
  
  public function GOLD_smilies($data_id) {
  
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":D"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/laugh.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":)"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/happy.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":("><img src="'.$this->getCurrentTemplatePath().'/images/smilies/bored.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=";)"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/wink.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":P"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/tongue.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":X"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/not_even.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":O"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/agape.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":grin:"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/grin.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":shocked:"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/shocked.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":cry:"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/cry.png" /></a>');
		$GOLD_html .= $this->output('<a href="javascript:;" data-id="'.$data_id.'" title=":sunglasses:"><img src="'.$this->getCurrentTemplatePath().'/images/smilies/sunglasses.png" /></a>');
		
    return $GOLD_html;
  }

  public function GOLD_admin_posts($table, $where, $per_page) {
	if($this->GOLD_REQUEST('sub2_gold') == 'delete') {
		$result = mysql_query("SELECT * FROM $table WHERE $where='".$this->GOLD_REQUEST('sub3_gold')."'");
		while($row = mysql_fetch_array($result)) {
			unlink("gold-app/gold-uploads/media/".$row['post_thumb']);
			unlink("gold-app/gold-uploads/media/".$row['post_img']);
			mysql_query("DELETE FROM $table WHERE $where='".$this->GOLD_REQUEST('sub3_gold')."'");
			header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
		}
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == 'add') {
		header("Location: ".$this->GOLD_ROOT()."/submit");
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == 'edit') {
		$GOLD_html .= $this->output('
		<form name="form" action="" method="post">
			<div class="container_class">');
		$row = mysql_fetch_array(mysql_query("SELECT * FROM $table WHERE $where='".$this->GOLD_REQUEST("sub3_gold")."'"));
		if($_POST['submit']) {
			if(!$_POST['title']) { echo '<span class="fill_out_admin">'.$this->LANG('please_fill_out_important_fields').'</span>'; } else {
				$form_data = array(
					'post_title' => mysql_real_escape_string($_POST['title']),
					'year' => mysql_real_escape_string($_POST['year']),
					'imdb' => mysql_real_escape_string($_POST['imdb']),
					'directed_by' => mysql_real_escape_string($_POST['directed_by']),
					'casts' => mysql_real_escape_string($_POST['casts']),
					'post_content' => mysql_real_escape_string($_POST['description']),
					'movie_flv' => mysql_real_escape_string($_POST['movie_flv']),
					'movie_iframe' => mysql_real_escape_string($_POST['movie_iframe'])
				);
				$query_episodes = mysql_query("SELECT * FROM gold_episodes WHERE movie_id='".$row['post_name']."'");
				while($row_episode = mysql_fetch_array($query_episodes)) {
					$update = mysql_query("UPDATE `gold_episodes` SET `movie_link`='".$_POST['movie_flv_'.$row_episode['id']]."', `movie_iframe`='".$_POST['movie_iframe_'.$row_episode['id']]."', `episode_name`='".$_POST['episode_name_'.$row_episode['id']]."' WHERE id='".$row_episode['id']."'");
				}

				$episodes_res = mysql_query("SELECT (@row:=@row+1) AS ROW_ID, gold_episodes.* FROM gold_episodes gold_episodes, (SELECT @row := 0) r WHERE movie_id='".$row['post_name']."'");
				$episode_name = $_POST['episode_name'];
				$episodes_movie_flv = $_POST['episodes_movie_flv'];
				$episodes_movie_iframe = $_POST['episodes_movie_iframe'];
				foreach ($episode_name as $key=>$value ) {
					$key_id = $key+1;
					$insert = mysql_query("INSERT INTO `gold_episodes` (`movie_id`, `episode_name`, `movie_link`, `movie_iframe`) VALUES ('".$row['post_name']."', '".$value."', '".$episodes_movie_flv[$key]."', '".$episodes_movie_iframe[$key]."')");
					
				}
				$this->GOLD_DB_UPDATE('gold_posts', $form_data, "WHERE post_id = '".$this->GOLD_REQUEST('sub3_gold')."'");
				header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
			}
		}
		
		$GOLD_html .= $this->output('
		<label>Movie Title</label><br />
		<input type="text" id="upload-title" name="title" class="field" pattern=".{3,128}" value="'.$row['post_title'].'" placeholder="Movie Title" required="required" style="margin-right: 8px; width: 100%;">
		
		<label>Movie Year & IMDb Rating</label><br />
		<div style="width: 102%;">
			<input type="text" id="upload-title" name="year" class="field" pattern=".{3,128}" value="'.$row['year'].'" placeholder="Movie Year" required="required" style="width: 48%; float: left; margin-right: 8px;">
			<input type="text" id="upload-title" name="imdb" class="field" pattern=".{3,128}" value="'.$row['imdb'].'" placeholder="IMDb Rating" required="required" style="width: 47%; float: left;">
		</div>
		<label>Directed By</label><br />
		<input type="text" id="upload-title" name="directed_by" class="field" pattern=".{3,128}" value="'.$row['directed_by'].'" placeholder="Directed By" required="required" style="width: 100%;">
		<label>Actors</label><br />
		<input type="text" id="upload-title" name="casts" class="field" pattern=".{3,128}" value="'.$row['casts'].'" placeholder="Actors" required="required" style="width: 100%;">
		<label>Movie Description</label><br />
		<textarea id="upload-desc" name="description" class="field" placeholder="Movie Description" style="width: 102%;outline: 0px;height: 50px;  display: inline-block;
  height: 100px;
  line-height: 24px;
  padding: 0px 7px;
  margin-bottom: 4px;
  font-size: 14px;
  color: rgb(127, 140, 141);
  vertical-align: middle;
  box-shadow: none;
  border: 1px solid rgb(177, 178, 179);
  border-image-source: initial;
  border-image-slice: initial;
  border-image-width: initial;
  border-image-outset: initial;
  border-image-repeat: initial;
  font-family: Helvetica, Arial, sans-serif;
  border-radius: 3px;
  -webkit-appearance: none;
  -webkit-transition: background-color 0.24s ease-in-out;
  transition: background-color 0.24s ease-in-out;
  margin-bottom: 12px;" required="required">'.$row['post_content'].'</textarea>
		
		<div style="margin-top: 14px; padding-bottom: 20px;">
			<label for="movie_flv">Movie Link (flv, mp4 and etc.)</label>
			<div class="add-url-input cf"><input id="add-url" type="url" name="movie_flv" class="field" placeholder="http://" value="'.$row['movie_flv'].'"></div>
			<label for="movie_iframe">Movie Iframe Link</label>
			<div class="add-url-input cf"><input id="add-url" type="url" name="movie_iframe" class="field" placeholder="http://" value="'.$row['movie_iframe'].'"></div>
		</div>');

$episodes_re = mysql_query("SELECT (@row:=@row+1) AS ROW_ID, gold_episodes.* FROM gold_episodes gold_episodes, (SELECT @row := 0) r WHERE movie_id='".$row['post_name']."'");
			while($episode = mysql_fetch_array($episodes_re)) {
				$GOLD_html .= $this->output('
				<div style="padding-bottom: 20px;">
					<label style="text-decoration: underline;"><strong>Episode '.$episode['ROW_ID'].'</strong></label>
					<div class="add-url-input cf"><input id="add-url" type="text" value="'.$episode['episode_name'].'" name="episode_name_'.$episode['id'].'" class="field" placeholder="Episode Name"></div>
					<div class="add-url-input cf"><input id="add-url" type="text" value="'.$episode['movie_link'].'" name="movie_flv_'.$episode['id'].'" class="field" placeholder="Episode Link (flv, mp4 and etc.)"></div>
					<div class="add-url-input cf"><input id="add-url" type="text" value="'.$episode['movie_iframe'].'" name="movie_iframe_'.$episode['id'].'" class="field" placeholder="Episode Iframe Link"></div>
				</div>
				');
			}
$GOLD_html .= $this->output('
		<div id="episodes_result">

		</div>
		<input type="button" value="Add Episode" onclick="add_episode('.mysql_num_rows($episodes_re).')" style="float: right!important; margin-top: 10px; margin-bottom: 10px; background-color: #4CAF50 !important; text-decoration: none; border: none; font-size: 16px; padding: 6px 18px; border-radius: 40px; color: #FFF; text-align: center; letter-spacing: 0.5px; position: relative; cursor: pointer; display: inline-block; overflow: hidden; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; vertical-align: middle; z-index: 1;">

	');
$GOLD_html .= $this->output('
				<input type="submit" name="submit" value="'.$this->LANG('save_changes').'">
			</div>
		</form>
		');
		
		$GOLD_html .= $this->output('
			</div>
		</form>');
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == '') {
	
	$GOLD_html .= $this->output('
		<section class="panel panel-default table-dynamic">
        <div class="panel-heading"><strong><i class="color-gray fa fa-table"></i> POSTS</strong><a href="'.$this->GOLD_ROOT().$this->GOLD_REQUEST("gold").'/'.$this->GOLD_REQUEST("sub_gold").'/add" class="add_function">Add Movie</a></div>

        <table class="table table-bordered table-striped table-responsive">
            <thead>
                <tr>
                    <th><div class="th">
                        Image
                    </div></th>
					<th><div class="th">
                        Title
                    </div></th>
					<th><div class="th">
                        Options
                    </div></th>
                </tr>
            </thead>
            <tbody>');
	   
	    $page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
	    if ($page <= 0) $page = 1;
	    $startpoint = ($page * $per_page) - $per_page;
 
		$result = mysql_query("SELECT * FROM $table ORDER BY $where DESC LIMIT {$startpoint} , {$per_page}");
		while($row = mysql_fetch_array($result)) {
			if($row['post_type'] == '0') {
				$post_type = '<img src="'.$this->GOLD_ROOT().'gold-app/gold-uploads/media/'.$row['post_thumb'].'" style="border-radius: 2px; width: 82px; margin-bottom: -6px; border-radius: 5px;">';
			}
    		elseif($row['post_type'] == '1') {
				$post_type = '<img src="'.$row['post_thumb'].'" style="border-radius: 2px; width: 82px; margin-bottom: -6px; border-radius: 5px;">';
     		}
			$GOLD_CHECK_CATEGORY = mysql_query("SELECT * FROM gold_categories WHERE category_id='".$row['category_id']."'");
			$GOLD_CATEGORY = mysql_fetch_array($GOLD_CHECK_CATEGORY);
			$GOLD_html .= $this->output('
	            <tr data-ng-repeat="store in currentPageStores" class="ng-scope">
                    <td class="ng-binding" style="width: 80px;">'.$post_type.'</td>
					<td class="ng-binding"><a href="'.$this->GOLD_ROOT().$GOLD_CATEGORY['name'].'/'.$row['post_name'].'" target="_blank">'.$row['post_title'].'</a></td>
					<td class="ng-binding">
						<a href="'.$this->GOLD_ROOT().$this->GOLD_REQUEST("gold").'/'.$this->GOLD_REQUEST("sub_gold").'/edit/'.$row[$where].'"><i class="color-green fa fa-edit"></i></a>
						<a href="'.$this->GOLD_ROOT().$this->GOLD_REQUEST("gold").'/'.$this->GOLD_REQUEST("sub_gold").'/delete/'.$row[$where].'" style="margin-left: 15px;"><i class="color-warning fa fa-ban"></i></a>
					</td>
                </tr>');
		}

      $GOLD_html .= $this->output('</tbody>
        </table>');
	
      // displaying paginaiton.
	  $GOLD_html .= $this->pagination($table, 'post_id', $per_page);
	
	}
	
    return $GOLD_html;
  
  }
  
  public function GOLD_admin() {
  
	$GOLD_html .= $this->output('<div id="admin_panel">
	<style>
	
.container_class input[type=submit] {
display: inline-block;
color: #fff;
background-color: #EC3A39;
margin-top: 10px;
padding: 9px 20px;
border: 1px solid #EC3A39;
border-radius: 3px;
box-shadow: 0 1px 0 rgba(0,0,0,0.08);
font: bold 14px/20px "Helvetica Neue",Arial,Helvetica,Geneva,sans-serif;
cursor: pointer;
text-decoration: none;
display: inline-block;
text-align: center;
-webkit-appearance: none;
}

.container_class input[type=email], input[type=number], input[type=password], input[type=tel], input[type=url], select, textarea {
border: 1px solid #ddd;
-webkit-box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
box-shadow: inset 0 1px 2px rgba(0,0,0,.07);
background-color: #fff;
color: #333;
-webkit-transition: .05s border-color ease-in-out;
transition: .05s border-color ease-in-out;
outline: 0;
-webkit-box-sizing: border-box;
-moz-box-sizing: border-box;
box-sizing: border-box;
padding: 3px 5px;
font-size: 14px;
line-height: 15px;
-webkit-border-radius: 0;
border-radius: 0;
}
	</style>');
	
	if($this->GOLD_REQUEST('sub_gold') == 'general') { $selected = 'class="selected"'; }
	elseif($this->GOLD_REQUEST('sub_gold') == 'layout') { $selected_layout = 'class="selected"'; }
	elseif($this->GOLD_REQUEST('sub_gold') == 'posts') { $selected_posts = 'class="selected"'; }
	elseif($this->GOLD_REQUEST('sub_gold') == 'pages') { $selected_pages = 'class="selected"'; }
	elseif($this->GOLD_REQUEST('sub_gold') == 'options') { $selected_options = 'class="selected"'; }
	elseif($this->GOLD_REQUEST('sub_gold') == 'categories') { $selected_categories = 'class="selected"'; }
	elseif($this->GOLD_REQUEST('sub_gold') == 'menu') { $selected_menu = 'class="selected"'; }
	elseif($this->GOLD_REQUEST('sub_gold') == 'settings') { $selected_settings = 'class="selected"'; }
	
		$GOLD_html .= $this->output('
		<ul class="nav nav-boxed nav-justified">
			<li><a href="'.$this->GOLD_ROOT().'admin/general/" '.$selected.'><i class="color-success fa fa-home"></i> <span class="text-indent">'.$this->LANG('admin_home').'</span></a></li>
			<li><a href="'.$this->GOLD_ROOT().'admin/posts/" '.$selected_posts.'><i class="color-danger fa fa-bullhorn"></i> <span class="text-indent">Movies</span></a></li>
			<li><a href="'.$this->GOLD_ROOT().'admin/pages/" '.$selected_pages.'><i class="color-danger fa fa-book"></i> <span class="text-indent">Pages</span></a></li>
			<li><a href="'.$this->GOLD_ROOT().'admin/layout/" '.$selected_layout.'><i class="color-danger fa fa-desktop"></i> <span class="text-indent">'.$this->LANG('admin_layout').'</span></a></li>
			<li><a href="'.$this->GOLD_ROOT().'admin/categories/" '.$selected_categories.'><i class="color-primary fa fa-navicon"></i> <span class="text-indent">'.$this->LANG('admin_categories').'</span></a></li>
			<li><a href="'.$this->GOLD_ROOT().'admin/menu/" '.$selected_menu.'><i class="color-gray fa fa-sitemap"></i> <span class="text-indent">'.$this->LANG('admin_menu').'</span></a></li>
			<li><a href="'.$this->GOLD_ROOT().'submit" '.$selected_submit.'><i class="color-gray fa fa-plus"></i> <span class="text-indent">ADD MOVIE</span></a></li>
			<li><a href="'.$this->GOLD_ROOT().'admin/settings/" '.$selected_settings.'><i class="color-gray fa fa-gear"></i> <span class="text-indent">'.$this->LANG('admin_settings').'</span></a></li>
		</ul>');
		
		if($this->GOLD_REQUEST('sub_gold') == '' || $this->GOLD_REQUEST('sub_gold') == 'general') {
			$GOLD_html .= $this->GOLD_admin_index();
		}
		elseif($this->GOLD_REQUEST('sub_gold') == 'layout') {
			$GOLD_html .= $this->GOLD_admin_layout('', '');
		}
		elseif($this->GOLD_REQUEST('sub_gold') == 'posts') {
			$GOLD_html .= $this->GOLD_admin_posts("gold_posts", "post_id", "30");
		}
		elseif($this->GOLD_REQUEST('sub_gold') == 'categories') {
			$GOLD_html .= $this->GOLD_admin_categories("gold_categories", "category_id", "30");
		}
		elseif($this->GOLD_REQUEST('sub_gold') == 'menu') {
			$GOLD_html .= $this->GOLD_admin_menu('', '', '');
		}
		elseif($this->GOLD_REQUEST('sub_gold') == 'settings') {
			$GOLD_html .= $this->GOLD_admin_settings('', '');
		}
		elseif($this->GOLD_REQUEST('sub_gold') == 'pages') {
			$GOLD_html .= $this->GOLD_admin_pages("gold_pages", "page_id", "30");
		}
		
	
	$GOLD_html .= $this->output('</div>');
	
    return $GOLD_html;
  }
  
  public function GOLD_admin_index() {
	$GOLD_html .= $this->output('
		<div class="row" style="display: inline-block; margin-bottom: -30px;">
		<style>@media (max-width: 1100px) { #special_div { width: 100%!important; } }</style>
	        <div id="special_div" class="col-lg-5 col-xsm-6" style="width: 42.77777%;">
	            <div class="panel mini-box">
	                <span class="btn-icon-lined btn-icon-round btn-icon-lg-alt btn-success">
	                    <i class="fa fa-picture-o"></i>
	                </span>
	                <div class="box-info">
	                    <p class="size-h2">'.$this->Admin_Today_Uploaded_Media().'</p>
	                    <p class="text-muted"><span data-i18n="Today Media">'.$this->LANG('admin_today_media').'</span></p>
	                </div>
	            </div>
	        </div>
	        <div class="col-lg-6 col-xsm-6">
	            <div class="panel mini-box">
	                <span class="btn-icon-lined btn-icon-round btn-icon-lg-alt btn-danger">
	                    <i class="fa fa-bullhorn"></i>
	                </span>
	                <div class="box-info">
	                    <p class="size-h2">'.$this->Admin_Uploaded_Media().'</p>
	                    <p class="text-muted"><span data-i18n="All Media">'.$this->LANG('admin_all_media').'</span></p>
	                </div>
	            </div>
	        </div>
		</div>
		<div class="updated-notification" style="margin-bottom: 220px;">
			<p style="margin-bottom: 15px; display: block;"></p>
			<span class="Now-Version">Welcome to Gold MOVIES !</span>
			<p style="margin-top: 5px;"><strong><i class="fcolor-warning fa fa-desktop" id="icon_id"></i> <span style="font-size: 16px;position: relative;top: -2px;margin-left: 5px;"><span style="color: #F00;">Layout</span> - <span style="font-weight: normal;color: #000;font-size: 14px;">Change: Skins, Languages, Logo, Adverts</span></span></strong></p>
			<p style="margin-top: 5px;"><strong><i class="fcolor-warning fa fa-navicon" id="icon_id"></i> <span style="font-size: 16px;position: relative;top: -2px;margin-left: 5px;"><span style="color: #F00;">Categories</span> - <span style="font-weight: normal;color: #000;font-size: 14px;">Dynamic Tables, Add, Edit and Remove Categories</span></span></strong></p>
			<p style="margin-top: 5px;"><strong><i class="fcolor-warning fa fa-sitemap" id="icon_id"></i> <span style="font-size: 16px;position: relative;top: -2px;margin-left: 5px;"><span style="color: #F00;">Menu</span> - <span style="font-weight: normal;color: #000;font-size: 14px;">Drag Menus, Drag Comments, Drag Main Sidebar, Drag Profile Sidebar</span></span></strong></p>
			<p style="margin-top: 5px;"><strong><i class="fcolor-warning fa fa-gear" id="icon_id"></i> <span style="font-size: 16px;position: relative;top: -2px;margin-left: 5px;"><span style="color: #F00;">Settings</span> - <span style="font-weight: normal;color: #000;font-size: 14px;">Website Title, Description, Keywords and other information</span></span></strong></p>
		</div>
    ');
	
    return $GOLD_html;
  
  }
  
  public function paginate($reload, $page, $tpages) {
    $adjacents = 2;
    $prevlabel = "‹";
    $nextlabel = " &nbsp;<font style='font-size: 18px;'>&rsaquo;</font>";
    $out = "";
    // previous
    if ($page == 0) {
        $out.= "<li ng-if='directionLinks' ng-class='{disabled: noPrevious()}' class='ng-scope'><a href='' ng-click='selectPage(page - 1)' class='ng-binding'>‹</a></li>";
    } elseif ($page == 1) {
        $out.= "<li ng-if='directionLinks' ng-class='{disabled: noPrevious()}' class='ng-scope'><a href='' ng-click='selectPage(page - 1)' class='ng-binding'>‹</a></li>";
    } elseif ($page == 2) {
        $out.= "<li ng-if='directionLinks' ng-class='{disabled: noPrevious()}' class='ng-scope'><a href=\"" . $reload . "\" ng-click='selectPage(page - 1)' class='ng-binding'>‹</a></li>";
    } else {
        $out.= "<li ng-if='directionLinks' ng-class='{disabled: noPrevious()}' class='ng-scope'><a href='" . $reload . "&page=" . ($page - 1) . "' ng-click='selectPage(page - 1)' class='ng-binding'>‹</a></li>";
    }
  
    $pmin = ($page > $adjacents) ? ($page - $adjacents) : 1;
    $pmax = ($page < ($tpages - $adjacents)) ? ($page + $adjacents) : $tpages;
    for ($i = $pmin; $i <= $pmax; $i++) {
        if ($i == $page) {
            $out.= "<li  class=\"active\"><a href=''>" . $i . "</a></li>\n";
        } elseif ($i == 1) {
            $out.= "<li><a  href=\"" . $reload . "\">" . $i . "</a>\n</li>";
        } else {
            $out.= "<li><a  href=\"" . $reload . "&page=" . $i . "\">" . $i . "</a>\n</li>";
        }
    }
    
    
    // next
    if ($page < $tpages) {
        $out.= "<li ng-if='directionLinks' ng-class='{disabled: noPrevious()}' class='ng-scope'><a href='' ng-click='selectPage(page - 1)' class='ng-binding'>›</a></li>";
    } else {
        $out.= "<li ng-if='directionLinks' ng-class='{disabled: noPrevious()}' class='ng-scope'><a href='" . $reload . "&page=" . ($page + 1) . "' ng-click='selectPage(page + 1)' class='ng-binding'>›</a></li>";
    }
    $out.= "";
    return $out;
  }

  public function pagination($table, $order, $per_page) {
    if($this->GOLD_REQUEST('gold') == 'search') { 
		$result = @mysql_query("SELECT ".$table." ORDER BY ".$order." DESC") or die(mysql_error());
	} else {
		$result = @mysql_query("SELECT * FROM ".$table." ORDER BY ".$order." DESC") or die(mysql_error());
	}
	
    $total_results = mysql_num_rows($result);
    $total_pages = ceil($total_results / $per_page);//total pages we going to have
    
    //-------------if page is setcheck------------------//
    if (isset($_GET['page'])) {
        $show_page = $_GET['page'];             //it will telles the current page
        if ($show_page > 0 && $show_page <= $total_pages) {
            $start = ($show_page - 1) * $per_page;
            $end = $start + $per_page;
        } else {
            // error - show first set of results
            $start = 0;              
            $end = $per_page;
        }
    } else {
        // if page isn't set, show first set of results
        $start = 0;
        $end = $per_page;
    }
    // display pagination
    $page = intval($_GET['page']);
    
    if ($page <= 0)
    $page = 1;
    
	$reload = $this->GOLD_ROOT()."index.php?gold=".$this->GOLD_REQUEST('gold')."&sub_gold=".$this->GOLD_REQUEST('sub_gold')."&q=".$this->GOLD_REQUEST('q')."";
    
	echo '<footer class="table-footer">
            <div style="margin-top: 20px; margin-left: -15px; margin-right: -15px; width: 100%;">
                <div class="col-md-6 text-right pagination-container">
                    <ul class="pagination-sm pagination ng-isolate-scope ng-pristine ng-valid" ng-model="currentPage" total-items="filteredStores.length" max-size="4" ng-change="select(currentPage)" items-per-page="numPerPage" rotate="false" previous-text="‹" next-text="›" boundary-links="true">';
                    if ($total_pages > 0) {
                        echo $this->paginate($reload, $show_page, $total_pages);
                    }
                    echo "</ul></div></div></footer>";
  }

  public function GOLD_admin_pages($table, $where, $per_page) {
	if($this->GOLD_REQUEST('sub2_gold') == 'delete') {
		mysql_query("DELETE FROM $table WHERE $where='".$this->GOLD_REQUEST('sub3_gold')."'");
		header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == 'add') {
		$GOLD_html .= $this->output('
		<form name="form" action="" method="post">
			<div class="container_class">');
			
		if($_POST['submit']) {
			if(!$_POST['title'] || !$_POST['name']) { echo '<span class="fill_out_admin">'.$this->LANG('please_fill_out_important_fields').'</span>'; } else {
				$form_data = array(
					'name' => mysql_real_escape_string($_POST['name']),
					'title' => mysql_real_escape_string($_POST['title']),
					'content' => mysql_real_escape_string($_POST['content']),
					'status' => '1'
				);
				$this->GOLD_DB_INSERT('gold_pages', $form_data);
				header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
			}
		}
		
		$GOLD_html .= $this->output('
				<div class="field">
					<label>Page Title <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="title" value="" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Page Name <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="name" value="" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Page Content <span style="color:#EC3A39;">*</span></label>
					<textarea name="content" style="width: 102%;height:250px;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;"></textarea>
				</div>
				<input type="submit" name="submit" value="'.$this->LANG('save_changes').'">
			</div>
		</form>
		');
		
		$GOLD_html .= $this->output('
			</div>
		</form>');
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == 'edit') {
		$GOLD_html .= $this->output('
		<form name="form" action="" method="post">
			<div class="container_class">');
		$row = mysql_fetch_array(mysql_query("SELECT * FROM $table WHERE $where='".$this->GOLD_REQUEST("sub3_gold")."'"));
		if($_POST['submit']) {
			if(!$_POST['title'] || !$_POST['name']) { echo '<span class="fill_out_admin">'.$this->LANG('please_fill_out_important_fields').'</span>'; } else {
				$form_data = array(
					'name' => mysql_real_escape_string($_POST['name']),
					'title' => mysql_real_escape_string($_POST['title']),
					'content' => mysql_real_escape_string($_POST['content']),
					'status' => '1'
				);
				$this->GOLD_DB_UPDATE('gold_pages', $form_data, "WHERE page_id = '".$this->GOLD_REQUEST('sub3_gold')."'");
				header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
			}
		}
		
		$GOLD_html .= $this->output('
				<div class="field"><label>Page Title <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="title" value="'.$row['title'].'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Page Name <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="name" value="'.$row['name'].'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Page Content <span style="color:#EC3A39;">*</span></label>
					<textarea name="content" style="width: 102%;height:250px;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">'.$row['content'].'</textarea>
				</div>
				<input type="submit" name="submit" value="'.$this->LANG('save_changes').'">
			</div>
		</form>
		');
		
		$GOLD_html .= $this->output('
			</div>
		</form>');
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == '') {
	
	$GOLD_html .= $this->output('
		<section class="panel panel-default table-dynamic">
        <div class="panel-heading"><strong><i class="color-gray fa fa-table"></i> PAGES</strong><a href="'.$this->GOLD_ROOT().$this->GOLD_REQUEST("gold").'/'.$this->GOLD_REQUEST("sub_gold").'/add" class="add_function">ADD PAGE</a></div>

        <table class="table table-bordered table-striped table-responsive">
            <thead>
                <tr>
                    <th><div class="th">
                        Page Name
                    </div></th>
					<th><div class="th">
                        Options
                    </div></th>
                </tr>
            </thead>
            <tbody>');
	   
	    $page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
	    if ($page <= 0) $page = 1;
	    $startpoint = ($page * $per_page) - $per_page;
 
		$result = mysql_query("SELECT * FROM $table ORDER BY $where DESC LIMIT {$startpoint} , {$per_page}");
		while($row = mysql_fetch_array($result)) {
			if($row['name'] == 'feedback') {
			
			} else {
				$edit_info = '<a href="'.$this->GOLD_ROOT().$this->GOLD_REQUEST("gold").'/'.$this->GOLD_REQUEST("sub_gold").'/edit/'.$row[$where].'"><i class="color-green fa fa-edit"></i></a>';
			}
			$GOLD_html .= $this->output('
	            <tr data-ng-repeat="store in currentPageStores" class="ng-scope">
                    <td class="ng-binding"><a href="'.$this->GOLD_ROOT().'pages/'.$row['name'].'" target="_blank">'.$row['title'].'</a></td>
					<td class="ng-binding">
						'.$edit_info.'
						<a href="'.$this->GOLD_ROOT().$this->GOLD_REQUEST("gold").'/'.$this->GOLD_REQUEST("sub_gold").'/delete/'.$row[$where].'" style="margin-left: 15px;"><i class="color-warning fa fa-ban"></i></a>
					</td>
                </tr>');
		}

      $GOLD_html .= $this->output('</tbody>
        </table>');
	
      // displaying paginaiton.
	  $GOLD_html .= $this->pagination($table, 'page_id', $per_page);
	
	}
	
    return $GOLD_html;
  
  }
  
  public function GOLD_admin_menu($table, $where, $per_page) {
    if($this->GOLD_REQUEST('sub2_gold') == 'active') {
		if($this->GOLD_REQUEST('sub3_gold') == 'menu') {
			mysql_query("UPDATE gold_menu SET menu_status='1' WHERE menu_name='".$this->GOLD_REQUEST('sub3_gold')."'");
		} elseif($this->GOLD_REQUEST('sub3_gold') == 'main_sidebar') {
			mysql_query("UPDATE gold_blocks SET block_status='1' WHERE block_type='main' AND block_name='".$this->GOLD_REQUEST('sub4_gold')."'");
		} elseif($this->GOLD_REQUEST('sub3_gold') == 'profile_sidebar') {
			mysql_query("UPDATE gold_blocks SET block_status='1' WHERE block_type='profile' AND block_name='".$this->GOLD_REQUEST('sub4_gold')."'");
		} elseif($this->GOLD_REQUEST('sub3_gold') == 'post_sidebar') {
			mysql_query("UPDATE gold_blocks SET block_status='1' WHERE block_type='post' AND block_name='".$this->GOLD_REQUEST('sub4_gold')."'");
		}
		header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == 'inactive') {
		if($this->GOLD_REQUEST('sub3_gold') == 'menu') {
			mysql_query("UPDATE gold_menu SET menu_status='0' WHERE menu_name='".$this->GOLD_REQUEST('sub3_gold')."'");
		} elseif($this->GOLD_REQUEST('sub3_gold') == 'main_sidebar') {
			mysql_query("UPDATE gold_blocks SET block_status='0' WHERE block_type='main' AND block_name='".$this->GOLD_REQUEST('sub4_gold')."'");
		} elseif($this->GOLD_REQUEST('sub3_gold') == 'profile_sidebar') {
			mysql_query("UPDATE gold_blocks SET block_status='0' WHERE block_type='profile' AND block_name='".$this->GOLD_REQUEST('sub4_gold')."'");
		} elseif($this->GOLD_REQUEST('sub3_gold') == 'post_sidebar') {
			mysql_query("UPDATE gold_blocks SET block_status='0' WHERE block_type='post' AND block_name='".$this->GOLD_REQUEST('sub4_gold')."'");
		}
		header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
	}
      $GOLD_html .= $this->output('<div class="container_class">');

		$GOLD_html .= $this->output('<span class="container_title">Drag Main Sidebar</span>');
		$GOLD_html .= $this->output('<div id="main_sidebar_drag_elements">
			<ul style="-webkit-padding-start: 0px;">');
				
				$query = mysql_query("SELECT * FROM gold_blocks WHERE block_type='main'");
				while($row = mysql_fetch_array($query)) {
					$block2[$row['block_name']] = '<li id="MainSidebarArray_'.$this->block($row['block_name']).'">'.$row['block_title'].' '.$this->block_status($row['block_name']).'</li>';
				}
				
				$menu_sql2 = mysql_query("SELECT * FROM gold_blocks WHERE block_type='main' ORDER BY block_position ASC");
				while($block_media2 = mysql_fetch_array($menu_sql2)) {
					$block_position = $block_media2['block_name'];
					echo $block2[$block_position];
				}
	  $GOLD_html .= $this->output('</ul>
		</div>');
		
	  $GOLD_html .= $this->output('</div>');
	
    return $GOLD_html;
  
  }
  
  public function GOLD_admin_layout($table, $where) {
	if($this->GOLD_REQUEST('sub2_gold') == '') {
		$GOLD_html .= $this->output('
		<form name="form" action="" method="post">
			<div class="container_class">');
		if($_POST['submit']) {
			if(!$_POST['gold_skin'] || !$_POST['gold_lang'] || !$_POST['gold_logo']) { echo '<span class="fill_out_admin">'.$this->LANG('please_fill_out_important_fields').'</span>'; } else {
				$gold_skin = array(
					'set_name' => "gold_skin",
					'set_content' => mysql_real_escape_string($_POST['gold_skin'])
				);
				$TMDB_API_KEY = array(
					'widget_title' => "TMDB_API_KEY",
					'widget_code' => mysql_real_escape_string($_POST['TMDB_API_KEY'])
				);
				$gold_lang = array(
					'set_name' => "gold_lang",
					'set_content' => mysql_real_escape_string($_POST['gold_lang'])
				);
				$gold_logo = array(
					'set_name' => "gold_logo",
					'set_content' => mysql_real_escape_string($_POST['gold_logo'])
				);
				$gold_email = array(
					'set_name' => "gold_email",
					'set_content' => mysql_real_escape_string($_POST['gold_email'])
				);
				$center_advert = array(
					'widget_title' => "CenterAdvert",
					'widget_code' => mysql_real_escape_string($_POST['center_advert'])
				);
				$post_advert = array(
					'widget_title' => "PostAdvert",
					'widget_code' => mysql_real_escape_string($_POST['post_advert'])
				);
				$sidebar_advert = array(
					'widget_title' => "SidebarAdvert",
					'widget_code' => mysql_real_escape_string($_POST['sidebar_advert'])
				);
				$analytics = array(
					'widget_title' => "Analytics",
					'widget_code' => mysql_real_escape_string($_POST['Analytics'])
				);
				$this->GOLD_DB_UPDATE("gold_settings", $gold_skin, "WHERE set_name = 'gold_skin'");
				$this->GOLD_DB_UPDATE("gold_widgets", $TMDB_API_KEY, "WHERE widget_title = 'TMDB_API_KEY'");
				$this->GOLD_DB_UPDATE("gold_settings", $gold_infobox, "WHERE set_name = 'gold_infobox'");
				$this->GOLD_DB_UPDATE("gold_settings", $gold_lang, "WHERE set_name = 'gold_lang'");
				$this->GOLD_DB_UPDATE("gold_settings", $gold_email, "WHERE set_name = 'gold_email'");
				$this->GOLD_DB_UPDATE("gold_settings", $gold_logo, "WHERE set_name = 'gold_logo'");
				$this->GOLD_DB_UPDATE("gold_widgets", $center_advert, "WHERE widget_title = 'CenterAdvert'");
				$this->GOLD_DB_UPDATE("gold_widgets", $sidebar_advert, "WHERE widget_title = 'SidebarAdvert'");
				$this->GOLD_DB_UPDATE("gold_widgets", $post_advert, "WHERE widget_title = 'PostAdvert'");
				$this->GOLD_DB_UPDATE("gold_widgets", $analytics, "WHERE widget_title = 'Analytics'");
				header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
			}
		}
		
		$GOLD_html .= $this->output('
				<div class="field">
					<label>Gold Skin (Template Name) <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="gold_skin" value="'.$this->set("gold_skin").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>TMDB API KEY (For more features)</label>
					<input type="text" name="TMDB_API_KEY" value="'.$this->widget_echo("TMDB_API_KEY").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Email</label>
					<input type="text" name="gold_email" value="'.$this->set("gold_email").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Gold Language <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="gold_lang" value="'.$this->set("gold_lang").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Gold Logo <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="gold_logo" value="'.$this->set("gold_logo").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Center Advert</label>
					<textarea name="center_advert" style="width: 102%;height:100px;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">'.$this->widget_echo("CenterAdvert").'</textarea>
				</div>
				<div class="field">
					<label>Sidebar Advert</label>
					<textarea name="sidebar_advert" style="width: 102%;height:100px;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">'.$this->widget_echo("SidebarAdvert").'</textarea>
				</div>
				<div class="field">
					<label>Post Advert (Advert Between Movies)</label>
					<textarea name="post_advert" style="width: 102%;height:100px;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">'.$this->widget_echo("PostAdvert").'</textarea>
				</div>
				<div class="field">
					<label>Google Analytics</label>
					<textarea name="Analytics" style="width: 102%;height:100px;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">'.$this->widget_echo("Analytics").'</textarea>
				</div>
				
				<input type="submit" name="submit" value="'.$this->LANG('save_changes').'">
			</div>
		</form>
		');
		
		$GOLD_html .= $this->output('
			</div>
		</form>');
	}
    return $GOLD_html;
  
  }
  
  public function GOLD_admin_settings($table, $where) {
	if($this->GOLD_REQUEST('sub2_gold') == '') {
		$GOLD_html .= $this->output('
		<form name="form" action="" method="post">
			<div class="container_class">');
		if($_POST['submit']) {
			if(!$_POST['gold_website_title'] || !$_POST['gold_website_description'] || !$_POST['gold_website_keywords'] || !$_POST['gold_max_related_media'] || !$_POST['gold_rows']) { echo '<span class="fill_out_admin">'.$this->LANG('please_fill_out_important_fields').'</span>'; } else {
				$gold_website_title = array(
					'set_name' => "gold_website_title",
					'set_content' => mysql_real_escape_string($_POST['gold_website_title'])
				);
				$gold_website_description = array(
					'set_name' => "gold_website_description",
					'set_content' => mysql_real_escape_string($_POST['gold_website_description'])
				);
				$gold_website_keywords = array(
					'set_name' => "gold_website_keywords",
					'set_content' => mysql_real_escape_string($_POST['gold_website_keywords'])
				);
				$gold_max_related_media = array(
					'set_name' => "gold_max_related_media",
					'set_content' => mysql_real_escape_string($_POST['gold_max_related_media'])
				);
				$gold_rows = array(
					'set_name' => "gold_rows",
					'set_content' => mysql_real_escape_string($_POST['gold_rows'])
				);
				$this->GOLD_DB_UPDATE("gold_settings", $gold_website_title, "WHERE set_name = 'gold_website_title'");
				$this->GOLD_DB_UPDATE("gold_settings", $gold_website_description, "WHERE set_name = 'gold_website_description'");
				$this->GOLD_DB_UPDATE("gold_settings", $gold_website_keywords, "WHERE set_name = 'gold_website_keywords'");
				$this->GOLD_DB_UPDATE("gold_settings", $gold_max_related_media, "WHERE set_name = 'gold_max_related_media'");
				$this->GOLD_DB_UPDATE("gold_settings", $gold_rows, "WHERE set_name = 'gold_rows'");
				header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
			}
		}
		
		$GOLD_html .= $this->output('
				<div class="field">
					<label>Website Title <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="gold_website_title" value="'.$this->set("gold_website_title").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Website Description <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="gold_website_description" value="'.$this->set("gold_website_description").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Website Keywords <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="gold_website_keywords" value="'.$this->set("gold_website_keywords").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Rows Num. <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="gold_rows" value="'.$this->set("gold_rows").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Max. Num. Related MEDIA <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="gold_max_related_media" value="'.$this->set("gold_max_related_media").'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				
				<input type="submit" name="submit" value="'.$this->LANG('save_changes').'">
			</div>
		</form>
		');
		
		$GOLD_html .= $this->output('
			</div>
		</form>');
	}
    return $GOLD_html;
  
  }
  
  public function GOLD_admin_categories($table, $where, $per_page) {
	if($this->GOLD_REQUEST('sub2_gold') == 'delete') {
		mysql_query("DELETE FROM $table WHERE $where='".$this->GOLD_REQUEST('sub3_gold')."'");
		header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == 'add') {
		$GOLD_html .= $this->output('
		<form name="form" action="" method="post">
			<div class="container_class">');
			
		if($_POST['submit']) {
			if(!$_POST['title'] || !$_POST['name']) { echo '<span class="fill_out_admin">'.$this->LANG('please_fill_out_important_fields').'</span>'; } else {
				$form_data = array(
					'parent_id' => mysql_real_escape_string($_POST['parent_id']),
					'title' => mysql_real_escape_string($_POST['title']),
					'name' => mysql_real_escape_string($_POST['name']),
					'status' => '1'
				);
				$this->GOLD_DB_INSERT('gold_categories', $form_data);
				header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
			}
		}
		
		$GOLD_html .= $this->output('
				<div class="field"><label>Genre name <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="title" value="" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Genre ID <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="name" value="" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Parent category</label>
					<select name="parent_id" style="width: 100%; border: 1px solid #EC3A39; border-radius: 3px; font-family: Arial; outline: 0;">
					<option value="">Choose Parent Genre</option>');
	  				$GOLD_html .= $this->GOLD_INNER_category("parent");
	  				$GOLD_html .= $this->output('
					</select>
				</div>
				<input type="submit" name="submit" value="'.$this->LANG('save_changes').'">
			</div>
		</form>
		');
		
		$GOLD_html .= $this->output('
			</div>
		</form>');
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == 'edit') {
		$GOLD_html .= $this->output('
		<form name="form" action="" method="post">
			<div class="container_class">');
		$row = mysql_fetch_array(mysql_query("SELECT * FROM $table WHERE $where='".$this->GOLD_REQUEST("sub3_gold")."'"));
		if($_POST['submit']) {
			if(!$_POST['title'] || !$_POST['name']) { echo '<span class="fill_out_admin">'.$this->LANG('please_fill_out_important_fields').'</span>'; } else {
				$form_data = array(
					'parent_id' => mysql_real_escape_string($_POST['parent_id']),
					'title' => mysql_real_escape_string($_POST['title']),
					'name' => mysql_real_escape_string($_POST['name']),
					'status' => '1'
				);
				$this->GOLD_DB_UPDATE('gold_categories', $form_data, "WHERE category_id = '".$this->GOLD_REQUEST('sub3_gold')."'");
				header("Location: ".$this->GOLD_ROOT().$this->GOLD_REQUEST("gold")."/".$this->GOLD_REQUEST("sub_gold")."/");
			}
		}
		
		$GOLD_html .= $this->output('
				<div class="field"><label>Genre name <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="title" value="'.$row['title'].'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Genre ID <span style="color:#EC3A39;">*</span></label>
					<input type="text" name="name" value="'.$row['name'].'" style="width: 100%;border: 1px solid #EC3A39;border-radius: 3px;margin-top: 0px;">
				</div>
				<div class="field">
					<label>Parent category</label>
					<select name="parent_id" style="width: 100%; border: 1px solid #EC3A39; border-radius: 3px; font-family: Arial; outline: 0;">
					<option value="">Choose Parent Genre</option>');
	  				$GOLD_html .= $this->GOLD_INNER_category("parent");
	  				$GOLD_html .= $this->output('
					</select>
				</div>
				<input type="submit" name="submit" value="'.$this->LANG('save_changes').'">
			</div>
		</form>
		');
		
		$GOLD_html .= $this->output('
			</div>
		</form>');
	}
	elseif($this->GOLD_REQUEST('sub2_gold') == '') {
	
	$GOLD_html .= $this->output('
		<section class="panel panel-default table-dynamic">
        <div class="panel-heading"><strong><i class="color-gray fa fa-table"></i> '.$this->LANG('categories').'</strong><a href="'.$this->GOLD_ROOT().$this->GOLD_REQUEST("gold").'/'.$this->GOLD_REQUEST("sub_gold").'/add" class="add_function">'.$this->LANG('admin_add_category').'</a></div>

        <table class="table table-bordered table-striped table-responsive">
            <thead>
                <tr>
                    <th><div class="th">
                        Genre Name
                    </div></th>
					<th><div class="th">
                        Options
                    </div></th>
                </tr>
            </thead>
            <tbody>');
	   
	    $page = (int)(!isset($_GET["page"]) ? 1 : $_GET["page"]);
	    if ($page <= 0) $page = 1;
	    $startpoint = ($page * $per_page) - $per_page;
 
		$result = mysql_query("SELECT * FROM $table ORDER BY $where DESC LIMIT {$startpoint} , {$per_page}");
		while($row = mysql_fetch_array($result)) {
			$GOLD_html .= $this->output('
	            <tr data-ng-repeat="store in currentPageStores" class="ng-scope">
                    <td class="ng-binding"><a href="'.$this->GOLD_ROOT().'genre/'.$row['name'].'" target="_blank">'.$row['title'].'</a></td>
					<td class="ng-binding">
						<a href="'.$this->GOLD_ROOT().$this->GOLD_REQUEST("gold").'/'.$this->GOLD_REQUEST("sub_gold").'/edit/'.$row[$where].'"><i class="color-green fa fa-edit"></i></a>
						<a href="'.$this->GOLD_ROOT().$this->GOLD_REQUEST("gold").'/'.$this->GOLD_REQUEST("sub_gold").'/delete/'.$row[$where].'" style="margin-left: 15px;"><i class="color-warning fa fa-ban"></i></a>
					</td>
                </tr>');
		}

      $GOLD_html .= $this->output('</tbody>
        </table>');
	
      // displaying paginaiton.
	  $GOLD_html .= $this->pagination($table, 'category_id', $per_page);
	
	}
	
    return $GOLD_html;
  
  }
  
  public function footer() {
  
		$GOLD_html .= $this->output('
		<footer id="footer" style="margin-bottom: -25px;">	
			<ul>
				<li>
					<p class="home">POPULAR MOVIES</p>
				</li>
				<li>
					<p class="general">POPULAR EPISODES</p>
				</li>
				<li>
					<p class="pages">TRENDING NOW</p>
				</li>
				<li>
					<p class="users">MOVIE TRAILERS</p>
				</li>
			</ul>
			
			<span class="powered_by">2015 © Powered by <a target="_blank" style="color: rgb(234, 255, 0);">Gold MOVIES</a>
			
			
			</span>
		</footer>');
		
		return $GOLD_html;
	}
  
}

?>