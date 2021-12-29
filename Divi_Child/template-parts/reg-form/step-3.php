<?php
  $user_id = get_current_user_id();
  $diet = get_field('ACF_diet', 'user_' . $user_id);
  $diet_explanation = get_field('ACF_diet_explanation', 'user_' . $user_id);
  $allergies = get_field('ACF_allergies', 'user_' . $user_id);
  $allergies_explanation = get_field('ACF_allergies_explanation', 'user_' . $user_id);
  $doctor_psychologist = get_field('ACF_doctor_psychologist', 'user_' . $user_id);
  $doctor_explanation = get_field('ACF_doctor_explanation', 'user_' . $user_id);
  $financial_aid = get_user_meta( $user_id, 'financial_aid', true);
?>

<div class="register-step-content container">
  <div class="register-step__progress-bar">
    <div class="register-step__current-progress"></div>
  </div>

    <h3>Dietary</h3>
    <p style="font-weight: bold">Because courses through the Trial Lawyers College include meals prepared at the course venue, we request food allergy and dietary preference information for the health and safety of all our students. You are required to monitor your own food intake, and to carry the appropriate remedy for your allergies. Your answers will be shared with the appropriate staff to enable them to make informed decisions about meal plans.</p>

    <form action="#" id="step-form">
      <input type="hidden" name="action" value="reg_dietary">

      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Do you follow a specific diet?</label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="diet" id="diet_yes" <?php echo $diet ? 'checked' : '' ?>>
          <label for="diet_yes">Yes</label>
          <input type="radio" value="no" name="diet" id="diet_no" <?php echo !$diet ? 'checked' : '' ?>>
          <label for="diet_no">No</label>
        </div>
      </div>
      <div class="register-step__info-row">
        <textarea name="diet_explanation" id="diet_explanation" cols="20" rows="3" placeholder="If yes, please explain."><?php echo isset($diet_explanation) ? $diet_explanation : ''; ?></textarea>
      </div>

      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Do you have any food allergies?</label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="allergies" id="allergies_yes" <?php echo $allergies ? 'checked' : '' ?>>
          <label for="allergies_yes">Yes</label>
          <input type="radio" value="no" name="allergies" id="allergies_no" <?php echo !$allergies ? 'checked' : '' ?>>
          <label for="allergies_no">No</label>
        </div>
      </div>
      <div class="register-step__info-row">
        <textarea name="allergies_explanation" id="allergies_explanation" cols="20" rows="3" placeholder="If yes, please explain."><?php echo isset($allergies_explanation) ? $allergies_explanation : ''; ?></textarea>
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
      
      <hr>
      <div class="register-step__submit-container">
        <?php $course_sku = $args['course']->get_sku(); ?>
        <a class="register-step__prev-btn" href="<?php echo home_url("/course-registration?step=2&courseNumber=$course_sku"); ?>">Previous</a>
        <?php if ( $financial_aid ): ?>
          <a class="register-step__next-btn" href="<?php echo home_url("/course-registration?step=4&courseNumber=$course_sku"); ?>">Next</a>
        <?php else: ?>
          <a class="register-step__next-btn" href="<?php echo home_url("/checkout"); ?>">Next</a>
        <?php endif; ?>
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

      if ($('input[name="diet"]:checked').val() === 'yes' && $('#diet_explanation').val().length === 0) {
        $('.register-step-content div.error').remove();
        $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
        $('#diet_explanation').addClass('error');
      }

      if ($('input[name="allergies"]:checked').val() === 'yes' && $('#allergies_explanation').val().length === 0) {
        $('.register-step-content div.error').remove();
        $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
        $('#allergies_explanation').addClass('error');
      }

      if ($('input[name="doctor_psychologist"]:checked').val() === 'yes' && $('#doctor_explanation').val().length === 0) {
        $('.register-step-content div.error').remove();
        $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
        $('#doctor_explanation').addClass('error');
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