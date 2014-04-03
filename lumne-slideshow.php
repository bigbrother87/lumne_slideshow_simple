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
		<h1>Settings here</h1>
		<form action="options.php" method="post">
			<?php lumne_slideshow_setting_gallery_number(); ?>
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
		add_settings_field('lumne_slideshow_gallery_number',   'Gallery Number',   'lumne_slideshow_setting_gallery_number', 'plugin');
		$i=0;
		add_settings_section("plugin_gallery_{$i}", "Gallery Settings {$i}", 'plugin_section_text', 'plugin');
		add_settings_field("plugin_text_width_{$i}",       'Width',            'plugin_setting_width',        'plugin', "plugin_gallery_{$i}", array('id'=>$i));
		add_settings_field("plugin_text_height_{$i}",      'Height',           'plugin_setting_height',       'plugin', "plugin_gallery_{$i}", array('id'=>$i));
		add_settings_field("plugin_text_slidepause_{$i}",  'Slide Pause',      'plugin_setting_slidepause',   'plugin', "plugin_gallery_{$i}", array('id'=>$i));
		add_settings_field("plugin_text_transition_{$i}",  'Transition Speed', 'plugin_setting_transition',   'plugin', "plugin_gallery_{$i}", array('id'=>$i));
		add_settings_field("plugin_text_images_{$i}",      'Image Settings',   'plugin_setting_images',       'plugin', "plugin_gallery_{$i}", array('id'=>$i));
	}

	function lumne_slideshow_setting_gallery_number(){
		echo '<select>
			<option value="none">Please select a slideshow ID:</option>
			<option value="0">0</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			</select>';
	}

	function lumne_slideshow_admin_content(){
		wp_enqueue_script('jquery');
		wp_register_script('lumne_slideshow_admin', plugins_url('js/lumne_slideshow_admin.js', __FILE__), array('jquery')); // Depends on jQuery
		wp_enqueue_script('lumne_slideshow_admin');
		wp_enqueue_media();

	}
	
	// Displays description text for admin page section
	function plugin_section_text($section){
		$id = substr($section['id'], strpos($section['id'], 'plugin_gallery_')+15);
		echo '<p>Settings for gallery ID '.$id.'.</p>';
	}
	

	// Displays Path attribute input
	function plugin_setting_path(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<input id='plugin_text_path_{$id}' name='plugin_options[path_{$id}]' size='40' type='text' value='".$options["path_{$id}"]."' />";
	}
	
	// Displays Width attribute input
	function plugin_setting_width(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<input id='plugin_text_width_{$id}' name='plugin_options[width_{$id}]' size='40' type='text' value='".$options["width_{$id}"]."' />";
	}
	
	// Displays Height attribute input
	function plugin_setting_height(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<input id='plugin_text_height_{$id}' name='plugin_options[height_{$id}]' size='40' type='text' value='".$options["height_{$id}"]."' />";
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

	// Displays Dots options
	function plugin_setting_dots(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<input id='plugin_text_dots_{$id}' name='plugin_options[dots_{$id}]' type='checkbox' value='1'".($options["dots_{$id}"]==1?' checked':'')." />";
	}

	// Displays Path attribute input
	function plugin_setting_dots_location(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<select id='plugin_text_dots_location_{$id}' name='plugin_options[dots_location_{$id}]'>\n";
			echo "<option value='tl'".($options["dots_location_{$id}"]=='tl'?' selected="selected"':'').">Top Left</option>";
			echo "<option value='tr'".($options["dots_location_{$id}"]=='tr'?' selected="selected"':'').">Top Right</option>";
			echo "<option value='bl'".($options["dots_location_{$id}"]=='bl'?' selected="selected"':'').">Bottom Left</option>";
			echo "<option value='br'".($options["dots_location_{$id}"]=='br'?' selected="selected"':'').">Bottom Right</option>";
		echo "</select>";
	}

	// Displays Thumbnail options
	function plugin_setting_thumbs(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<input id='plugin_text_thumbs_{$id}' name='plugin_options[thumbs_{$id}]' type='checkbox' value='1'".($options["thumbs_{$id}"]==1?' checked':'')." />";
	}
	
	// Displays Thumbnail Width attribute input
	function plugin_setting_width_th(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<input id='plugin_text_width_th_{$id}' name='plugin_options[width_th_{$id}]' size='40' type='text' value='".$options["width_th_{$id}"]."' />";
	}
	
	// Displays Thumbnail Height attribute input
	function plugin_setting_height_th(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		echo "<input id='plugin_text_height_th_{$id}' name='plugin_options[height_th_{$id}]' size='40' type='text' value='".$options["height_th_{$id}"]."' />";
	}

	function plugin_setting_images(array $args){
		$id = $args['id'];
		$options = get_option('plugin_options');
		$path = plugin_dir_url(__FILE__);

		$c = 0;
		echo '<table>';
		while(isset($options["path_{$id}_{$c}"])){
			//echo 'linke';
			//echo $options["path_{$id}_{$c}"];

			/*echo "<label for='plugin_text_image_{$id}_{$c}' class='lumne-image'>"
						."<img src='".$options["path_{$id}_{$c}"]."' class='lumne-image-preview mid-align' />"
						."<input type='hidden' name='plugin_options[path_{$id}_{$c}]' value='".$options["path_{$id}_{$c}"]."' />"
						."<input id='plugin_text_image_{$id}_{$c}' class='lumne-image-link mid-align'"
							." name='plugin_options[link_{$id}_{$c}]' size='30' type='text' value='"
							.$options["link_{$id}_{$c}"]."' placeholder='Insert link here (e.g. http://lumne.net)' />"
					."</label>"."<img src='".$path.'img/delete.png'."' id='delete_{$id}_{$c}' class='delete-image mid-align' /><br />";
			*/
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
			$c++;
		}
		echo '<td><input type="button" id="new_image'.$id.'" class="image-button" value="New image" data-count="'.$c.'" /></td><td></td><td></td>';
		echo '</table>';
	}
	
	// Validates input
	function plugin_options_validate($input){
		$newinput['path'] = trim($input['path']);
		if(!preg_match('/^[a-z0-9\\/\\\\_.]*$/i', $newinput['path'])){
			$newinput['path'] = '';
		}
		return array_merge($newinput, $input);
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
		wp_register_script('jquery_scrollTo', plugins_url('js/jquery.scrollTo-1.4.3.1-min.js', __FILE__), array('jquery')); // Depends on jQuery
		wp_enqueue_script('jquery_scrollTo');

		$options = get_option('plugin_options');

		extract( shortcode_atts( array(	
								'id' => 0,
								'transition' => 'slide',
								'active' => 0
								), $atts ) );

		$files = array_diff(scandir(getcwd().$options["path_{$id}"]),
								array('.','..'));


		$active = (in_array('active', $atts));

		$top = '';
		$output = '';
		
		$img_count = 0;

		while(isset($options["path_{$id}_{$img_count}"])){
			$top .= (isset($options["link_{$id}_{$image_count}"]) && filter_var($options["link_{$id}_{$image_count}"], FILTER_VALIDATE_URL) ? '<a href="'.$options["link_{$id}_{$image_count}"].'">' : '');
				$top .= "<img class='lumne_image' id='image{$id}_".$img_count."'src='".$options["path_{$id}_{$img_count}"].$file."' />";
			$top .= (isset($options["link_{$id}_{$image_count}"]) && filter_var($options["link_{$id}_{$image_count}"], FILTER_VALIDATE_URL) ? '</a>' : '');
			$img_count++;
		}

		$output = '<div class="lumne-slideshow'.($active?' active':'').'" id="gallery'.$id
							.'"data-pause="'.(!empty($options["slide_{$id}"])?$options["slide_{$id}"]:'6500')
							.'" data-trans="'.(!empty($options["trans_{$id}"])?$options["trans_{$id}"]:'300')
							.'" data-width="'.$options["width_{$id}"]
							.'" data-height="'.$options["height_{$id}"].'" data-effect="'.'slide'.'">
						<div class="top-show" style="height:'
							.$options["height_{$id}"].'px;" >'
							.$top
					  .'</div>'
				 .'</div>';

		return $output;
	}

	add_shortcode('lumne_slideshow', 'lumne_slideshow_output');
	// do_shortcode('[lumne_slideshow]')

?>