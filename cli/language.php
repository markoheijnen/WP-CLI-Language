<?php

/**
 * Install, update and uninstall languages
 *
 * @package wp-cli
 */
class Language_Command {

	public function __construct() {
		parent::__construct();
	}

	protected function update_refresh() {
		// Clear existing caches
		wp_clean_plugins_cache();
		wp_clean_themes_cache();
		delete_site_transient( 'update_core' );

		wp_version_check();  // check for Core updates
		wp_update_themes();  // Check for Theme updates
		wp_update_plugins(); // Check for Plugin updates
	}

	/**
	 * See the status of one or all languages.
	 *
	 * ## OPTIONS
	 *
	 * [<locale>]
	 * : A particular locale to show the status for.
	 */
	public function status( $args ) {
		parent::status( $args );
	}

	protected function status_single( $args ) {
		if ( empty( $args[0] ) )
			exit(1);

		$locale = $args[0];
		$info   = array(
			array( 'name' => 'Locale', 'value' => $locale ),
			array()
		);

		foreach( array( 'core', 'plugins', 'themes' ) as $type ) {
			$translations = wp_get_installed_translations( $type );

			foreach( $translations as $textdomain => $language ) {
				if( key( $language ) == $locale ) {
					$info[] = array( 'name' => 'Text Domain',   'value' => $textdomain );
					$info[] = array( 'name' => 'Project Name',  'value' => $language[ $locale ]['Project-Id-Version'] );
					$info[] = array( 'name' => 'Revision Data', 'value' => $language[ $locale ]['PO-Revision-Date'] );
					$info[] = array();
				}
			}
		}

		foreach( $info as $row ) {
			if( ! $row )
				WP_CLI::line();
			else
				WP_CLI::line( WP_CLI::colorize( str_pad( $row['name'], 15 ). "%n" . $row['value'] ) );
		}
	}

	protected function get_all_items() {
		$languages = array();

		$from_api = get_site_transient( 'update_core' );

		$available_languages = get_available_languages(); // based on .mo files
		$installed_languages = wp_get_installed_translations('core'); // based on .po files and used for the updater

		foreach ( $available_languages as $language ) {
			$languages[ $language ] = array(
				'name'    => $language,
				'status'  => 'active', // Languages that can be used or seen as active. Maybe use get_site_option( 'WPLANG' )
				'update'  => true // By default say it should be updated incase of missing .po files
			);
		}

		foreach ( $installed_languages['default'] as $language => $language_data ) {
			if( ! isset( $languages[ $language ] ) ) {
				$languages[ $language ] = array(
					'name'    => $language,
					'status'  => 'inactive',
				);
			}

			$languages[ $language ]['version'] = $language_data['PO-Revision-Date'];

			if( ! isset( $from_api->translations[ $language ] ) )
				$languages[ $language ]['update'] = false;
		}

		return $languages;
	}

	protected function install_from_repo( $slug, $assoc_args ) {

	}

	protected function get_item_list() {

	}

	protected function filter_item_list( $items, $args ) {

	}

	protected function get_status( $file ) {
		wp_get_translation_updates();
	}

}

WP_CLI::add_command( 'language', 'Language_Command' );

