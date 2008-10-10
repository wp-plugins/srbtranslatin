<?php
/*
 *
 * Copyright (c) 2008 Predrag Supurović
 *
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, without
 * modification, are permitted provided that the following conditions
 * are met:
 * 1. Redistributions of source code must retain the above copyright
 *    notice, this list of conditions and the following disclaimer.
 * 2. Redistributions in binary form must reproduce the above copyright
 *    notice, this list of conditions and the following disclaimer
 *    in the documentation and/or other materials provided with the
 *    distribution.
 * 3. The name of the author may not be used to endorse or promote
 *    products derived from this software without specific prior
 *    written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE AUTHOR ``AS IS'' AND ANY EXPRESS
 * OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED
 * WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED.  IN NO EVENT SHALL THE AUTHOR BE LIABLE FOR ANY
 * DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
 * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
 * GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER
 * IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR
 * OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN
 * IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 */

/*
Plugin Name: Transliteration of Serbian Cyrillic to Latin Script
Plugin URI: http://pedja.supurovic.net/srbtranslatin-potpuno-resenje-za-preslovljavanje-cirilice-u-latinicu-za-wordpress/
Description: Allows users to choose if they want to see site in Serbian Cyrillic or Serbian Latin script. After installation, check <a href="options-general.php?page=srbtranslatoptions">Settings</a>
Author: Predrag Supurović
Version: 0.13 beta (not yet for public use)
Author URI: http://pedja.supurovic.net
*/


/***********************************************************/
/***********************************************************/
/***********************************************************/

//		global $stl_show_widget_title;
//		global $stl_widget_title;
//		global $stl_widget_type;


$stl_default_language_opt_name = 'stl_default_language';
$stl_default_language_data_field_name = '$stl_default_language';

$stl_default_language = get_option( $stl_default_language_opt_name );

if ( ($stl_default_language != 'cir') and ($stl_default_language != 'lat') ) {
	$stl_default_language = 'cir';
}

$stl_transliterate_title_opt_name = 'stl_transliterate_title';
$stl_transliterate_title_data_field_name = 'stl_transliterate_title';
$stl_transliterate_title = get_option( $stl_transliterate_title_opt_name ) == 'on';

$stl_widget_title_opt_name = 'stl_widget_title';
$stl_widget_title_data_field_name = 'stl_widget_title';
$stl_widget_title = get_option( $stl_widget_title_opt_name );
if (empty ($stl_widget_title)) $stl_widget_title  = "Избор писма";

$stl_show_widget_title_opt_name = 'stl_show_widget_title';
$stl_show_widget_title_data_field_name = 'stl_show_widget_title';
$stl_show_widget_title = get_option ($stl_show_widget_title_opt_name) == 'on';

$stl_widget_type_opt_name = 'stl_widget_type';
$stl_widget_type_data_field_name = 'stl_widget_type';
$stl_widget_type = get_option ($stl_widget_type_opt_name);
if ( ($stl_widget_type != 'links') and ($stl_widget_type != 'list') ) {
	$stl_widget_type = 'links';
}


$stl_current_language = $stl_default_language;
$stl_global['init'] = true;


// Hook for adding admin menus
add_action('admin_menu', 'stl_add_page');



include ('urlt.php');

class SrbTransLatin {
    var $replace = array(
	    "А" => "A",
		"Б" => "B",
		"В" => "V",
		"Г" => "G",
		"Д" => "D",
		"Ђ" => "Đ",
		"Е" => "E",
		"Ж" => "Ž",
		"З" => "Z",
		"И" => "I",
		"Ј" => "J",
		"К" => "K",
		"Л" => "L",
		"Љ" => "LJ",
		"М" => "M",
		"Н" => "N",
		"Њ" => "NJ",
		"О" => "O",
		"П" => "P",
		"Р" => "R",
		"С" => "S",
		"Ш" => "Š",
		"Т" => "T",
		"Ћ" => "Ć",
		"У" => "U",
		"Ф" => "F",
		"Х" => "H",
		"Ц" => "C",
		"Ч" => "Č",
		"Џ" => "DŽ",
		"Ш" => "Š",
        "а" => "a",
		"б" => "b",
		"в" => "v",
		"г" => "g",
		"д" => "d",
		"ђ" => "đ",
		"е" => "e",
		"ж" => "ž",
		"з" => "z",
		"и" => "i",
		"ј" => "j",
		"к" => "k",
		"л" => "l",
		"љ" => "lj",
		"м" => "m",
		"н" => "n",
		"њ" => "nj",
		"о" => "o",
		"п" => "p",
		"р" => "r",
		"с" => "s",
		"ш" => "š",
		"т" => "t",
		"ћ" => "ć",
		"у" => "u",
		"ф" => "f",
		"х" => "h",
		"ц" => "c",
		"ч" => "č",
		"џ" => "dž",
		"ш" => "š",
		"Ња" => "Nja",
		"Ње" => "Nje",
		"Њи" => "Nji",
		"Њо" => "Njo",
		"Њу" => "Nju",
		"Ља" => "Lja",
		"Ље" => "Lje",
		"Љи" => "Lji",
		"Љо" => "Ljo",
		"Љу" => "Lju",
		"Џа" => "Dža",
		"Џе" => "Dže",
		"Џи" => "Dži",
		"Џо" => "Džo",
		"Џу" => "Džu"
   );


