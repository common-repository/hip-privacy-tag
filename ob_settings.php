<?php
//
//  SETTINGS CONFIGURATION CLASS
//
//  By Olly Benson / v 1.2 / 13 July 2011 / http://code.olib.co.uk
//  Modified / Bugfix by Karl Cohrs / 17 July 2011 / http://karlcohrs.com
//
//  HOW TO USE
//  * add a include() to this file in your plugin.
//  * amend the config class below to add your own settings requirements.
//  * to avoid potential conflicts recommended you do a global search/replace on this page to replace 'ob_settings' with something unique
//  * Full details of how to use Settings see here: http://codex.wordpress.org/Settings_API

class hip_settings_config {

// MAIN CONFIGURATION SETTINGS

var $group = "hipSettings"; // defines setting groups (should be bespoke to your settings)
var $page_name = "hip_privacy"; // defines which pages settings will appear on. Either bespoke or media/discussion/reading etc

//  DISPLAY SETTINGS
//  (only used if bespoke page_name)

var $title = "HIP Privacy Tag Settings";  // page title that is displayed
var $intro_text = "This allows you to configure the HIP Tag exactly the way you want it.  Once configured you add the widget to your blog from the Widgets section."; // text below title
var $nav_title = "HIP Privacy Tag"; // how page is listed on left-hand Settings panel

//  SECTIONS
//  Each section should be own array within $sections.
//  Should contatin title, description and fields, which should be array of all fields.
//  Fields array should contain:
//  * label: the displayed label of the field. Required.
//  * description: the field description, displayed under the field. Optional
//  * suffix: displays right of the field entry. Optional
//  * default_value: default value if field is empty. Optional
//  * dropdown: allows you to offer dropdown functionality on field. Value is array listed below. Optional
//  * function: will call function other than default text field to display options. Option
//  * callback: will run callback function to validate field. Optional
//  * All variables are sent to display function as array, therefore other variables can be added if needed for display purposes

var $sections = array(
    'display_options' => array
    (
        'title' => "Display Options",
        'description' => "Settings to do with how the plugin is displayed",
        'fields' => array
        (
           'display_type' => array
           (
		      'label' => "Display Type",
			  'description' => "Choose how to display the tag",
			  'dropdown' => "dd_display_type",
			  'default_value' => "4"
		   ),
          'display_text' => array
          (
			  'label' => "Display Text",
			  'description' => "Choose text verb to show.  (Does not apply if you are not showing tag)",
			  'dropdown' => "dd_text",
			  'default_value' => "4"
		  ),
          'width' => array
          (
              'label' => "Width",
              'description' => " Width of the display",
              'length' => "3",
              'suffix' => "px",
              'default_value' => "250"
          ),
          'height' => array
          (
              'label' => "Height",
              'description' => " Height of the display",
              'length' => "3",
              'suffix' => "px",
              'default_value' => "80"
          )
      )
   ),
   'data_options' => array
   (
          'title' => 'Data Options',
          'description' => "Settings to do with what data you gather",
          'fields' => array
          (
            'data_page' => array
            (
			  'label' => "Current Page Url",
			  'description' => "Add current page URL to users HIP Profile",
			  'dropdown' => "dd_yes_no",
			  'default_value' => "1"
			),
            'data_meta' => array
            (
			  'label' => "HTML Meta Tags",
			  'description' => "Add current page META tags to users HIP Profile",
			  'dropdown' => "dd_yes_no",
			  'default_value' => "1"
			),
            'data_meta_count' => array
            (
			  'label' => "HTML Meta Tags Count",
			  'description' => "How many META tags to add. (Does not apply if you are not storing META tags)",
			  'dropdown' => "dd_meta_count",
			  'default_value' => "1"
            ),
            'data_custom' => array(
			  'label' => "Custom Data",
			  'description' => "To store custom data.",
			  'dropdown' => "dd_yes_no",
			  'default_value' => "1"
            ),
	    'data_search' => array
            (
                          'label' => "User Searches",
                          'description' => "Add user search used to get to current page if they came from a search engine",
                          'dropdown' => "dd_yes_no",
                          'default_value' => "1"
                        ),
         )
       )
    );

