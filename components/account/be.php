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

	$uid = isset( $_GET['uid'] ) ? $_GET['uid'] : ( isset( $_SESSION['realUserId'] ) ? $_SESSION['realUserId'] : $_SESSION['userid'] );
	$user = $userDA->getById( $uid );

	if( empty( $_GET['uid'] ) ){
		beSelf();
		header( 'Location: ' . APPLICATION_ROOT_URL . 'users.php' );
	}
	else{
		beSomeone( $user );
		header( 'Location: ' . APPLICATION_ROOT_URL . 'users.php' );
	}

	function beSomeone( $user ){
		if( isset($_SESSION['realUserId']) ){
			beSelf();
		}

		if( $_SESSION['roleid'] == 1 ){
			//store the real stuff
			$_SESSION['realRoleId'] = $_SESSION['roleid'];
			$_SESSION['realUserId'] = $_SESSION['userid'];

			//store the fake stuff
			$_SESSION['roleid'] = $user->getAuthentication()->getRoleId();
			$_SESSION['userid'] = $user->getUserId();
		}
	}

	function beSelf(){
		// retrieve the real stuff
		$_SESSION['userid'] = $_SESSION['realUserId'];
		$_SESSION['roleid'] = $_SESSION['realRoleId'];
		unset( $_SESSION['realRoleId'] );
		unset( $_SESSION['realUserId'] );
	}
