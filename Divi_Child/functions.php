<?php

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
    
    if ( is_page( 'register' ) ) {
        wp_enqueue_style( 'register-styles', get_stylesheet_directory_uri() . '/assets/register.css', [], '1.3' );
    }
    
    if ( is_page( 'course-registration' ) ) {
        wp_enqueue_style( 'course-register-styles', get_stylesheet_directory_uri() . '/assets/course-register.css', [], strtotime("now") );
    }
    
    if ( is_checkout() ) {
        wp_enqueue_style( 'checkout-styles', get_stylesheet_directory_uri() . '/assets/checkout.css', [], strtotime("now") );
    }

    if ( is_page( 1348 ) || is_page( 42168 ) ) {
        wp_enqueue_style( 'datatables-styles' , 'https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.css', [], null);
        wp_enqueue_script( 'datatables-scripts' , 'https://cdn.datatables.net/v/dt/dt-1.10.23/datatables.min.js', [], null, true);
    }
}
	

//======================================================================
// CUSTOM DASHBOARD
//======================================================================
// ADMIN FOOTER TEXT
function remove_footer_admin () {
    echo "Divi Child Theme";
} 

add_filter('admin_footer_text', 'remove_footer_admin');

/**
 * Adds 'Profit' column header to 'Orders' page immediately after 'Total' column.
 *
 * @param string[] $columns
 * @return string[] $new_columns
 */
/* function sv_wc_cogs_add_order_course_column_header( $columns ) {

    $new_columns = array();

    foreach ( $columns as $column_name => $column_info ) {

        $new_columns[ $column_name ] = $column_info;

        if ( 'order_date' === $column_name ) {
            $new_columns['custom_column_course'] = __( 'Course', 'my-textdomain' );
			$new_columns['custom_column_topic'] = __( 'Topic', 'my-textdomain' );
			$new_columns['custom_column_student'] = __( 'Student', 'my-textdomain' );
        }
    }

    return $new_columns;
}
add_filter( 'manage_edit-shop_order_columns', 'sv_wc_cogs_add_order_course_column_header', 20 );
*/

/**
 * @snippet       Add Column to Orders Table (e.g. Billing Country) - WooCommerce
 * @how-to        Get CustomizeWoo.com FREE
 * @sourcecode    https://businessbloomer.com/?p=78723
 * @author        Rodolfo Melogli
 * @compatible    WooCommerce 3.4.5
 */
 
/* add_filter( 'manage_edit-shop_order_columns', 'bbloomer_add_new_order_admin_list_column' );
 
function bbloomer_add_new_order_admin_list_column( $columns ) {
    $columns['course_name'] = 'Course';
	$columns['topic_name'] = 'Topic';
	$columns['student_name'] = 'Student';
    return $columns;
}
 
add_action( 'manage_shop_order_posts_custom_column', 'bbloomer_add_new_order_admin_list_column_content' ); */
 
function sv_wc_cogs_add_order_course_column_header( $columns ) {

    $new_columns = array();

    foreach ( $columns as $column_name => $column_info ) {

        $new_columns[ $column_name ] = $column_info;

        if ( 'order_number' === $column_name ) {
            unset( $new_columns[$column_name] );
            $new_columns['order_number_custom'] = __( 'Order', 'my-textdomain' );
            $new_columns['course_name'] = __( 'Course', 'my-textdomain' );
			$new_columns['topic_name'] = __( 'Topic', 'my-textdomain' );
			$new_columns['course_type'] = __( 'Type', 'my-textdomain' );
			$new_columns['student_name'] = __( 'Student', 'my-textdomain' );
        }
    }

    return $new_columns;
}

add_filter( 'manage_edit-shop_order_columns', 'sv_wc_cogs_add_order_course_column_header', 20 ); 

add_action( 'manage_shop_order_posts_custom_column', 'bbloomer_add_new_order_admin_list_column_content' );

function bbloomer_add_new_order_admin_list_column_content( $column) {
	
    global $post;
 
    if ( 'student_name' === $column ) {
 
        $order = wc_get_order( $post->ID );
      
        if ( $order->user_id != '' ){
            $user_info = get_userdata($order->user_id);
            echo $user_info->display_name;
        }
    }

    if ( 'order_number_custom' === $column ) {
        $order = wc_get_order( $post->ID );
        $order_number = $order->get_id();
        $user_id = $order->get_user_id();
        $edit_url = $order->get_edit_order_url();

        if ( $user_id ){
            $userdata = get_userdata($order->user_id);
            $first_name = $userdata->user_firstname;
            $last_name = $userdata->user_lastname;
        }

        echo "<a href='$edit_url'>#$order_number $first_name $last_name</a>";
    }
}

add_action('manage_shop_order_posts_custom_column', 'orders_list_preview_items', 20, 2 );
function orders_list_preview_items($column, $post_id) {
    
    global $the_order, $post;
    
    if ('course_name' === $column) {
        
        // Start list
        echo '<ul class="orders-list-items-preview">';
        
        // Loop through order items
        foreach($the_order->get_items() as $item) {
            
            $product = $item->get_product();
            $img     = wp_get_attachment_url($product->get_image_id());
            
            $name    = $item->get_name();
            
            echo "<li>
                $name
            </li>";
        }
        
        // End list
        echo '</ul>';
    }
    
	
	
}

add_action('manage_shop_order_posts_custom_column', 'show_order_topic', 20, 2 );

function show_order_topic($column, $post_id) {
	global $the_order, $post;
    
    if ('course_type' === $column) {
        
        foreach($the_order->get_items() as $item) {
            $product = $item->get_product();
            $product_id = $product->get_id();
			$topics = wc_get_product_category_list($product_id);
			echo $topics;
        }
		
    } elseif ( 'topic_name' === $column ) {
        $topics = '';
        foreach($the_order->get_items() as $item) {
            $product = $item->get_product();
            $product_id = $product->get_id();
			$topics .= get_field('course_topic', $product_id);
        }
        echo $topics;
    }
}

// List Items Per Page Users Insights

add_filter('usin_user_list_options', 'customize_user_options');
 
function customize_user_options($options){  
  //define the pagination options
  $options['pageOptions'] = array(10, 20, 50, 100, 200);
  
  return $options;
}

add_shortcode('brief-form', 'render_brief_form');

function render_brief_form() {
    ob_start();
    require_once get_stylesheet_directory() . '/template-parts/upload-brief-form.php';
    return ob_get_clean();
}

add_shortcode('students-table', 'render_students_table');

function render_students_table() {
    $user_ids = [];
    $meta_query = [
      'relation' => 'AND'
    ];

    $meta_query[] = [
      'key' => 'ACF_member_groups',
      'value' => 'student',
      'compare' => 'LIKE'
    ];

    $meta_query[] = [
      'key' => 'ACF_member_groups',
      'value' => 'registered',
      'compare' => 'LIKE'
    ];

    $students = get_users([
      'number' => -1,
      'offset' => 0,
      'meta_query' => $meta_query
    ]);

    if (count($students) > 0) {
      foreach ($students as $user) {
        array_push($user_ids, $user->ID);
      }
    }
    
    ob_start();
    require_once get_stylesheet_directory() . '/template-parts/students-table.php';
    return ob_get_clean();
}

