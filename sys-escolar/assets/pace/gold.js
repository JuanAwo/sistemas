function add_episode(counter) {
    counter += 1;
	$('#episodes_result').append(
		'<span id=add-seperator class=or-seperator style=margin: 24px auto 34px;><em>New Episode</em></span>'
		+
		'<div style="margin-top: -24px;">'
		+
		'	<div class="add-url-input cf"><input id="add-url" type="text" required="required" name="episode_name[]" class="field" placeholder="Episode Name"></div>'
		+
		'	<div class="add-url-input cf"><input id="add-url" type="text" name="episodes_movie_flv[]" class="field" placeholder="Episode Link (flv, mp4 and etc.)"></div>'
		+
		'	<div class="add-url-input cf"><input id="add-url" type="text" name="episodes_movie_iframe[]" class="field" placeholder="Episode Iframe Link"></div>'
		+
		'</div>'
	);
}

function show_episode(episode_id) {
	$.get(sub_folder+"/gold-app/gold-includes/GOLD.php?episode_id="+episode_id, function(data, status) {
		$("#EPISODE_PLAYER").html(data);
	});
}
function FETCH_MOVIE() {
	var title = document.getElementsByName("title")[0].value;
	var year = document.getElementsByName("year")[0].value;
	$.get(sub_folder+"/gold-app/gold-includes/GOLD.php?API=1&API_TITLE="+title+"&API_YEAR="+year, function(data, status) {
		obj = JSON.parse(data);
		document.getElementsByName("year")[0].value = obj.year;
		document.getElementsByName("imdb")[0].value = obj.imdb;
		document.getElementsByName("directed_by")[0].value = obj.directors;
		document.getElementsByName("casts")[0].value = obj.casts;
		document.getElementsByName("description")[0].value = obj.description;
		document.getElementsByName("movie_iframe")[0].value = obj.youtube;
	});
}

$(function () {
$('.episodes-list').anythingSlider({
	'theme':'episodes',
    'expand':true,
    'hashTags':false,
    'showMultiple':4,
    'changeBy': 4,
    'buildArrows':false,
    'buildNavigation':false,
    'buildStartStop':false,
    'infiniteSlides':false,
    'resizeContents':true,
    'stopAtEnd': true,
    'enableKeyboard': false
});   

$('.prev-episode').click(function(e) {
	$('.episodes-list').data('AnythingSlider').goBack();
	e.preventDefault();
});

$('.next-episode').click(function(e) { 
	$('.episodes-list').data('AnythingSlider').goForward();
	e.preventDefault();
});

});
$(document).ready(function () {
	$(".select-movie-genre").fadeIn('slow');
	$(".select-movie-genre").click(function (e) {
		e.stopPropagation();$("#select-movie-genre").fadeToggle(50);
	});
	$(document).click(function () {
		var $el = $("#select-movie-genre");
		if ($el.is(":visible")) {
			$el.fadeIn(50);
			$el.fadeOut(50);
		}
	});
	$(".select-movie-genre").mouseleave(function() {
		$("#select-movie-genre").css('display', 'none');
	});
});
$(function(){
	$("#comment_value").keyup(function(){
    	$(".char_num").text($(this).val().length);
	});
	$('.add_comment').autosize();
	$('#loginform').submit(function(e){
		return false;
	});
	
	$('#open_modal').leanModal({ top: 110, overlay: 0.60, closeButton: ".hidemodal" });
});

$("#login_button").live('click', function(){
			username=$("#signin-email").val();
			password=$("#signin-password").val();
		  	$.ajax({
		   		type: "POST",
		   		url: "/gold-app/gold-includes/GOLD.php",
				data: "gold=login&name="+username+"&password="+password,
		   		success: function(html){
					if(html=='true') {
			 			//$("#add_err").html("right username or password");
			 			window.location="/admin";
					} else {
			 			$("#cd-error-message").css('display', 'inline', 'important');
			 			$("#cd-error-message").html(html);
					}
		   		}
			});
			return false;
		});
		