    function SrbTransLatin() {
			global $stl_default_language;
			global $stl_current_language;
			global $stl_transliterate_title;
			
			
		if ( isset($_REQUEST['lang']) ) {
			$stl_current_language  = $_REQUEST['lang'];
		} 
		
		if ( ($stl_current_language  != "cir") and ($stl_current_language != "lat") ) {
			$stl_current_language = $stl_default_language;
		}
		
		add_action("plugins_loaded",array(&$this,"init_lang"));

		if ($stl_transliterate_title) {
			add_action('sanitize_title', array(&$this, 'change_permalink'), 0);
		}

		add_action('wp_head', array(&$this,'buffer_start'));
		add_action('wp_footer', array(&$this,'buffer_end'));
		
		add_action('rss_head', array(&$this,'buffer_start'));		
		add_action('rss_footer', array(&$this,'buffer_end'));		

		add_action('atom_head', array(&$this,'buffer_start'));		
		add_action('atom_footer', array(&$this,'buffer_end'));		
		
		add_action('rdf_head', array(&$this,'buffer_start'));		
		add_action('rdf_footer', array(&$this,'buffer_end'));		
		
		add_action('rss2_head', array(&$this,'buffer_start'));		
		add_action('rss2_footer', array(&$this,'buffer_end'));		
		
		add_filter('option_blogname', array(&$this,'callback'), 0);
		add_filter('option_blogdescription', array(&$this,'callback'), 0);		

	} // function 
	
	
	function init_lang() {
		register_sidebar_widget("Serbian Scripts", array (&$this,"stl_scripts_widget"));
/*
		register_sidebar_widget("Serbian Transliteration (links)", array (&$this,"stl_links_widget"));
		register_sidebar_widget("Serbian Transliteration (list)",  array (&$this,"stl_list_widget"));
*/
	}



	function stl_scripts_widget() {
		global $stl_default_language;
		global $stl_current_language;
		global $stl_show_widget_title;
		global $stl_widget_title;
		global $stl_widget_type;

		global $stl_global;
		
		
?>
<!-- Widget Serbian Scripts -->
<?php
		if ($stl_show_widget_title) {
?>
<h3 class="widgettitle"><?php echo $stl_widget_title ?></h3>
<?php
		}
		if ($stl_widget_type == 'list') {
?>
<form action="" method="post">
<select name="lang" id="lang" onchange="this.form.submit()">
<option value="cir" <?php echo $stl_current_language=='cir' ? 'selected="selected"' : '' ?>><lang id="skip">ћирилица</lang></option>
<option value="lat" <?php echo $stl_current_language=='lat' ? 'selected="selected"' : '' ?>><lang id="skip">латиница</lang></option>
</select>
</form>
<?php
		} else {
?>
<ul>
<li><a href="<?php echo $stl_default_language != '1cir' ? '?lang=cir"' : '' ?>"><lang id="skip">ћирилица</lang></a></li>
<li><a href="<?php echo $stl_default_language != '1lat' ? '?lang=lat"' : '' ?>"><lang id="skip">latinica</lang></a></li>
</ul>

<?php
		} // if
?>

<!-- Widget Serbian Scripts -->
<?php
	} // function



	function convert_script($text) {
		global $stl_default_language;
		global $stl_current_language ;
		global $stl_global;
		
		$m_text = $text;
		$m_chunks = $this->parse_lang ($text, '');
		$m_text = $this->join_lang($m_chunks);
		
		if ( $stl_current_language != $stl_default_language ) {
			$m_text = alter_url_batch ($m_text);		
		}
		return $m_text;
	}
	
	
	function callback($buffer) {
		$m_buffer = $this->convert_script($buffer);
		return $m_buffer;
	}
	
	function buffer_start() { 
		ob_start(array(&$this,"callback")); 
	}
	 
	function buffer_end() {
		global $stl_global;	 
		ob_end_flush(); 
		
//print_r ($stl_global);
		
	}

