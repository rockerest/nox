// Utilities
(function( Utils, undefined ){

	// Navigation helper
	Utils.go = function(loc){
		window.location=loc;
		return false;
	};

	// querystring parser
	Utils.getUrlVars = function(){
		var map = {};
/* <![CDATA[ */
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
/* ]]> */
		function(m,key,value)
		{
      		map[key] = value;
      	});

	    return map;
	};

	// add or extend querystring parameters
	Utils.insertUrlParam = function( key, value, retain ){
	    key = escape(key); value = escape(value);

	    var kvp = document.location.search.substr(1).split("&");
		if( retain != null && retain == false ){
			kvp = "";
		}
		if (kvp == ""){
			document.location.search = key + "=" + value;
	    }
		else{
			var i=kvp.length; var x; while(i--) {
				x = kvp[i].split("=");

				if (x[0]==key){
						x[1] = value;
						kvp[i] = x.join("=");
						break;
				}
			}

			if( i < 0 ){
				kvp[kvp.length] = [key,value].join("=");
			}

			//this will reload the page, it"s likely better to store this until finished
			document.location.search = kvp.join("&");
		}
	};

	Utils.recursiveObjectMerge = function( primary, overwrite ){
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
				// destination doesn"t have that property, create and set it
				primary[p] = overwrite[p];
			}
		}

		// primary is modified (it"s a reference), but pass it back
		// to keep up the idea that this function returns a result
		return primary;
	};
	window.Utils = Utils;

}( window.Utils = window.Utils || {} ));

// Browser Wrappers
(function( Browser, undefined ){

	// set up the Cookie jar
	(function( Cookies, undefined ){
		Cookies.cookies = document.cookie;

		Cookies.add = function( cookie ){
			// takes a Cookie object and adds it to the list
			if( Cookies.get( cookie.name ) ){
				Cookies.remove( cookie.name );
			}

			document.cookie = cookie.toString();
		};

		Cookies.get = function( name ){
			var results = Cookies.cookies.match( "(^|;) ?" + name + "=([^;]*)(;|$)" );

			if( results ){
				return unescape( results[2] );
			}
			else{
				return null;
			}
		};

		Cookies.remove = function( name ){
			var past = new Date();
			past.setTime( past.getTime() - 10101010 );

			document.cookie = name += "=; expires=" + past.toGMTString();
		};
	}( Browser.Cookies = Browser.Cookies || {} ));

	// define a single Cookie
	Browser.Cookie = function( name, value, settings ){
		this.defaults = {
			expire:		0,		// defaults to session
			path:		"/",	// defaults to the root of the web server
			domain:		null,	// no default domain
			secure:		false,	// defaults to not secure
		};

		if( typeof settings == "undefined" || !( settings instanceof Object ) ){
			settings = {};
		}

		this.settings = Utils.recursiveObjectMerge( this.defaults, settings );

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
		};
	};
	window.Browser = Browser;

}( window.Browser = window.Browser || {} ));
