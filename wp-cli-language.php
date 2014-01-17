<?php
/*
Plugin Name: WP CLI Language
Plugin URI: 
Description: 
Version:     1.0
License:     GPLv2 or later
Author:      Marko Heijnen
Author URI:  http://markoheijnen.com
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.

Online: http://www.gnu.org/licenses/gpl.txt
*/

if ( ! defined('ABSPATH') ) {
    die();
}

function wp_cli_language_load() {
	global $wp_version;

	if( version_compare( $wp_version, '3.7', '>=' ) ) {
		if ( defined('WP_CLI') && WP_CLI ) {
			include dirname( __FILE__ ) . '/cli/language.php';
		}
	}
}

add_action( 'plugins_loaded', 'wp_cli_language_load' );
