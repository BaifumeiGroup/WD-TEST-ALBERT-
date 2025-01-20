<?php
/*
Plugin Name: Custom Woocommerce
Description: A plugin that allows a non-technical user (e.g., marketing team) to upload and manage rotating banners directly from the WordPress admin dashboard, without requiring developer intervention.
Version: 1.0
Author: Baifumei
*/

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once('inc/banner-cpt.php');
require_once('inc/admin.php');
require_once('inc/frontend.php');