 // DROPDOWN OPTIONS
 // For drop down choices.  Each set of choices should be unique array
 // Use key => value to indicate name => display name
 // For default_value in options field use key, not value
 // You can have multiple instances of the same dropdown options

var $dropdown_options = array (
    'dd_display_type' => array (
        '1' => "Image + Text",
        '2' => "Image Only",
        '3' => "Text Only",
        '4' => "Nothing",
        ),
	'dd_text' => array (
			'HIP Compliant Website' => "HIP Compliant Website",
			'Your Privacy Protected By HIP' => "Your Privacy Protected By HIP",
			'Data Gathered By HIP' => "Data Gathered By HIP",
			'Privacy Compliant Website' => "Privacy Compliant Website",
		),
	'dd_yes_no' => array (
			'1' => "Yes",
			'2' => "No",
		),
	'dd_meta_count' => array (
			'1' => "1",
			'2' => "2",
			'3' => "3",
		)
    );



//  end class
};

class hip_settings {

function hip_settings($settings_class) {
    global $hip_settings;
    $hip_settings = get_class_vars($settings_class);

    if (function_exists('add_action')) :
      add_action('admin_init', array( &$this, 'plugin_admin_init'));
      add_action('admin_menu', array( &$this, 'plugin_admin_add_page'));
      endif;
}

function plugin_admin_add_page() {
  global $hip_settings;
  add_options_page($hip_settings['title'], $hip_settings['nav_title'], 'manage_options', $hip_settings['page_name'], array( &$this,'plugin_options_page'));
  }

function plugin_options_page() {
  global $hip_settings;
printf('</pre>
<div>
<h2>%s</h2>
%s
<form action="options.php" method="post">',$hip_settings['title'],$hip_settings['intro_text']);
 settings_fields($hip_settings['group']);
 do_settings_sections($hip_settings['page_name']);
 printf('<input type="submit" name="Submit" value="%s" /></form></div>
<pre>
',__('Save Changes'));
  }

function plugin_admin_init(){
  global $hip_settings;
  foreach ($hip_settings["sections"] AS $section_key=>$section_value) :
    add_settings_section($section_key, $section_value['title'], array( &$this, 'plugin_section_text'), $hip_settings['page_name'], $section_value);
    foreach ($section_value['fields'] AS $field_key=>$field_value) :
      $function = (!empty($field_value['dropdown'])) ? array( &$this, 'plugin_setting_dropdown' ) : array( &$this, 'plugin_setting_string' );
      $function = (!empty($field_value['function'])) ? $field_value['function'] : $function;
      $callback = (!empty($field_value['callback'])) ? $field_value['callback'] : NULL;
      add_settings_field($hip_settings['group'].'_'.$field_key, $field_value['label'], $function, $hip_settings['page_name'], $section_key,array_merge($field_value,array('name' => $hip_settings['group'].'_'.$field_key)));
      register_setting($hip_settings['group'], $hip_settings['group'].'_'.$field_key,$callback);
      endforeach;
    endforeach;
  }

function plugin_section_text($value = NULL) {
  global $hip_settings;
  printf("
%s

",$hip_settings['sections'][$value['id']]['description']);
}

function plugin_setting_string($value = NULL) {
  $options = get_option($value['name']);
  $default_value = (!empty ($value['default_value'])) ? $value['default_value'] : NULL;
  printf('<input id="%s" type="text" name="%1$s[text_string]" value="%2$s" size="40" /> %3$s%4$s',
    $value['name'],
    (!empty ($options['text_string'])) ? $options['text_string'] : $default_value,
    (!empty ($value['suffix'])) ? $value['suffix'] : NULL,
    (!empty ($value['description'])) ? sprintf("<em>%s</em>",$value['description']) : NULL);
  }

function plugin_setting_dropdown($value = NULL) {
  global $hip_settings;
  $options = get_option($value['name']);
  $default_value = (!empty ($value['default_value'])) ? $value['default_value'] : NULL;
  $current_value = ($options['text_string']) ? $options['text_string'] : $default_value;
    $chooseFrom = "";
    $choices = $hip_settings['dropdown_options'][$value['dropdown']];
  foreach($choices AS $key=>$option) :
    $chooseFrom .= sprintf('<option value="%s" %s>%s</option>',
      $key,($current_value == $key ) ? ' selected="selected"' : NULL,$option);
    endforeach;
    printf('
<select id="%s" name="%1$s[text_string]">%2$s</select>
%3$s',$value['name'],$chooseFrom,
  (!empty ($value['description'])) ? sprintf("<em>%s</em>",$value['description']) : NULL);
  }


//end class
}

$hip_settings_init = new hip_settings('hip_settings_config');
?>
