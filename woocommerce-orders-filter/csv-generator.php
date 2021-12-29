<?php

class WCOF_CSV_Generator {
  public $order;
  public $columns = [
    'Course Number' => 'get_course_number',
    'Registration Status' => 'get_order_status',
    'Date of Registration' => 'get_order_date',
    'User Name' => 'get_user_login',
    'Email' => 'get_user_email',
    'First Name' => 'get_user_first_name',
    'Last Name' => 'get_user_last_name',
    'Date Of Birth' => 'get_user_birthdate',
    'Gender' => 'get_user_gender',
    'Ethnicity' => 'get_user_ethnicity',
    'Business Name' => 'get_user_business_name',
    'Business Website' => 'get_user_business_website',
    'Address' => 'get_user_address',
    'City' => 'get_user_city',
    'State' => 'get_user_state',
    'Zip' => 'get_user_zip',
    'Mobile Phone' => 'get_user_mobile_phone',
    'Work Phone' => 'get_user_work_phone',
    'Practice Type' => 'get_user_practice_type',
    'Region' => 'get_user_region',
    'Grad Month' => 'get_user_grad_month',
    'Grad Year' => 'get_user_grad_year',
    'Are you currently licensed to practice law in a U.S. state and a member in good standing of a state bar?' => 'is_user_licensed',
    'If not, please explain why are interested in participating at TLC.' => 'get_why_user_interested',
    'Do you currently represent clients?' => 'is_user_represents_clients',
    'If not, what do you hope to accomplish as a student at TLC?' => 'get_user_represents_explanation',
    'Have you ever been convicted of a crime?' => 'is_user_convicted',
    'If yes, please explain.' => 'get_user_crime_explanation',
    'Do you currently represent insurance companies, large corporations or governmental entities other than legal services or public defenders?' => 'is_user_represents_corporations',
    'Have you ever been treated, or are you currently being treated, by a doctor or psychologist or other treating persons for any physical, mental or psychological issues or concerns that may be affected by participating in psychodrama?  If so, please explain below.' => 'is_user_treated',
    'If yes, please explain' => 'get_user_treated_explanation',
    'Is 3Wk Grad' => 'is_user_3wk',
    'Is 7 Stepper' => 'is_user_7stepper',
    'Is Student' => 'is_user_student',
    'Is Ranch Club Member' => 'is_user_ranch_club_member',
    'Is Warrior Subscriber' => 'is_user_warrior_subscriber',
    'Is Faculty' => 'is_user_faculty_member',
    'Tuition Amount Paid' => 'get_order_tution_amount',
    'Warrior Magazine Amount Paid' => 'get_warrior_subscription_amount',
    'Ranch Club Amount Paid' => 'get_ranch_club_amount',
    'Payment Type' => 'get_payment_type',
    'Tuition Plan' => 'get_tution_plan',
    'Payment Transaction Info' => 'get_payment_transaction_info',
    'Financial Aid?' => 'order_needs_financial_aid',
    'Financial Aid Details' => 'get_order_aid_details',
    'Financial Aid Income Range' => 'get_order_income_range',
    'How many jury trials have you conducted as lead counsel?' => 'get_order_jury_trials',
    'How many jury trials have you conducted as lead counsel that reached a verdict?' => 'get_order_verdict_trials',
    'How many jury trials have you conducted as co-counsel in which you conducted a direct or cross examination?' => 'get_order_exam_trials',
    'How many administrative hearings as lead counsel?' => 'get_order_hearings',
    'What is the primary nature of your administrative hearings?' => 'get_order_hearings_nature',
    'At your hearings, do you present witnesses and make arguments?' => 'get_order_make_arguments',
    'List the types of cases you generally try:' => 'get_order_cases_types',
    'In what year did you begin practicing law?' => 'get_order_begin_year',
    'If you have previously applied for this course, in which year(s) did you apply?' => 'get_order_prev_applies',
    'How many other TLC courses have you attended?' => 'get_order_attended_courses',
    'If you have attended a TLC course before, what was the most recent TLC course you attended?' => 'get_order_recent_course',
    'Reference 1' => 'get_order_reference_1',
    'Reference 2' => 'get_order_reference_2',
    'Reference 3' => 'get_order_reference_3',
    'Application Essay' => 'get_order_essay',
    'Are you committed to attending?' => 'is_user_committed_to_attending',
    'Diet Plan?' => 'is_user_has_diet',
    'Diet Plan Explanation' => 'get_user_diet_explanation',
    'Food Allergies?' => 'is_user_has_allergies',
    'Food Allergy Explanation' => 'get_user_allergy_explanation'
  ];

