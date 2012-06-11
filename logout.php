<?php
	require_once( 'components/system/Preload.php' );
	$fwd = isset($_GET['fwd']) ? urldecode($_GET['fwd']) : null;

	if( !$fwd ){
		$fwd = 'index.php?code=4';
	}

	// Load the CURRENT session
	$session = new \backbone\Session(0,APPLICATION_ROOT_URL);
	$session->setSession();
	// destroy the current session
	session_destroy();
	setcookie($_COOKIE['identifier'], '', time() - 100100100);
	setcookie('identifier', '', time() - 100100100);
	// Load a NEW session
	$session = new \backbone\Session(0,APPLICATION_ROOT_URL);
	$session->setSession();

	header('Location: ' . $fwd);
?>
