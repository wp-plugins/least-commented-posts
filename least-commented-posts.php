<?php
/*
Plugin Name: Least commented posts plugins
Plugin URI: http://xiaoke.name/798.html
Description: Least commented posts plugins  -  作者：小可。感谢老唐的帮助，没有他，这个插件不能面世。在侧边栏列出评论最少的文章，当然，你也可以修改成评论最多的文章，把ASC改成DESC就可以了，不过要到修改文件中去改。<a href="/wp-admin/plugin-editor.php?file=least-commented-posts/least-commented-posts.php">点击修改</a> 把其中$cold_post_order = ASC 的ASC修改成DESC就是热评文章了。Author: Xiao Ke. Thank help of Laotang, without him, this plug-in is not available. Comment least listed in the sidebar article, of course, you can also modify the Most articles changed to ASC DESC on it, however, to modify the file to change. <a href="/wp-admin/plugin-editor.php?file=least-commented-posts/least-commented-posts.php"> click Modify </ a> which $ cold_post_order = ASC ASC modified DESC is Lively article.
Version: 1.0.0.3
Author: 小可
Author URI: http://xiaoke.name/
*/
//DESC 升序(热评) ASC 倒序(冷评) 
	function get_coldcommented($cold_post_order = ASC,$limit) {
    global $wpdb, $post;
    $coldcommenteds = $wpdb->get_results("SELECT id, post_title, comment_count 
		FROM {$wpdb->prefix}posts 
		WHERE post_type='post' AND post_status='publish' AND post_password='' 
		ORDER BY comment_count $cold_post_order LIMIT $limit");
    foreach ($coldcommenteds as $post) {
			$post_title = htmlspecialchars(stripslashes($post->post_title));
			echo "<li><a href=\"".get_permalink($post->id)."\" title=\"$post_title\">$post_title</a></li>";
    }
}

class widget_test extends WP_Widget {
    function widget_test() {
        $widget_ops = array('description' => '在侧边栏列出评论最少的文章，当然，你也可以修改成评论最多的文章，把ASC改成DESC就可以了，不过要到修改文件中去改。　　小可博客　http://xiaoke.name/');
        $this->WP_Widget('widget_test', '小可牌冷评文章', $widget_ops);
    }
	
    function widget($args, $instance) {
        extract($args);
        $title = apply_filters('widget_title', esc_attr($instance['title']));
        $limit = strip_tags($instance['limit']);
        echo $before_widget.$before_title.$title.$after_title;
        echo '<ul>';
        get_coldcommented('',$limit);  //小工具需要执行的函数
        echo '</ul>';
        echo $after_widget;
    }
	
	function update($new_instance, $old_instance) {
        if (!isset($new_instance['submit'])) {
            return false;
        }
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['limit'] = strip_tags($new_instance['limit']);
        return $instance;
    }
	
	function form($instance) {
        global $wpdb;
        $instance = wp_parse_args((array) $instance, array('title' => '', 'limit' => ''));
        $title = esc_attr($instance['title']);
        $limit = strip_tags($instance['limit']);
?>

	<p>
            <label for="<?php echo $this->get_field_id('title'); ?>">小工具的名字：<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id('limit'); ?>">显示文章数量：<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="text" value="<?php echo $limit; ?>" /></label>
        </p>
        <input type="hidden" id="<?php echo $this->get_field_id('submit'); ?>" name="<?php echo $this->get_field_name('submit'); ?>" value="1" />
	
<?php
    }
}
add_action('widgets_init', 'widget_test_init');
function widget_test_init() {
    register_widget('widget_test');
}
?>