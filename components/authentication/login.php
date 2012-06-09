<?php
	$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, __FILE__), 0, -3 )) . '/';
	require_once( $home . 'components/system/Preload.php' );

	$password = isset($_POST['password']) ? $_POST['password'] : null;
	$identity = isset($_POST['email']) ? $_POST['email'] : null;

	if( $password != null && $identity != null ){
		$tmp = \model\Authentication::validate($identity, $password);

		if( $tmp ){
			$tmp->session->setSessionVar('active', true);
			$tmp->session->setSessionVar('roleid', $tmp->authentication->role->roleid);
			$tmp->session->setSessionVar('userid', $tmp->userid);

			throw new \backbone\RedirectBrowserException("/home.php?code=0");
		}
		else{
			throw new \backbone\RedirectBrowserException("/index.php?code=1&email=" . $identity);
		}
	}
	else{
		throw new \backbone\RedirectBrowserException("/index.php?code=0&email=" . $identity);
	}
?>
