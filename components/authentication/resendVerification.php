<?php
    $home = implode( DIRECTORY_SEPARATOR, array_slice( explode(DIRECTORY_SEPARATOR, $_SERVER["SCRIPT_FILENAME"]), 0, -3 ) ) . '/';
    require_once( $home . 'components/system/Preload.php' );

    $acc        = new \model\Access();
    $em         = $acc->getEntityManager();

    $qlBusiness = new \business\QuickLogin( $em );
    $userRepo   = $em->getRepository( 'model\entities\User' );
    $qlRepo     = $em->getRepository( 'model\entities\QuickLogin' );

    $mail   = new \utilities\SwiftMailLoader();

    $userid = isset($_GET['uid']) ? $_GET['uid'] : null;

    $user = $userRepo->find( $userid );
    $self = $userRepo->find( $_SESSION['userid'] );

    if( $self->getAuthentication()->getRole()->getId() == 1 && $user ){
        //create login hash
        $ql = new \model\entities\QuickLogin();
        $ql ->setHash( $qlBusiness->createHash( $user->getAuthentication()->getIdentity() ) )
            ->setUser( $user );

        $ql = $acc->persistFlushRefresh( $ql );

        //load email template
        ob_start();
        include( $home . 'components/templates/account_create.html');
        $body       = ob_get_clean();
        $subject    = 'Nox System Email Verification';
        $to         = $user->getContacts()[0]->getEmail();
        $from       = 'no-reply-automator@nox.thomasrandolph.info';

        $message    = $mail->newMessage( $subject, $body, 'text/html' )
                        ->setTo( $to )
                        ->setFrom( $from );

        //strip HTML and format to be at least readable.
        $plain = strip_tags( preg_replace( '#(</p>)|(<br />)|(<br/>)#i', "\n", preg_replace( "#<a href=[\"|'](.*?)[\"|'].*?>(.*?)</a>#i", "$2 $1", $body ) ) );
        $message->addPart( $plain, 'text/plain' );

        if( $mail->sendMessage( $message ) ){
            //redirect to user management
            throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=19' );
        }
        else{
            throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=20' );
        }
    }
    else{
        throw new \backbone\RedirectBrowserException( APPLICATION_ROOT_URL . 'users.php?code=21' );
    }
