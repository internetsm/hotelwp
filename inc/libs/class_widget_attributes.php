<?php

class Thim_Widget_Attributes {
    const VERSION = '0.2.2';
    /**
     * Initialize plugin
     */
    public static function thim_setup() {
        if ( is_admin() ) {
            // Add necessary input on widget configuration form
            add_action( 'in_widget_form', array( __CLASS__, 'thim_input_fields' ), 10, 3 );
            // Save widget attributes
            add_filter( 'widget_update_callback', array( __CLASS__, 'thim_save_attributes' ), 10, 4 );
        } else {
            // Insert attributes into widget markup
            add_filter( 'dynamic_sidebar_params', array( __CLASS__, 'thim_insert_attributes' ) );
        }
    }

    /**
     * Inject input fields into widget configuration form
     *
     * @since   0.1
     * @wp_hook action in_widget_form
     *
     * @param object $widget Widget object
     *
     * @return NULL
     */
    public static function thim_input_fields( $widget, $return, $instance ) {
        $instance = self::thim_get_attributes( $instance );
        ?>
        <p>
            <?php printf(
                '<label for="%s">%s</label>',
                esc_attr( $widget->get_field_id( 'widget-class' ) ),
                esc_html__( 'Extra Class', 'hotel-wp' )
            ) ?>
            <?php
            printf(
                '<input type="text" class="widefat" id="%s" name="%s" value="%s" />',
                esc_attr( $widget->get_field_id( 'widget-class' ) ),
                esc_attr( $widget->get_field_name( 'widget-class' ) ),
                esc_attr( $instance['widget-class'] )
            );
            ?>
        </p>
        <?php
        return null;
    }


    /**
     * Get default attributes
     *
     * @since 0.1
     *
     * @param array $instance Widget instance configuration
     *
     * @return array
     */
    private static function thim_get_attributes( $instance ) {
        $instance = wp_parse_args(
            $instance,
            array(
                'widget-class' => '',
            )
        );

        return $instance;
    }


    /**
     * Save attributes upon widget saving
     *
     * @since   0.1
     * @wp_hook filter widget_update_callback
     *
     * @param array  $instance     Current widget instance configuration
     * @param array  $new_instance New widget instance configuration
     * @param array  $old_instance Old Widget instance configuration
     * @param object $widget       Widget object
     *
     * @return array
     */
    public static function thim_save_attributes( $instance, $new_instance, $old_instance, $widget ) {
        $instance['widget-class'] = '';

        // Classes
        if ( !empty( $new_instance['widget-class'] ) ) {
            $instance['widget-class'] = apply_filters(
                'widget_attribute_classes',
                implode(
                    ' ',
                    array_map(
                        'sanitize_html_class',
                        explode( ' ', $new_instance['widget-class'] )
                    )
                )
            );
        } else {
            $instance['widget-class'] = '';
        }

        return $instance;
    }


    /**
     * Insert attributes into widget markup
     *
     * @since  0.1
     * @filter dynamic_sidebar_params
     *
     * @param array $params Widget parameters
     *
     * @return Array
     */
    public static function thim_insert_attributes( $params ) {
        global $wp_registered_widgets;

        $widget_id  = $params[0]['widget_id'];
        $widget_obj = $wp_registered_widgets[$widget_id];

        if (
            !isset( $widget_obj['callback'][0] )
            || !is_object( $widget_obj['callback'][0] )
        ) {
            return $params;
        }

        $widget_options = get_option( $widget_obj['callback'][0]->option_name );
        if ( empty( $widget_options ) ) {
            return $params;
        }

        $widget_num = $widget_obj['params'][0]['number'];
        if ( empty( $widget_options[$widget_num] ) ) {
            return $params;
        }

        $instance = $widget_options[$widget_num];

        // Classes
        if ( !empty( $instance['widget-class'] ) ) {
            $params[0]['before_widget'] = preg_replace(
                '/class="/',
                sprintf( 'class="%s ', $instance['widget-class'] ),
                $params[0]['before_widget'],
                1
            );
        }

        return $params;
    }
}
add_action( 'widgets_init', array( 'Thim_Widget_Attributes', 'thim_setup' ) );