add_shortcode('faculty-table', 'render_faculty_table');

function render_faculty_table() {
    $user_ids = [];
    $meta_query = [
      'relation' => 'AND'
    ];

    $meta_query[] = [
      'key' => 'ACF_member_groups',
      'value' => 'faculty',
      'compare' => 'LIKE'
    ];

    $students = get_users([
      'number' => -1,
      'offset' => 0,
      'meta_query' => $meta_query
    ]);

    if (count($students) > 0) {
      foreach ($students as $user) {
        array_push($user_ids, $user->ID);
      }
    }
    
    ob_start();
    require_once get_stylesheet_directory() . '/template-parts/faculty-table.php';
    return ob_get_clean();
}



add_action( 'admin_post_nopriv_upload_brief', 'upload_brief' );
add_action( 'admin_post_upload_brief', 'upload_brief' );

function upload_brief() {
    require_once ABSPATH . 'wp-admin/includes/admin.php';

    if( !wp_verify_nonce( $_POST['brief_upload_nonce'], 'brief_upload' ) ) {
        wp_die('Error: nonce is not verified');
    }

    if (empty($_FILES)) {
        wp_die('Error: you must provide at least one file with .doc, .docx or .pdf extension');
    }

    $file = $_FILES['file'];

    $file_return = wp_handle_upload($file, [
        'test_form' => false
    ]);

    if ( isset( $file_return['error'] ) ) {
        wp_die('There was an error while uploading the file');
    }

    $filename = $file_return['file'];

    $term = get_term_by( 'slug', 'alumni-briefs', 'mediacat' );

    $attachment = [
        'post_mime_type' => $file_return['type'],
        'post_title' => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
        'post_content' => '',
        'post_category' => [$term->term_id],
        'post_status' => 'inherit',
        'guid' => $file_return['url']
    ];

    $attachment_id = wp_insert_attachment($attachment, $file_return['url']);

    wp_set_post_terms($attachment_id, [$term->term_id], 'mediacat' );

    if( 0 < intval( $attachment_id ) ) {
        return wp_redirect( home_url() . '/success-page/' );
    } else {
        wp_die('There was an error when inserting the file into our library');
    }
}

// Register Custom Post Type
function register_warrior_article_post_type() {

	$labels = array(
		'name'                  => 'Warrior Articles',
		'singular_name'         => 'Warrior Articles',
		'menu_name'             => 'Warrior Articles',
		'name_admin_bar'        => 'Warrior Articles',
		'archives'              => 'Warrior Articles',
		'attributes'            => 'Item Attributes',
		'parent_item_colon'     => 'Parent Item:',
		'all_items'             => 'All Items',
		'add_new_item'          => 'Add New Item',
		'add_new'               => 'Add New',
		'new_item'              => 'New Item',
		'edit_item'             => 'Edit Item',
		'update_item'           => 'Update Item',
		'view_item'             => 'View Item',
		'view_items'            => 'View Items',
		'search_items'          => 'Search Item',
		'not_found'             => 'Not found',
		'not_found_in_trash'    => 'Not found in Trash',
		'featured_image'        => 'Featured Image',
		'set_featured_image'    => 'Set featured image',
		'remove_featured_image' => 'Remove featured image',
		'use_featured_image'    => 'Use as featured image',
		'insert_into_item'      => 'Insert into item',
		'uploaded_to_this_item' => 'Uploaded to this item',
		'items_list'            => 'Items list',
		'items_list_navigation' => 'Items list navigation',
		'filter_items_list'     => 'Filter items list',
	);
	$args = array(
		'label'                 => 'Warrior Articles',
		'description'           => 'Warrior Articles',
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'custom-fields' ),
		'taxonomies'            => array( 'category', 'post_tag' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => false,
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'warrior_article', $args );

}
add_action( 'init', 'register_warrior_article_post_type', 0 );

// Add the custom columns to the warrior article post type:
add_filter( 'manage_warrior_article_posts_columns', 'set_custom_edit_warrior_article_columns' );
function set_custom_edit_warrior_article_columns($columns) {
    $columns['article_author'] = 'Author';
    return $columns;
}

// Add the data to the custom columns for the warrior_article post type:
add_action( 'manage_warrior_article_posts_custom_column' , 'custom_warrior_article_column', 10, 2 );
function custom_warrior_article_column( $column, $post_id ) {
    switch ( $column ) {

        case 'article_author' :
            $article_author = get_field( 'author', $post_id );
            echo $article_author;
            break;

    }
}

use Barn2\Plugin\Posts_Table_Pro\Data\Abstract_Table_Data;

if ( class_exists( 'Barn2\Plugin\Posts_Table_Pro\Data\Abstract_Table_Data' ) ) {
    class Posts_Table_Article_Title_Column extends Abstract_Table_Data { 

        public function get_data() { 
            // Retrieve the media type from somewhere. In this example, we get it from the post meta. 

            if ( !get_post_meta( $this->post->ID, 'or_upload_here', true ) || strlen( get_post_meta( $this->post->ID, 'or_upload_here', true ) ) == 0 ) {
                $href = home_url() . '/wp-content/uploads/docs' . get_post_meta( $this->post->ID, 'document_path', true );
                $post_title = get_the_title($post);
                $html = sprintf('<a href="%1$s">%2$s</a>', $href, $post_title); 
                return $html;
            } else {
                $attachment_id = get_post_meta( $this->post->ID, 'or_upload_here', true );
                $attachment_url = wp_get_attachment_url( $attachment_id );
                $post_title = get_the_title($post);
                $html = sprintf('<a href="%1$s">%2$s</a>', $attachment_url, $post_title); 
                return $html;
            }
            
        } 
    }

    add_filter( 'posts_table_custom_table_data_article_title', function( $obj, $post, $args ) { 
        return new Posts_Table_Article_Title_Column( $post ); 
    }, 10, 3 );
}

/* changes by Farah */
function OF_save_user_hook( $post_id, $xml_node, $is_update ) {

    if(OF_user_id_exists($post_id)){
        $ACF_member_groups = get_field('ACF_member_groups', 'user_'.$post_id);
        if(!empty($ACF_member_groups)){
            if(!is_array($ACF_member_groups)){
                $ACF_member_groups = explode(',', $ACF_member_groups);
                update_field('ACF_member_groups', $ACF_member_groups, 'user_'.$post_id);
            }
        }
    } 

}
add_action( 'pmxi_saved_post', 'OF_save_user_hook', 10, 3 );

function OF_user_id_exists($user){

    global $wpdb;

    $count = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $wpdb->users WHERE ID = %d", $user));

    if($count == 1){ return true; }else{ return false; }

}

add_action( 'admin_post_nopriv_register-subscriber', 'register_subscriber' );
add_action( 'admin_post_register-subscriber', 'register_subscriber' );

