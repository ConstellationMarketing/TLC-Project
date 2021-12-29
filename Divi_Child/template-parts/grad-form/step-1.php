<?php
  $user_id = get_current_user_id();
  $user = get_user_by('id', $user_id);

  $firstname = get_userdata($user_id)->first_name;
  $lastname = get_userdata($user_id)->last_name;
  $email = get_userdata($user_id)->user_email;
  $phone = get_user_meta($user_id, 'billing_phone', true);
  $birthdate = get_field('ACF_date_of_birth', 'user_' . $user_id);
  $gender = get_field('ACF_gender', 'user_' . $user_id);
  $ethnicity = get_field('ACF_ethnicity', 'user_' . $user_id);

  $businessname = get_user_meta($user_id, 'billing_company', true);
  $businesswebsite = get_field('ACF_businesswebsite', 'user_' . $user_id);
  $practice_type = get_field('ACF_practice_type', 'user_' . $user_id);
  $billing_address = get_user_meta($user_id, 'billing_address_1', true);
  $billing_city = get_user_meta($user_id, 'billing_city', true);
  $billing_state = get_user_meta($user_id, 'billing_state', true);
  $billing_postcode = get_user_meta($user_id, 'billing_postcode', true);

  $licensed = get_field('ACF_licensed', 'user_' . $user_id);
  $why_interested = get_field('ACF_why_interested', 'user_' . $user_id);
  $represent_clients = get_field('ACF_represent_clients', 'user_' . $user_id);
  $represent_explanation = get_field('ACF_represent_explanation', 'user_' . $user_id);
  $crime = get_field('ACF_crime', 'user_' . $user_id);
  $crime_explanation = get_field('ACF_crime_explanation', 'user_' . $user_id);
  $corporations = get_field('ACF_corporations', 'user_' . $user_id);
?>

