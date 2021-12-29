<?php

add_shortcode('course-food-info', 'render_course_food_info');

function render_course_food_info() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return 'N/A';
  }

  $food_info = get_field('food_lodging_&_roommates', $course_id);

  if (!$food_info) {
    return 'N/A';
  }
  
  return trim( $food_info );
}

add_shortcode('course-travel-info', 'render_course_travel_info');

function render_course_travel_info() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return 'N/A';
  }

  $travel_info = get_field('travel_information', $course_id);

  if (!$travel_info) {
    return 'N/A';
  }

  return trim( $travel_info );
}

add_shortcode('course-faculty-text', 'render_course_faculty_text');

function render_course_faculty_text() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $faculty_text = get_field('faculty_text', $course_id);

  if (!$faculty_text) {
    return '';
  }

  return trim( $faculty_text );
}

add_shortcode('course-faculty-subtitle', 'render_course_faculty_subtitle');

function render_course_faculty_subtitle() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $faculty_subtitle = get_field('faculty_sub_title', $course_id);

  if (!$faculty_subtitle) {
    return '';
  }

  return trim( $faculty_subtitle );
}

add_shortcode('course-schedule-text', 'render_course_schedule_text');

function render_course_schedule_text() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $schedule_text = get_field('schedule_text', $course_id);

  if (!$schedule_text) {
    return '';
  }

  return trim( $schedule_text );
}

add_shortcode('course-schedule-subtitle', 'render_course_schedule_subtitle');

function render_course_schedule_subtitle() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $schedule_subtitle = get_field('schedule_sub_title', $course_id);

  if (!$schedule_subtitle) {
    return '';
  }

  return trim( $schedule_subtitle );
}

add_shortcode('course-cle-text', 'render_course_cle_text');

function render_course_cle_text() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $cle_text = get_field('cle_text', $course_id);

  if (!$cle_text) {
    return '';
  }

  return trim( $cle_text );
}

add_shortcode('course-materials', 'render_course_materials');

function render_course_materials() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $materials = get_field('course_material_prerequisites', $course_id);

  if (!$materials) {
    return '';
  }

  return trim( $materials );
}

add_shortcode('course-name', 'render_course_name');

function render_course_name() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $product = wc_get_product( $course_id );

  if (!$product) {
    return '';
  }

  $product_name = $product->get_name();

  return trim( $product_name );
}

add_shortcode('course-start-date', 'render_course_start_date');

function render_course_start_date() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $start_date = get_field('course_start_date', $course_id);

  if (!$start_date) {
    return '';
  }

  return trim( $start_date );
}

add_shortcode('course-end-date', 'render_course_end_date');

function render_course_end_date() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $end_date = get_field('course_end_date', $course_id);

  if (!$end_date) {
    return '';
  }

  return trim( $end_date );
}

add_shortcode('course-tuition', 'render_course_tuition');

function render_course_tuition() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $tuition = get_field('tuition', $course_id);

  if (!$tuition) {
    return '';
  }

  return trim( $tuition );
}

add_shortcode('course-location', 'render_course_location');

function render_course_location() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $location = get_field('location', $course_id);

  if (!$location) {
    return '';
  }

  return trim( $location );
}

add_shortcode('course-about-text', 'render_course_about_text');

function render_course_about_text() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $about_text = get_field('about_text', $course_id);

  if (!$about_text) {
    return '';
  }

  return trim( $about_text );
}

add_shortcode('course-about-subtitle', 'render_course_about_subtitle');

function render_course_about_subtitle() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $about_subtitle = get_field('about_sub_title', $course_id);

  if (!$about_subtitle) {
    return '';
  }

  return trim( $about_subtitle );
}

add_shortcode('course-location-text', 'render_course_location_text');

function render_course_location_text() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $location_text = get_field('location_text', $course_id);

  if (!$location_text) {
    return '';
  }

  return trim( $location_text );
}

add_shortcode('course-location-subtitle', 'render_course_location_subtitle');

function render_course_location_subtitle() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $location_subtitle = get_field('location_sub_title', $course_id);

  if (!$location_subtitle) {
    return '';
  }

  return trim( $location_subtitle );
}

add_shortcode('course-sku', 'render_course_sku');

function render_course_sku() {
  global $post;
  $course_id = get_field('course', $post->ID);

  if (!$course_id) {
    return '';
  }

  $product = wc_get_product( $course_id );
  $product_sku = $product->get_sku();

  if (!$product_sku) {
    return '';
  }

  return $product_sku;
}