function register_subscriber() {
    if ( empty($_POST) || ! wp_verify_nonce( $_POST['rs-nonce'], 'register-subscriber') ){
        wp_die('Access denied: nonce was not verified');
    }

    $username = $_POST['username'];
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];

    $userdata = [
        'user_login' => $username,
        'user_pass' => $password,
        'user_email' => $email,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'role' => 'subscriber'
    ];

    $user_id = wp_insert_user( $userdata );

    if( is_wp_error( $user_id ) ) {
        wp_die( $user_id->get_error_message() );
    }

    update_user_meta( $user_id, 'billing_phone', $phone );

    wp_redirect( home_url('/my-account') );
}

add_action('wp_ajax_reg_user_info', 'ajax_reg_user_info');

function ajax_reg_user_info() {
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $ethnicity = $_POST['ethnicity'];
    $businessname = $_POST['businessname'];
    $businesswebsite = $_POST['businesswebsite'];
    $practice_type = $_POST['practice_type'];
    $billing_address = $_POST['billing_address'];
    $billing_city = $_POST['billing_city'];
    $billing_state = $_POST['billing_state'];
    $billing_postcode = $_POST['billing_postcode'];
    $licensed = isset( $_POST['licensed'] ) && $_POST['licensed'] === 'yes';
    $why_interested =  $_POST['why_interested'];
    $represent_clients = isset( $_POST['represent_clients'] ) && $_POST['represent_clients'] === 'yes';
    $represent_explanation = $_POST['represent_explanation'];
    $crime = isset( $_POST['crime'] ) && $_POST['crime'] === 'yes';
    $crime_explanation = $_POST['crime_explanation'];
    $corporations = isset( $_POST['corporations'] ) && $_POST['corporations'] === 'yes';
    $begin_year = $_POST['begin_year'];

    $user_id = get_current_user_id();

    wp_update_user([
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'user_email' => $email,
    ]);

    update_user_meta($user_id, 'billing_phone', $phone);
    update_field('ACF_date_of_birth', $birthdate, 'user_' . $user_id);
    update_field('ACF_gender', $gender, 'user_' . $user_id);
    update_field('ACF_ethnicity', $ethnicity, 'user_' . $user_id);
    update_user_meta($user_id, 'billing_company', $businessname);
    update_field('ACF_businesswebsite', $businesswebsite, 'user_' . $user_id);
    update_field('ACF_practice_type', $practice_type, 'user_' . $user_id);
    update_user_meta($user_id, 'billing_address_1', $billing_address);
    update_user_meta($user_id, 'billing_city', $billing_city);
    update_user_meta($user_id, 'billing_state', $billing_state);
    update_user_meta($user_id, 'billing_postcode', $billing_postcode);
    update_field('ACF_licensed', $licensed, 'user_' . $user_id);
    update_field('ACF_why_interested', $why_interested, 'user_' . $user_id);
    update_field('ACF_represent_clients', $represent_clients, 'user_' . $user_id);
    update_field('ACF_represent_explanation', $represent_explanation, 'user_' . $user_id);
    update_field('ACF_crime', $crime, 'user_' . $user_id);
    update_field('ACF_crime_explanation', $crime_explanation, 'user_' . $user_id);
    update_field('ACF_corporations', $corporations, 'user_' . $user_id);
    update_user_meta($user_id, 'reg_begin_year', $begin_year);

    wp_send_json(['success' => true]);
    wp_die();
}

add_action('wp_ajax_reg_payment_methods', 'ajax_reg_payment_methods');

function ajax_reg_payment_methods() {
    $variation_id = $_POST['tuition_plan'];
    WC()->cart->empty_cart();
    if ( $variation_id != 0 ) {
        WC()->cart->add_to_cart( $_POST['product_id'], 1, $variation_id );
    } else {
        WC()->cart->add_to_cart( $_POST['product_id'], 1 );
    }
    $user_id = get_current_user_id();
    $financial_aid = isset( $_POST['financial_aid'] ) && $_POST['financial_aid'] === 'yes';
    $why_aid = $_POST['why_aid'];
    $income_range = $_POST['income_range'];

    update_user_meta( $user_id, 'financial_aid', $financial_aid );
    update_user_meta( $user_id , 'why_aid', $why_aid);
    update_user_meta( $user_id, 'income_range', $income_range );

    $warrior_subscribe = isset( $_POST['warrior_subscribe'] ) && $_POST['warrior_subscribe'] === 'yes';

    if ($warrior_subscribe) {
        // 24096 is the ID of the WooCommerce product that corresponds to Warrior Magazine Subscription
        WC()->cart->add_to_cart( 24096, 1);
    }

    wp_send_json([
        'success' => true
    ]);
    wp_die();
}

add_action('wp_ajax_reg_dietary', 'ajax_reg_dietary');

function ajax_reg_dietary() {
    $user_id = get_current_user_id();
    $diet = isset( $_POST['diet'] ) && $_POST['diet'] === 'yes';
    $diet_explanation = $_POST['diet_explanation'];
    $allergies = isset( $_POST['allergies'] ) && $_POST['allergies'] === 'yes';
    $allergies_explanation = $_POST['allergies_explanation'];
    $doctor_psychologist = isset( $_POST['doctor_psychologist'] ) && $_POST['doctor_psychologist'] === 'yes';
    $doctor_explanation = $_POST['doctor_explanation'];

    update_field('ACF_diet', $diet, 'user_' . $user_id);
    update_field('ACF_diet_explanation', $diet_explanation, 'user_' . $user_id);
    update_field('ACF_allergies', $allergies, 'user_' . $user_id);
    update_field('ACF_allergies_explanation', $allergies_explanation, 'user_' . $user_id);
    update_field('ACF_doctor_psychologist', $doctor_psychologist, 'user_' . $user_id);
    update_field('ACF_doctor_explanation', $doctor_explanation, 'user_' . $user_id);

    wp_send_json(['success' => true]);
    wp_die();
}

add_action('wp_ajax_3wk_user_info', 'wk_user_info');

