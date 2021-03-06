<?php
/**
 * The Template for displaying all archive events.
 *
 * Override this template by copying it to yourtheme/wp-events-manager/templates/archive-event.php
 *
 * @author        ThimPress
 * @package    wp-events-manager/template
 * @version     1.0
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

global $query_string;

$thim_options = get_theme_mods();
$happening_active = $upcoming_active = $expired_active = '';
$status = isset($_COOKIE['status']) ? $_COOKIE['status'] : 'upcoming';

switch ($status) {
    case 'upcoming':
        $upcoming_active = 'active';
        break;
    case 'expired':
        $expired_active = 'active';
        break;
    default:
        $happening_active = 'active';
        break;
}
$per_page = isset($thim_options['event_per_page']) ? get_theme_mod('event_per_page') : 4;

$number_column = isset($thim_options['event_column']) ? $thim_options['event_column'] : 2;

if (isset($_GET['column'])) {
    $number_column = $_GET['column'];
}


?>

<?php
/**
 * tp_event_before_main_content hook
 *
 * @hooked tp_event_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked tp_event_breadcrumb - 20
 */
do_action('tp_event_before_main_content');
?>

<?php
/**
 * tp_event_archive_description hook
 *
 * @hooked tp_event_taxonomy_archive_description - 10
 * @hooked tp_event_room_archive_description - 10
 */
do_action('tp_event_archive_description');
?>

<?php
/**
 * tp_event_before_event_loop hook
 *
 * @hooked tp_event_result_count - 20
 * @hooked tp_event_catalog_ordering - 30
 */
do_action('tp_event_before_event_loop');
?>

<div class="archive-content tab-content column-<?php echo esc_attr($number_column); ?>">

    <div class="tab-pane row <?php echo esc_attr($happening_active); ?>" id="happening">
        <?php

        if ($status === 'upcoming') {
            $paged = (get_query_var('paged')) ? intval(get_query_var('paged')) : 1;
        } else {
            $paged = 1;
        }

        $offset = ($paged == 1) ? 0 : (($paged - 1) * $per_page);
        $happening_args = array(
            'post_type' => array('tp_event'),
            'post_status' => 'tp-event-happenning',
            'posts_per_page' => $per_page,
            'page' => $paged,
            'offset' => $offset
        );


        $happening_args['posts_status'] = $status;

        $events_happening = new WP_Query($happening_args);


        if ($events_happening->have_posts()) {
            echo '<div class="clearfix">';
            while ($events_happening->have_posts()) {
                $events_happening->the_post();
                wpems_get_template_part('content', 'event');
            }
            echo '</div>';
        } else {
            echo '<p>No happening events!</p>';
        }

        thim_event_paging_nav($events_happening, 'happening');

        wp_reset_postdata();
        ?>
    </div>
    <div class="tab-pane row <?php echo esc_attr($upcoming_active); ?>" id="upcoming">
        <?php

        if ($status === 'upcoming') {
            $paged = (get_query_var('paged')) ? intval(get_query_var('paged')) : 1;
        } else {
            $paged = 1;
        }
        $offset = ($paged == 1) ? 0 : (($paged - 1) * $per_page);
        $upcoming_args = array(
            'post_type' => array('tp_event'),
            'post_status' => 'tp-event-upcoming',
            'posts_per_page' => $per_page,
            'page' => $paged,
            'offset' => $offset
        );
        $upcoming_args['posts_status'] = $status;

        $events_upcoming = new WP_Query($upcoming_args);

        if ($events_upcoming->have_posts()) {
            echo '<div class="clearfix">';
            while ($events_upcoming->have_posts()) {
                $events_upcoming->the_post();
                wpems_get_template_part('content', 'event');
            }
            echo '</div>';
        } else {
            echo '<p>No upcoming events!</p>';
        }

        thim_event_paging_nav($events_upcoming, 'upcoming');

        wp_reset_postdata();
        ?>
    </div>
    <div class="tab-pane row <?php echo esc_attr($expired_active); ?>" id="expired">
        <?php


        if ($status === 'expired') {
            $paged = (get_query_var('paged')) ? intval(get_query_var('paged')) : 1;
        } else {
            $paged = 1;
        }

        $offset = ($paged == 1) ? 0 : (($paged - 1) * $per_page);
        $expired_args = array(
            'post_type' => array('tp_event'),
            'post_status' => 'tp-event-expired',
            'posts_per_page' => $per_page,
            'page' => $paged,
            'offset' => $offset
        );

        $expired_args['posts_status'] = $status;

        $events_expired = new WP_Query($expired_args);

        if ($events_expired->have_posts()) {
            echo '<div class="clearfix">';
            while ($events_expired->have_posts()) {
                $events_expired->the_post();
                wpems_get_template_part('content', 'event');
            }
            echo '</div>';
        }

        thim_event_paging_nav($events_expired, 'expired');

        wp_reset_postdata();
        ?>
    </div>

</div>

<?php
/**
 * tp_event_after_event_loop hook
 *
 * @hooked tp_event_pagination - 10
 */
do_action('tp_event_after_event_loop');
?>

<?php
/**
 * tp_event_after_main_content hook
 *
 * @hooked tp_event_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('tp_event_after_main_content');
?>

<?php
/**
 * tp_event_sidebar hook
 *
 * @hooked tp_event_get_sidebar - 10
 */
do_action('tp_event_sidebar');
?>