<div class="register-step-content container">
  <div class="register-step__progress-bar">
    <div class="register-step__current-progress"></div>
  </div>

    <h3>Basic Info</h3>

    <form action="#" id="step-form">
      <input type="hidden" name="action" value="grad_user_info">
      <input type="hidden" name="product_sku" value="<?php echo $args['course']->get_sku(); ?>">

      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="firstname">First Name</label>
          <input class="required" type="text" id="firstname" name="firstname" value="<?php echo $firstname; ?>">
        </div>
        <div class="register-step__input-container">
          <label for="lastname">Last Name</label>
          <input class="required" type="text" id="lastname" name="lastname" value="<?php echo $lastname; ?>">
        </div>
      </div>

      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="email">Email</label>
          <input class="required" type="text" id="email" name="email" value="<?php echo $email; ?>">
        </div>
      </div>

      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="phone">Mobile Phone</label>
          <input class="required" type="text" id="phone" name="phone" value="<?php echo $phone; ?>">
        </div>
        <div class="register-step__input-container">
          <label for="birthdate">Date of Birth</label>
          <input class="required" type="text" id="birthdate" name="birthdate" value="<?php echo $birthdate; ?>">
        </div>
      </div>

      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="gender">Gender</label>
          <?php 
            $genders = get_field_object( 'field_6008636d314ca' )['choices'];
          ?>
          <select class="required" name="gender" id="gender">
            <?php foreach ($genders as $key => $value): ?>
              <option value="<?php echo $value; ?>" <?php echo $gender === $value ? 'selected' : '' ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="register-step__input-container">
          <label for="ethnicity">Ethnicity</label>
          <?php 
            $ethnicities = get_field_object( 'field_600863b0314cb' )['choices'];
          ?>
          <select class="required" name="ethnicity" id="ethnicity">
            <?php foreach ($ethnicities as $key => $value): ?>
              <option value="<?php echo $value; ?>" <?php echo $ethnicity === $value ? 'selected' : '' ?>><?php echo $value; ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
      
      <h3>Business Info</h3>

      <div class="register-step__form-row">
        <div class="register-step__input-container cols-3">
          <label for="businessname">Firm or Business Name</label>
          <input type="text" id="businessname" name="businessname" value="<?php echo $businessname; ?>">
        </div>
        <div class="register-step__input-container cols-3">
          <label for="businesswebsite">Firm or Business Website</label>
          <input type="text" id="businesswebsite" name="businesswebsite" value="<?php echo $businesswebsite; ?>">
        </div>
        <div class="register-step__input-container cols-3">
          <label for="practice_type">Practice Type</label>
          <input class="required" type="text" id="practice_type" name="practice_type" value="<?php echo $practice_type; ?>">
        </div>
      </div>

      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="billing_address">Address</label>
          <input class="required" type="text" id="billing_address" name="billing_address" value="<?php echo $billing_address; ?>">
        </div>
        <div class="register-step__input-container">
          <label for="billing_city">City</label>
          <input class="required" type="text" id="billing_city" name="billing_city" value="<?php echo $billing_city; ?>">
        </div>
      </div>

      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="billing_state">State</label>
          <input class="required" type="text" id="billing_state" name="billing_state" value="<?php echo $billing_state; ?>">
        </div>
        <div class="register-step__input-container">
          <label for="billing_postcode">Zip</label>
          <input class="required" type="text" id="billing_postcode" name="billing_postcode" value="<?php echo $billing_postcode; ?>">
        </div>
      </div>

      <h3>Practice Info</h3>
      
      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Are you currently licensed to practice law in a U.S. state and a member in good standing of a state bar?</label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="licensed" id="licensed_yes" <?php echo $licensed ? 'checked' : '' ?>>
          <label for="licensed_yes">Yes</label>
          <input type="radio" value="no" name="licensed" id="licensed_no" <?php echo !$licensed ? 'checked' : '' ?>>
          <label for="licensed_no">No</label>
        </div>
      </div>
      <div class="register-step__info-row">
        <textarea name="why_interested" id="why_interested" cols="20" rows="3" placeholder="If not, please explain why are interested in participating at TLC."><?php echo isset($why_interested) ? $why_interested : ''; ?></textarea>
      </div>

      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Do you currently represent clients?</label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="represent_clients" id="represents_yes" <?php echo $represent_clients ? 'checked' : ''; ?>>
          <label for="represents_yes">Yes</label>
          <input type="radio" value="no" name="represent_clients" id="represents_no" <?php echo !$represent_clients ? 'checked' : ''; ?>>
          <label for="represents_no">No</label>
        </div>
      </div>
      <div class="register-step__info-row">
        <textarea name="represent_explanation" id="represent_explanation" cols="20" rows="3" placeholder="If not, what do you hope to accomplish as a student at TLC?"><?php echo isset($represent_explanation) ? $represent_explanation : ''; ?></textarea>
      </div>

      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Have you ever been convicted of a crime?</label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="crime" id="convicted_yes" <?php echo $crime ? 'checked' : ''; ?>>
          <label for="convicted_yes">Yes</label>
          <input type="radio" value="no" name="crime" id="convicted_no" <?php echo !$crime ? 'checked' : ''; ?>>
          <label for="convicted_no">No</label>
        </div>
      </div>
      <div class="register-step__info-row">
        <textarea name="crime_explanation" id="crime_explanation" cols="20" rows="3" placeholder="If yes, please explain."><?php echo isset($crime_explanation) ? $crime_explanation : ''; ?></textarea>
      </div>

      <div class="register-step__info-row register-step__info-row--yesno">
        <label>Do you or your firm currently represent insurance companies, large corporations or governmental entities other than legal services or public defenders?</label>
        <div class="register-step__yesno">
          <input type="radio" value="yes" name="corporations" id="other_companies_yes" <?php echo $corporations ? 'checked' : ''; ?>>
          <label for="other_companies_yes">Yes</label>
          <input type="radio" value="no" name="corporations" id="other_companies_no" <?php echo !$corporations ? 'checked' : ''; ?>>
          <label for="other_companies_no">No</label>
        </div>
      </div>

      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="begin_year">In what year did you begin practicing law?</label>
          <textarea class="required" name="begin_year" id="begin_year" cols="20" rows="3" placeholder="Enter the year you began practicing law"></textarea>
        </div>
      </div>
      
      <div class="register-step__form-row">
        <div class="register-step__input-container">
          <label for="recent_graduate_course">Have you attended a TLC Graduate Course in previous years? If so, please state below your year(s) of attendance at Grad 1 or Grad 2 programs.</label>
          <textarea name="recent_graduate_course" id="recent_graduate_course" cols="20" rows="3" placeholder="Detailed information about the recently attended TLC courses."></textarea>
        </div>
      </div>
      
      <hr>
      <div class="register-step__submit-container">
        <?php $course_sku = $args['course']->get_sku(); ?>
        <a class="register-step__prev-btn" href="<?php echo home_url("/course-registration?step=0&courseNumber=$course_sku"); ?>">Previous</a>
        <a class="register-step__next-btn" href="<?php echo home_url("/course-registration?step=2&courseNumber=$course_sku"); ?>">Next</a>
      </div>
    </form>
</div>

<script>
  jQuery(document).ready(function ($) {
    $('.register-step__next-btn').on('click', function (e) {
      e.preventDefault();
      let href = $(this).attr('href');

      let formData = $('#step-form').serialize();

      $('.register-step__input-container input.error, .register-step__input-container select.error, .register-step__info-row textarea.error, textarea.error').removeClass('error');
      $('.register-step-content div.error').remove();

      $('input.required, textarea.required, select.required').each(function () {
        if (!$(this).val()) {
          $('.register-step-content div.error').remove();
          $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
          $(this).addClass('error');
        }
      })

      if ($('input[name="licensed"]:checked').val() === 'no' && $('#why_interested').val().length === 0) {
        $('.register-step-content div.error').remove();
        $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
        $('#why_interested').addClass('error');
      }

      if ($('input[name="represent_clients"]:checked').val() === 'no' && $('#represent_explanation').val().length === 0) {
        $('.register-step-content div.error').remove();
        $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
        $('#represent_explanation').addClass('error');
      }

      if ($('input[name="crime"]:checked').val() === 'yes' && $('#crime_explanation').val().length === 0) {
        $('.register-step-content div.error').remove();
        $('<div class="error">Some of the required fields are empty.</div>').insertBefore($('#step-form hr'));
        $('#crime_explanation').addClass('error');
      }

      if ($('.register-step-content .error').length) {
        return;
      }

      $.ajax({
        url: '<?php echo admin_url('admin-ajax.php'); ?>',
        type: 'POST',
        data: formData,
        success: function( data ) {
          console.log(data);
          if (data.success) {
            window.localStorage.setItem('order_id', data.order_id);
            window.location.replace(href);
          }
        }
		  });
    });
  });
</script>