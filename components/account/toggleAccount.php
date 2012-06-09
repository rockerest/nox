<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	if( !$_SESSION['active'] ){
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

	if( $self == $user || $_SESSION['roleid'] < 3 ){
		if( $user->authentication->disabled ){
			if( enable($user->userid) ){
				header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=6');
			}
			else{
				header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=8');
			}
		}
		else{
			if( disable($user->userid) ){
				header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=5');
			}
			else{
				header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=7');
			}
		}
	}
	else{
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}

	function disable($id){
		return \model\Authentication::disableByUserID($id);
	}

	function enable($id){
		return \model\Authentication::enableByUserID($id);
	}
?>
