$("#username").autocomplete({
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
