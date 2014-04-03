<?php
	/**
	 * Plugin Name: Lumne Slideshow
	 * Plugin URI: http://lumne.net/plugins/slideshow/updates
	 * Description: Slideshow designed and developed by Lumne.
	 * Version: 0.1
	 * Author: Chad Milburn
	 * Author URI: http://lumne.net
	 * License: GPL2

	 Copyright 2013  Lumne  (email : chad@lumne.net)

		This program is free software; you can redistribute it and/or modify
		it under the terms of the GNU General Public License, version 2, as 
		published by the Free Software Foundation.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
	*/
	
	function lumne_slideshow_activation(){
	
	}
	
	function lumne_slideshow_deactivation(){
	
	}
	
	register_activation_hook(__FILE__, 'lumne_slideshow_activation');
	register_deactivation_hook(__FILE__, 'lumne_slideshow_deactivation');
	
	add_action('admin_menu', 'lumne_slideshow_plugin_settings');
	add_action('admin_init','lumne_slideshow_admin_init');

	function lumne_slideshow_plugin_settings(){
		add_options_page('Lumne Slideshow Settings', 'Lumne Slideshow Settings', 'administrator', 'lumne_slideshow_settings', 'lumne_slideshow_display_settings');
		
		// Or add_menu_page
	}
	
	function lumne_slideshow_display_settings(){
	?>
		<h1>Lumne Slideshow Settings</h1>
		<form action="options.php" method="post">
			<?php settings_fields('plugin_options'); ?>
			<?php do_settings_sections('plugin'); ?>
			<input type="submit" value="<?php esc_attr_e('Save Changes'); ?>" />
		</form>
	<?php
		return true;
	}
	
	// Initiates admin page sections
	function lumne_slideshow_admin_init(){
		wp_register_style('lumne_slideshow', plugins_url('css/lumne_slideshow_admin.css', __FILE__));
		wp_enqueue_style('lumne_slideshow');
		lumne_slideshow_admin_content();

		register_setting('plugin_options', 'plugin_options', 'plugin_options_validate');
		$i=0;
		add_settings_section("plugin_gallery_{$i}", 'Adjust settings below as needed.', 'plugin_section_text', 'plugin');
		add_settings_field("plugin_text_slidepause_{$i}",  'Slide Pause',      'plugin_setting_slidepause',   'plugin', "plugin_gallery_{$i}", array('id'=>$i));
		add_settings_field("plugin_text_transition_{$i}",  'Transition Speed', 'plugin_setting_transition',   'plugin', "plugin_gallery_{$i}", array('id'=>$i));
		add_settings_field("plugin_text_images_{$i}",      'Image Settings',   'plugin_setting_images',       'plugin', "plugin_gallery_{$i}", array('id'=>$i));
	}

	function lumne_slideshow_admin_content(){
		wp_enqueue_script('jquery');
		wp_register_script('lumne_slideshow_admin', plugins_url('js/lumne_slideshow_admin.js', __FILE__), array('jquery')); // Depends on jQuery
		wp_enqueue_script('lumne_slideshow_admin');
		wp_enqueue_media();

	}
	
	// Displays description text for admin page section
	function plugin_section_text(){
		//$id = substr($section['id'], strpos($section['id'], 'plugin_gallery_')+15);
		//echo '<p>Settings for gallery.</p>';
	}

	// Displays Slide Pause Speed input
	function plugin_setting_slidepause(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<input id='plugin_text_slidepause_{$id}' name='plugin_options[slide_{$id}]' size='40' type='text' value='".(!empty($options["slide_{$id}"])?$options["slide_{$id}"]:'6500')."' />";
	}

	// Displays Slide Transisiton Speed input
	function plugin_setting_transition(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<input id='plugin_text_transition_{$id}' name='plugin_options[trans_{$id}]' size='40' type='text' value='".(!empty($options["trans_{$id}"])?$options["trans_{$id}"]:'300')."' />";
	}

	function plugin_setting_images(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		$path = plugin_dir_url(__FILE__);

		$images = preg_grep("/path_{$id}_\d*/", array_keys($options));

		//$c = 0;
		echo '<table class="lumne-image-table" data-delete-image="'.$path.'img/delete.png'.'">';
		foreach($images as $image){
			$c = substr(strrchr($image, '_'),1);
			echo '<tr class="lumne-image-row">';
				echo "<td>";
					echo "<img src='".$options["path_{$id}_{$c}"]."' class='lumne-image-preview' />";
					echo "<input type='hidden' name='plugin_options[path_{$id}_{$c}]' value='".$options["path_{$id}_{$c}"]."' />";
				echo "</td>";
				echo "<td>";
					echo "<input id='plugin_text_image_{$id}_{$c}' class='lumne-image-link' name='plugin_options[link_{$id}_{$c}]' size='30'";
						echo " type='text' value='".$options["link_{$id}_{$c}"]."' placeholder='Insert link here (e.g. http://lumne.net)' />";
				echo "</td>";
				echo "<td><img src='".$path."img/delete.png' id='delete_{$id}_{$c}' class='delete-image' /></td>";
			echo '</tr>';

		}
		echo '<td><input type="button" id="new_image'.$id.'" class="image-button" value="New image" data-count="'.$c.'" /></td>';
		echo '</table>';
	}
	
	// Validates input
	function plugin_options_validate($input){
		// Renumber images starting with zero
		 $images = preg_grep("/path_\d*_\d*/", array_keys($input));
		 //print_r($images);
		 //echo nl2br(print_r($input, true));
		 ksort($images);
		 $temp_array = array();
		 $c = 0;
		 $id = array();
		 foreach($images as $image){
		 	preg_match("/path_(\d*)_(\d*)/", $image, $id);
		 	//echo nl2br(print_r($id, true));
		 	//else echo 'No match found. Check regex.';
		 	$temp_array['path_'.$id[1].'_'.$c.''] = $input['path_'.$id[1].'_'.$id[2].''];
		 	$temp_array['link_'.$id[1].'_'.$c.''] = $input['link_'.$id[1].'_'.$id[2].''];
		 	unset($input['path_'.$id[1].'_'.$id[2].'']);
		 	unset($input['link_'.$id[1].'_'.$id[2].'']);
		 	$c++;
		 }
		 ksort($temp_array);

		return array_merge($input, $temp_array);
	}

	/*****************************************/
	function lumne_slideshow_output($atts,$content=NULL){
		/**/
		wp_register_style('lumne_slideshow', plugins_url('css/lumne_slideshow.css', __FILE__));
		wp_enqueue_style('lumne_slideshow');
		
		wp_enqueue_script('jquery');
		wp_register_script('lumne_slideshow', plugins_url('js/lumne_slideshow.js', __FILE__), array('jquery')); // Depends on jQuery
		wp_enqueue_script('lumne_slideshow');
		wp_register_script('jquery_effects', plugins_url('js/jquery-ui-1.10.3.custom.min.js', __FILE__), array('jquery')); // Depends on jQuery
		wp_enqueue_script('jquery_effects');

		$options = get_option('plugin_options');

		extract( shortcode_atts( array(	
								'id' => 0,
								'transition' => 'slide',
								'active' => 0
								), $atts ) );

		//$files = array_diff(scandir(getcwd().$options["path_{$id}"]),
		//						array('.','..'));


		$active = (in_array('active', $atts));

		$top = '';
		$output = '';

		$images = preg_grep("/path_{$id}_\d*/", array_keys($options));

		foreach($images as $image){
			$c = substr(strrchr($image, '_'),1);
			$top .= (isset($options["link_{$id}_{$c}"]) && filter_var($options["link_{$id}_{$c}"], FILTER_VALIDATE_URL) ? '<a href="'.$options["link_{$id}_{$c}"].'">' : '');
				$top .= "<img class='lumne_image' id='image{$id}_".$c."'src='".$options["path_{$id}_{$c}"]."' />";
			$top .= (isset($options["link_{$id}_{$c}"]) && filter_var($options["link_{$id}_{$c}"], FILTER_VALIDATE_URL) ? '</a>' : '');
		}

		$output = '<div class="lumne-slideshow'.($active?' active':'').'" id="gallery'.$id
							.'"data-pause="'.(!empty($options["slide_{$id}"])?$options["slide_{$id}"]:'6500')
							.'" data-trans="'.(!empty($options["trans_{$id}"])?$options["trans_{$id}"]:'300')
							.'" data-effect="'.'slide'.'">
						<div class="top-show">'
							.$top
					  .'</div>'
				 .'</div>';

		return $output;
	}

	add_shortcode('lumne_slideshow', 'lumne_slideshow_output');
	// do_shortcode('[lumne_slideshow]')

?>