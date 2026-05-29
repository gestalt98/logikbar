<?php
/**
 * Kopa Framework Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @author      Kopatheme
 * @category    Core
 * @package     KopaFramework/Functions
 * @since       1.0.0
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Include core functions
// formating functions
include_once( 'kopa-formatting-functions.php' );

// utility functions for getting options from 
// theme options, sidebar & layout manager
include_once( 'kopa-settings-functions.php' );

// google fonts, system fonts utility functions
include_once( 'kopa-fonts-functions.php' );