Utils.namespace( "window.nox" );

$( function(){
    "use strict";
    $("#users #username").autocomplete({
        source: "components/account/ajax-search.php",
        minLength: 2,
        search: function( e, ui ){
            $( this )
                .next( "img" )
                .show();
        },
        response: function( e, ui ){
            $( this )
                .next( "img" )
                .hide();
        },
        select: function( e, ui ){
            $("#userid").val( ui.item.userid );
            $("#username").val( ui.item.fullName );

            Browser.go( 'components/account/be.php?uid=' + ui.item.userid );

            return false;
        }
    })
    .data( "ui-autocomplete" )._renderItem = function( ul, item ){
        return $("<li></li>")
            .data( "item.autocomplete", item )
            .append( "<a>" + item.display + "</a>" )
            .appendTo( ul );
    };
});
