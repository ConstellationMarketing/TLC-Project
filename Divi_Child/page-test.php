<?php get_header(); ?>>

<?php 
  // $users_json = file_get_contents( get_stylesheet_directory_uri() . '/members.json' );
  // $users = json_decode($users_json, JSON_FORCE_OBJECT);
  // $issues = [];
  
  // foreach ($users as $user) {
  //   $username = $user['User Name'];
  //   $email = $user['Email'];

  //   $user_already_exists = false;
  //   $user_by_email = get_user_by( 'email', $email );
  //   $user_by_login = get_user_by( 'login', $username );

  //   if ($user_by_email) {
  //     $user_already_exists = $user_by_email;
  //   } else if ($user_by_login) {
  //     $user_already_exists = $user_by_login;
  //   }

  //   $first_name = $user['First Name'];
  //   $last_name = $user['Last Name'];
  //   $birthdate = $user['Date Of Birth'];
  //   $gender = $user['Gender'];
  //   $ethinicity = $user['Ethnicity'];
  //   $business_name = $user['Business Name'];
  //   $business_website = $user['Business Website'];
  //   $address = $user['Address'];
  //   $city = $user['City'];
  //   $state = $user['State'];
  //   $zip = $user['Zip'];
  //   $mobile_phone = $user['Mobile Phone'];
  //   $work_phone = $user['Work Phone'];
  //   $practice_type = $user['Practice Type'];
  //   $region = $user['Region'];
  //   $grad_month = $user['Grad Month'];
  //   $grad_year = $user['Grad Year'];
  //   $is_3wk = $user['Is 3Wk Grad'] === 'True' ? true : false;
  //   $us_7step = $user['Is 7 Stepper'] === 'True' ? true : false;
  //   $is_student = $user['Is Student'] === 'True' ? true : false;
  //   $is_ranch_club = $user['Is Ranch Club Member'] === 'True' ? true : false;
  //   $is_warrior = $user['Is Warrior Subscriber'] === 'True' ? true : false;
  //   $is_faculty = $user['Is Faculty'] === 'True' ? true : false;
  //   $is_registered = true;

  //   $user_groups = [];

  //   if ($is_3wk) {
  //     $user_groups[] = '3week';
  //   }

  //   if ($is_7step) {
  //     $user_groups[] = '7step';
  //   }

  //   if ($is_ranch_club) {
  //     $user_groups[] = 'ranch_club';
  //   }

  //   if ($is_registered) {
  //     $user_groups[] = 'registered';
  //   }

  //   if ($is_student) {
  //     $user_groups[] = 'student';
  //   }

  //   if ($is_warrior) {
  //     $user_groups[] = 'warrior';
  //   }

  //   if ($is_faculty) {
  //     $user_groups[] = 'faculty';
  //   }

  //   $userdata = [
  //     'user_pass' => wp_generate_password( 12 ),
  //     'user_login' => $username,
  //     'user_url' => $business_website,
  //     'user_email' => $email,
  //     'first_name' => $first_name,
  //     'last_name' => $last_name
  //   ];

  //   if ($user_already_exists !== false) {
  //     $userdata['ID'] = $user_already_exists->ID;
  //   }

  //   $user_id = wp_insert_user( $userdata );

  //   if ( !is_wp_error( $user_id ) ) {

  //     if ($user_already_exists === false) {
  //       echo "User successfully created: $user_id <br>";
  //     }

  //     update_field( 'ACF_date_of_birth', $birthdate, 'user_' . $user_id );
  //     update_field( 'ACF_gender', $gender, 'user_' . $user_id );
  //     update_field( 'ACF_ethnicity', $ethinicity, 'user_' . $user_id );
  //     update_user_meta( $user_id, 'billing_company', $business_name );
  //     update_user_meta( $user_id, 'billing_address_1', $address );
  //     update_user_meta( $user_id, 'billing_city', $city );
  //     update_user_meta( $user_id, 'billing_state', $state );
  //     update_user_meta( $user_id, 'billing_postcode', $zip );
  //     update_user_meta( $user_id, 'billing_postcode', $zip );
  //     update_field( 'work_phone', $work_phone, 'user_' . $user_id );
  //     update_user_meta( $user_id, 'billing_phone', $mobile_phone );
  //     update_field( 'ACF_practice_type', $practice_type, 'user_' . $user_id );
  //     update_field( 'ACF_tlc_region', $region, 'user_' . $user_id );
  //     update_field( 'ACF_graduation_month', $grad_month, 'user_' . $user_id );
  //     update_field( 'ACF_graduation_year', $grad_year, 'user_' . $user_id );
  //     update_field( 'ACF_member_groups', $user_groups, 'user_' . $user_id );

  //     echo "User successfully updated: $user_id";
  //     echo "<br>";
  //   } else {
  //     echo "There was an error while updating a user with this email: $email <br>";
  //     echo $user_id->get_error_message();
  //     echo "<br>";
      
  //     $issues[] = [
  //       'email' => $email,
  //       'reason' => $user_id->get_error_message()
  //     ];
  //   }
  // }

  // echo "<pre>";
  // echo json_encode($issues);
  // echo "</pre>";
