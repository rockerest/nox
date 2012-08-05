<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$userDA = new \model\access\UserAccess();

	if( !$_SESSION['active'] ){
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}
	elseif( $_SESSION['roleid'] > 1 ){
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}

	$self = $userDA->getById( $_SESSION['userid'] );
	$uid = isset($_GET['uid']) ? $_GET['uid'] : null;

	if( $uid ){
		$user = $userDA->getById( $uid );
	}
	else{
		$user = false;
	}

	if( $_SESSION['roleid'] == 1 ){
		if( $user->delete() ){
			header('Location: ' . APPLICATION_ROOT_URL . 'users.php?code=3');
		}
		else{
			header('Location: ' . APPLICATION_ROOT_URL . 'users.php?code=4&ec=CASCADE_DELETE_FAIL--' . $user->getUserId() );
		}
	}
	else{
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}
?>
