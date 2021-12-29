<?php get_header(); ?>

<div class="container register-form-container">
  <h1>Create an account</h1>
  <form action="<?php echo esc_url( admin_url('admin-post.php') ); ?>" method="POST" class="register-form">
    <?php wp_nonce_field( 'register-subscriber', 'rs-nonce' ); ?>
    <input type="hidden" name="action" value="register-subscriber">

    <div class="register-form__row">
      <label for="username">User Name</label>
      <input type="text" name="username" id="username" placeholder="User Name">
    </div>

    <div class="register-form__row">
      <label for="firstname">First Name</label>
      <input type="text" name="firstname" id="firstname" placeholder="First Name">
    </div>

    <div class="register-form__row">
      <label for="lastname">Last Name</label>
      <input type="text" name="lastname" id="lastname" placeholder="Last Name">
    </div>

    <div class="register-form__row">
      <label for="email">Email Address</label>
      <input type="email" name="email" id="email" placeholder="Email Address">
    </div>

    <div class="register-form__row">
      <label for="phone">Phone</label>
      <input type="phone" name="phone" id="phone" placeholder="Phone">
    </div>

    <div class="register-form__row">
      <label for="password">Password</label>
      <input type="password" name="password" id="password" placeholder="Password">
    </div>

    <button>Submit</button>
  </form>
</div>

<?php get_footer(); ?>