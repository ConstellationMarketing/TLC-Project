<?php
  if ( !is_user_logged_in() ) {
    wp_redirect( wp_login_url() );
  }

  if ( !isset( $_GET['user_id'] ) ) {
    wp_redirect( home_url() );
  }

  $user = get_user_by( 'id', intval( $_GET['user_id'] ) );

  if ($user == false) {
    wp_redirect( home_url() );
  }

  $user_name = get_user_meta( $user->ID , 'first_name', true );
  $user_last_name = get_user_meta( $user->ID , 'last_name', true );
  $user_email = get_userdata( $user->ID )->user_email;

  $current_user = wp_get_current_user();
  $current_user_name = get_user_meta( $current_user->ID , 'first_name', true );
  $current_user_last_name = get_user_meta( $current_user->ID , 'last_name', true );
  $current_user_email = get_userdata( $current_user->ID )->user_email;


  if ( isset( $_POST['confirm'] ) ) {
    if ( !isset( $_POST['return_address'] ) || !isset( $_POST['subject'] ) || !isset( $_POST['message'] ) ) {
      $success = false;
    }

    if ( strlen( $_POST['return_address'] ) === 0 || strlen( $_POST['subject'] ) === 0 || strlen( $_POST['message'] ) === 0 ) {
      $success = false;
    }

    $return_address = sanitize_email( $_POST['return_address'] );
    $request_subject = stripslashes( $_POST['subject'] );
    $request_message = stripslashes( $_POST['message'] );

    ob_start();

    require __DIR__  . '/emails/contact-request-email.php';

    $message = ob_get_clean();

    wp_mail( $user_email, 'Trial Lawyers College: Someone wants to contact you', $message, ['content-type: text/html'] );

    $success = true;
  }
?>

<style>
  .contact-student-page {
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .contact-student-success {
    background-color: #dff0d8;
    color: #3c763d;
    padding: 15px;
    margin-bottom: 18px;
    width: 100%;
  }

  .contact-student-error {
    background-color: #ba2123;
    color: white;
    padding: 15px;
    margin-bottom: 18px;
    width: 100%;
  }

  .contact-student-form {
    padding: 20px 0;
  }

  .contact-student-form__to {
    margin-bottom: 20px;
  }

  .contact-student-form form > div {
    display: flex;
    flex-direction: column;
    margin-bottom: 10px;
  }

  .contact-student-form form .buttons-container {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    justify-content: space-between;
  }

  .contact-student-form form .cancel-link {
    font-size: 16px;
    display: inline-block;
    background: #ba2123;
    color: white;
    font-weight: bold;
    padding: 5px 20px;
    margin-bottom: 10px;
  }

  .contact-student-form form .send-button {
    font-size: 16px;
    display: inline-block;
    background: #52b152;
    color: white;
    font-weight: bold;
    padding: 5px 20px;
    border: none;
    margin-bottom: 10px;
    cursor: pointer;
  }
</style>

<?php get_header(); ?>
  <div style="min-height: 400px;" class="contact-student-page container">
    <?php if ( isset( $_POST['confirm'] ) ): ?>

      <?php if ( $success ): ?>
        <div class="contact-student-success">
          Your contact request has been sent
        </div>
      <?php else: ?>
        <div class="contact-student-error">
          Something went wrong
        </div>
      <?php endif; ?>

    <?php else: ?>

      <div class="contact-student-form">
        <div class="contact-student-form__to">
          <strong>To: <?php echo $user_name ?> <?php echo $user_last_name ?></strong>
        </div>

        <form action="" method="POST">
          <input type="hidden" name="confirm" value="true">
          <div>
            <label for="return_address">Return Address</label>
            <input required type="email" id="return_address" name="return_address" placeholder="Return Address">
          </div>
          <div>
            <label for="subject">Subject</label>
            <input required type="text" id="subject" name="subject" placeholder="Subject">
          </div>
          <div>
            <label for="subject">Message</label>
            <textarea required name="message" id="message" cols="60" rows="10"></textarea>
          </div>
          <div class="buttons-container">
            <a class="cancel-link" href="/alumni-resources/student-directory/">Cancel</a>
            <button class="send-button">Send Message</button>
          </div>
        </form>
      </div>

    <?php endif; ?>
  </div>
<?php get_footer(); ?>