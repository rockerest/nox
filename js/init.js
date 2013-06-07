var fadeout = function(){
    var faders = $('.fadeout');

    faders.each(
        function( index ){
            var time = $(this).attr('data-fadetime');
            if( Utils.isInt(time) ){
                $( this ).fadeOut(time);
            }
            else{
                $( this ).fadeOut(3500);
            }
        }
    );
}

Utils.namespace( "window.nox" );

$(function(){
    "use strict";
    //default tooltip setup.
    var tooltips = {
        position:{
            my: 'bottom center',
            at: 'top center',
            target: 'mouse'
        },
        style: {
            classes: 'qtip-light qtip-shadow qtip-rounded'
        }
    }

    $('.tooltip').qtip(
        $.extend({}, tooltips, {
        })
    );

    //html elements (cloned) for default Tooltip.
    $('.elementTooltip').each(
        function()
        {
            var element = $(this).attr('data-tooltipid');
            $(this).qtip({
                content:{
                    text: $('#' + element).clone()
                }
            });
        }
    );

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
        $.extend({}, tooltips, {
            position:{
                my: 'right center',
                at: 'left center'
            }
        })
    );

    //element fixed below
    $('header .fixedElementTooltip-below').each(
        function()
        {
            var element = $(this).attr('data-tooltipid');
            $(this).qtip(
                $.extend({}, tooltips, {
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

    $('#index #create #email').keyup(
        function(){
            if( $(this).val() ){
                $('#verify_email').fadeIn(300);
            }
            else if( $(this).val() == '' ){
                $('#verify_email').fadeOut(300);
            }
        }
    );

    $('#index #create #password').keyup(
        function(){
            if( $(this).val() ){
                $('#verify_password').fadeIn(300);
            }
            else if( $(this).val() == '' ){
                $('#verify_password').fadeOut(300);
            }
        }
    );

    $('#index #create').submit(
        function(e){
            if( ($('#email').val() != $('#vemail').val()) ){
                $('#email').addClass('error');
                $('#vemail').addClass('error');
                e.preventDefault();
            }
            else{
                //allow
            }

            if( $('#password').val() != $('#vpass').val() ){
                e.preventDefault();
            }
            else{
                //allow
            }

        }
    );

    $('#account button[data-link="gender"]').click(
        function(){
            $('button[data-link="gender"]').removeClass('active');
            $(this).addClass('active');

            var link = $(this).attr('data-match');
            $('input[type="radio"][data-link="gender"][value="' + link + '"]').click();
        }
    );

    $('#account #submit').click(
        function(){
            $('#information').submit();
        }
    );

    $('#account #delete').click(
        function(){
            Browser.go('components/account/delete.php?uid=' + $(this).attr('data-id') );
        }
    );

    $('#account #toggle').click(
        function(){
            var items = $('fieldset[data-control="toggle"]');
            items.fadeToggle(500);

            var state = $(this).attr('data-toggle');

            if( state == 0 )
            {
                $(this).children('span').html('Hide optional fields');
                $(this).children('img').attr('src', 'images/icons/16/arrow-180.png');
                $(this).attr('data-toggle', 1);
            }
            else if( state == 1 )
            {
                $(this).children('span').html('Show optional fields');
                $(this).children('img').attr('src', 'images/icons/16/arrow.png');
                $(this).attr('data-toggle', 0);
            }

            return false;
        }
    );

    $("#users #username").autocomplete({
        source: "components/account/ajax-search.php",
        minLength: 2,
        select: function( e, ui ){
            $("#userid").val( ui.item.userid );
            $("#username").val( ui.item.fullName );

            $( "<img>" ).prop({
                "src" : "images/icons/16/animated/ui-progress-bar-indeterminate.gif",
                "alt" : "loading"
            })
            .css({
                "margin" : "4px 1px -4px -18px"
            }).insertAfter( $("#username") );

            Browser.go( 'components/account/be.php?uid=' + ui.item.userid );

            return false;
        }
    })
    .data( "autocomplete" )._renderItem = function( ul, item ){
        return $("<li></li>")
            .data( "item.autocomplete", item )
            .append( "<a>" + item.display + "</a>" )
            .appendTo( ul );
    };

    fadeout();
});
