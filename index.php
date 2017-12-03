<?php
/*
Plugin Name: Evandis Widget
Plugin URI:  https://evandis.fr/widget/
Description: Displays the Evandis texts in a widget.
Version:     0.1
Author:      Evandis
Author URI:  http://www.evandis.com
License:     GPL3 or later
Domain Path: /languages
Text Domain: evandis-widget
*/

/*  Copyright 2007-2017 Florent Maillefaud (email: contact at restezconnectes.fr)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

defined( 'ABSPATH' )
	or die( 'No direct load ! ' );

define( 'EVANW_DIR', plugin_dir_path( __FILE__ ) );
define( 'EVANW_URL', plugin_dir_url( __FILE__ ) );
define( 'EVANW_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

if( !defined( 'EVANW_VERSION' )) { define( 'EVANW_VERSION', '0.1' ); }

require EVANW_DIR . 'classes/class.php';
require EVANW_DIR . 'includes/widget.php';

add_action( 'plugins_loaded', '_evw_load' );
function _evw_load() {
	$evandis_widget = new evandis_widget();
	$evandis_widget->hooks();
}

// Enable localization
add_action( 'init', '_evw_load_translation' );
function _evw_load_translation() {
    load_plugin_textdomain( 'evandis-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}