<?php 
  $cart = WC()->cart;
  $checkout = WC()->checkout();
  $order_id = $checkout->create_order([]);
  $order = wc_get_order($order_id);
  update_post_meta($order_id, '_customer_user', get_current_user_id());
  $order->calculate_totals();
  $order->update_status('processing', 'order_note');
  $cart->empty_cart();
  handle_new_order( $order_id );
?>

<div class="register-step-content container" style="min-height: 400px;">
  <div style="text-align: center;">
    <h2>Thank you for applying!</h2>
    <h3>Someone from our team will contact you soon.</h3>
  </div>
</div>