function wk_user_info() {
    // User Data
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $ethnicity = $_POST['ethnicity'];
    $businessname = $_POST['businessname'];
    $businesswebsite = $_POST['businesswebsite'];
    $practice_type = $_POST['practice_type'];
    $billing_address = $_POST['billing_address'];
    $billing_city = $_POST['billing_city'];
    $billing_state = $_POST['billing_state'];
    $billing_postcode = $_POST['billing_postcode'];
    $licensed = isset( $_POST['licensed'] ) && $_POST['licensed'] === 'yes';
    $why_interested =  $_POST['why_interested'];
    $represent_clients = isset( $_POST['represent_clients'] ) && $_POST['represent_clients'] === 'yes';
    $represent_explanation = $_POST['represent_explanation'];
    $crime = isset( $_POST['crime'] ) && $_POST['crime'] === 'yes';
    $crime_explanation = $_POST['crime_explanation'];
    $corporations = isset( $_POST['corporations'] ) && $_POST['corporations'] === 'yes';

    $user_id = get_current_user_id();

    // Update User Data
    wp_update_user([
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'user_email' => $email,
    ]);

    update_user_meta($user_id, 'billing_phone', $phone);
    update_field('ACF_date_of_birth', $birthdate, 'user_' . $user_id);
    update_field('ACF_gender', $gender, 'user_' . $user_id);
    update_field('ACF_ethnicity', $ethnicity, 'user_' . $user_id);
    update_user_meta($user_id, 'billing_company', $businessname);
    update_field('ACF_businesswebsite', $businesswebsite, 'user_' . $user_id);
    update_field('ACF_practice_type', $practice_type, 'user_' . $user_id);
    update_user_meta($user_id, 'billing_address_1', $billing_address);
    update_user_meta($user_id, 'billing_city', $billing_city);
    update_user_meta($user_id, 'billing_state', $billing_state);
    update_user_meta($user_id, 'billing_postcode', $billing_postcode);
    update_field('ACF_licensed', $licensed, 'user_' . $user_id);
    update_field('ACF_why_interested', $why_interested, 'user_' . $user_id);
    update_field('ACF_represent_clients', $represent_clients, 'user_' . $user_id);
    update_field('ACF_represent_explanation', $represent_explanation, 'user_' . $user_id);
    update_field('ACF_crime', $crime, 'user_' . $user_id);
    update_field('ACF_crime_explanation', $crime_explanation, 'user_' . $user_id);
    update_field('ACF_corporations', $corporations, 'user_' . $user_id);

    // Order Data
    $jury_trials = $_POST['jury_trials'];
    $verdict_trials = $_POST['verdict_trials'];
    $exam_trials = $_POST['exam_trials'];
    $hearings = $_POST['hearings'];
    $hearings_nature = $_POST['hearings_nature'];
    $make_arguments = $_POST['make_arguments'];
    $cases_types = $_POST['cases_types'];
    $begin_year = $_POST['begin_year'];
    $prev_applies = $_POST['prev_applies'];
    $attended_courses = $_POST['attended_courses'];
    $recent_course = $_POST['recent_course'];
    $reference_1 = $_POST['reference_1'];
    $reference_2 = $_POST['reference_2'];
    $reference_3 = $_POST['reference_3'];

    // Create order and fill the initial data
    $product_id = wc_get_product_id_by_sku( $_POST['product_sku'] );
    $order = wc_create_order();
    $order->set_customer_id( $user_id );
    $order->add_product( get_product( $product_id ) );
    $order->save();

    // Update order data
    $order_id = $order->get_id();
    update_post_meta( $order_id, 'jury_trials', $jury_trials );
    update_post_meta( $order_id, 'verdict_trials', $verdict_trials );
    update_post_meta( $order_id, 'exam_trials', $exam_trials );
    update_post_meta( $order_id, 'hearings', $hearings );
    update_post_meta( $order_id, 'hearings_nature', $hearings_nature );
    update_post_meta( $order_id, 'make_arguments', $make_arguments );
    update_post_meta( $order_id, 'cases_types', $cases_types );
    update_post_meta( $order_id, 'begin_year', $begin_year );
    update_post_meta( $order_id, 'prev_applies', $prev_applies );
    update_post_meta( $order_id, 'attended_courses', $attended_courses );
    update_post_meta( $order_id, 'recent_course', $recent_course );
    update_post_meta( $order_id, 'reference_1', $reference_1 );
    update_post_meta( $order_id, 'reference_2', $reference_2 );
    update_post_meta( $order_id, 'reference_3', $reference_3 );

    $order->update_status( 'wc-on-hold' );

    wp_send_json(['success' => true, 'order_id' => $order_id]);
    wp_die();
}

add_action('wp_ajax_3wk_essay', 'wk_essay');

function wk_essay() {
    $order_id = $_POST['order_id'];
    $essay = $_POST['essay'];

    update_post_meta( $order_id, 'essay', $essay );

    wp_send_json([
        'success' => true
    ]);

    wp_die();
}

add_action('wp_ajax_3wk_final', 'wk_final');

function wk_final() {
    $order_id = $_POST['order_id'];
    $financial_aid = isset( $_POST['financial_aid'] ) && $_POST['financial_aid'] === 'yes';
    $why_aid = $_POST['why_aid'];
    $income_range = $_POST['income_range'];

    update_post_meta( $order_id, 'financial_aid', $financial_aid );
    update_post_meta( $order_id , 'why_aid', $why_aid);
    update_post_meta( $order_id, 'income_range', $income_range );

    $order = wc_get_order($order_id);
    $order->update_status( 'wc-processing' );
    $order->save();

    $user_id = get_current_user_id();
    $doctor_psychologist = isset( $_POST['doctor_psychologist'] ) && $_POST['doctor_psychologist'] === 'yes';
    $doctor_explanation = $_POST['doctor_explanation'];

    update_field('ACF_doctor_psychologist', $doctor_psychologist, 'user_' . $user_id);
    update_field('ACF_doctor_explanation', $doctor_explanation, 'user_' . $user_id);

    handle_3wk_emails($order_id);

    wp_send_json([
        'success' => true,
    ]);

    wp_die();
}

add_action('wp_ajax_grad_user_info', 'grad_user_info');