  public function print_headings() {
    $columns = $this->columns;
    $headings = [];

    foreach ($columns as $column_name => $column_function) {
      if ( is_int($column_name) ) {
        continue;
      } else {
        $headings[] = $column_name;
      }
    }

    $out = fopen('php://output', 'w');
    fputcsv($out, $headings);
    fclose($out);
  }

  public function is_renewal_order($order) {
    $related_subscriptions = wcs_get_subscriptions_for_renewal_order( $order );

    if ( wcs_is_order( $order ) && ! empty( $related_subscriptions ) ) {
      $is_renewal = true;
    } else {
      $is_renewal = false;
    }
  
    return apply_filters( 'woocommerce_subscriptions_is_renewal_order', $is_renewal, $order );
  }

  public function print_order($order_id) {
    $order = wc_get_order($order_id);
    $this->order = $order;

    if ( $this->is_renewal_order( $order ) ) {
      return;
    }

    $columns = $this->columns;
    $properties = [];

    foreach ($columns as $column_name => $column_function) {
      if ( is_int($column_name) ) {
        continue;
      }

      $properties[] = $this->$column_function($order_id);
    }

    $out = fopen('php://output', 'w');
    fputcsv($out, $properties);
    fclose($out);
  }

  private function get_course_number($order_id) {
    $product_number = '';
    $items = $this->order->get_items(); 
        
    foreach ( $items as $item ) {      
        $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id(); 
        $product = wc_get_product( $product_id );
        $product_number = strlen( $product->get_sku() ) > 0 ? $product->get_sku() : $product->get_name();
        break;
    }

    return $product_number;
  }

  private function get_order_status($order_id) {
    return $this->order->get_status();
  }

  private function get_order_date($order_id) {
    return $this->order->get_date_created()->format('m/d/Y');
  }

  private function get_user_login($order_id) {
    $user = $this->order->get_user();
    return $user->user_login;
  }

  private function get_user_email($order_id) {
    $user = $this->order->get_user();
    return $user->user_email;
  }

  private function get_user_first_name($order_id) {
    $user = $this->order->get_user();
    return get_user_meta( $user->ID, 'first_name', true );
  }

  private function get_user_last_name($order_id) {
    $user = $this->order->get_user();
    return get_user_meta( $user->ID, 'last_name', true );
  }

  private function get_user_birthdate($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_date_of_birth', 'user_' . $user->ID );
  }

  private function get_user_gender($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_gender', 'user_' . $user->ID );
  }

  private function get_user_ethnicity($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_ethnicity', 'user_' . $user->ID );
  }

  private function get_user_business_name($order_id) {
    $user = $this->order->get_user();
    return get_user_meta($user->ID, 'billing_company', true);;
  }

  private function get_user_business_website($order_id) {
    $user = $this->order->get_user();
    $userdata = get_userdata($user->ID);
    return $userdata->user_url;
  }

  private function get_user_address($order_id) {
    $user = $this->order->get_user();
    return get_user_meta( $user->ID, 'billing_address_1', true );
  }

  private function get_user_city($order_id) {
    $user = $this->order->get_user();
    return get_user_meta( $user->ID, 'billing_city', true );
  }

  private function get_user_state($order_id) {
    $user = $this->order->get_user();
    return get_user_meta( $user->ID, 'billing_state', true );
  }

  private function get_user_zip($order_id) {
    $user = $this->order->get_user();
    return get_user_meta( $user->ID, 'billing_postcode', true );
  }

  private function get_user_mobile_phone($order_id) {
    $user = $this->order->get_user();
    return get_user_meta( $user->ID, 'mobile_phone', true );
  }

  private function get_user_work_phone($order_id) {
    $user = $this->order->get_user();
    return get_user_meta( $user->ID, 'billing_phone', true );
  }

  private function get_user_practice_type($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_practice_type', 'user_' . $user->ID );
  }

