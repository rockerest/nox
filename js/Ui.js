Utils.namespace( "nox.Ui" );

(function( Ui, $, undefined ){
    "use strict";

    var defaults = {
        success: {
            text: "success",
            type: "success",
            layout: "topRight"
        },
        alert: {
            text: "alert",
            type: "alert",
            layout: "topRight"
        },
        error: {
            text: "error",
            type: "error",
            layout: "topRight"
        },
        warning: {
            text: "warning",
            type: "warning",
            layout: "topRight"
        },
        information: {
            text: "information",
            type: "information",
            layout: "topRight"
        },
        confirm: {
            text: "confirm",
            type: "confirm",
            layout: "topRight"
        }
    };

    // default tooltip setup
    Ui.tooltips = {
        position:{
            my: 'bottom center',
            at: 'top center',
            target: 'mouse'
        },
        style: {
            classes: 'qtip-light qtip-shadow qtip-rounded'
        }
    };

    Ui.notifications = {
        success         : defaults.success,
        alert           : defaults.alert,
        error           : defaults.error,
        warning         : defaults.warning,
        information     : defaults.information,
        confirm         : defaults.confirm,
        dismissError    : Utils.merge( defaults.error, { buttons: [{
            addClass: 'pill primary',
            text: 'Okay, dismiss',
            onClick: function($notyfy) {
                $notyfy.close();
            }
          }]
        })
    };

    Ui.fadeout = function(){
        var faders = $('.fadeout');

        faders.each(
            function(){
                var time = $(this).attr('data-fadetime');
                if( Utils.isInt(time) ){
                    $( this ).fadeOut(time);
                }
                else{
                    $( this ).fadeOut(3500);
                }
            }
        );
    };

    Ui.alert = function( name ){
        Ui.overloadAlert( name, {} );
    };

    Ui.overloadAlert = function( name, options ){
        if( typeof notyfy == "function" ){
            notyfy( Utils.merge( Ui.notifications[ name ], options ) );
        }
        else{
            throw new ReferenceError( "notyfy() is undefined or not a function." );
        }
    };
}(window.nox.Ui = window.nox.Ui || {}, jQuery ));
