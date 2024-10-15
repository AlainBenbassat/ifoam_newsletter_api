<?php
/**
* Plugin Name: IFOAM Newsletter API
* Plugin URI: https://github.com/AlainBenbassat/ifoam_newsletter_api
* Description: Processes newsletter or press release subscriptions coming from the IFOAM website
* Version: 3.0
* Author: Alain Benbassat
* Author URI: https://www.businessandcode.eu/
**/

if ( ! defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function ifoam_newsletter_api() {
  if (!is_admin()) {
    require_once __DIR__ . '/src/ifoam_newsletter_controller.php';
    $response = IfoamNewsletterController::process();
    wp_send_json($response);
  }
}

add_shortcode('newsletter-api', 'ifoam_newsletter_api');