  private function get_user_region($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_tlc_region', 'user_' . $user->ID );
  }

  private function get_user_grad_month($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_graduation_month', 'user_' . $user->ID );
  }

  private function get_user_grad_year($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_graduation_year', 'user_' . $user->ID );
  }

  private function is_user_licensed($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_licensed', 'user_' . $user->ID ) == 1 ? 'true' : 'false';
  }

  private function get_why_user_interested($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_why_interested', 'user_' . $user->ID );
  }

  private function is_user_represents_clients($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_represent_clients', 'user_' . $user->ID ) == 1 ? 'true' : 'false';
  }

  private function get_user_represents_explanation($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_represent_explanation', 'user_' . $user->ID );
  }

  private function is_user_convicted($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_crime', 'user_' . $user->ID ) == 1 ? 'true' : 'false';
  }

  private function get_user_crime_explanation($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_crime_explanation', 'user_' . $user->ID );
  }

  private function is_user_represents_corporations($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_corporations', 'user_' . $user->ID ) == 1 ? 'true' : 'false';
  }

  private function is_user_treated($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_doctor_psychologist', 'user_' . $user->ID ) == 1 ? 'true' : 'false';
  }

  private function get_user_treated_explanation($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_doctor_explanation', 'user_' . $user->ID );
  }

  private function user_in_group($user_id, $group_slug) {
    $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);
    $in_group = 'false';
    
    if ( !isset( $groups ) || $groups === null || count( $groups ) === 0 ) {
      return $in_group;
    }

    foreach ($groups as $group) {
      if ($group == $group_slug) {
        $in_group = 'true';
        break;
      }
    }

