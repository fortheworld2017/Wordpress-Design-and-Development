<?php

require dirname( __FILE__ ) . '/class-wyde-logger.php';

class Wyde_WP_Importer_Logger_ServerSentEvents extends Wyde_WP_Importer_Logger {
	/**
	 * Logs with an arbitrary level.
	 *
	 * @param mixed $level
	 * @param string $message
	 * @param array $context
	 * @return null
	 */
	public function log( $level, $message, array $context = array() ) {
		$data = compact( 'level', 'message' );

		switch ( $level ) {
			case 'emergency':
			case 'alert':
			case 'critical':
			case 'error':
			case 'warning':
			case 'notice':
			case 'info':
			case 'debug':
				if ( defined( 'WYDE_IMPORT_DEBUG' ) && WYDE_IMPORT_DEBUG ) {
					echo "event: log\n";
					echo 'data: ' . wp_json_encode( $data ) . "\n\n";
					flush();
					break;
				}
				break;
		}
	}
}
