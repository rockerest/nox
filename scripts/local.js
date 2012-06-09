//default Tipped setup.  Use another class for custom Tippeds
Tipped.create('.tipped', {
	hideOn: [
				{ element: 'self', event: 'mouseleave' },
				{ element: 'tooltip', event: 'mouseenter' }
			],
	target: 'mouse'
});

//html elements (cloned) for default Tipped.
$('.elementTipped').each(
	function()
	{
		var element = $(this).attr('data-tippedID');
		Tipped.create(
			this,
			$('#' + element).clone(true, true).removeAttr('id')[0],
			{
				hideOn: [
							{ element: 'self', event: 'mouseleave' },
							{ element: 'tooltip', event: 'mouseenter' }
						],
				target: 'mouse'
			}
		);
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