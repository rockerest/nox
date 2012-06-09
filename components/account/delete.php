<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	if( !$_SESSION['active'] ){
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}
	elseif( $_SESSION['roleid'] > 2 ){
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}

	$self = \model\User::getByID($_SESSION['userid']);
	$uid = isset($_GET['uid']) ? $_GET['uid'] : null;
	$tb = isset($_GET['tb']) ? $_GET['tb'] : null;

	//determine return script
	switch( $tb ){
		case 'u':
			$return = 'users';
			break;
		default:
			$return = 'home';
			break;
	}

	if( $uid ){
		$user = \model\User::getByID($uid);
	}
	else{
		$user = false;
	}

	if( $_SESSION['roleid'] == 1 ){
		if( \model\User::deleteByID($user->userid) ){
			header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=3');
		}
		else{
			header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=4&ec=CASCADE_DELETE_FAIL--' . $user->userid);
		}
	}
	else{
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}
?>
