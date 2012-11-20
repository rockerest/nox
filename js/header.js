$( '#title' ).click(function(){
	Browser.go('index.php');
});

$( '#head_logout' ).hover(
	function(){
		$(this).children('img').attr('src', 'images/icons/16/door-open-out.png');
	},
	function(){
		$(this).children('img').attr('src', 'images/icons/16/door--arrow.png');
	}
);

//element default left
$('.tooltip-left').qtip(
	$.extend({}, tooltips, {
		position:{
			my: 'right center',
			at: 'left center'
		}
	})
);

//element fixed below
$('.fixedElementTooltip-below').each(
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