	function change_permalink($title) {
		global $wpdb;
		if ($term = $wpdb->get_var("SELECT slug FROM $wpdb->terms WHERE name='$title'")) 
			return $term; 
		else 
			return strtr($title,$this->replace);

	}
	
	
    //
    // parse_lang ($p_input, $p_def_lang)
    //
    // Parse input string int chunks delimited by <lang></lang> tabs. That allows us to process them with 
	// different languages (each chunk may be set to other language provided in <lang id="nn"> tag where 
	//	nn is language id). You may set defautl language which is used if chunk doews not heave it's own 
	// language set. If chuks are nested, language of containeer will be used for contained chung, except
	// if contained chung does not have its own language set.
	function parse_lang ($p_input, $p_def_lang) {
		$regex = '#\<lang.*?\>((?:[^<]|\<(?!/?lang.*?\>)|(?R))+)\</lang\>#';
		
		if (preg_match ($regex, $p_input)) {
			$split = preg_split ($regex, $p_input,2);
	
			//tekuci nivo, prefiks
			unset ($m_out);
			$m_out['lang'] = $p_def_lang;
			$m_out['value'] = $split[0];
			$out[0] = $m_out;
	
			// sledeci nivo po dubini
			$m_input = $p_input;
			
			if (strlen ($split[0]) > 0) $m_input = substr ($m_input, strlen ($split[0]));
			if (strlen ($split[1]) > 0) $m_input = substr ($m_input, 0, - strlen ($split[1]));
			
			preg_match ('/\<lang(( +[^\>]*)?id=(")?(([a-z]-?)*)"?)?\>/', $p_input, $m_matches);
			
			if (! empty ($m_matches[4])) {
				$m_cur_lang = $m_matches[4]; 
			} else {
				$m_cur_lang = $p_def_lang; 
			}
			
			$m_input = preg_replace ($regex, '$1', $m_input);		
	
			unset ($m_out);
			$m_out[0] = $this->parse_lang ($m_input, $m_cur_lang);
			$out[1] = $m_out;
			
			// tekuci nivo, sufiks
			unset ($m_out);
			$m_out['lang'] = $p_def_lang;
			$m_out = $this->parse_lang ($split[1], $m_out['lang']);
			$out[2] = $m_out;
				
			$m_result = $out;
		} else {
			$m_result[0]['lang'] = $p_def_lang;
			$m_result[0]['value'] = $p_input;
		}
		return ($m_result);
	}
	
	
    //
    // function joinLang ($p_input)
    //
    // Parse input string int chunks delimited by <lang></lang> tabs. That allows us to process them with 
	// different languages (each chunk may be set to other language provided in <lang id="nn"> tag where 
	//	nn is language id)
	function join_lang ($p_input) {
		global $stl_global;
		global $stl_current_language;
		
		$m_result = '';
		foreach ($p_input as $m_item) {
			if (isset ($m_item['value'])) {
				if (! empty ($m_item['value'])) {
					if ($m_item['lang'] === 'skip') {
						$m_result .= $m_item['value'];
					} else {
						if ( $stl_current_language == "lat" ) {
							$m_result .= strtr($m_item['value'], $this->replace);
						} else {
							$m_result .= $m_item['value'];
						}
					}
				}
			} else {
				$m_result .= $this->join_lang ($m_item);
			}
		}
		return ($m_result);
	}	
	
	
	
	function test ($m_string) {
		return "###$m_string###";
	}
	


} // class

	function alter_url_batch ($p_url) {
	  return preg_replace_callback("/(src=\"|href=\"|background=\"|\<link\>)(.*?)(\"|\<\/link\>)/is", 'alter_url', $p_url);
	}

	function alter_url($p_urls){
		global $stl_current_language;
		global $stl_default_language;
		global $g_buffer;

		$m_site_url = get_option('home');

		if ( $m_site_url == substr ($p_urls[2], 0, strlen ($m_site_url)) ) {
			return $p_urls[1] . url_add_param ($p_urls[2], 'lang=' . $stl_current_language, false) . $p_urls[3];
		} else {
			return $p_urls[1] . $p_urls[2] . $p_urls[3];
		}
	}
	
	
	
function stl_add_page() {

    // Add a new submenu under Options:
    add_options_page('SrbTransLat', 'SrbTransLat', 8, 'srbtranslatoptions', 'stl_options_page');
	
}	

