<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$code = isset($_GET['code']) ? $_GET['code'] : null;

	if( $code ){
		$ql = \model\Quick_Login::getByHash($code);
		if( $ql ){
			$user = \model\User::getByID($ql->userid);
			$user->disabled = 0;
			$user->save();

			$user->session->setSessionVar('active', true);
			$user->session->setSessionVar('roleid', $user->authentication->role->roleid);
			$user->session->setSessionVar('userid', $user->userid);

			$ql->used = 1;
			$ql->save();

			throw new \backbone\RedirectBrowserException("/home.php?code=0");
		}
		else{
			throw new \backbone\RedirectBrowserException('/index.php?code=9');
		}
	}
	else{
		throw new \backbone\RedirectBrowserException('/index.php?code=9');
	}
?>
