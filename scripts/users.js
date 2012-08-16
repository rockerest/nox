$("#username").autocomplete({
    source: "components/account/ajax-search.php",
    minLength: 2,
    select: function( e, ui ){
        $("#userid").val( ui.item.userid );
        $("#username").val( ui.item.fullName );

        go_here( 'components/account/be.php?uid=' + ui.item.userid );

        return false;
    }
})
.data( "autocomplete" )._renderItem = function( ul, item ){
    return $("<li></li>")
        .data( "item.autocomplete", item )
        .append( "<a>" + item.display + "</a>" )
        .appendTo( ul );
};