function grad_user_info() {
    // User Data
    $first_name = $_POST['firstname'];
    $last_name = $_POST['lastname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $birthdate = $_POST['birthdate'];
    $gender = $_POST['gender'];
    $ethnicity = $_POST['ethnicity'];
    $businessname = $_POST['businessname'];
    $businesswebsite = $_POST['businesswebsite'];
    $practice_type = $_POST['practice_type'];
    $billing_address = $_POST['billing_address'];
    $billing_city = $_POST['billing_city'];
    $billing_state = $_POST['billing_state'];
    $billing_postcode = $_POST['billing_postcode'];
    $licensed = isset( $_POST['licensed'] ) && $_POST['licensed'] === 'yes';
    $why_interested =  $_POST['why_interested'];
    $represent_clients = isset( $_POST['represent_clients'] ) && $_POST['represent_clients'] === 'yes';
    $represent_explanation = $_POST['represent_explanation'];
    $crime = isset( $_POST['crime'] ) && $_POST['crime'] === 'yes';
    $crime_explanation = $_POST['crime_explanation'];
    $corporations = isset( $_POST['corporations'] ) && $_POST['corporations'] === 'yes';

    $user_id = get_current_user_id();

    // Update User Data
    wp_update_user([
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'user_email' => $email,
    ]);

    update_user_meta($user_id, 'billing_phone', $phone);
    update_field('ACF_date_of_birth', $birthdate, 'user_' . $user_id);
    update_field('ACF_gender', $gender, 'user_' . $user_id);
    update_field('ACF_ethnicity', $ethnicity, 'user_' . $user_id);
    update_user_meta($user_id, 'billing_company', $businessname);
    update_field('ACF_businesswebsite', $businesswebsite, 'user_' . $user_id);
    update_field('ACF_practice_type', $practice_type, 'user_' . $user_id);
    update_user_meta($user_id, 'billing_address_1', $billing_address);
    update_user_meta($user_id, 'billing_city', $billing_city);
    update_user_meta($user_id, 'billing_state', $billing_state);
    update_user_meta($user_id, 'billing_postcode', $billing_postcode);
    update_field('ACF_licensed', $licensed, 'user_' . $user_id);
    update_field('ACF_why_interested', $why_interested, 'user_' . $user_id);
    update_field('ACF_represent_clients', $represent_clients, 'user_' . $user_id);
    update_field('ACF_represent_explanation', $represent_explanation, 'user_' . $user_id);
    update_field('ACF_crime', $crime, 'user_' . $user_id);
    update_field('ACF_crime_explanation', $crime_explanation, 'user_' . $user_id);
    update_field('ACF_corporations', $corporations, 'user_' . $user_id);

    // Order Data
    $begin_year = $_POST['begin_year'];
    $recent_graduate_course = $_POST['recent_graduate_course'];

    // Create order and fill the initial data
    $product_id = wc_get_product_id_by_sku( $_POST['product_sku'] );
    $order = wc_create_order();
    $order->set_customer_id( $user_id );
    $order->add_product( get_product( $product_id ) );
    $order->save();

    // Update order data
    $order_id = $order->get_id();
    update_post_meta( $order_id, 'begin_year', $begin_year );
    update_post_meta( $order_id, 'recent_graduate_course', $recent_graduate_course );

    $order->update_status( 'wc-on-hold' );

    wp_send_json(['success' => true, 'order_id' => $order_id]);
    wp_die();
}

add_action('wp_ajax_grad_final', 'grad_final');

function grad_final() {
    $order_id = $_POST['order_id'];
    $financial_aid = isset( $_POST['financial_aid'] ) && $_POST['financial_aid'] === 'yes';
    $why_aid = $_POST['why_aid'];
    $income_range = $_POST['income_range'];

    update_post_meta( $order_id, 'financial_aid', $financial_aid );
    update_post_meta( $order_id , 'why_aid', $why_aid);
    update_post_meta( $order_id, 'income_range', $income_range );

    $order = wc_get_order($order_id);
    $order->update_status( 'wc-processing' );
    $order->save();

    $user_id = get_current_user_id();
    $doctor_psychologist = isset( $_POST['doctor_psychologist'] ) && $_POST['doctor_psychologist'] === 'yes';
    $doctor_explanation = $_POST['doctor_explanation'];

    update_field('ACF_doctor_psychologist', $doctor_psychologist, 'user_' . $user_id);
    update_field('ACF_doctor_explanation', $doctor_explanation, 'user_' . $user_id);

    handle_grad_emails($order_id);

    wp_send_json([
        'success' => true,
    ]);

    wp_die();
}

add_action('woocommerce_thankyou', 'handle_new_order', 10, 1);

function handle_new_order ($order_id) {
    $order = wc_get_order($order_id);

    $user_id = $order->get_user_id();

    $financial_aid = get_user_meta( $user_id, 'financial_aid', true);
    $why_aid = get_user_meta( $user_id , 'why_aid', true);
    $income_range = get_user_meta( $user_id, 'income_range', true );

    update_post_meta( $order_id, 'financial_aid', $financial_aid );
    update_post_meta( $order_id, 'why_aid', $why_aid );
    update_post_meta( $order_id, 'income_range', $income_range );

    $is_reg_course = false;

    $items = $order->get_items(); 
        
    foreach ( $items as $item ) {      
        $product_id = $item->get_product_id();  
        if ( has_term( 'reg', 'product_cat', $product_id ) ) {
            $is_reg_course = true;
            break;
        }
    }

    if (get_post_meta( $order_id, 'email_sent', true )) {
        return;
    }

    if ($is_reg_course) {
        $begin_year = get_user_meta( $user_id, 'reg_begin_year', true );
        update_post_meta( $order_id, 'begin_year', $begin_year );
        handle_reg_course_emails($order_id);
        update_post_meta( $order_id, 'email_sent', true );
    }
}

function handle_reg_course_emails($order_id) {
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();

    $email = get_userdata($user_id)->user_email;
    $firstname = get_userdata($user_id)->first_name;
    $lastname = get_userdata($user_id)->last_name;
    $course_name = '';
    $course_start_date = '';
    $course_sku = '';
    $payment_type = '';
    $course_total = '';
    $payment_method = $order->get_payment_method_title();
    $financial_aid = get_post_meta( $order_id, 'financial_aid', true );

    $items = $order->get_items(); 
        
    foreach ( $items as $item ) {      
        $product_id = $item->get_product_id();
        $variation_id = $item['variation_id'];

        if ($variation_id) {
            $payment_type = wc_get_product( $variation_id )->get_attribute( 'payment' );
        } else {
            $payment_type = 'Full';
        }

        if ( has_term( 'reg', 'product_cat', $product_id ) ) {
            $product = wc_get_product($product_id);
            $course_name = $product->get_name();
            $course_start_date = get_field( 'course_start_date', $product_id );
            $course_end_date = get_field( 'course_end_date', $product_id );
            $course_sku = $product->get_sku();
            $course_total = $item->get_total();
            $course_registration_page = get_permalink( get_field( 'registration_page', $product_id ) );
            break;
        }
    }

    ob_start();

    require_once __DIR__ . '/emails/reg-course-client.php';

    $message = ob_get_clean();

    wp_mail( [$email, 'registrar@triallawyerscollege.org','loris@triallawyerscollege.org','nancy@triallawyerscollege.org'], "Course Registration $firstname $lastname, $course_sku", $message, ['content-type: text/html']);

    ob_start();

    require_once __DIR__ . '/emails/reg-admin-email.php';

    $message = ob_get_clean();

    wp_mail( ['nancy@triallawyerscollege.org','registrar@triallawyerscollege.org','loris@triallawyerscollege.org'], 'New member', $message, ['content-type: text/html'] );
}

function add_user_to_students($user_id) {
    $user = get_user_by( 'id', $user_id );

    $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);
    $is_student = false;
    
    if ( !isset( $groups ) || $groups === null || count( $groups ) === 0 ) {
        $groups = [];
        array_push($groups, 'student');
        update_field('field_60085d0ca279f', $groups, 'user_' . $user_id);
    }

    foreach ($groups as $group) {
      if ($group == 'student') {
        $is_student = true;
        break;
      }
    }

    if ($is_student) {
        return;
    }

    $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);
    array_push($groups, 'student');
    update_field('field_60085d0ca279f', $groups, 'user_' . $user_id);
}

function handle_3wk_emails($order_id) {
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();

    $email = get_userdata($user_id)->user_email;
    $firstname = get_userdata($user_id)->first_name;
    $lastname = get_userdata($user_id)->last_name;
    $user_asked_for_aid = get_field( 'financial_aid', $order_id );

    $course_name = '';
    $course_sku = '';

    $items = $order->get_items(); 
    foreach ( $items as $item ) {      
        $product_id = $item->get_product_id();
        $variation_id = $item['variation_id'];

        if ( has_term( '3wk', 'product_cat', $product_id ) ) {
            $product = wc_get_product($product_id);
            $course_name = $product->get_name();
            $course_sku = $product->get_sku();
            break;
        }
    }

    ob_start();

    require_once __DIR__ . '/emails/3wk-course-client.php';

    $message = ob_get_clean();

    $client_message_was_sent = wp_mail( [$email, 'registrar@triallawyerscollege.org','loris@triallawyerscollege.org','nancy@triallawyerscollege.org'], 'Thank you for applying', $message, ['content-type: text/html']);

    ob_start();

    require_once __DIR__ . '/emails/3wk-admin-email.php';

    $message = ob_get_clean();

    wp_mail( ['nancy@triallawyerscollege.org','registrar@triallawyerscollege.org','loris@triallawyerscollege.org'], 'New member', $message, ['content-type: text/html'] );
}

