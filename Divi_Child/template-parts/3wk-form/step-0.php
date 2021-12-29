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

    <h2>APPLICATION CHECKLIST</h2>
    <p>Thank you for your interest in our 3-week college course! We look forward to receiving your application and have provided the following information to help streamline your application process. Please note, if you need to step away you may leave the online application at any time and resume your progress when you return.</p>
    <h3>Prepare Your Information</h3>
    <p>We recommend having the following information ready in order to make the application process as quick and easy as possible:</p>
    <strong>Information Requested During Registration:</strong>
    <ul>
      <li>Number of trials conducted as lead counsel</li>
      <li>Number of trials as lead counsel that reached a verdict</li>
      <li>Number of jury trials conducted as co-counsel in which you conducted a direct or cross examination</li>
      <li>Participation history in Administrative Hearings (if applicable)</li>
      <li>Please be prepared to describe the nature of the cases you handle</li>
      <li>Previous attendance of other Trial Lawyers College program, if any</li>
      <li>History of previous application to the 3-Week College, if any</li>
      <li>Previous criminal convictions you have received and their disposition.</li>
      <li>Contact information for three professional references. The Committees may contact your references.</li>
      <li>A typed 850 word essay, checked for spelling and grammar and ready to paste into the online application. Please note: you may elect to skip the essay portion at this time and return to the online application to complete it prior to the application deadline. Your application will be held as pending until you enter the essay. The essay asks: Tell us about yourself and why you want to attend the Trial Lawyers College 3-Week Course. For example, what life experiences have most shaped you? What is your greatest accomplishment; and what is your greatest loss? Please tell us about your dreams and your failures. We are not interested in the name of your law school, the clubs you belong to, your golf handicap, or your financial or social successes. We are interested in learning something about you, as a person. We want to know about your life goals as a trial lawyer, as well as what you hope to obtain from attending the Trial Lawyers College.</li>
    </ul>
    <h3>The Application Process</h3>
    <p>Once the deadline closes for the 3-Week College, all finalized applications received will be sent to the TLC Board Review Committee for their review and ranking. We typically receive 100+ applications for the 55 open seats at the 3-Week College. After ranking, all applications will be sent to the TLC Board Selection Committee for their final class selection. There is no late application, nor are there rolling admissions. Please be mindful of the application deadlines. For more information, visit our <a id="faq-link" href="/about-tlc/faq/">FAQ</a> page. We work to make admission decisions approximately two months prior to the start date of the College to which you apply.</p>
    <h3>Application Eligibility</h3>
    <strong>Applications are open to U.S. citizens and Permanent Residents (Green Card holders) who meet all of the following criteria:</strong>
    <ol>
      <li>The applicant is currently licensed to practice law in the United States;</li>
      <li>The applicant is a member in good standing of a State Bar; and</li>
      <li>The applicant and the applicant's firm do not currently represent insurance companies, large corporations or governmental entities other than as a legal aid provider or public defender</li>
      <li>Paralegals of qualifying attorneys are eligible to attend the Trial Skill Foundations course only.</li>
    </ol>
    <hr>
    <strong>Your application cannot be supplemented or edited after you click "Finish Application". Please be careful to include all information, including your final essay, before clicking "Finish Application"!</strong>
    <div class="register-step__submit-container">
      <a class="register-step__next-btn" href="<?php echo home_url("/course-registration?step=1&courseNumber=$course_sku"); ?>">Next</a>
    </div>

  <?php endif; ?>
</div>