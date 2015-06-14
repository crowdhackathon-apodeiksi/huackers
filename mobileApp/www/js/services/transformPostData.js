app.factory("transformRequestAsFormPost",function() {
	return {
		transform : function(data) {
            if (!angular.isObject(data))		    	 // If this is not an object, defer to native stringification.
                return( ( data == null ) ? "" : data.toString() );

            var buffer = [];
            for ( var name in data ) {
                if (!data.hasOwnProperty(name))
                    continue;

                var value = data[ name ];
                buffer.push(encodeURIComponent( name ) + "=" + encodeURIComponent( ( value == null ) ? "" : value ));
            }

            var source = buffer.join("&").replace( /%20/g, "+" );
            return( source );
	    }
	}
});