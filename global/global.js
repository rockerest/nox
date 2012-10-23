function go_here(loc)
{
	window.location=loc;
	return false;
}

function getUrlVars()
{
	var map = {};
/* <![CDATA[ */
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
/* ]]> */
		function(m,key,value)
		{
      		map[key] = value;
      	}
	);
    return map;
}

function insertParam(key, value, retain)
{
    key = escape(key); value = escape(value);

    var kvp = document.location.search.substr(1).split('&');
	if( retain != null && retain == false )
	{
		kvp = "";
	}
	if (kvp == "")
	{
		document.location.search = key + "=" + value;
    }
	else
	{
		var i=kvp.length; var x; while(i--)
		{
			x = kvp[i].split('=');

			if (x[0]==key)
			{
					x[1] = value;
					kvp[i] = x.join('=');
					break;
			}
		}

		if(i<0) {kvp[kvp.length] = [key,value].join('=');}

		//this will reload the page, it's likely better to store this until finished
		document.location.search = kvp.join('&');
	}
}

function recursiveObjectMerge( primary, overwrite ){
	for( var p in overwrite ){
		try{
			//try an update
			if( overwrite[p].constructor == Object ){
				primary[p] = recursiveObjectMerge( primary[p], overwrite[p] );
			}
			else{
				primary[p] = overwrite[p];
			}
		}
		catch( e ){
			// destination doesn't have that property, create and set it
			primary[p] = overwrite[p];
		}
	}

	// primary is modified (it's a reference), but pass it back
	// to keep up the idea that this function returns a result
	return primary;
}

function Cookies(){
	this.cookies = document.cookie,

	this.add = function( cookie ){
		// takes a Cookie object and adds it to the list
		if( this.get( cookie.name ) ){
			this.delete( cookie.name );
		}

		document.cookie = cookie.toString();
	},

	this.get = function( name ){
		var results = this.cookies.match( '(^|;) ?' + name + '=([^;]*)(;|$)' );

		if( results ){
			return unescape( results[2] );
		}
		else{
			return null;
		}
	},

	this.delete = function( name ){
		var past = new Date();
		past.setTime( past.getTime() - 10101010 );

		document.cookie = name += "=; expires=" + past.toGMTString();
	}
}

function Cookie( name, value, settings ){
	this.defaults = {
		expire:		0,		// defaults to session
		path:		"/",	// defaults to the root of the web server
		domain:		null,	// no default domain
		secure:		false	// defaults to not secure
	};

	if( typeof settings == "undefined" || !( settings instanceof Object ) ){
		settings = {};
	}

	this.settings = recursiveObjectMerge( this.defaults, settings );

	this.name = name;
	this.value = value;

	this.toString = function(){

		var selfString = this.name + "=" + escape( this.value );

		// set the expiration
		if( this.settings.expire != 0 && this.settings.expire instanceof Date ){
			selfString += "; expires=" + this.settings.expire.toGMTString();
		}

		// set the path
		if( this.settings.path != null && this.settings.path != "" ){
			selfString += "; path=" + escape( this.settings.path );
		}

		// set the domain
		if( this.settings.domain != null && this.settings.domain != "" ){
			selfString += "; domain=" + escape( this.settings.domain );
		}

		// set whether secure or not
		if( this.settings.secure ){
			selfString += "; secure";
		}

		return selfString;
	}
}


