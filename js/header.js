Utils.namespace( "window.nox" );

$( function(){
    "use strict";
    $( 'header #title' ).click(function(){
        Browser.go('index.php');
    });

    $( 'header #head_logout' ).hover(
        function(){
            $(this).children('img').attr('src', 'images/icons/16/door-open-out.png');
        },
        function(){
            $(this).children('img').attr('src', 'images/icons/16/door--arrow.png');
        }
    );

    //element default left
    $('header .tooltip-left').qtip(
        $.extend({}, nox.Ui.tooltips, {
            position:{
                my: 'right center',
                at: 'left center'
            }
        })
    );

    //element fixed below
    $('header .fixedElementTooltip-below').each(
        function(){
            var element = $(this).attr('data-tooltipid');
            $(this).qtip(
                $.extend({}, nox.Ui.tooltips, {
                    position:{
                        my: 'top left',
                        at: 'bottom left',
                        target: false
                    },
                    content:{
                        text: $('#' + element).clone()
                    },
                    hide:{
                        fixed: true,
                        delay: 250
                    }
                })
            );
        }
    );
});
