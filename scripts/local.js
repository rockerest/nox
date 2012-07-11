//default tooltip setup.
var tooltips = {
	position:{
		my: 'bottom center',
		at: 'top center',
		target: 'mouse'
	},
	style: {
		classes: 'ui-tooltip-light ui-tooltip-shadow ui-tooltip-rounded'
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

var fadeout = function()
{
	var faders = $('.fadeout');

	faders.each(
		function( index )
		{
			var time = $(this).attr('data-fadetime');
			if( is_int(time) )
			{
				$( this ).fadeOut(time);
			}
			else
			{
				$( this ).fadeOut(3500);
			}
		}
	);
}

function is_int(value)
{
	if( (parseFloat(value) == parseInt(value) ) && !isNaN(value) )
	{
		return true;
	}
	else
	{
		return false;
	}
}

fadeout();
