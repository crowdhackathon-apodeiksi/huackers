app.factory('Authentication', function($http, $q, $window, $sanitize, transformRequestAsFormPost) {
	var sanitizeCredentials = function(credentials) {
    	return {
      		email: $sanitize(credentials.email),
      		password: $sanitize(credentials.password)
    	};
  	};

	return {
		getUser:function () {
			if( window.localStorage['afm'] && 
       	  		window.localStorage['api_key'] &&
       	  		/*window.localStorage['afm'].trim().length==9 &&*/
       	  		window.localStorage['api_key'].trim().length>0) {
				var user = {
					afm : window.localStorage['afm'],
					api_key : window.localStorage['api_key']
				};
				
				return user;
			}
			else
				$window.location.href = '#/login';
		},
		login : function(credentials) { debugger;
	    	var deferred = $q.defer();  
			$http({
				method: 'POST',
				url: 'http://83.212.118.7/camelot/api/user_api/userlogin/',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
	        	data: transformRequestAsFormPost.transform(credentials)
			}).success(function(data) { debugger;
				window.localStorage['email'] = credentials.email;
				window.localStorage['afm'] = data.afm;
				window.localStorage['api_key'] = data.api_key;
				if(!angular.isUndefined(data.is_company)){
					window.localStorage['is_company'] = 1;
				}

	        	deferred.resolve(data);
	      	}).error(function(data){
	        	deferred.reject(data);
	      	});
	      
	      	return deferred.promise;
	    },
	    logout : function(credentials) {
	    	window.localStorage.removeItem('afm');
	    	window.localStorage.removeItem('email');
	    	window.localStorage.removeItem('is_company');
			window.localStorage.removeItem('api_key');
			window.localStorage.removeItem('facebook');
			window.localStorage.removeItem('twitter');
			debugger;
			$window.location.href = '#/login';
	    },
	    register : function(userData) {
	      	var deferred = $q.defer();  
			$http({
				method: 'POST',
				url: 'http://83.212.118.7/camelot/api/user_api/userregister/',
				headers: { 'Content-Type' : 'application/json' },
	        	data: transformRequestAsFormPost.transform(userData)
			}).success(function(data) { 
	        	deferred.resolve(data);
	      	}).error(function(data){
	        	deferred.reject(data);
	      	});
	      
	      	return deferred.promise;
		}
	}
});