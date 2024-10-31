<?php
/*
Plugin Name: My Post Editor
Plugin URI: http://www.ij2ee.com/my-post-editor
Description: The plugin's role is to append user-specified content before or after an article,and support html.
For instance, you can put google ads,website traffic statistics code, etc.
Version: 1.2
Author: 三少
Author URI:  http://www.ij2ee.com/
*/
 class My_Post_Editor {
	var $pre_txt = "pre_txt";
	var $suf_txt = "suf_txt";
	var $option_name = "myPostEditor";
	function My_Post_Editor(){

	}
	
	function edit_setting() {
		if ( isset($_POST['action']) && $_POST['action'] == 'update' ){
			update_option($this->pre_txt, stripslashes($_POST['pre_txt']));
			update_option($this->suf_txt, stripslashes($_POST['suf_txt']));
			echo '<div id="message" class="updated fade"><p><strong>post editor </strong></p></div>';
		}elseif(isset($_POST['action']) && $_POST['action'] == 'delete'){
			delete_option($this->pre_txt);
			delete_option($this->suf_txt);
			echo '<div id="message" class="updated fade"><p><strong>Your setting was deleted. </strong></p></div>';
		}
		echo '<div class="wrap">';
		
		echo '<form id=', "\"{$this->option_name}\"",  ' action="'.$_SERVER['REQUEST_URI'].'" method="post">';
		wp_nonce_field('update-options');
		
		echo '<ul>';
		
		echo '<li>'.__('add some content before or after your post content. you can use the delete button to delte this option','my-post-editor').'</li>';
		echo '<li>';
		echo '<h2>'.__('Content before your post','my-post-editor').'</h2>';
		echo '<textarea name="pre_txt" style="width:100%;height:200px;">';
		if($settings = get_option($this->pre_txt)){
			echo stripslashes($settings);
		}else{
			;
		}
		echo '</textarea>';
		echo '</li><li>';
		echo '<h2>'.__('Content after your post','my-post-editor').'</h2>';
		echo '<textarea name="suf_txt" style="width:100%;height:200px;">';
		if($settings = get_option($this->suf_txt)){
			echo stripslashes($settings);
		}else{
			;
		}
		
		echo '</textarea>';

		echo '</li>';
		echo '<li><input type="hidden" id="action" name="action" value="update">';
		echo '<p><input type="submit" value="'.__('update','my-post-editor').'"> <input type="button" onclick="delete_setting();" value="'.__('delete','my-post-editor').'"></p></li>';
		echo '</ul>';
		echo "<script type='text/javascript'>function delete_setting(){alert('delete?');document.getElementById('action').value='delete';document.getElementById('{$this->option_name}').submit();}</script>";
		
		echo '</form>';
		echo '</div>';
		_e('Content before your post','my-post-editor');
	}
	
	function My_Post_Editor_admin() {
	  if (function_exists('add_options_page')) {
	    add_options_page('My Post Editor' /* page title */, 
	                     'My Post Editor' /* menu title */, 
	                     8 /* min. user level */, 
	                     basename(__FILE__) /* php file */ , 
	                     array($this, 'edit_setting') /* function for subpanel */);
	  }
	}
	
}

function insertFootNoteForMPE($content) {
        if(is_single()&&1==1) {
				$content= stripslashes(get_option("pre_txt")).$content;
                $content.= stripslashes(get_option("suf_txt"));
        }
        return $content;
}
add_filter ('the_content', 'insertFootNoteForMPE');
$_My_Post_Editor = new My_Post_Editor();
add_action('admin_menu',  array($_My_Post_Editor, 'My_Post_Editor_admin'));
//load_plugin_textdomain('my-post-editor', "/wp-content/plugins/my-post-editor/");
load_plugin_textdomain( 'my-post-editor', '', dirname( plugin_basename( __FILE__ ) ) . '/lang' );
?>
