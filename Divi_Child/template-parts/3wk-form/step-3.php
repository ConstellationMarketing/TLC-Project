<?php
  $user_id = get_current_user_id();
  $diet = get_field('ACF_diet', 'user_' . $user_id);
  $diet_explanation = get_field('ACF_diet_explanation', 'user_' . $user_id);
  $allergies = get_field('ACF_allergies', 'user_' . $user_id);
  $allergies_explanation = get_field('ACF_allergies_explanation', 'user_' . $user_id);
  $doctor_psychologist = get_field('ACF_doctor_psychologist', 'user_' . $user_id);
  $doctor_explanation = get_field('ACF_doctor_explanation', 'user_' . $user_id);
?>

<div class="register-step-content container">
  <div class="register-step__progress-bar">
    <div class="register-step__current-progress"></div>
  </div>

    <form action="#" id="step-form">
      <input type="hidden" name="action" value="3wk_final">
      <input type="hidden" name="order_id" value="">
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

      <h3>Psychodrama</h3>
      <p style="font-weight: bold">A part of TLC's curriculum includes psychodrama. (Psychodrama, as used by TLC, is a method led by certified psychodramatists in which students use spontaneous action to re-enact stories of their own lives so they can better understand their clients' stories and more effectively represent them.) A student's participation is strictly voluntary and they may withdraw at any time and it will not jeopardize their continuation at TLC. Rarely do people respond adversely to psychodrama. If accepted to a TLC program, every student is advised to make an inquiry into their mental health provider as to their ability to participate in psychodrama at the Trial Lawyers College.</p>
      <p style="font-weight: bold">In order for us to be able to evaluate an applicant's fitness for this portion of our training, we need an honest answer to the following question:</p>

      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Have you ever been treated, or are you currently being treated, by a doctor or psychologist or other treating persons for any physical, mental or psychological issues or concerns that may be affected by participating in psychodrama? If so, please explain below.</label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="doctor_psychologist" id="doctor_psychologist_yes" <?php echo $doctor_psychologist ? 'checked' : '' ?>>
          <label for="doctor_psychologist_yes">Yes</label>
          <input type="radio" value="no" name="doctor_psychologist" id="doctor_psychologist_no" <?php echo !$doctor_psychologist ? 'checked' : '' ?>>
          <label for="doctor_psychologist_no">No</label>
        </div>
      </div>
      <div class="register-step__info-row">
        <textarea name="doctor_explanation" id="doctor_explanation" cols="20" rows="3" placeholder="If yes, please explain."><?php echo isset($doctor_explanation) ? $doctor_explanation : ''; ?></textarea>
      </div>

      <h3>Pledge</h3>
      <p>You have almost completed your application!</p>
      <p>All applications to the 3-week Trial Lawyers College are reviewed and selected, based not only on your application's content, but also to ensure a diverse and well-rounded class including geographic diversity, a good mix of criminal defense and civil lawyers, gender, race and religious diversity, and other factors that we want to prioritize for every College class. Admission determinations will be sent by email, about 6-8 weeks after the application deadline.</p>
      <div class="register-step__form-row" style="flex-wrap: nowrap">
        <input type="checkbox" name="pledge">
        <div>
          <p> I pledge that all statements and information furnished in this registration are true, complete and correct to the best of my knowledge and belief, and are made in good faith. I understand the statements or information furnished on this form are subject to verification and I agree to furnish supporting documents or information when so requested and/or names, addresses and phone numbers (if known) of officials or other individuals who can substantiate the qualifications described above. I also understand that intentional misstatements or falsification may result in voiding my account and any associated registrations.</p>
        </div>
      </div>

      <h3>Terms and Conditions</h3>
      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Your acceptance to the Trial Lawyers College 3-week program will follow a rigorous competitive application process. Before you submit your application, we want to confirm that if accepted, you are prepared to clear your personal and work calendars for the entire time that you will be away.</label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="terms" id="terms_yes">
          <label for="terms_yes">Yes</label>
          <input type="radio" value="no" name="terms" id="terms_no" checked>
          <label for="terms_no">No</label>
        </div>
      </div>
      
      <hr>
      <strong>Your application cannot be supplemented or edited after you click "Finish Application". Please be careful to include all information, including your final essay, before clicking "Finish Application"!</strong>
      <div class="register-step__submit-container">
        <?php $course_sku = $args['course']->get_sku(); ?>
        <a class="register-step__prev-btn" href="<?php echo home_url("/course-registration?step=2&courseNumber=$course_sku"); ?>">Previous</a>
        <a class="register-step__next-btn" href="<?php echo home_url("/course-registration?step=4&courseNumber=$course_sku"); ?>">Finish Application</a>
      </div>
    </form>
</div>

<script>
  jQuery(document).ready(function ($) {
    $('input[name="order_id"]').val(window.localStorage.getItem('order_id'));
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

      if ($('input[name="doctor_psychologist"]:checked').val() === 'yes' && $('#doctor_explanation').val().length === 0) {
        $('.register-step-content div.error').remove();
        $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
        $('#doctor_explanation').addClass('error');
      }

      if ($('input[name="terms"]:checked').val() === 'no' || !$('input[name="pledge"]').prop('checked')) {
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