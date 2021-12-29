<?php

/**
 * Plugin Name:       WooCommerce Orders Filter
 * Version:           1.0
 * Author:            Artemy Kaydash
 */

require_once plugin_dir_path( __FILE__ ) . 'csv-generator.php';

class WC_Orders_Filter {
  public function __construct() {
    add_action( 'admin_enqueue_scripts', [$this, 'enqueue_assets'] );
    add_action( 'manage_posts_extra_tablenav', [$this, 'render_filter_form'] );
    add_action( 'pre_get_posts', [$this, 'filter_orders'], 9999 );
    add_action( 'admin_init', [$this, 'return_orders_csv'] );
  }

  public function enqueue_assets($hook) {
    global $post_type;
    if ($hook === 'edit.php' && $post_type === 'shop_order') {
      $this->enqueue_scripts();
      $this->enqueue_styles();
    }
  }

  private function enqueue_scripts() {
    wp_enqueue_script( 'wcof-select2' , plugin_dir_url( __FILE__ ) . 'assets/js/select2.min.js' , ['jquery'], null, true );
    wp_enqueue_script( 'wcof-main-scripts' , plugin_dir_url( __FILE__ ) . 'assets/js/main.js' , ['wcof-select2'], current_time('string'), true );
  }

  private function enqueue_styles() {
    wp_enqueue_style( 'wcof-select2-styles', plugin_dir_url( __FILE__ ) . 'assets/css/select2.min.css', [], null);
    wp_enqueue_style( 'wcof-main-styles', plugin_dir_url( __FILE__ ) . 'assets/css/main.css', [], current_time('string'));
  }

  private function get_products() {
    $products = [];
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'orderby'=> 'title', 
        'order' => 'ASC',
        'tax_query' => [
          'relation' => 'AND',
          [
            'taxonomy' => 'product_cat',
            'field' => 'slug',
            'terms' => [ 'bundles', 'clothing', 'donations', 'gifts', 'merchandise', 'premium-content', 'uncategorized' ],
            'operator' => 'NOT IN'
          ]
        ]
    );

    $loop = new WP_Query( $args );

    while ( $loop->have_posts() ) : $loop->the_post();
        global $product;
        $title = get_the_title();
        $id = get_the_ID();
        array_push($products, [
          'title' => $title,
          'id' => $id
        ]);
    endwhile;