$("#cd-error-message").hide();
$(function(){
		$("#drag_elements ul").sortable({ scroll: true, scrollSensitivity: 100, opacity: 0.6, cursor: "move", update: function() {
						var order = "gold=admin_menu&"+$(this).sortable("serialize") + "&action=updateRecordsListings";
						$.post("/gold-app/gold-includes/GOLD.php", order, function(theResponse){
							
						});
		} });
		$("#main_sidebar_drag_elements ul").sortable({ scroll: true, scrollSensitivity: 100, opacity: 0.6, cursor: "move", update: function() {
						var order = "gold=admin_menu&"+$(this).sortable("serialize") + "&action=main_sidebar_updateRecordsListings";
						$.post("/gold-app/gold-includes/GOLD.php", order, function(theResponse){
							
						});
		} });
		$("#profile_sidebar_drag_elements ul").sortable({ scroll: true, scrollSensitivity: 100, opacity: 0.6, cursor: "move", update: function() {
						var order = "gold=admin_menu&"+$(this).sortable("serialize") + "&action=profile_sidebar_updateRecordsListings";
						$.post("/gold-app/gold-includes/GOLD.php", order, function(theResponse){
							
						});
		} });
		$("#post_sidebar_drag_elements ul").sortable({ scroll: true, scrollSensitivity: 100, opacity: 0.6, cursor: "move", update: function() {
						var order = "gold=admin_menu&"+$(this).sortable("serialize") + "&action=post_sidebar_updateRecordsListings";
						$.post("/gold-app/gold-includes/GOLD.php", order, function(theResponse){
							
						});
		} });
		function ins2pos(str, id) {
   			var TextArea = document.getElementById(id);
   			var val = TextArea.value;
   			var before = val.substring(0, TextArea.selectionStart);
   			var after = val.substring(TextArea.selectionEnd, val.length);
   			TextArea.value = before + str + after;
		}
		$('#reply_emoticons a').live('click', function() {
   			GOLD_id = $(this).attr('data-id');
			var smiley = $(this).attr('title');
    		ins2pos(smiley, 'reply_textarea'+GOLD_id);
		});
		$('#emoticons a').live('click', function() {
			var smiley = $(this).attr('title');
    		ins2pos(smiley, 'comment_value');
		});
		$(".social-share").click(function(event) {
    		var width  = 575,
       			height = 400,
        		left   = ($(window).width()  - width)  / 2,
        		top    = ($(window).height() - height) / 2,
        		url    = this.href,
        		opts   = 'status=1' +
                 ',width='  + width  +
                 ',height=' + height +
       	         ',top='    + top    +
        	     ',left='   + left;
    	
    			window.open(url, 'twitter', opts);
 			return false;
		});
		$("a.reply").live('click', function() {
			GOLD_id = $(this).attr('id');
			var toggled = $(this).data('toggled');
   	 		$(this).data('toggled', !toggled);
   			if (!toggled) {
				$("#reply_comment"+GOLD_id).css('display', 'block');
				$("#reply_textarea"+GOLD_id).css('height', '53px');
			} else {
				$("#reply_comment"+GOLD_id).css('display', 'none');
				$("#reply_textarea"+GOLD_id).css('height', '53px');
			}
		});
		$("a.flag").live('click', function(){
			GOLD_id = $(this).attr('id');
			jQuery("#flag_buttons"+GOLD_id).addClass("comments-div-flag");
			$.ajax({
				type: "POST",
				data: "gold=flag&id="+$(this).attr("id")+"&user_id="+$(this).attr("user_id")+"&type="+$(this).attr("type"),
				url: "/gold-app/gold-includes/GOLD.php",
				success: function(msg) { }
			});
		});
		$("a.btn-up").live('click', function(){
			GOLD_id = $(this).attr('id');
			jQuery("#vote_box_buttons"+GOLD_id).addClass('div-vote-up');
			$.ajax({
				type: "POST",
				data: "gold=vote_up&id="+$(this).attr("id")+"&user_id="+$(this).attr("user_id"),
				url: "/gold-app/gold-includes/GOLD.php",
				success: function(msg)
				{
					$("#vote_box_num"+GOLD_id).html(msg);
					$("#vote_box_num"+GOLD_id).fadeIn();
				}
			});
		});
		$("a.btn-down").live('click', function(){
			//get the id
			GOLD_id = $(this).attr('id');
			jQuery("#vote_box_buttons"+GOLD_id).removeClass('div-vote-up');
			//the main ajax request
			$.ajax({
				type: "POST",
				data: "gold=vote_down&id="+$(this).attr("id")+"&user_id="+$(this).attr("user_id"),
				url: "/gold-app/gold-includes/GOLD.php",
				success: function(msg)
				{
					$("#vote_box_num"+GOLD_id).html(msg);
					$("#vote_box_num"+GOLD_id).fadeIn();
				}
			});
		});
		$("a.comments-vote-up").live('click', function(){
			GOLD_id = $(this).attr('id');
			jQuery("#vote_buttons"+GOLD_id).addClass('comments-div-vote-up');
			$("span#comment_votes"+GOLD_id).fadeOut("fast");
			$.ajax({
				type: "POST",
				data: "gold=comments_vote_up&id="+$(this).attr("id")+"&user_id="+$(this).attr("user_id"),
				url: "/gold-app/gold-includes/GOLD.php",
				success: function(msg)
				{
					$("span#comment_votes"+GOLD_id).html(msg);
					$("span#comment_votes"+GOLD_id).fadeIn();
				}
			});
		});
		$("a.comments-vote-down").live('click', function(){
			//get the id
			GOLD_id = $(this).attr('id');
			jQuery("#vote_buttons"+GOLD_id).removeClass('up');
			jQuery("#vote_buttons"+GOLD_id).removeClass('comments-div-vote-up');
			//the main ajax request
			$.ajax({
				type: "POST",
				data: "gold=comments_vote_down&id="+$(this).attr("id")+"&user_id="+$(this).attr("user_id"),
				url: "/gold-app/gold-includes/GOLD.php",
				success: function(msg)
				{
					$("span#comment_votes"+GOLD_id).fadeOut();
					$("span#comment_votes"+GOLD_id).html(msg);
					$("span#comment_votes"+GOLD_id).fadeIn();
				}
			});
		});
		$("#submit_reply_comment").live('click', function(){
		GOLD_id = $(this).attr('data-id');
		var comment	= $("#reply_textarea"+GOLD_id).val();
	    var post_id	= $("#post_id"+GOLD_id).val();
		
		if(comment=='')
		{
			$("#gold_comments_error"+GOLD_id).show();
			return false;
		}		
		var GOLD_DATA = "gold=submit_reply_comments&post_id="+post_id+"&comment="+comment+"&reply_comment_id="+GOLD_id;
		//alert(dataString);
		  $.ajax({
				type: "POST",  
				url: "/gold-app/gold-includes/GOLD.php", 
				cache:  false ,
				data: GOLD_DATA,
				beforeSend: function(){
					$("#gold_comments_error"+GOLD_id).hide();
			    },
				success: function(data)
				{
					$("#comment_children_tree"+GOLD_id).html(data);
					$("#comment_children_tree"+GOLD_id).fadeIn();
					$("#reply_comment"+GOLD_id).css("display", "none");
					$("#gold_comments_error"+GOLD_id).hide();
					$("#reply_textarea"+GOLD_id).val('');
					return false;
				}
			});	
			return false;;
		});
		$("#submit_comment").live('click', function(){
			var comment	= $("#comment_value").val();
	   		var post_id	= $("#post_id").val();
		
			if(comment=='')
			{
				$("#gold_comments_error").show();
				return false;
			}		
			var GOLD_DATA = "gold=comments&post_id="+post_id+"&comment="+comment;
			//alert(dataString);
		  $.ajax({
				type: "POST",  
				url: "/gold-app/gold-includes/GOLD.php", 
				cache:  false ,
				data: GOLD_DATA,  
				beforeSend: function(){
					$("#gold_comments_error").hide();
					$('#loading-indicator').show();	
			    },
				success: function(data)
				{
					$(".char_num").html('0');
					$("#gold_comments_error").hide();
					$(".responsive_no_comments").hide();
					$('#loading-indicator').hide();
					$('.responsive_comments_tree').html(data);
					$("#comment_value").val('');
					return false;
				}
			});	
			return false;;
	});
	});	


