app.controller('SplashCtrl', function($scope, $timeout, $window, Receipt){
	$timeout(function() {
       	if( window.localStorage['afm'] && 
       	  	window.localStorage['api_key'] &&
       	  	/*window.localStorage['afm'].trim().length==9 &&*/
       	  	window.localStorage['api_key'].trim().length>0) 
			$window.location.href = '#/app/camera';
		else
			$window.location.href = '#/login';
    }, 2000);
});