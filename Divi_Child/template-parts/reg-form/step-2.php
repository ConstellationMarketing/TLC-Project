<?php
  $course_sku = $args['course']->get_sku();
  $course_id = wc_get_product_id_by_sku($course_sku);
  $course = wc_get_product($course_id);

  if ( $course->is_type('variable-subscription') ) {
    $available_variations = $course->get_available_variations();
  } else {
    $available_variations = null;
  }
?>

<div class="register-step-content container">
  <div class="register-step__progress-bar">
    <div class="register-step__current-progress"></div>
  </div>

    <h3>Payment</h3>
    <h4>Tution Payment Options</h4>

    <form action="#" id="step-form">
      <input type="hidden" name="action" value="reg_payment_methods">
      <input type="hidden" name="product_id" value="<?php echo $course_id; ?>">

      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="tuition_plan">Select a Tuition Payment Plan</label>
          <?php if ( $available_variations !== null ): ?>
            <?php 
              $plans = $available_variations;
            ?>
            <select name="tuition_plan" id="tuition_plan">
              <?php foreach ($plans as $plan): ?>
                <option value="<?php echo $plan['variation_id'] ; ?>"><?php echo $plan['attributes']['attribute_payment'] ?></option>
              <?php endforeach; ?>
            </select>
          <?php else: ?>
            <select name="tuition_plan" id="tuition_plan">
              <option value="0">Full</option>
            </select>
          <?php endif; ?>
        </div>
      </div>

      <h4>Financial Aid</h4>

      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Are you interesting in applying for financial aid for this course?</label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="financial_aid" id="financial_aid_yes">
          <label for="financial_aid_yes">Yes</label>
          <input type="radio" value="no" name="financial_aid" id="financial_aid_no" checked>
          <label for="financial_aid_no">No</label>
        </div>
      </div>

      <div class="register-step__info-row">
        <textarea name="why_aid" id="why_aid" cols="20" rows="3" placeholder="If yes, please explain your financial need?"></textarea>
      </div>

      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="income_range">Income Range?</label>
          <select name="income_range" id="income_range">
            <option value="">Select an Income Range</option>
            <option value="Less than $20,000">Less than $20,000</option>
            <option value="$20,000 to $34,999">$20,000 to $34,999</option>
            <option value="$35,000 to $49,999">$35,000 to $49,999</option>
            <option value="$50,000 to $74,999">$50,000 to $74,999</option>
            <option value="$75,000 to $99,999">$75,000 to $99,999</option>
            <option value="$100,000 to $149,999">$100,000 to $149,999</option>
            <option value="$150,000 to $199,999">$150,000 to $199,999</option>
            <option value="$200,000 or more">$200,000 or more</option>
          </select>
        </div>
      </div>
      
      <?php if ( !user_is_warrior_subscriber( get_current_user_id() ) ): ?>

      <h3>The Warrior Magazine</h3>
      
      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Would you like to subscribe to the quarterly TLC's Warrior Magazine for $40.00/year? For more information about our magazine please click <a href="/alumni-resources/warrior-magazine-subscription/">HERE</a></label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="warrior_subscribe" id="warrior_subscribe_yes">
          <label for="warrior_subscribe_yes">Yes</label>
          <input type="radio" value="no" name="warrior_subscribe" id="warrior_subscribe_no" checked>
          <label for="warrior_subscribe_no">No</label>
        </div>
      </div>

      <?php endif; ?>

      <h3>Terms and Conditions</h3>
      <div class="register-step__form-row" style="flex-wrap: nowrap">
        <input type="checkbox" name="terms">
        <div>
          <p>The entire balance for a TLC course is due 90 days after the start date of the course, unless other arrangements have been made with the Registrar or Financial Officer. Any registrations made within 5 days of the start of the course must be paid in full at the time of registration.</p>
          <p>Tuition payments are only transferable to another TLC course within the same calendar year or to a substitute participant for the same course.</p>
          <p>Cancellations must be e-mailed to TLC’s Registrar, Registrar@triallawyerscollege.org</p>
          <p>Cancellations received at least 14 calendar days prior to the course will receive a full refund. Cancellations received 13 – 6 calendar days prior to the course will receive a 50% refund. We regret that no refunds can be made 5 days or less prior to the start date of the course.</p>
          <p><strong>Graduate I and Graduate II Course Cancellation Policy:</strong> All students must remit a non-refundable $500 deposit at the time their application to a Grad Program is accepted by the Faculty Leaders, (not to be confused with the date of submitting one's application online.)</p>
        </div>
      </div>
      <div class="register-step__form-row" style="flex-wrap: nowrap">
        <input type="checkbox" name="pledge">
        <div>
          <p>I pledge that all statements and information furnished in this registration are true, complete and correct to the best of my knowledge and belief, and are made in good faith. I understand the statements or information furnished on this form are subject to verification and I agree to furnish supporting documents or information when so requested and/or names, addresses and phone numbers (if known) of officials or other individuals who can substantiate the qualifications described above. I also understand that intentional misstatements or falsification may result in voiding my account and any associated registrations.</p>
        </div>
      </div>
      
      <hr>
      <div class="register-step__submit-container">
        <?php $course_sku = $args['course']->get_sku(); ?>
        <a class="register-step__prev-btn" href="<?php echo home_url("/course-registration?step=1&courseNumber=$course_sku"); ?>">Previous</a>
        <a class="register-step__next-btn" href="<?php echo home_url("/course-registration?step=3&courseNumber=$course_sku"); ?>">Next</a>
      </div>
    </form>
</div>

<script>
  jQuery(document).ready(function ($) {
    $('.register-step__next-btn').on('click', function (e) {
      e.preventDefault();
      let href = $(this).attr('href');

      $('.register-step__input-container input.error, .register-step__input-container select.error, .register-step__info-row textarea.error').removeClass('error');
      $('.register-step-content div.error').remove();

      if ($('input[name="financial_aid"]:checked').val() === 'yes' && $('#why_aid').val().length === 0) {
        $('.register-step-content div.error').remove();
        $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
        $('#why_aid').addClass('error');
      }

      if ($('input[name="financial_aid"]:checked').val() === 'yes' && $('#income_range').val().length === 0) {
        $('.register-step-content div.error').remove();
        $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
        $('#income_range').addClass('error');
      }

      if (!$('input[name="terms"]').prop('checked') || !$('input[name="pledge"]').prop('checked')) {
        $('<div class="error">You must check both checkboxes above and pledge that all statements are true before submitting your registration..</div>').insertBefore($('#step-form hr'));
      }

      if ($('.register-step-content .error').length) {
        return;
      }

      let formData = $('#step-form').serialize();

      $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: formData,
        success: function( data ) {
          console.log(data)
          if (data.success) {
            window.location.replace(href);
          }
        }
		  });
    });
  });
</script>