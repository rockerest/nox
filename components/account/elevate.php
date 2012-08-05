<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$userDA = new \model\access\UserAccess();
	$permit = new \business\Permission();

	if( !$_SESSION['active'] ){
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}
	elseif( $_SESSION['roleid'] > 1 ){
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}

	$userid = isset($_GET['uid']) ? $_GET['uid'] : $_SESSION['userid'];
	$self = $userDA->getById( $_SESSION['userid'] );
	$attempt = $userDA->getById( $userid );

	$tAuth = $attempt->getAuthentication();

	if( $permit->elevateRole( $self, $attempt ) ){
		$tAuth->setRoleId( $tAuth->getRoleId() - 1 );
		if( $tAuth->save() ){
			header( 'Location: ' . APPLICATION_ROOT_URL . 'users.php?code=13' );
		}
		else{
			header( 'Location: ' . APPLICATION_ROOT_URL . 'users.php?code=14' );
		}
	}
	else{
		header( 'Location: ' . APPLICATION_ROOT_URL . 'users.php?code=15' );
	}
