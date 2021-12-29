<div class="register-step-content container">

  <p>Applicants to the 3-Week program of the Trial Lawyers College are encouraged to read the <a id="faq-link" href="/about-tlc/faq/">FAQ</a> page before proceeding in the application form. <strng>Applicants are strongly encouraged to type their essay offline</strong>, so that in case of internet/technical issues they will have a copy for future reference.</p>
  <p>Given time limitations, we recognize that you may not be ready to author and complete this important essay at this time. The website will allow you to log-on later and complete this essay or edit any other page, without having to create another application. However, you <strong>MUST COMPLETE THIS ESSAY ONLINE AND SUBMIT YOUR FINAL APPLICATION BY THE DEADLINE.</strong> <u>If you do not submit your completed application by the deadline, your application will not be considered for admission.</u></p>
  <p>Your application cannot be supplemented or edited after you click "finish application" on the final step of the application process, so please be careful to include all information before clicking to finish!</p>
  <p>Thank you for your interest in applying to the College, and if you have any questions, please don't hesitate to call our office at 307-432-4042.</p>

  <hr>

  <p>In 850 words or less, please tell us about yourself and why you want to attend the Trial Lawyers College 3-Week Course.</p>
  <p>For example, what life experiences have most shaped you? What is your greatest accomplishment; and what is your greatest loss? Please tell us about your dreams and your failures. We are not interested in the name of your law school, the clubs you belong to, your golf handicap, or your financial or social successes. We are interested in learning something about you, as a person. We want to know about your life goals as a trial lawyer, as well as what you hope to obtain from attending the Trial Lawyers College.</p>

  <form action="#" id="step-form">
    <input type="hidden" name="action" value="3wk_essay">
    <input type="hidden" name="order_id" value="">

    <textarea class="required" name="essay" id="essay" cols="20" rows="10" maxlength="6000" placeholder="Your essay!"></textarea>

  </form>
  <hr>

  <strong>Your application cannot be supplemented or edited after you click "Finish Application". Please be careful to include all information, including your final essay, before clicking "Finish Application"!</strong>
  <div class="register-step__submit-container">
    <?php $course_sku = $args['course']->get_sku(); ?>
    <a class="register-step__prev-btn" href="<?php echo home_url("/course-registration?step=1&courseNumber=$course_sku"); ?>">Previous</a>
    <a class="register-step__next-btn" href="<?php echo home_url("/course-registration?step=3&courseNumber=$course_sku"); ?>">Next</a>
  </div>

</div>

<script>
  jQuery(document).ready(function ($) {
    $('input[name="order_id"]').val(window.localStorage.getItem('order_id'));

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
            window.location.replace(href);
          }
        }
		  });
    });
  });
</script>