// mt_options_page() displays the page content for the Test Options submenu
function stl_options_page() {
	global $stl_default_language_opt_name;
	global $stl_default_language_data_field_name;
	global $stl_widget_title_opt_name;
	global $stl_widget_title_data_field_name;
	global $stl_transliterate_title_opt_name;
	global $stl_transliterate_title_data_field_name;
	global $stl_show_widget_title_opt_name;
	global $stl_show_widget_title_data_field_name;
	global $stl_widget_type_opt_name;
	global $stl_widget_type_data_field_name;
	
	
   // Read in existing option value from database
    $stl_default_language_opt_val = get_option( $stl_default_language_opt_name );
	if (empty ($stl_default_language_opt_val)) $stl_default_language_opt_val = 'cir';
	
	$stl_widget_title_opt_val = get_option($stl_widget_title_opt_name);
	if (empty ($stl_widget_title_opt_val)) $stl_widget_title_opt_val = "Избор писма";

	$stl_transliterate_title_opt_val = get_option($stl_transliterate_title_opt_name);
	if (empty ($stl_transliterate_title_opt_val)) $stl_transliterate_title_opt_val = 'on';

	$stl_show_widget_title_opt_val = get_option($stl_show_widget_title_opt_name);	
	if (empty ($stl_show_widget_title_opt_val)) $stl_show_widget_title_opt_val = 'off';	

	$stl_widget_type_opt_val = get_option($stl_widget_type_opt_name);	

	if ( ($stl_widget_type_opt_val != 'links') and ($stl_widget_type_opt_val != 'list') ) {
		$stl_widget_type_opt_val = 'links';
	}

    // See if the user has posted us some information
    // If they did, this hidden field will be set to 'Y'
    if( ! empty ($_POST['Submit']) ) {
        // Read their posted value
        $stl_default_language_opt_val = $_POST[ $stl_default_language_data_field_name ];
        update_option( $stl_default_language_opt_name, $stl_default_language_opt_val );

        $stl_widget_title_opt_val = $_POST[ $stl_widget_title_data_field_name ];
        update_option( $stl_widget_title_opt_name, $stl_widget_title_opt_val );
				
				$stl_transliterate_title_opt_val = $_POST[$stl_transliterate_title_data_field_name];
        update_option( $stl_transliterate_title_opt_name, $stl_transliterate_title_opt_val );
		
				$stl_show_widget_title_opt_val = $_POST[$stl_show_widget_title_data_field_name];	
        update_option( $stl_show_widget_title_opt_name, $stl_show_widget_title_opt_val );
		
				$stl_widget_type_opt_val = $_POST[$stl_widget_type_data_field_name];		
        update_option( $stl_widget_type_opt_name, $stl_widget_type_opt_val );		

        // Put an options updated message on the screen

?>
<div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
<?php

    }

    // Now display the options editing screen

    echo '<div class="wrap">';

    // header

    echo "<h2>" . __( 'SrbTransLat Plugin Options') . "</h2>";

    // options form
    
    ?>

<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<table class="form-table">
<tr>
<th scope="row"><?php _e("Default script:"); ?></th>
<td>
<select name="<?php echo $stl_default_language_data_field_name; ?>">
<option value="cir" <?php echo $stl_default_language_opt_val=='cir' ? 'selected="selected"' : '' ?>><lang id="skip">ћирилица</lang></option>
<option value="lat" <?php echo $stl_default_language_opt_val=='lat' ? 'selected="selected"' : '' ?>><lang id="skip">латиница</lang></option>
</select>
<br /><?php _e("Set script that would be used as default, if user do not make script choice"); ?></td>
</tr>
<tr>
<th scope="row"><?php _e("Permalink options:"); ?></th>
<td><input name="<?php echo $stl_transliterate_title_data_field_name; ?>" type="checkbox" <?php echo $stl_transliterate_title_opt_val=='on' ? 'checked="checked"' : '' ?>> <?php _e("transliterate title to permalink"); ?><br />
Check to make blog autocreate permalinks in Latin script even if title is in Cyrillic.
</td>
</tr>
<tr>
<th scope="row"><?php _e("Widget options:"); ?></th>
<td>
<input name="<?php echo $stl_show_widget_title_data_field_name; ?>" type="checkbox" <?php echo $stl_show_widget_title_opt_val=='on' ? 'checked="checked"' : '' ?>> <?php _e("show widget title"); ?>
<br />
<?php _e("Widget title:"); ?> <input name="<?php echo $stl_widget_title_data_field_name; ?>" value="<?php echo $stl_widget_title_opt_val; ?>">
<br />
<?php _e("Selection type:"); ?>
<select name="<?php echo $stl_widget_type_data_field_name; ?>">
<option value="links" <?php echo $stl_widget_type_opt_val=='links' ? 'selected="selected"' : '' ?>><lang id="skip"><?php _e("web links")?></lang></option>
<option value="list" <?php echo $stl_widget_type_opt_val=='list' ? 'selected="selected"' : '' ?>><lang id="skip"><?php _e("combo box")?></lang></option>
</select>

</td>
</tr>
</table>
<hr />

<p class="submit">
<input type="submit" name="Submit" value="<?php _e('Update Options') ?>" />
</p>

</form>
</div>

<?php
 
}



$wppSrbTransLatin =& new SrbTransLatin;

?>