function handle_grad_emails($order_id) {
    $order = wc_get_order($order_id);
    $user_id = $order->get_user_id();

    $email = get_userdata($user_id)->user_email;
    $firstname = get_userdata($user_id)->first_name;
    $lastname = get_userdata($user_id)->last_name;
    $user_asked_for_aid = get_field( 'financial_aid', $order_id );

    $course_name = '';
    $course_sku = '';
    $course_start_date = '';

    $items = $order->get_items(); 
    foreach ( $items as $item ) {      
        $product_id = $item->get_product_id();
        $variation_id = $item['variation_id'];

        if ( has_term( 'grad', 'product_cat', $product_id ) ) {
            $product = wc_get_product($product_id);
            $course_name = $product->get_name();
            $course_start_date = get_field( 'course_start_date', $product_id );
            $course_sku = $product->get_sku();
            break;
        }
    }

    ob_start();

    require_once __DIR__ . '/emails/grad-course-client.php';

    $message = ob_get_clean();

    wp_mail( [$email, 'registrar@triallawyerscollege.org','loris@triallawyerscollege.org','nancy@triallawyerscollege.org'], 'Thank you for applying', $message, ['content-type: text/html']);

    ob_start();

    require_once __DIR__ . '/emails/grad-admin-email.php';

    $message = ob_get_clean();

    wp_mail( ['nancy@triallawyerscollege.org','registrar@triallawyerscollege.org','loris@triallawyerscollege.org'], 'New member', $message, ['content-type: text/html'] );
}

add_action( 'template_redirect', 'block_warrior_archive_for_regular_users' );
function block_warrior_archive_for_regular_users() {
    if (is_page(12341)) {
        if ( !is_user_logged_in() ) {
            wp_redirect( home_url('/alumni-resources/warrior-magazine-subscription/') );
            exit;
        }

        if ( current_user_can('editor') || current_user_can('administrator') ) {
            return;
        }

        $user_groups = get_field( 'ACF_member_groups', 'user_' . get_current_user_id() );

        if (!$user_groups || !in_array( 'warrior', $user_groups )) {
            wp_redirect( home_url('/alumni-resources/warrior-magazine-subscription/') );
            exit;
        }
    }
}

// Add user to a group if the order contains Warrior Magazine or Ranch Club subscription

add_action( 'woocommerce_thankyou', 'add_user_to_group' );

function add_user_to_group( $order_id ){
    $order = wc_get_order( $order_id );
    $user_id = $order->get_user_id();

    $warrior_magazine_subscription_id = 24096;
    $ranch_club_subscription_id = 24152;

    $items = $order->get_items(); 
    foreach ( $items as $item_id => $item ) {
        $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
        
        if ($product_id == $warrior_magazine_subscription_id) {
            $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);
            array_push($groups, 'warrior');
            update_field('field_60085d0ca279f', $groups, 'user_' . $user_id);
        }

        if ($product_id == $ranch_club_subscription_id) {
            $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);
            array_push($groups, 'ranch_club');
            update_field('field_60085d0ca279f', $groups, 'user_' . $user_id);
        }
    }
}

// Remove user from the group when subscription is canceled or expired

add_action('woocommerce_subscription_status_cancelled', 'remove_user_from_group');
add_action('woocommerce_subscription_status_expired', 'remove_user_from_group');

function remove_user_from_group($subscription) {
    $subscription_products = $subscription->get_items();
    $user_id = $subscription->get_user_id();

    $warrior_magazine_subscription_id = 24096;
    $ranch_club_subscription_id = 24152;

    foreach ( $subscription_products as $item_id => $item ) {
        $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
        
        if ($product_id == $warrior_magazine_subscription_id) {
            $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);
            array_push($groups, 'warrior');

            $new_groups = [];

            foreach ($groups as $group) {
                if ($group !== 'warrior') {
                    array_push($new_groups, $group);
                } else {
                    continue;
                }
            }

            update_field('field_60085d0ca279f', $new_groups, 'user_' . $user_id);
        }

        if ($product_id == $ranch_club_subscription_id) {
            $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);
            array_push($groups, 'warrior');

            $new_groups = [];

            foreach ($groups as $group) {
                if ($group !== 'ranch_club') {
                    array_push($new_groups, $group);
                } else {
                    continue;
                }
            }

            update_field('field_60085d0ca279f', $new_groups, 'user_' . $user_id);
        }
    }
}

// Begin square featured image in Divi Blog Module

function ld_blog_square_image_width($width) {
	return 300;
}
function ld_blog_square_image_height($height) {
	return 300;
}
add_filter( 'et_pb_blog_image_width', 'ld_blog_square_image_width' );
add_filter( 'et_pb_blog_image_height', 'ld_blog_square_image_height' );

// End stop square featured image in Divi Blog Module

add_action( 'wp_footer', 'hide_auth_buttons_for_authorized_user' );
function hide_auth_buttons_for_authorized_user () {
    if ( is_user_logged_in() ) {
        ?>
            <script>
                jQuery(document).ready(function ($) {
                    $('#et-secondary-nav li').each(function () {
                        let isAuthLink = $(this).find('a').text().toLowerCase().includes('register') || $(this).find('a').text().toLowerCase().includes('log in');

                        if (isAuthLink) {
                            $(this).remove();
                        }
                    })
                    
                    $('#et-secondary-nav').prepend('<li class="menu-item"><a href="<?php echo wp_logout_url(); ?>">Log Out<a/></li>');
                });
            </script>
        <?php
    }
}

add_action( 'template_redirect', 'block_students_directory_for_non_students' );

function block_students_directory_for_non_students() {
    if ( !is_page(1348) ) {
        return;
    }


    if ( current_user_can('editor') || current_user_can('administrator') ) {
        return;
    }

    $user_id = get_current_user_id();
    $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);

    if ( !isset( $groups ) || $groups === null || count( $groups ) === 0 ) {
        wp_redirect( home_url() . '/protected' );
    }

    $is_student = false;

    foreach ($groups as $group) {
        if ($group == 'student') {
            $is_student = true;
            break;
        }
    }

    if (!$is_student) {
        wp_redirect( home_url() . '/protected' );
    }
}

add_action( 'template_redirect', 'block_pages_for_non_warrior_subscribers' );

function block_pages_for_non_warrior_subscribers() {
    if ( !is_page(12341) && !is_page(12327) ) {
        return;
    }


    if ( current_user_can('editor') || current_user_can('administrator') ) {
        return;
    }

    $user_id = get_current_user_id();
    $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);

    if ( !isset( $groups ) || $groups === null || count( $groups ) === 0 ) {
        wp_redirect( home_url() . '/protected' );
    }

    $is_subscriber = false;

    foreach ($groups as $group) {
        if ($group == 'warrior') {
            $is_subscriber = true;
            break;
        }
    }

    if (!$is_subscriber) {
        wp_redirect( home_url() . '/protected' );
    }
}

