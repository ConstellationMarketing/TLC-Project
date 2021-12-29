<?php

/**
 * Plugin Name:       Members Filter
 * Version:           1.0
 * Author:            Artemy Kaydash
 */

define( 'MEMBERS_FILTER_DIR', plugin_dir_path( dirname( __FILE__ ) ) );
define( 'MEMBERS_FILTER_DIR_URL', plugin_dir_url( __FILE__ ) );

require_once plugin_dir_path( __FILE__ ) . 'merge-users.php';

class MembersFilter {
  public function __construct() {
    add_action( 'admin_menu', [$this, 'register_admin_page'] );
    remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );
    add_action( 'admin_head', [$this, 'hide_personal_options'] );
    add_action( 'wp_ajax_merge-users' , [$this, 'merge_users'] );
    add_action( 'wp_ajax_update-login' , [$this, 'update_login'] );
    add_action( 'admin_init' , [$this, 'print_csv'] );
    register_activation_hook( __FILE__, [$this, 'activate'] );
    register_deactivation_hook( __FILE__, [$this, 'deactivate'] );
  }

  public function activate() {
    $role = get_role( 'editor' );
    $role->add_cap( 'edit_users' );
  }

  public function deactivate() {
    $role = get_role( 'editor' );
    $role->remove_cap( 'edit_users' );
  }

  public function register_admin_page() {
    $page = add_menu_page(
			'Members',
			'Members',
			'edit_users',
			'members',
			[$this, 'render_members_page'],
			'dashicons-universal-access',
			21
    );
    
    $subpage_main = add_submenu_page( 'members', 'Members', 'Members', 'edit_users', 'members');
    $subpage_merge = add_submenu_page( 'members', 'Merge users', 'Merge users', 'edit_users', 'merge', [$this, 'render_user_merging_form'] );
    $subpage_change_login = add_submenu_page( 'members', 'Change username', 'Change username', 'edit_users', 'change-username', [$this, 'render_change_login_form'] );

    add_action( 'load-' . $page, [$this, 'enqueue_styles'] );
    add_action( 'load-' . $subpage_main, [$this, 'enqueue_styles'] );
    add_action( 'load-' . $subpage_merge, [$this, 'enqueue_styles'] );
    add_action( 'load-' . $subpage_change_login, [$this, 'enqueue_styles'] );
    add_action( 'load-' . $page, [$this, 'enqueue_scripts'] );
    add_action( 'load-' . $subpage_main, [$this, 'enqueue_scripts'] );
    add_action( 'load-' . $subpage_merge, [$this, 'enqueue_scripts'] );
    add_action( 'load-' . $subpage_change_login, [$this, 'enqueue_scripts'] );
  }

  public function enqueue_styles() {
    wp_enqueue_style( 'members-styles', plugin_dir_url( __FILE__ ) . 'assets/styles.css', [], time()  );
    wp_enqueue_style( 'select2-styles', 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css' );
  }

  public function enqueue_scripts() {
    wp_enqueue_script( 'members-scripts' , plugin_dir_url( __FILE__ ) . 'assets/main.js' , ['jquery'], '1.0', true );
    wp_enqueue_script( 'select2-scripts' , 'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js', ['jquery'], null, true);
  }

  private function get_members($params) {
    $page_num = $params['page_num'];
    $per_page = $params['per_page'];

    if ( isset($params['get_all']) && $params['get_all'] === true ) {
      $page_num = 1;
      $per_page = -1;
    }

    $meta_query = [
      'relation' => 'AND'
    ];

    if ( $params['group'] !== null ) {
      $meta_query[] = [
        'key' => 'ACF_member_groups',
        'value' => $params['group'],
        'compare' => 'LIKE'
      ];
    }

    if( $params['state'] !== null ) {
      $meta_query[] = [
        'key' => 'billing_state',
        'value' => $params['state'],
        'compare' => '='
      ];
    }

    if( $params['region'] !== null ) {
      $meta_query[] = [
        'key' => 'ACF_tlc_region',
        'value' => $params['region'],
        'compare' => 'LIKE'
      ];
    }

    if( $params['search'] !== null ) {
      $meta_query[] = [
        'relation' => 'OR',
        [
          'key'     => 'first_name',
          'value'   => $params['search'],
          'compare' => 'LIKE'
        ],
        [
          'key'     => 'last_name',
          'value'   => $params['search'],
          'compare' => 'LIKE'
        ]
      ];
    }

    $members = new WP_User_Query([
      'number' => $per_page,
      'offset' => $page_num > 1 ? ($page_num - 1) * $per_page : 0,
      'meta_query' => $meta_query,
      'count_total' => true,
    ]);

    return [
      'members' => $members->get_results(),
      'total' => $members->get_total()
    ];
  }

  private function get_groups() {
    $groups = get_field_object( 'field_60085d0ca279f' )['choices'];
    return $groups;
  }

  private function get_states() {
    global $wpdb;
    $results = $wpdb->get_results( "SELECT DISTINCT meta_value FROM $wpdb->usermeta WHERE meta_key LIKE 'billing_state'", OBJECT );

    $states = array_map(function ($row) {
      return $row->meta_value;
    }, $results);

    $states = array_filter($states, function ($str) {
      return strlen($str) > 0;
    });

    sort($states);

    return $states;
  }

  private function get_regions() {
    $regions = get_field_object( 'field_60085e17a27a0' )['choices'];
    return $regions;
  }

  public function render_members_page() {
    $page_num = isset( $_GET['page_num'] ) ? intval( $_GET['page_num'] ) : 1;
    $per_page = isset( $_GET['per_page'] ) ? intval( $_GET['per_page'] ) : 100;
    $group = isset( $_GET['group'] ) && $_GET['group'] !== 'all' ? $_GET['group'] : null;
    $state = isset( $_GET['state'] ) && $_GET['state'] !== 'all' ? $_GET['state'] : null;
    $region = isset( $_GET['region'] ) && $_GET['region'] !== 'all' ? $_GET['region'] : null;
    $search = isset( $_GET['search'] ) && strlen( $_GET['search'] ) > 0 ? $_GET['search'] : null;

    $members_data = $this->get_members([
      'page_num' => $page_num,
      'per_page' => $per_page,
      'group' => $group,
      'state' => $state,
      'region' => $region,
      'search' => $search
    ]);

    $members = $members_data['members'];
    $members_total = $members_data['total'];
    $groups = $this->get_groups();
    $states = $this->get_states();
    $regions = $this->get_regions();

    require_once plugin_dir_path( __FILE__ ) . 'views/members-page.php';
  }

  public function render_user_merging_form() {
    require_once plugin_dir_path( __FILE__ ) . 'views/merging-page.php';
  }

  public function render_change_login_form() {
    require_once plugin_dir_path( __FILE__ ) . 'views/change-login.php';
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

  public function print_csv() {
    if ( !is_admin() ) {
      return;
    }

    if ( !isset( $_GET['mf-export'] ) ) {
      return;
    }

    if ( !current_user_can('editor') && !current_user_can('administrator') ) {
      return;
    }

    header('Content-Disposition: attachment; filename="members.csv";');

    $group = isset( $_GET['group'] ) && $_GET['group'] !== 'all' ? $_GET['group'] : null;
    $state = isset( $_GET['state'] ) && $_GET['state'] !== 'all' ? $_GET['state'] : null;
    $region = isset( $_GET['region'] ) && $_GET['region'] !== 'all' ? $_GET['region'] : null;
    $search = isset( $_GET['search'] ) && strlen( $_GET['search'] ) > 0 ? $_GET['search'] : null;

    $members_data = $this->get_members([
      'page_num' => 1,
      'per_page' => -1,
      'group' => $group,
      'state' => $state,
      'region' => $region,
      'search' => $search,
      'get_all' => true
    ]);

    $members = $members_data['members'];

    $headings = ["User Name", "Email", "First Name", "Last Name", "Date Of Birth", "Gender", "Ethnicity", "Business Name", "Business Website", "Address", "City", "State", "Zip", "Mobile Phone", "Work Phone", "Practice Type", "Region", "Grad Month", "Grad Year", "Is 3Wk Grad", "Is 7 Stepper", "Is Student", "Is Ranch Club Member", "Is Warrior Subscriber", "Is Faculty"];

    $out = fopen('php://output', 'w');
    fputcsv($out, $headings);
    fclose($out);

    foreach ($members as $member) {
      $username = get_userdata( $member->ID )->user_login;
      $email = get_userdata( $member->ID )->user_email;
      $first_name = get_userdata( $member->ID )->user_firstname;
      $last_name = get_userdata( $member->ID )->user_lastname;
      $birthdate = get_field( 'ACF_date_of_birth', 'user_' . $member->ID );
      $gender = get_field( 'ACF_gender', 'user_' . $member->ID );
      $ethnicity = get_field( 'ACF_ethnicity', 'user_' . $member->ID );
      $business_name = get_user_meta( $member->ID, 'billing_company', true);
      $business_website = get_userdata( $member->ID )->user_url;
      $address = get_user_meta( $member->ID, 'billing_address_1', true );
      $city = get_user_meta( $member->ID, 'billing_city', true );
      $state = get_user_meta( $member->ID, 'billing_state', true );
      $zip = get_user_meta( $member->ID, 'billing_postcode', true );
      $mobile_phone = get_field( 'billing_phone', 'user_' . $member->ID );
      $work_phone = get_user_meta( $member->ID, 'work_phone', true );
      $practice_type = get_field( 'ACF_practice_type', 'user_' . $member->ID );
      $region = get_user_meta( $member->ID, 'ACF_tlc_region', true );
      $grad_month = get_field( 'ACF_graduation_month', 'user_' . $member->ID );
      $grad_year = get_field( 'ACF_graduation_year', 'user_' . $member->ID );
      $is_3wk = $this->user_in_group($member->ID, '3week');
      $is_7stepper = $this->user_in_group($member->ID, '7step');
      $is_student = $this->user_in_group($member->ID, 'student');
      $is_ranch_club = $this->user_in_group($member->ID, 'ranch_club');
      $is_warrior = $this->user_in_group($member->ID, 'warrior');
      $is_faculty = $this->user_in_group($member->ID, 'faculty');

      $properties = [
        $username, 
        $email, 
        $first_name, 
        $last_name, 
        $birthdate, 
        $gender,
        $ethnicity,
        $business_name,
        $business_website,
        $address,
        $city,
        $state, 
        $zip,
        $mobile_phone,
        $work_phone,
        $practice_type,
        $region,
        $grad_month,
        $grad_year,
        $is_3wk,
        $is_7stepper,
        $is_student,
        $is_ranch_club,
        $is_warrior,
        $is_faculty
      ];

      $out = fopen('php://output', 'a');
      fputcsv($out, $properties);
      fclose($out);
    }

    exit;
  }

  public function hide_personal_options(){
    ?>
      <script>
        jQuery(document).ready(function ($) {
          jQuery('h2:contains(Personal Options)').next().remove();
          jQuery('h2:contains(Personal Options)').remove();
          jQuery('h2:contains(Contact Info)').next().find('tr:not(.user-email-wrap, .user-url-wrap)').remove();
          jQuery('.yoast.yoast-settings').remove();
        })
      </script>
    <?php
  }

  public function get_students() {
    $user_query = new WP_User_Query([
      'number' => -1,
      'meta_query' => [
        'relation' => 'AND',
        [
          'key' => 'ACF_member_groups',
          'value' => 'student',
          'compare' => 'LIKE'
        ]
      ],
    ]);

    $students = $user_query->get_results();

    return $students;
  }

  public function merge_users() {
    global $user_merger;
    if ( !is_admin() ) {
      return;
    }

    $user_1_id = $_POST['user_1_id'];
    $user_2_id = $_POST['user_2_id'];

    if ( !isset($user_1_id) || !isset($user_2_id) ) {
      return;
    }

    $res = $user_merger->merge($user_1_id, $user_2_id);

    wp_send_json($res);

    wp_die();

    // delete old user
  }

  public function update_login() {
    global $wpdb;
    if ( !is_admin() ) {
      return;
    }

    $user_id = $_POST['user_id'];

    if ( !$user_id ) {
      return;
    }

    $new_login = $_POST['new_login'];

    $login_is_unique = ( get_user_by( 'login' , $new_login ) === false );

    if ( !$login_is_unique ) {
      wp_send_json([
        'error' => 'This login belongs to another user'
      ]);
      wp_die();
    }

    $res = $wpdb->update($wpdb->users, ['user_login' => $new_login], ['ID' => $user_id]);

    wp_send_json(['test' => 1]);

    if ( $res !== false ) {
      wp_send_json([
        'success' => true
      ]);
    } else {
      wp_send_json([
        'error' => 'Something went wrong'
      ]);
    }
    wp_die();
  }
}

$members_filter = new MembersFilter;