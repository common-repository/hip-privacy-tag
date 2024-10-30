<?php
/*
Plugin Name: HIP Privacy API
Plugin URI: http://www.thehumaninformationproject.com/publisher/wordpress/index.html
Description: Privacy compliant User Profile plugin to read/write data about user.  Earn revenue from the data you collect.
Version: 1.1
Author: The Human Information Project
Author URI: http://www.thehumaninformationproject.com/publisher/wordpress/index.html
License: GPL2
*/
?>
<?php
/*  Copyright 2011 The Human Information Project  (email : wordpressplugin@thehumaninformationproject.com)

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
?>
<?php
include_once 'hip_tag_widget.php';

/**
 * Add Settings link to plugins - code from GD Star Ratings
 */
function add_settings_link($links, $file) {
	static $this_plugin;
	if (!$this_plugin) $this_plugin = plugin_basename(__FILE__);

	if ($file == $this_plugin){
		$settings_link = '<a href="admin.php?page=hip_privacy">'.__("Settings", "hip-privacy").'</a>';
 		array_unshift($links, $settings_link);
	}
	return $links;
}

add_filter('plugin_action_links', 'add_settings_link', 10, 2 );
add_action('widgets_init', create_function('', 'return register_widget("HIPTagWidget");'));
?>