add_action( 'woocommerce_order_status_completed', 'add_user_to_students_after_order_completed');

function add_user_to_students_after_order_completed ($order_id) {
    $order = wc_get_order( $order_id );
    $user = $order->get_user();
    $user_id = $order->get_user_id();
    add_user_to_students($user_id);
}

// Course Page Shortcodes

require_once get_stylesheet_directory() . '/course-shortcodes/course-shortcodes.php';

// Custom Avatars Hook


function filter_get_avatar_url( $url, $id_or_email, $args ) { 
    global $blog_id;
    global $wpdb;
    $user_has_new_avatar = get_the_author_meta( $wpdb->get_blog_prefix($blog_id).'user_avatar', $id_or_email );

    if ( $user_has_new_avatar && strlen( $user_has_new_avatar ) > 0 ) {
        return $url;
    }


    $old_avatar_filename = get_user_meta( $id_or_email, 'old_avatar', true);

    if ( $old_avatar_filename && strlen( $old_avatar_filename ) > 0 ) {
        $upload_directory_url = wp_get_upload_dir()['baseurl'];
        return $upload_directory_url . '/Faculty/' . $old_avatar_filename;
    }

    return $url; 
}; 

// add the filter 
add_filter( 'get_avatar_url', 'filter_get_avatar_url', 10, 3 ); 

function add_to_cart_subscribe_validation( $passed, $product_id ) { 
    $user_id = get_current_user_id();

    if ($user_id == 0) {
        return $passed;
    }

    $warrior_magazine_subscription_id = 24096;
    $ranch_club_subscription_id = 24152;

    if ($product_id == $warrior_magazine_subscription_id) {
        $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);

        if (!$groups || count($groups) == 0) {
            $passed = true;
        }
        
        if ( in_array( 'warrior', $groups ) ) {
            wc_add_notice( __( 'Cannot add to cart, you are an active subscriber!', 'woocommerce' ), 'error' );
            $passed = false;
        }
    }

    if ($product_id == $ranch_club_subscription_id) {
        $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);

        if (!$groups || count($groups) == 0) {
            $passed = true;
        }
        
        if ( in_array( 'ranch_club', $groups ) ) {
            wc_add_notice( __( 'Cannot add to cart, you are an active subscriber!', 'woocommerce' ), 'error' );
            $passed = false;
        }
    }

    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'add_to_cart_subscribe_validation', 10, 5 );  

add_action('subscriptions_created_for_order', 'new_subscription_email_notification');

function new_subscription_email_notification($order) {
    $user = $order->get_user();
    
    if ($user) {
        $first_name = get_user_meta( $user->ID, 'first_name', true );
        $last_name = get_user_meta( $user->ID, 'last_name', true );
    }

    $warrior_magazine_subscription_id = 24096;
    $ranch_club_subscription_id = 24152;

    $warrior_subscriber = false;
    $ranch_club_subscriber = false;

    $items = $order->get_items(); 
    foreach ( $items as $item_id => $item ) {
        $product_id = $item->get_variation_id() ? $item->get_variation_id() : $item->get_product_id();
        
        if ($product_id == $warrior_magazine_subscription_id) {
            $warrior_subscriber = true;
        }

        if ($product_id == $ranch_club_subscription_id) {
            $ranch_club_subscriber = true;
        }
    }

    $message = '';

    $message .= "$first_name $last_name";

    if ($warrior_subscriber && !$ranch_club_subscriber) {
        $message .= " has subscribed for warrior magazine";
    }

    if ($ranch_club_subscriber && !$warrior_subscriber) {
        $message .= " has subscribed for ranch club";
    }

    if ($ranch_club_subscriber && $warrior_subscriber) {
        $message .= " has subscribed for warrior magazine and ranch club";
    }

    if (!$ranch_club_subscriber && !$warrior_subscriber) {
        return;
    }

    $message .= "<br/>";

    $user_email = get_userdata($user->ID)->user_email;
    $user_zip = get_user_meta($user->ID, 'billing_postcode', true);
    $user_address = get_user_meta($user->ID, 'billing_address_1', true);
    $user_state = get_user_meta($user->ID, 'billing_state', true);
    $user_phone = get_user_meta($user->ID, 'billing_phone', true);

    $message .= "Email: " . $user_email . "<br/>"; 
    $message .= "Phone: " . $user_phone . "<br/>"; 
    $message .= "State: " . $user_state . "<br/>"; 
    $message .= "ZIP: " . $user_zip . "<br/>"; 
    $message .= "Address: " . $user_address . "<br/>"; 

    wp_mail( ['nancy@triallawyerscollege.org','registrar@triallawyerscollege.org','loris@triallawyerscollege.org'], 'New Subscriber', $message, ['content-type: text/html'] );
}

add_action( 'woocommerce_cart_calculate_fees', 'add_ranch_club_discount' ); 

function add_ranch_club_discount() { 
    $discount_price = 200; 
    $user = wp_get_current_user();
    $user_id = $user->ID;

    $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);
    $is_ranch_club_member = false;

    if ( !isset( $groups ) || $groups === null || count( $groups ) === 0 ) {
      return;
    }

    foreach ($groups as $group) {
      if ($group == 'ranch_club') {
        $is_ranch_club_member = true;
        break;
      }
    }

    if ( !$is_ranch_club_member ) {
        return;
    }

    $discounted_products_counter = 0;

    // We store all the products' IDs in this array so we don't have to give customers a discount multiple times for the same product
    // In other words, one discounted product = one $200 discount
    $discounted_products = [];

    foreach ( WC()->cart->get_cart() as $cart_item ) {
        $product_id = $cart_item['product_id'];

        if ( in_array( $product_id, $discounted_products ) ) {
            continue;
        }

        if ( get_field( 'ranch_club_discount', $product_id ) ) {
            array_push( $discounted_products, $product_id );
            $discounted_products_counter++;
        }
    }
    
    if ($discounted_products_counter === 0) {
        return;
    }

    $total_discount = $discounted_products_counter * $discount_price;

    WC()->cart->add_fee( 'Ranch Club Discount', -$total_discount, true, 'standard' ); 
}

// Function to check if the user already registered/applied for a certain course.
// Used in registration and application forms at the step-0
function user_already_registered_for_a_course( $user_id, $course_sku ) {
    $customer_orders = get_posts( array(
        'numberposts' => -1,
        'meta_key'    => '_customer_user',
        'meta_value'  => $user_id,
        'post_type'   => wc_get_order_types(),
        'post_status' => [ 'wc-completed', 'wc-processing' ]
    ) );

    $course_id = wc_get_product_id_by_sku( $course_sku );

    foreach ( $customer_orders as $customer_order ) {
        $order = wc_get_order( $customer_order->ID );

        foreach ( $order->get_items() as $item_id => $item ) {
            $product_id = $item->get_product_id();
            $variation_id = $item->get_variation_id();

            if ( $product_id == $course_id || $variation_id == $course_id ) {
                return true;
            } else {
                continue;
            }
        }
    }

    return false;
};

// Allow regular users to access wp-admin
add_filter( 'woocommerce_prevent_admin_access', '__return_false' );

