<?php
add_filter('et_template_mobile', 'fre_change_full_width_template', 10, 3);
function fre_change_full_width_template($new_template, $template, $filename) {
	if($filename == 'page-full-width.php' && et_load_mobile()) {
		global $post;
		$page_on_front = get_option('page_on_front');
		if($page_on_front == $post->ID) {
			$child_path = get_stylesheet_directory() . '/mobile' . '/page-home.php';
			if(file_exists($child_path)) {
				return $child_path;
			}
			return get_template_directory() . '/mobile' . '/page-home.php';
		}
	}
	return $new_template;
}
