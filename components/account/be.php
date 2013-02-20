<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$acc		= new \model\Access();
	$em			= $acc->getEntityManager();
	$userRepo	= $em->getRepository( 'model\entities\User' );

	if( !$_SESSION['active'] || $_SESSION['roleid'] > 1 ){
		throw new \backbone\RedirectBrowserException( 'Location: ' . APPLICATION_ROOT_URL . 'index.php?code=2' );
	}

	$uid	= isset( $_GET['uid'] ) ? $_GET['uid'] : ( isset( $_SESSION['realUserId'] ) ? $_SESSION['realUserId'] : $_SESSION['userid'] );
	$user	= $userRepo->find( $uid );

	if( empty( $_GET['uid'] ) ){
		beSelf();
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php' );
	}
	else{
		beSomeone( $user );
		throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php' );
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
			$_SESSION['roleid'] = $user->getAuthentication()->getRole()->getId();
			$_SESSION['userid'] = $user->getId();
		}
	}

	function beSelf(){
		// retrieve the real stuff
		$_SESSION['userid'] = $_SESSION['realUserId'];
		$_SESSION['roleid'] = $_SESSION['realRoleId'];
		unset( $_SESSION['realRoleId'] );
		unset( $_SESSION['realUserId'] );
	}
