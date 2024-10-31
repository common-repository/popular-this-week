<?php
/*
Plugin Name: Popular This Week
Plugin URI: http://wordpress.org/extend/plugins/popular-this-week/
Description: Figures out what the most popular posts on the last week are
Version: 1.0
Author: Chris Northwood
Author URI: http://www.pling.org.uk/

Nouse revision: 1228

*/

function popular_this_week($a)
{
    global $wpdb, $table_prefix;
    
    echo $a['before_widget'];
    echo $a['before_title'] . 'Popular This Week' . $a['after_title'];
    echo '<ul id="popular-this-week">';
    
    if (!isset($a['count']))
    {
        $query = $wpdb->prepare("SELECT DISTINCT post_ID FROM `{$table_prefix}ptw` GROUP BY post_ID ORDER BY COUNT(*) DESC LIMIT 0,%d", get_option('ptw_count'));
    }
    else
    {
        $query = $wpdb->prepare("SELECT DISTINCT post_ID FROM `{$table_prefix}ptw` GROUP BY post_ID ORDER BY COUNT(*) DESC LIMIT 0,%d", $a['count']);
    }
    $res = $wpdb->get_col($query);
    foreach ($res as $id)
    {
        echo '<li><a href="' . get_permalink($id) . '">' . get_the_title($id) . '</a></li>';
    }
    
    echo '</ul>';
    echo $a['after_widget'];
}

// Put this in your template
function ptw_countview()
{
    global $wpdb, $post, $table_prefix;
    
    if (!isset($post))
    {
        return;
    }
    
    $query = "INSERT INTO `{$table_prefix}ptw` (post_ID,hitdt) VALUES('$post->ID', NOW())";
    $wpdb->query($query);
}

/* Clean up database */
register_activation_hook(__FILE__, 'ptw_activate');
add_action('ptw_cron', 'ptw_cron');

function ptw_activate()
{
    global $wpdb, $table_prefix;
    
    $sql = "CREATE TABLE IF NOT EXISTS`{$table_prefix}ptw` (`post_ID` BIGINT( 20 ) NOT NULL , `hitdt` DATETIME NOT NULL)";
    $wpdb->query($sql);
    wp_schedule_event(time(), 'hourly', 'ptw_cron');
    add_option('ptw_count', 5);
}

function ptw_cron()
{
    global $wpdb, $table_prefix;
    
    // Delete hits older than 1 week
    $wpdb->query("DELETE FROM `{$table_prefix}ptw` WHERE hitdt < DATE_SUB(NOW(), INTERVAL 1 week)");
}


register_deactivation_hook(__FILE__, 'ptw_unschedule');

function ptw_unschedule()
{
    wp_clear_scheduled_hook('ptw_cron');
}

function ptw_loaded()
{
    register_sidebar_widget('Popular This Week', 'popular_this_week');
    register_widget_control('Popular This Week', 'ptw_options');
}

add_action('plugins_loaded', 'ptw_loaded');

function ptw_options()
{
    if (isset($_POST['ptw-count']))
    {
        update_option('ptw_count', (int)$_POST['ptw-count']);
    }
?>
    <p><label for="ptw-count">Number to show: <input style="width: 250px;" id="ptw-count" name="ptw-count" type="text" value="<?php echo get_option('ptw_count'); ?>" /></label></p>
<?php
}

?>
