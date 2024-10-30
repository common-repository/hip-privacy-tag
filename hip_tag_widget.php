<?php
include_once 'ob_settings.php';
?>
<?php
/**
 * HipTagWidget Class
 */
class HIPTagWidget extends WP_Widget {
    /** constructor */
    function HIPTagWidget() {
        parent::WP_Widget(false, $name = 'HIPTagWidget');
    }

    /** @see WP_Widget::widget */
    function widget($args, $instance) {
        extract( $args );
        $title = '';//apply_filters('widget_title', $instance['title']);
		$content = $before_widget . $before_title . $title . $after_title . '<ul><li>' . $this->renderContent() . '</li></ul>'. $after_widget;
		echo $content;
    }

    /** @see WP_Widget::update */
	function update($new_instance, $old_instance) {
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {
    	echo "Configure options for this widget in the <a href='./options-general.php?page=hip_privacy'>Settings Menu</a>";
    }

    function renderContent(){
    	$display_type = get_option("hipSettings_display_type", 1);
		$display_type = $display_type['text_string'];

		$display_text = get_option("hipSettings_display_text", 1);
		$display_text = $display_text['text_string'];

		$display_width = get_option("hipSettings_width", 250);
		$display_width = $display_width['text_string'];

		$display_height = get_option("hipSettings_height", 40);
		$display_height = $display_height['text_string'];

		if($display_type === "4"){
			$display_width = "0";
			$display_height = "0";
		}

		$data_page = get_option("hipSettings_data_page", 1);
		$data_page = $data_page['text_string'];

		$data_meta = get_option("hipSettings_data_meta", 1);
		$data_meta = $data_meta['text_string'];
		if($data_meta == "0"){
			$data_meta = "";
		}

		$data_meta_count = get_option("hipSettings_data_meta_count", 1);
		$data_meta_count = $data_meta_count['text_string'];

		$data_custom = get_option("hipSettings_data_custom", 1);
		$data_custom = $data_custom['text_string'];

		$data_search = get_option("hipSettings_data_search", 1);
		$data_search = get_option("hipSettings_data_search", 1);
        if($data_search == "0"){
	        $data_search = "";
	    }

		return "<scr" . "ipt type='text/javascript'>
			var params = {};
			params.page = '" . $data_page . "';
			params.meta_limit= '" . $data_meta_count . "';
			params.meta='" . $date_meta ."';
			params.searchterms='" . $data_search  . "';
			params.custom = '" . $data_custom . "';
			params.show = '" . $display_type . "';
			params.man_url= '';
			params.width= '" . $display_width . "';
			params.height= '" . $display_height . "';
			params.verbdesc= '" . $display_text . "';
			params.verbbutton= 'optin';
			params.button= '2';
			params.font='arial';
			params.img = 'hip_25.png';

			if(typeof HIPCustomData !== 'undefined'){params.customdata=HIPCustomData;}else{params.customdata='';}</scr" . "ipt><scr" . "ipt src='http://hip-data/js/hip_ups.js'></scr" . "ipt>";
    }
}
?>
