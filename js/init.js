Utils.namespace( "window.nox" );

$(function(){
    "use strict";

    $('.tooltip').qtip(
        $.extend({}, nox.Ui.tooltips, {
        })
    );

    //html elements (cloned) for default Tooltip.
    $('.elementTooltip').each(
        function(){
            var element = $(this).attr('data-tooltipid');
            $(this).qtip({
                content:{
                    text: $('#' + element).clone()
                }
            });
        }
    );

    nox.Ui.fadeout();
});
