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

    <h2>GRAD COURSE APPLICATION CHECKLIST</h2>
    <p>Dear TLC Warrior: Due to the high demand for our graduate programs, the impact of cancellations by those admitted, and our desire to ensure that each TLC program has representation with regards to gender, race and type of practice, we have opted to switch to an application process, rather than a registration process for our graduate programs. During the acceptance process, we will strive to be fair and transparent, giving all alums an opportunity in the coming years to fully participate in our graduate programs. There will be no payment required at the time of submitting the application, but if you are accepted, you will be required to pay a $500.00 non-refundable deposit unless other scholarship arrangements have been made with Lori Schmidt. Thank you for your patience as we strive to meet everyone's desires and needs, and for your ongoing loyalty and commitment to your training with the Trial Lawyers College.</p>

    <h3>Prepare Your Information</h3>
    <strong>In addition to the basic contact information, we suggest you have the following information ready in order to ease the application process:</strong>
    <ul>
      <li>Describe the nature of the cases you handle;</li>
      <li>List the previous year(s) of attendance at other Trial Lawyers College Graduate Course(s), if any.</li>
    </ul>

    <h3>Application Eligibility</h3>
    <strong>Applications are open to U.S. citizens and Permanent Residents (Green Card holders) who meet all of the following criteria:</strong>
    <ol>
      <li>The applicant is a graduate of the Trial Lawyers College, or attendance at this grad course will complete one's 7-Step Program;</li>
      <li>The applicant is currently licensed to practice law in the United States;</li>
      <li>The applicant is a member in good standing of a State Bar; and</li>
      <li>The applicant and the applicant's firm do not currently represent insurance companies, large corporations or governmental entities other than as a legal aid provider or public defender.</li>
    </ol>

    <hr>
    
    <div class="register-step__submit-container">
      <?php $course_sku = $args['course']->get_sku(); ?>
      <a class="register-step__next-btn" href="<?php echo home_url("/course-registration?step=1&courseNumber=$course_sku"); ?>">Next</a>
    </div>
  
  <?php endif; ?>
</div>