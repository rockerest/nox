<?php
    require_once( 'components/system/Preload.php' );

    $allowed = array(
                        1   => 'Admin'
                    );

    $page   = new \render\Page("Menus", 'menus', $allowed);
    $tmpl   = new \backbone\Template();
    $menuDA = new \model\access\MenuAccess();

    $page->run();
    $tmpl->user->self       = $page->self;

    $tmpl->control->action  = isset( $_GET['action'] ) ? $_GET['action'] : null;
    $tmpl->control->code    = isset( $_GET['code'] ) ? $_GET['code'] : -1;

    $tmpl->data->menus      = \utilities\Converter::toArray( $menuDA->getAll() );
    $tmpl->data->permit     = new \business\Permission();

    switch( $tmpl->control->code ){
        case 0:
                // filler error
                $tmpl->control->alert['type'] = "error";
                $tmpl->control->alert['message'] = "I'm sorry Dave, I can't let you do that.";
                break;
        default:
                break;
    }

    $html = $tmpl->build('menus.html');
    $css = $tmpl->build('menus.css');
    $js = $tmpl->build('menus.js');

    $appContent = array(
                        'html'  =>  $html,
                        'css'   =>  array(  'code' => $css,
                                            'link' => 'menus'
                                            ),
                        'js' => array(  'code' => $js,
                                        'link' => 'menus'
                                        )
                        );

    echo $page->build($appContent);

?>
