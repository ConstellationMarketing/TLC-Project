<?php 
  if ( !is_user_logged_in() ) {
    wp_redirect( wp_login_url() );
  }

  if ( !isset( $_GET['courseNumber'] ) ) {
    wp_redirect(home_url('/'));
  }

  $course_sku = $_GET['courseNumber'];

  $product_id = wc_get_product_id_by_sku( $course_sku );

  if ($product_id === null) {
    wp_redirect(home_url('/'));
  }

  $course = wc_get_product($product_id);

  $step = isset( $_GET['step'] ) ? intval( $_GET['step'] ) : 0; 
?>


<?php get_header(); ?>

<header class="course-register__header">
  <div class="container">
    <h1 class="course-register__title">
      <?php echo $course->get_name(); ?>
    </h1>
  </div>
</header>

<?php 

if( has_term( 'reg', 'product_cat', $product_id ) ) {
  get_template_part('template-parts/reg-form/step', $step, [
    'course' => $course
  ]);
} else if( has_term( '3wk', 'product_cat', $product_id ) ) {
  get_template_part('template-parts/3wk-form/step', $step, [
    'course' => $course
  ]);
} else if( has_term( 'grad', 'product_cat', $product_id ) ) {
  get_template_part('template-parts/grad-form/step', $step, [
    'course' => $course
  ]);
}

?>

<?php  get_footer(); ?>