<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$userDA = new \model\access\UserAccess();
	$authDA = new \model\access\AuthenticationAccess();

	if( !$_SESSION['active'] ){
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}

	$self = $userDA->getById( $_SESSION['userid'] );
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
		$user = $userDA->getById( $uid );
	}
	else{
		$user = false;
	}

	if( $self == $user || $_SESSION['roleid'] < 3 ){
		$auth = $user->getAuthentication();
		if( $auth->getResetPassword() ){
			$auth->setResetPassword( 0 );
			if( $auth->save() ){
				header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=10');
			}
			else{
				header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=12');
			}
		}
		else{
			$auth->setResetPassword( 1 );
			if( $auth->save() ){
				header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=9');
			}
			else{
				header('Location: ' . APPLICATION_ROOT_URL . $return . '.php?code=11');
			}
		}
	}
	else{
		header('Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2');
	}
?>
