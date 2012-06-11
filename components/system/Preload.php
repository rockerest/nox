<?php
	define( 'APPLICATION_ROOT_URL', '/' );
	define( 'APPLICATION_CONFIGURATION_FILE', 'real_juggernaut.conf' );
	define( 'APPLICATION_ROOT_PATH', implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, __FILE__), 0, -3 )) . '/');
	define( 'APPLICATION_INCLUDE_PATH', get_include_path() . PATH_SEPARATOR . implode( PATH_SEPARATOR, array('.', 'components', 'content', 'global', 'scripts', 'images', 'styles') ) );

	set_include_path( APPLICATION_INCLUDE_PATH );

	function __autoload( $path ){
		// Do a little cleaning
		// swap back/forward slashes
		$slash = str_replace( '\\', '/', $path );

		$filename = APPLICATION_ROOT_PATH . 'classes/' . $slash . '.php';
		require_once( $filename );
	}

	$session = new \backbone\Session(0,APPLICATION_ROOT_URL);
	$session->setSession();