// Hide dashboard widgets for the regular users 
function remove_dashboard_widgets(){
    global $wp_meta_boxes;
    
    if ( !( current_user_can('editor') || current_user_can('administrator') ) ) {
        unset($wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']);
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('jetpack_summary_widget', 'dashboard', 'normal');
        remove_meta_box('dashboard_primary', 'dashboard', 'normal');
    }
}
add_action('wp_dashboard_setup', 'remove_dashboard_widgets');

// Hide JetPack menu item for the regular users
function pinkstone_remove_jetpack() {
	if( class_exists( 'Jetpack' ) && !( current_user_can('editor') || current_user_can('administrator') ) ) {
		remove_menu_page( 'jetpack' );
	}
}
add_action( 'admin_init', 'pinkstone_remove_jetpack' );

// Hide "Help" button at the top of the screen for the regular users
add_filter('contextual_help_list','contextual_help_list_remove');
function contextual_help_list_remove(){
    global $current_screen;
    if ( !current_user_can( 'editor' ) || !current_user_can( 'administrator' ) ) {
        $current_screen->remove_help_tabs();
    }
}

add_filter('acf/get_fields', 'hide_private_fields', 20, 2);
function hide_private_fields($fields, $parent) {
    if ( current_user_can('editor') || current_user_can('administrator') )  {
        return $fields;
    }

    if ( $parent['title'] !== 'Member Information' ) {
        return $fields;
    }

    $hidden_fields = ['ACF_member_groups', 'ACF_tlc_region', 'ACF_approved', 'ACF_student_id', 'ACF_comments', 'ACF_created_date', 'ACF_graduation_month', 'ACF_graduation_year'];

    foreach ( $fields as $index => $field ) {
        if ( in_array( $field['name'], $hidden_fields ) ) {
            unset( $fields[$index] );
        }
    }

    return $fields;
}

function remove_admin_bar() {
    if ( is_user_logged_in() ) {
        return true;
    }
    return false;
}

add_filter('show_admin_bar', 'remove_admin_bar');

function update_user_region( $user_id ) {
    $state = get_user_meta( $user_id, 'billing_state', true);
    $region_state_relation = [
        'AK' => 'Region 1',
        'WA' => 'Region 1',
        'MT' => 'Region 1',
        'OR' => 'Region 1',
        'ID' => 'Region 1',

        'CA' => 'Region 2',
        'NV' => 'Region 2',
        'HI' => 'Region 2',

        'UT' => 'Region 3',
        'AZ' => 'Region 3',
        'NM' => 'Region 3',
        'WY' => 'Region 3',
        'CO' => 'Region 3',

        'TX' => 'Region 4',

        'OK' => 'Region 5',
        'AR' => 'Region 5',
        'TN' => 'Region 5',
        'MS' => 'Region 5',
        'LA' => 'Region 5',

        'ND' => 'Region 6',
        'MN' => 'Region 6',
        'SD' => 'Region 6',
        'NE' => 'Region 6',
        'IA' => 'Region 6',
        'KS' => 'Region 6',
        'MO' => 'Region 6',
        
        'WI' => 'Region 7',
        'MI' => 'Region 7',
        'IL' => 'Region 7',
        'IN' => 'Region 7',
        'OH' => 'Region 7',
        'KY' => 'Region 7',

        'NY' => 'Region 8',
        'VT' => 'Region 8',
        'ME' => 'Region 8',
        'NH' => 'Region 8',
        'MA' => 'Region 8',
        'RI' => 'Region 8',
        'CT' => 'Region 8',

        'WV' => 'Region 9',
        'VA' => 'Region 9',
        'PA' => 'Region 9',
        'NJ' => 'Region 9',
        'DC' => 'Region 9',
        'DE' => 'Region 9',
        'MD' => 'Region 9',

        'AL' => 'Region 10',
        'GA' => 'Region 10',
        'SC' => 'Region 10',
        'NC' => 'Region 10',
        'VI' => 'Region 10',
        'PR' => 'Region 10',
        'FL' => 'Region 10',
    ];

    if ( isset( $region_state_relation[$state] ) ) {
        $region = $region_state_relation[$state];
        update_field( 'ACF_tlc_region', $region, 'user_' . $user_id );
    }
}

add_action( 'woocommerce_customer_save_address', 'update_user_region' );

function user_is_warrior_subscriber($user_id) {
    if ( $user_id === 0 ) return false;

    $groups = get_field('field_60085d0ca279f', 'user_' . $user_id);
    $is_warrior_subscriber = false;

    if ( !isset( $groups ) || $groups === null || count( $groups ) === 0 ) {
      return;
    }

    foreach ($groups as $group) {
      if ($group == 'warrior') {
        $is_warrior_subscriber = true;
        break;
      }
    }

    return $is_warrior_subscriber;
}

function show_my_account_link_in_the_toolbar($wp_admin_bar) {
    $args = array(
        'id' => 'woo-account',
        'title' => 'Billing Account', 
        'href' => '/my-account', 
        'meta' => array(
            'title' => 'Go to my billing account'
        )
    );
    $wp_admin_bar->add_node($args);
}
add_action('admin_bar_menu', 'show_my_account_link_in_the_toolbar', 999999);

add_action( 'admin_menu', 'wpse_91693_register' );

function wpse_91693_register() {
    add_menu_page(
        'Billing Account',     // page title
        'Billing Account',     // menu title
        'read',   // capability
        'billing-account',     // menu slug
        'redirect_to_billing_account' // callback function
    );
}
function redirect_to_billing_account() {
    wp_redirect('/my-account');
}

function smallenvelop_login_message( $message ) {
    if ( empty($message) ){
        return "<p style='text-align:center;margin-bottom:10px;'><strong style='color:#e33030;font-size:16px;'>In order to log in to the new website for the first time, please reset your password <a href='/wp-login.php?action=lostpassword' style='color:#e33030;'>here</a>. Thank you! <br> - The Trial Lawyers College Team.</strong></p>";
    } else {
        return $message;
    }
}

add_filter( 'login_message', 'smallenvelop_login_message' );

function my_login_logo() {
    ?>
        <style type="text/css">
            #login h1 a, .login h1 a {
                background-image: url("<?php echo get_stylesheet_directory_uri(); ?>/tlc login logo.png");
                height: 50px;
                width: 300px;
                background-size: contain;
                background-repeat: no-repeat;
                padding-bottom: 30px;
            }
        </style>
    <?php
}
add_action( 'login_enqueue_scripts', 'my_login_logo' );

add_filter( 'default_checkout_billing_country', 'change_default_checkout_country' );
add_filter( 'default_checkout_shipping_country', 'change_default_checkout_country' );

function change_default_checkout_country() {
    return 'US';
}

add_action( 'user_register', 'handle_new_member_registration' );
function handle_new_member_registration($user_id){
    update_field( 'ACF_member_groups', ['registered'], "user_$user_id" );
}

function change_default_login_redirect_url() {
  return get_home_url();
}

add_filter( 'login_redirect', 'change_default_login_redirect_url' );
add_filter( 'woocommerce_login_redirect', 'change_default_login_redirect_url' );