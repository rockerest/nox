<?php
    $home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
    require_once( $home . 'components/system/Preload.php' );

    $userDA = new \model\access\UserAccess();

    if( !$_SESSION['active'] || $_SESSION['roleid'] > 1 ){
        echo json_encode( array( "security" => "not authorized" ) );
    }

    $term = isset( $_GET['term'] ) ? $_GET['term'] : null;

    if( empty( $term ) ){
        echo json_encode( array( "search" => "empty" ) );
    }
    else{
        $possibilities = \utilities\Converter::toArray( $userDA->search( $term ) );

        $arr = array();
        foreach( $possibilities as $user ){
            $t              = new stdClass();
            $t->fullName    = $user->getFullName();
            $t->userid      = $user->getUserId();
            $t->display     = preg_replace( "/($term)/i", "<span class=\"highlight\">$1</span>", $user->getFullName() );

            $arr[]          = $t;
        }

        echo json_encode( $arr );
    }