    return $in_group;
  }

  private function is_user_3wk($order_id) {
    $user = $this->order->get_user();
    return $this->user_in_group($user->ID, '3week');
  }

  private function is_user_7stepper($order_id) {
    $user = $this->order->get_user();
    return $this->user_in_group($user->ID, '7step');
  }

  private function is_user_student($order_id) {
    $user = $this->order->get_user();
    return $this->user_in_group($user->ID, 'student');
  }

  private function is_user_ranch_club_member($order_id) {
    $user = $this->order->get_user();
    return $this->user_in_group($user->ID, 'ranch_club');
  }

  private function is_user_warrior_subscriber($order_id) {
    $user = $this->order->get_user();
    return $this->user_in_group($user->ID, 'warrior');
  }

  private function is_user_faculty_member($order_id) {
    $user = $this->order->get_user();
    return $this->user_in_group($user->ID, 'faculty');
  }

  private function get_order_tution_amount($order_id) {
    $tution = 0;
    $items = $this->order->get_items(); 
    $subscriptions = wcs_get_subscriptions_for_order( $order_id );

    if (empty($subscriptions)) {
      foreach ($items as $item) {
        $product = $item->get_product();
        $product_id = $product->get_parent_id()  ? $product->get_parent_id() : $product->get_id();
        $terms = get_the_terms( $product_id, 'product_cat' );

        if ( !is_array($terms) ) {
          return $tution;
        }
        
        foreach ($terms as $term) {
          if ($term->name == 'REG') {
            return $this->order->get_total();
          }
        }
      }
    }

    foreach ($subscriptions as $id => $subscription) {
      $s_items = $subscription->get_items();

      foreach($s_items as $s_item) {
        $product = $s_item->get_product();
        $product_id = $product->get_parent_id()  ? $product->get_parent_id() : $product->get_id();
        $terms = get_the_terms( $product_id, 'product_cat' );
        
        foreach ($terms as $term) {
          if ($term->name == 'REG') {
            return $subscription->get_total();
          }
        }
        // if product doesnt has reg course category and product id === warrior magazine product id it means this total goes to warrior subscroption total
      }
    }

    return $tution;
  }

  private function get_warrior_subscription_amount($order_id) {
    $amount = 0;
    $subscriptions = wcs_get_subscriptions_for_order( $order_id );

    foreach ($subscriptions as $id => $subscription) {
      $s_items = $subscription->get_items();

      foreach($s_items as $s_item) {
        $i = 1;
        $product = $s_item->get_product();
        $product_id = $product->get_parent_id()  ? $product->get_parent_id() : $product->get_id();
        $product_name = $product->get_name();
        

        if ( stripos( $product_name, 'warrior magazine' ) !== false ) {
          return $subscription->get_total();
        }
      }
    }

    return $amount;
  }

  private function get_ranch_club_amount($order_id) {
    $amount = 0;
    $items = $this->order->get_items(); 
    $subscriptions = wcs_get_subscriptions_for_order( $order_id );

    foreach ($subscriptions as $id => $subscription) {
      $s_items = $subscription->get_items();

      foreach($s_items as $s_item) {
        $i = 1;
        $product = $s_item->get_product();
        $product_id = $product->get_parent_id()  ? $product->get_parent_id() : $product->get_id();
        $product_name = $product->get_name();
        

        if ( stripos( $product_name, 'ranch club' ) !== false ) {
          return $subscription->get_total();
        }
      }
    }

    return $amount;
  }

  private function get_payment_type($order_id) {
    return $this->order->get_payment_method_title();
  }

  private function get_payment_transaction_info($order_id) {
    return '';
  }

  private function get_tution_plan($order_id) {
    $items = $this->order->get_items();

    foreach ($items as $item) {
      $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
      $product = wc_get_product( $product_id );
      $parent_id = $product->get_parent_id()  ? $product->get_parent_id() : $product->get_id();
      $terms = get_the_terms( $parent_id, 'product_cat' );

      if (empty($terms)) {
        continue;
      }
        
      foreach ($terms as $term) {
        if ($term->name == 'REG') {
          return $product->get_attribute( 'payment' );
        }
      }
    }
  }

  private function order_needs_financial_aid($order_id) {
    return get_post_meta($order_id, 'financial_aid', true) == 1 ? 'true' : 'false';
  }

  private function get_order_aid_details($order_id) {
    return get_post_meta($order_id, 'why_aid', true);
  }

  private function get_order_income_range($order_id) {
    return get_post_meta($order_id, 'income_range', true);
  }

  private function get_order_jury_trials($order_id) {
    return get_post_meta($order_id, 'jury_trials', true);
  }

  private function get_order_verdict_trials($order_id) {
    return get_post_meta($order_id, 'verdict_trials', true);
  }

  private function get_order_exam_trials($order_id) {
    return get_post_meta($order_id, 'exam_trials', true);
  }

  private function get_order_hearings($order_id) {
    return get_post_meta($order_id, 'hearings', true);
  }

  private function get_order_hearings_nature($order_id) {
    return get_post_meta($order_id, 'hearings_nature', true);
  }

  private function get_order_make_arguments($order_id) {
    return get_post_meta($order_id, 'make_arguments', true);
  }

  private function get_order_cases_types($order_id) {
    return get_post_meta($order_id, 'cases_types', true);
  }

  private function get_order_begin_year($order_id) {
    return get_post_meta($order_id, 'begin_year', true);
  }

  private function get_order_prev_applies($order_id) {
    return get_post_meta($order_id, 'prev_applies', true);
  }

  private function get_order_attended_courses($order_id) {
    return get_post_meta($order_id, 'attended_courses', true);
  }

  private function get_order_recent_course($order_id) {
    return get_post_meta($order_id, 'recent_course', true);
  }

  private function get_order_reference_1($order_id) {
    return get_post_meta($order_id, 'reference_1', true);
  }

  private function get_order_reference_2($order_id) {
    return get_post_meta($order_id, 'reference_2', true);
  }

  private function get_order_reference_3($order_id) {
    return get_post_meta($order_id, 'reference_3', true);
  }

  private function get_order_essay($order_id) {
    return get_post_meta($order_id, 'essay', true);
  }

  private function is_user_committed_to_attending($order_id) {
    return '';
  }

  private function is_user_has_diet($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_diet', 'user_' . $user->ID ) == 1 ? 'true' : 'false';
  }

  private function get_user_diet_explanation($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_diet_explanation', 'user_' . $user->ID );
  }

  private function is_user_has_allergies($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_allergies', 'user_' . $user->ID ) == 1 ? 'true' : 'false';
  }

  private function get_user_allergy_explanation($order_id) {
    $user = $this->order->get_user();
    return get_field( 'ACF_allergies_explanation', 'user_' . $user->ID );
  }
}

$wcof_csv_generator = new WCOF_CSV_Generator;