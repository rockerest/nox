<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	$require_once( $home . 'components/system/Preload.php' );

	if( !$_SESSION['active'] ){
		header('Location: /index.php?code=2');
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
		if( $user->authentication->resetPassword ){
			if( enable($user->userid) ){
				header('Location: /' . $return . '.php?code=10');
			}
			else{
				header('Location: /' . $return . '.php?code=12');
			}
		}
		else{
			if( disable($user->userid) ){
				header('Location: /' . $return . '.php?code=9');
			}
			else{
				header('Location: /' . $return . '.php?code=11');
			}
		}
	}
	else{
		header('Location: /index.php?code=2');
	}

	function disable($id){
		return \model\Authentication::forcePasswordChangeByUserID($id);
	}

	function enable($id){
		return \model\Authentication::acceptPasswordByUserID($id);
	}
?>
