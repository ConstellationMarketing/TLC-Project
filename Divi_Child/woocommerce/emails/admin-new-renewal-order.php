<?php
/**
 * Admin new renewal order email
 *
 * @author  Brent Shepherd
 * @package WooCommerce_Subscriptions/Templates/Emails
 * @version 2.6.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

do_action( 'woocommerce_email_header', $email_heading, $email ); ?>

<?php
$user_id = $order->get_user_id();
$userdata = get_userdata( $user_id );
$first_name = $userdata->user_firstname;
$last_name = $userdata->user_lastname;
$user_email = $userdata->user_email;

$formatted_user_name = "$first_name $last_name ($user_email)";
?>
<p><?php printf( esc_html_x( 'You have received a subscription renewal order from %1$s. Their order is as follows:', 'Used in admin email: new renewal order', 'woocommerce-subscriptions' ), $formatted_user_name );?></p>

<?php
/**
 * @hooked WC_Subscriptions_Email::order_details() Shows the order details table.
 * @since 2.1.0
 */
do_action( 'woocommerce_subscriptions_email_order_details', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_order_meta', $order, $sent_to_admin, $plain_text, $email );

do_action( 'woocommerce_email_customer_details', $order, $sent_to_admin, $plain_text, $email );

/**
 * Show user-defined additional content - this is set in each email's settings.
 */
if ( $additional_content ) {
	echo wp_kses_post( wpautop( wptexturize( $additional_content ) ) );
}

do_action( 'woocommerce_email_footer', $email );
