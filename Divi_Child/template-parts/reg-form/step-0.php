<?php 
  $user_id = get_current_user_id();
  $course_sku = $args['course']->get_sku(); 
  $user_email = get_userdata( $user_id )->user_email;
  $product_id = wc_get_product_id_by_sku( $course_sku );
  $already_registered = user_already_registered_for_a_course( $user_id, $course_sku );
?>

<div class="register-step-content register-step-content--0 container">
  <?php if ( $already_registered ): ?>
    <div class="already-registered-notification">
      <b>Sorry, but it seems that you already registered for this course.</b>
    </div>
  <?php else: ?>

    <h2>Registration Checklist</h2>
    <h3>Prepare Your Information</h3>
    <p>We recommend having the following information ready in order to make the registration process as quick and easy as possible:</p>
    <strong>Information Requested During Registration:</strong>
    <ul>
      <li>Name, contact and demographic information</li>
      <li>Practice information (type of cases and clients)</li>
      <li>Payment information (if requesting financial aid, be prepared to state income range and explain financial need)</li>
    </ul>
    <h3>Registration Eligibility</h3>
    <strong>Registration is open to U.S. citizens and Permanent Residents (Green Card holders) who meet all of the following criteria:</strong>
    <ol>
      <li>The applicant is currently licensed to practice law in the United States;</li>
      <li>The applicant is a member in good standing of a State Bar; and</li>
      <li>The applicant and the applicant's firm do not currently represent insurance companies, large corporations or governmental entities other than as a legal aid provider or public defender</li>
      <li>Paralegals of qualifying attorneys are eligible to attend the Trial Skill Foundations course only.</li>
    </ol>
    <hr>
    <div class="register-step__submit-container">
      <?php $course_sku = $args['course']->get_sku(); ?>
      <a class="register-step__next-btn" href="<?php echo home_url("/course-registration?step=1&courseNumber=$course_sku"); ?>">Next</a>
    </div>
  
  <?php endif; ?>
</div>