//<![CDATA[ 
$(window).load(function(){
	// Add class preview-right to Actor page
	// $('#container').find('.movie-element').each(function() {
	//	var index = $(this).index();
	//	if(4 - (index % 4) <= 2) {
	//		$(this).find('.preview-block').addClass('preview-right');
	//	}
	// });
	$('#profile_box').hover(function() {
    	 
	});
    $("#main-nav-toggle").click(function(event)
    {
        $(".sidebar").animate({width: 'toggle'});      
    });
    $("#close-aside").click(function(event)
    {
        $(".sidebar").animate({width: 'toggle'});      
    });
});//]]>  
 
//<![CDATA[ 

window.onload = function(){
        
    //Check File API support
    if(window.File && window.FileList && window.FileReader)
    {
        var filesInput = document.getElementById("add-file");
        
        filesInput.addEventListener("change", function(event){
            
            var files = event.target.files; //FileList object
            var output = document.getElementById("result");
            
            for(var i = 0; i< files.length; i++)
            {
                var file = files[i];
                
                //Only pics
                if(!file.type.match('image'))
                  continue;
                
                var picReader = new FileReader();
                
                picReader.addEventListener("load",function(event){
                    
					$("#upload-data").css("clear", "both");
					$("#add-seperator").hide();
					
                    var picFile = event.target;
                    
                    var div = document.createElement("div");
					
                    div.className = "preview_img";
					
                    div.innerHTML = "<img style='display: block; width: 280px; float: left;' src='" + picFile.result + "'" +
                            "title='" + picFile.name + "'/>";
							
                    output.insertBefore(div,null);            
                
                });
                
                 //Read the image
                picReader.readAsDataURL(file);
				
				
            }                               
           
        });
    }
    else
    {
        console.log("Your browser does not support File API");
    }
}
    
//]]> 