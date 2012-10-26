<?php
	define( 'APPLICATION_ROOT_URL', '/' );
	define( 'APPLICATION_CONFIGURATION_FILE', 'nox.conf' );
	define( 'APPLICATION_ROOT_PATH', implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, __FILE__), 0, -3 ) ) . '/' ); // "-3" is number of levels to go back to root.  "/components/system/Preload.php" in this case.
	define( 'APPLICATION_INCLUDE_PATH', get_include_path() . PATH_SEPARATOR . implode( PATH_SEPARATOR, array('.', 'components', 'content', 'global', 'js', 'images', 'styles' ) ) );

	set_include_path( APPLICATION_INCLUDE_PATH );

	spl_autoload_register('nox_autoloader');

	$tc = new \backbone\Config( APPLICATION_CONFIGURATION_FILE, APPLICATION_ROOT_PATH );
	$config = $tc->getConfig();
	unset( $tc );

	$session = new \backbone\Session(0,APPLICATION_ROOT_URL);
	$session->setSession();

	function nox_autoloader( $path ){
		// Do a little cleaning
		// swap back/forward slashes
		$slash = str_replace( '\\', '/', $path );

		$locations = array(
			'classes/',
			'vendors/'
		);

		foreach( $locations as $u ){
			$loc = APPLICATION_ROOT_PATH . $u . $slash . '.php';
			if( file_exists( $loc ) ){
				require( $loc );
			}
		}
	}
