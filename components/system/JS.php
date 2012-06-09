<?php
	header('Content-Type: text/javascript');

	$q = isset($_GET['q']) ? $_GET['q'] : null;
	preg_match_all('#[\w\.]+#', $q, $files);
	$content = '';

	foreach ($files[0] as $fn){
		$filename = $fn . ".js";

		$home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
		$folders = array(
							$home . "global/",
							$home . "global/blackbird/",
							$home . "global/tipped/",
							$home . "global/fullcalendar/",
							$home . "jquery/",
							$home . "scripts/"
						);
		foreach ( $folders as $folder ){
			if ( file_exists($folder . $filename) ){
				$content .= file_get_contents($folder . $filename);
			}
		}
		$content .= "\n\n";
	}

	echo $content;
?>
