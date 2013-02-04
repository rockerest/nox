// Utilities
(function( Utils, undefined ){
    "use strict";

    Utils.parseNamespace = function( namespace ){
        var parts = namespace.split(".");

        if( parts[0] == "window" ){
            parts = parts.slice( 1 );
        }

        return parts;
    };

    // namespace function
    //adapted from http://addyosmani.com/blog/essential-js-namespacing/
    Utils.namespace = function( namespace ){
        var parent = window,
            pl, i, parts;

        parts = Utils.parseNamespace( namespace );

        pl = parts.length;
        for( i = 0; i < pl; i++ ){
            //create missing steps in the chain
            if( parent[parts[i]] === undefined ){
                parent[parts[i]] = {};
            }

            parent = parent[parts[i]];
        }
    };

    // get Object Keys
    Utils.keys = function( object ){
        var ks = [],
            p;

        if( Object.keys ){
            return Object.keys( object );
        }
        else{
            if( object !== Object(object) ){
                throw new TypeError('Object.keys called on non-object');
            }

            for( p in object ){
                if( Object.prototype.hasOwnProperty.call( object, p ) ){
                    ks.push( p );
                }
            }

            return ks;
        }
    };

    // convert strings to boolean
    Utils.strToBool = function( str ){
        var bool;
        if (str.match(/^(true|1|yes)$/i) !== null) {
            bool = true;
        } else if (str.match(/^(false|0|no)*$/i) !== null) {
            bool = false;
        } else {
            // string isn't boolean
            bool = null;
        }
        return bool;
    };

    Utils.recursiveObjectMerge = function( primary, overwrite ){
        var p;
        for( p in overwrite ){
            try{
                //try an update
                if( overwrite[p].constructor == Object ){
                    primary[p] = Utils.recursiveObjectMerge( primary[p], overwrite[p] );
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
    };

    Utils.merge = function( one, two ){
        return Utils.recursiveObjectMerge( Utils.recursiveObjectMerge( {}, one ), two );
    };

    Utils.clone = function( object ){
        return Utils.merge( {}, object );
    };

    Utils.empty = function( obj ){
        if (typeof obj == 'undefined' || obj === null || obj === '') return true;
        if (typeof obj == 'number' && isNaN(obj)) return true;
        if (obj instanceof Date && isNaN(Number(obj))) return true;
        return false;
    };

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

			document.cookie = name += "=; expires=" + past.toUTCString();
		};
	}( Browser.Cookies = Browser.Cookies || {} ));

    (function( Storage, undefined ){
        Storage.init = function(){
            if( typeof Storage.__datastore != 'object' ){
                Storage.__datastore = {};
            }
        };

        Storage.store = function( key, value ){
            var data,parent,pl,i,parts;

            Storage.init();
            data = Storage.retrieve();
            parent = Storage.__datastore;

            Utils.namespace( "Browser.Storage.__datastore." + key );
            parts = Utils.parseNamespace( key );

            pl = parts.length - 1;
            for( i = 0; i < pl; i++ ){
                parent = parent[parts[i]];
            }

            parent[ parts[ i ] ] = value;
        };

        Storage.retrieve = function( key ){
            var parent = window.Browser.Storage.__datastore,
                data, pl, i, parts, el;
            Storage.init();

            if( key === undefined ){
                data = Storage.__datastore;
            }
            else{
                parts = Utils.parseNamespace( key );

                pl = parts.length;
                for( i = 0; i < pl; i++ ){
                    parent = parent[parts[i]];
                }

                data = parent;
            }

            return data;
        };

    }( Browser.Storage = Browser.Storage || {} ));

	// define a single Cookie
	Browser.Cookie = function( name, value, settings ){
		this.defaults = {
			expire:		0,		// defaults to session
			path:		"/",	// defaults to the root of the web server
			domain:		null,	// no default domain
			secure:		false	// defaults to not secure
		};

		if( settings === undefined || !( settings instanceof Object ) ){
			settings = {};
		}

		this.settings = Utils.merge( this.defaults, settings );

		this.name = name;
		this.value = value;

		this.toString = function(){
			var selfString = this.name + "=" + escape( this.value );

			// set the expiration
			if( this.settings.expire != 0 && this.settings.expire instanceof Date ){
				selfString += "; expires=" + this.settings.expire.toUTCString();
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

    // Navigation helper
    Browser.go = function(loc){
        window.location=loc;
        return false;
    };

    // querystring parser
    Browser.getUrlVars = function(){
        var map = {},
            parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi,
                function(m,key,value)
                {
                    map[key] = value;
                }
            );

        return map;
    };

    // add or extend querystring parameters
    Browser.insertUrlParam = function( key, value, retain ){
        key = escape(key); value = escape(value);

        var kvp = document.location.search.substr(1).split("&"),
            i,x;

        if( retain != null && retain == false ){
            kvp = "";
        }
        if (kvp == ""){
            document.location.search = key + "=" + value;
        }
        else{
            i = kvp.length;
            while(i--) {
                x = kvp[i].split("=");

                if( x[0] == key ){
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
}( window.Browser = window.Browser || {} ));
