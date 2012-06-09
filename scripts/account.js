$('button[data-link="gender"]').click(
	function(){
		$('button[data-link="gender"]').removeClass('active');
		$(this).addClass('active');
		
		var link = $(this).attr('data-match');
		$('input[type="radio"][data-link="gender"][value="' + link + '"]').click();
	}
);

$('#submit').click(
	function(){
		$('#account').submit();
	}
);

$('#delete').click(
	function(){
		go_here('components/account/delete.php?uid=' + $(this).attr('data-id') + '&tb=e');
	}
);

$('#toggle').click(
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