?>

<?php

  // $orders_json = file_get_contents( get_stylesheet_directory_uri() . '/orders.json' );
  // $orders = json_decode($orders_json, JSON_FORCE_OBJECT);
  // $issues = [];
  // $order_ids = [];

  // foreach ($orders as $order) {
  //   $course_number = $order['Course Number'];
  //   $status = $order['Registration Status'];
  //   $date = DateTime::createFromFormat('m/d/Y', $order['Date of Registration']);
  //   $date = $date->getTimestamp();

  //   // Get user info
  //   $user_by_email = get_user_by( 'email', $order['Email'] );
  //   $user_by_email = get_user_by( 'login', $order['User Name'] );
  //   $user = null;

  //   if ( $user_by_email ) {
  //     $user = $user_by_email;
  //   } else if ( $user_by_login ) {
  //     $user = $user_by_login;
  //   } else {
  //     $issues[] = [
  //       'email' => $order['Email'],
  //       'username' => $order['User Name'],
  //       'reason' => 'User not found'
  //     ];
  //     break;
  //   }

  //   // Get users' ACF fields
  //   $currently_licensed = $order['Are you currently licensed to practice law in a U.S. state and a member in good standing of a state bar?'];
  //   $currently_licensed = $currently_licensed === 'True' ? true : false;
  //   update_field( 'ACF_licensed', $currently_licensed, 'user_' . $user->ID );

  //   $why_interested = $order['If not, please explain why are interested in participating at TLC.'];
  //   update_field( 'ACF_why_interested', $why_interested, 'user_' . $user->ID );

  //   $represent_clients = $order['Do you currently represent clients?'];
  //   $represent_clients = $represent_clients === 'True' ? true : false;
  //   update_field( 'ACF_represent_clients', $represent_clients, 'user_' . $user->ID );

  //   $represent_explanation = $order['If not, what do you hope to accomplish as a student at TLC?'];
  //   update_field( 'ACF_represent_explanation', $represent_explanation, 'user_' . $user->ID );

  //   $crime = $order['Have you ever been convicted of a crime?'];
  //   $crime = $crime === 'True' ? true : false;
  //   update_field( 'ACF_crime', $crime, 'user_' . $user->ID );

  //   $crime_explanation = $order['If yes, please explain.'];
  //   update_field( 'ACF_crime_explanation', $crime_explanation, 'user_' . $user->ID );

  //   $corporations = $order['Do you currently represent insurance companies, large corporations or governmental entities other than legal services or public defenders?'];
  //   $corporations = $corporations === 'True' ? true : false;
  //   update_field( 'ACF_corporations', $corporations, 'user_' . $user->ID );

  //   $doctor_psychologist = $order['Have you ever been treated, or are you currently being treated, by a doctor or psychologist or other treating persons for any physical, mental or psychological issues or concerns that may be affected by participating in psychodrama?  If so, please explain below.'];
  //   $doctor_psychologist = $doctor_psychologist === 'True' ? true : false;
  //   update_field( 'ACF_doctor_psychologist', $doctor_psychologist, 'user_' . $user->ID );

  //   // Get billing info
  //   $address = array(
	// 		'first_name' => get_user_meta( $user->ID, 'first_name', true ),
	// 		'last_name'  => get_user_meta( $user->ID, 'last_name', true ),
	// 		'company'    => get_user_meta($user->ID, 'billing_company', true),
	// 		'email'      => $order['Email'],
	// 		'phone'      => get_user_meta( $user->ID, 'billing_phone', true ),
	// 		'address_1'  => get_user_meta( $user->ID, 'billing_address_1', true ),
	// 		'city'       => get_user_meta( $user->ID, 'billing_city', true ),
	// 		'state'      => get_user_meta( $user->ID, 'billing_state', true ),
	// 		'postcode'   => get_user_meta( $user->ID, 'billing_postcode', true )
	// 	);

  //   $order_status = 'completed';

  //   switch ($status) {
  //     case 'Cancelled':
  //       $order_status = 'cancelled';
  //       break;
  //     case 'In Progress':
  //       $order_status = 'processing';
  //       break;
  //     case 'Completed':
  //       $order_status = 'completed';
  //       break;
  //     case 'Registered':
  //       $order_status = 'processing';
  //       break;
  //     case 'Application Submitted':
  //       $order_status = 'completed';
  //       break;
  //     default:
  //       $order_status = 'completed';
  //       break;
  //   }

  //   // Get order's total
  //   $total = $order['Tuition Amount Paid'];
  //   $payment_method_title = $order['Payment Type'];

  //   // Get product info
  //   $product_id = wc_get_product_id_by_sku( $course_number );

  //   if ( $product_id === 0 ) {
  //     $issues[] = [

  //     ];
  //   }

  //   // Update order data
  //   $new_order = wc_create_order();
  //   $new_order->set_address( $address, 'billing' );
  //   $new_order->add_product( wc_get_product( $product_id ) );
  //   $new_order->update_status( $order_status );
  //   $new_order->set_date_created( $date );
  //   $new_order->set_customer_id( $user->ID );
  //   $new_order->set_payment_method_title( $payment_method_title );
  //   $new_order->set_total( $total );
  //   $new_order->save();

  //   // Update order meta fields
  //   $order_id = $new_order->get_id();
    
  //   $financial_aid = $order['Financial Aid?'] === 'True' ? true : false;
  //   update_post_meta( $order_id, 'financial_aid', $financial_aid );

  //   $why_aid = $order['Financial Aid Details'];
  //   update_post_meta( $order_id, 'why_aid', $why_aid );

  //   $income_range = $order['Financial Aid Income Range'];
  //   update_post_meta( $order_id, 'income_range', $income_range );

  //   $jury_trials = $order['How many jury trials have you conducted as lead counsel?'];
  //   update_post_meta( $order_id, 'jury_trials', $jury_trials );

  //   $verdict_trials = $order['How many jury trials have you conducted as lead counsel that reached a verdict?'];
  //   update_post_meta( $order_id, 'verdict_trials', $verdict_trials );

  //   $exam_trials = $order['How many jury trials have you conducted as co-counsel in which you conducted a direct or cross examination?'];
  //   update_post_meta( $order_id, 'exam_trials', $exam_trials );

  //   $hearings = $order['How many administrative hearings as lead counsel?'];
  //   update_post_meta( $order_id, 'hearings', $hearings );

  //   $hearings_nature = $order['What is the primary nature of your administrative hearings?'];
  //   update_post_meta( $order_id, 'hearings_nature', $hearings_nature );

  //   $make_arguments = $order['At your hearings, do you present witnesses and make arguments?'];
  //   update_post_meta( $order_id, 'make_arguments', $make_arguments );

  //   $cases_types = $order['List the types of cases you generally try:'];
  //   update_post_meta( $order_id, 'cases_types', $cases_types );

  //   $begin_year = $order['In what year did you begin practicing law?'];
  //   update_post_meta( $order_id, 'begin_year', $begin_year );

  //   $prev_applies = $order['If you have previously applied for this course, in which year(s) did you apply?'];
  //   update_post_meta( $order_id, 'prev_applies', $prev_applies );

  //   $attended_courses = $order['How many other TLC courses have you attended?'];
  //   update_post_meta( $order_id, 'attended_courses', $attended_courses );

  //   $recent_course = $order['f you have attended a TLC course before, what was the most recent TLC course you attended?'];
  //   update_post_meta( $order_id, 'recent_course', $recent_course );

  //   $reference_1 = $order['Reference 1'];
  //   update_post_meta( $order_id, 'reference_1', $reference_1 );

  //   $reference_2 = $order['Reference 2'];
  //   update_post_meta( $order_id, 'reference_2', $reference_1 );

  //   $reference_3 = $order['Reference 3'];
  //   update_post_meta( $order_id, 'reference_3', $reference_1 );

  //   $essay = $order['Application Essay'];
  //   update_post_meta( $order_id, 'essay', $essay );

  //   $diet = $order['Diet Plan?'] === 'True' ? true : false;
  //   update_field( 'ACF_diet', $diet, 'user_' . $user->ID );

  //   $diet_explanation = $order['Diet Plan Explanation'];
  //   update_field( 'ACF_diet_explanation', $diet_explanation, 'user_' . $user->ID );
    
  //   $allergies = $order['Food Allergies?'] === 'True' ? true : false;
  //   update_field( 'ACF_allergies', $allergies, 'user_' . $user->ID );
    
  //   $allergies_explanation = $order['Food Allergy Explanation'];
  //   update_field( 'ACF_allergies_explanation', $allergies_explanation, 'user_' . $user->ID );
    
  //   $order_ids[] = $order_id;
  // }

  // var_dump(json_encode($order_ids));
  // var_dump(json_encode($issues));
?>


<?php get_footer(); ?>