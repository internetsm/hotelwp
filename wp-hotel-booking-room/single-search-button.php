<?php
/**
 * @Author: Albert
 * @Date:   2017-10-13 10:08:34
 * @Last Modified by:   albert
 * @Last Modified time: 2017-10-13 10:08:34
 */

if ( !defined( 'ABSPATH' ) ) {
	exit();
}

global $hb_room;
if ( !is_singular( 'hb_room' ) ) {
	return;
}
if ( class_exists( 'WP_Hotel_Booking_Room' ) ) {
	?>
	<a href="#" data-id="<?php echo esc_attr( $hb_room->ID ) ?>" data-name="<?php echo esc_attr( $hb_room->name ) ?>" class="hb_button hb_primary" id="hb_room_load_booking_form"><?php esc_html_e( 'Book Now', 'hotel-wp' ); ?></a>
<?php } ?>