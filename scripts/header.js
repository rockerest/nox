$( '#title' ).click(function(){
	go_here('index.php');
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
					my: 'top center',
					at: 'bottom center',
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