    wp_reset_query();
    return $products;
  }

  private function get_order_statuses() {
    $statuses = wc_get_order_statuses();
    return $statuses;
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

  private function get_orders_by_product_id($product_id) {

    global $wpdb;

    return $wpdb->get_col( "
        SELECT DISTINCT woi.order_id
        FROM {$wpdb->prefix}woocommerce_order_itemmeta as woim, 
              {$wpdb->prefix}woocommerce_order_items as woi, 
              {$wpdb->prefix}posts as p
        WHERE  woi.order_item_id = woim.order_item_id
        AND woi.order_id = p.ID
        AND woim.meta_key IN ( '_product_id', '_variation_id' )
        AND woim.meta_value LIKE '$product_id'
        ORDER BY woi.order_item_id DESC"
    );

  }

  private function get_orders_by_status($status) {
    $order_ids = [];
    $orders = wc_get_orders([
      'limit' => '-1',
      'type' => 'shop_order',
      'status' => [$status]
    ]);

    return $order_ids;
  }

  public function render_filter_form() {
    global $post_type;

    if ($post_type !== 'shop_order') return;

    $products = $this->get_products();
    $statuses = $this->get_order_statuses();
    $groups = $this->get_groups();
    $states = $this->get_states();
    $regions = $this->get_regions();

    require_once plugin_dir_path( __FILE__ ) . 'views/order-filter.php';
  }

  private function get_users_by_group($slug) {
    $user_ids = [];
    $meta_query = [
      'relation' => 'AND'
    ];

    $meta_query[] = [
      'key' => 'ACF_member_groups',
      'value' => $slug,
      'compare' => 'LIKE'
    ];

    $users = get_users([
      'number' => -1,
      'offset' => 0,
      'meta_query' => $meta_query
    ]);

    if (count($users) > 0) {
      foreach ($users as $user) {
        array_push($user_ids, $user->ID);
      }
    }

    return $user_ids;
  }

  private function get_users_by_state($state) {
    $user_ids = [];
    $args = [
      'meta_key' => 'billing_state',
      'meta_value' => $state
    ];

    $users = get_users($args);

    if (count($users) > 0) {
      foreach ($users as $user) {
        array_push($user_ids, $user->ID);
      }
    }

    return $user_ids;
  }

  private function get_users_by_region($slug) {
    $user_ids = [];

    $meta_query = [
      'key' => 'ACF_tlc_region',
      'value' => $slug,
      'compare' => 'LIKE'
    ];

    $users = get_users([
      'number' => -1,
      'offset' => 0,
      'meta_query' => $meta_query
    ]);

    if (count($users) > 0) {
      foreach ($users as $user) {
        array_push($user_ids, $user->ID);
      }
    }

    return $user_ids;
  }

  public function filter_orders($query) {
    if ( ! is_admin() ) {
      return;
    }

    global $pagenow;

    if ( 'edit.php' === $pagenow && 'shop_order' === $query->query['post_type'] ) {
      $meta_query = [
        'relation' => 'AND'
      ];

      // Filter by students
      if ( isset($_GET['_customer_user']) && strlen($_GET['_customer_user']) > 0 ) {
        $user_id = [$_GET['_customer_user']];
      }

      if ( isset($_GET['group']) && $_GET['group'] !== 'all' ) {
        $users_by_group = $this->get_users_by_group($_GET['group']);
      }

      if( isset($_GET['state']) && $_GET['state'] !== 'all' ) {
        $users_by_state = $this->get_users_by_state($_GET['state']);
      }

      if( isset($_GET['region']) && $_GET['region'] !== 'all' ) {
        $users_by_region = $this->get_users_by_region($_GET['region']);
      }

      $user_filters = array_filter([$user_id, $users_by_group, $users_by_state, $users_by_region], function ($arr) {
        return $arr !== null;
      });

      if (count($user_filters)  === 1) {
        $common_user_elements = array_pop($user_filters);
      }

      if (count($user_filters) > 1) {
        $common_user_elements = call_user_func_array('array_intersect', $user_filters);
      }

      if (isset($common_user_elements)) {
        if (count($common_user_elements) === 0) {
          $common_user_elements = ['empty'];
        }

        $meta_query[] = [
          'key'=>'_customer_user',
          'value'=> $common_user_elements,
          'compare'=>'IN',
        ];

        $query->set('meta_query', $meta_query);
      }

      // Filter by orders data
      if ( isset($_GET['course_id']) && $_GET['course_id'] !== 'all' ) {
        $product_id = $_GET['course_id'];
        $orders = $this->get_orders_by_product_id($product_id);

        if (count($orders) === 0) {
          $orders = ['empty'];
        }
        $query->set('post__in', $orders);
      }

      if(isset($_GET['status'])) {
        $query->set('post_status', $_GET['status']);
      }

      // Filter by date

      if( strlen($_GET['start_date']) > 0 && strlen($_GET['end_date']) === 0 ) {
        $start_date_array = explode('/', $_GET['start_date']);
        $start_month = $start_date_array[0];
        $start_day = $start_date_array[1];
        $start_year = $start_date_array[2];

        $date_query = [
          'after' => [
            'year' => $start_year,
            'month' => $start_month,
            'day' => $start_day
          ]
        ];
      }

      if( strlen($_GET['end_date']) > 0 && strlen($_GET['start_date']) === 0 ) {
        $end_date_array = explode('/', $_GET['end_date']);
        $end_month = $end_date_array[0];
        $end_day = $end_date_array[1];
        $end_year = $end_date_array[2];

        $date_query = [
          'before' => [
            'year' => $end_year,
            'month' => $end_month,
            'day' => $end_day
          ]
        ];
      }

      if( strlen($_GET['end_date']) > 0 && strlen($_GET['start_date']) > 0 ) {
        $start_date_array = explode('/', $_GET['start_date']);
        $start_month = $start_date_array[0];
        $start_day = $start_date_array[1];
        $start_year = $start_date_array[2];

        $end_date_array = explode('/', $_GET['end_date']);
        $end_month = $end_date_array[0];
        $end_day = $end_date_array[1];
        $end_year = $end_date_array[2];

        $date_query = [
          'after' => [
            'year' => $start_year,
            'month' => $start_month,
            'day' => $start_day
          ],
          'before' => [
            'year' => $end_year,
            'month' => $end_month,
            'day' => $end_day
          ],
          'inclusive' => true
        ];
      }

      if(isset($date_query)) {
        $query->set('date_query', $date_query);
      }
    }
  }

  public function get_orders() {
    $meta_query = [
      'relation' => 'AND'
    ];

    $args = [
      'posts_per_page' => -1,
      'post_type'   => 'shop_order',
      'post_status'    => 'any',
      'fields' => 'ids'
    ];

    // Filter by students
    if ( isset($_GET['_customer_user']) && strlen($_GET['_customer_user']) > 0 ) {
      $user_id = [$_GET['_customer_user']];
    }

    if ( isset($_GET['group']) && $_GET['group'] !== 'all' ) {
      $users_by_group = $this->get_users_by_group($_GET['group']);
    }

    if( isset($_GET['state']) && $_GET['state'] !== 'all' ) {
      $users_by_state = $this->get_users_by_state($_GET['state']);
    }

    if( isset($_GET['region']) && $_GET['region'] !== 'all' ) {
      $users_by_region = $this->get_users_by_region($_GET['region']);
    }

    $user_filters = array_filter([$user_id, $users_by_group, $users_by_state, $users_by_region], function ($arr) {
      return $arr !== null;
    });

    if (count($user_filters)  === 1) {
      $common_user_elements = array_pop($user_filters);
    }

    if (count($user_filters) > 1) {
      $common_user_elements = call_user_func_array('array_intersect', $user_filters);
    }

    if (isset($common_user_elements)) {
      if (count($common_user_elements) === 0) {
        $common_user_elements = ['empty'];
      }

      $meta_query[] = [
        'key'=>'_customer_user',
        'value'=> $common_user_elements,
        'compare'=>'IN',
      ];

      $args['meta_query'] = $meta_query;
    }

    // Filter by orders data
    if ( isset($_GET['course_id']) && $_GET['course_id'] !== 'all' ) {
      $product_id = $_GET['course_id'];
      $orders = $this->get_orders_by_product_id($product_id);

      if (count($orders) === 0) {
        $orders = ['empty'];
      }
      $args['post__in'] = $orders;
    }

    if(isset($_GET['status'])) {
      $args['post_status'] = $_GET['status'];
    }

    // Filter by date

    if( strlen($_GET['start_date']) > 0 && strlen($_GET['end_date']) === 0 ) {
      $start_date_array = explode('/', $_GET['start_date']);
      $start_month = $start_date_array[0];
      $start_day = $start_date_array[1];
      $start_year = $start_date_array[2];

      $date_query = [
        'after' => [
          'year' => $start_year,
          'month' => $start_month,
          'day' => $start_day
        ]
      ];
    }

    if( strlen($_GET['end_date']) > 0 && strlen($_GET['start_date']) === 0 ) {
      $end_date_array = explode('/', $_GET['end_date']);
      $end_month = $end_date_array[0];
      $end_day = $end_date_array[1];
      $end_year = $end_date_array[2];

      $date_query = [
        'before' => [
          'year' => $end_year,
          'month' => $end_month,
          'day' => $end_day
        ]
      ];
    }

    if( strlen($_GET['end_date']) > 0 && strlen($_GET['start_date']) > 0 ) {
      $start_date_array = explode('/', $_GET['start_date']);
      $start_month = $start_date_array[0];
      $start_day = $start_date_array[1];
      $start_year = $start_date_array[2];

      $end_date_array = explode('/', $_GET['end_date']);
      $end_month = $end_date_array[0];
      $end_day = $end_date_array[1];
      $end_year = $end_date_array[2];

      $date_query = [
        'after' => [
          'year' => $start_year,
          'month' => $start_month,
          'day' => $start_day
        ],
        'before' => [
          'year' => $end_year,
          'month' => $end_month,
          'day' => $end_day
        ],
        'inclusive' => true
      ];
    }

    if(isset($date_query)) {
      $args['date_query'] = $date_query;
    }

    $query = new WP_Query($args);

    return $query;
  }

  public function return_orders_csv() {
    if (!is_admin()) {
      return;
    }

    if ( !current_user_can('editor') && !current_user_can('administrator') ) {
      return;
    }

    if ( isset($_GET['wcof-export']) ) {
      global $wcof_csv_generator;
      header('Content-Disposition: attachment; filename="orders.csv";');
      $query = $this->get_orders();

      $wcof_csv_generator->print_headings();

      if ( $query->have_posts() ) {
        $i = 0;
        while ( $query->have_posts() ) {
          $i++;
          $query->the_post();
          $order_id = get_the_ID();
          $wcof_csv_generator->print_order($order_id);
        }
      }

      wp_reset_postdata();

      exit;
    }
  }
}

$wc_orders_filter = new WC_Orders_Filter;