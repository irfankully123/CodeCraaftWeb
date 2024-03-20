<?php

class Hostinger_Helper {

	/**
	 *
	 * Check if plugin is active
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function is_plugin_active( $plugin_slug ): bool {
		$active_plugins = (array) get_option( 'active_plugins', array() );
		foreach ( $active_plugins as $active_plugin ) {
			if ( strpos( $active_plugin, $plugin_slug . '.php' ) !== false ) {
				return true;
			}
		}

		return false;
	}

	public static function get_api_token(): string {
		$api_token  = '';
		$token_file = HOSTINGER_WP_TOKEN;

		if ( file_exists( $token_file ) && ! empty( file_get_contents( $token_file ) ) ) {
			$api_token = file_get_contents( $token_file );
		}

		return $api_token;
	}


}

$hostiner_helper = new Hostinger_Helper();
