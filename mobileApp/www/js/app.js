// Ionic Starter App

// angular.module is a global place for creating, registering and retrieving Angular modules
// 'starter' is the name of this angular module example (also set in a <body> attribute in index.html)
// the 2nd parameter is an array of 'requires'
// 'starter.controllers' is found in controllers.js
var app = angular.module('starter', ['ionic', 'starter.controllers', 'ngCordova', 'ionic-datepicker', 'tc.chartjs', 'ngImgCrop','sotos.crop-image'])
/*app.config(['$httpProvider', function($httpProvider) {
    $httpProvider.defaults.useXDomain = true;
    $httpProvider.defaults.withCredentials = true;

}]);*/
app.run(function($ionicPlatform) {
  $ionicPlatform.ready(function() {
    // Hide the accessory bar by default (remove this to show the accessory bar above the keyboard
    // for form inputs)
    if (window.cordova && window.cordova.plugins.Keyboard) {
      cordova.plugins.Keyboard.hideKeyboardAccessoryBar(true);
    }
    if (window.StatusBar) {
      // org.apache.cordova.statusbar required
      StatusBar.styleDefault();
    }
  });
})

app.config(function($stateProvider, $urlRouterProvider) {
	$stateProvider
	.state('app_business', {
	    url: "/app_business",
	    abstract: true,
	    templateUrl: "templates/menu_business.html",
	    controller: 'AppCtrl'
	})
	.state('app_business.profile', {
      	url: "/profile",
      	views: {
        	'menuContent': {
          		templateUrl: "templates/profile_business.html",
          		controller: 'ProfileBusinessCtrl'
        	}
      	}	
    })
    .state('app_business.offers', {
      	url: "/offers",
      	views: {
        	'menuContent': {
          		templateUrl: "templates/offers_business.html",
          		controller: 'OffersCtrl'
        	}
      	}	
    })
    .state('app_business.offers_add', {
      	url: "/offers_add",
      	views: {
        	'menuContent': {
          		templateUrl: "templates/add_offers_business.html",
          		controller: 'OffersCtrl'
        	}
      	}	
    })
	$stateProvider
	.state('app', {
	    url: "/app",
	    abstract: true,
	    templateUrl: "templates/menu.html",
	    controller: 'AppCtrl'
	})
	.state('app.camera', {
    	url: "/camera",
    	views: {
      		'menuContent': {
        		templateUrl: "templates/camera.html",
				controller: 'CameraCtrl'
      		}
    	}
  	})
  	.state('app.receipts', {
    	url: "/receipts",
    	views: {
      		'menuContent': {
        		templateUrl: "templates/receipts.html",
        		controller: 'ReceiptsCtrl'
      		}
    	}
  	})
  	.state('app.confirm', {
	    url: "/confirm",
	    views: {
      		'menuContent': {
        		templateUrl: "templates/confirm.html",
        		controller: 'ConfirmCtrl'
      		}
    	}
	})
  	.state('app.gifts', {
    	url: "/gifts",
    	views: {
      		'menuContent': {
        		templateUrl: "templates/gifts.html",
        		controller : "GiftsCtrl"
      		}
    	}
  	})
    .state('app.profile', {
      	url: "/profile",
      	views: {
        	'menuContent': {
          		templateUrl: "templates/profile.html",
          		controller: 'ProfileCtrl'
        	}
      	}	
    })
    .state('app.settings', {
      	url: "/settings",
      	views: {
        	'menuContent': {
          		templateUrl: "templates/settings.html",
          		controller: 'SettingsCtrl'
        	}
      	}	
    })
    .state('identify', {
      	url: "/identify",
      	templateUrl: "templates/identify.html",
      	controller: 'IdentifyCtrl'
    })
    .state('register', {
      	url: "/register",
      	templateUrl: "templates/register.html",
      	controller: 'RegisterCtrl'
    })
    .state('register_business', {
      	url: "/register_business",
      	templateUrl: "templates/register_business.html",
      	controller: 'RegisterBussinessCtrl'
    })
    .state('login', {
      	url: "/login",
      	templateUrl: "templates/login.html",
      	controller: 'LoginCtrl'
    })
    .state('logout', {
      	url: "/logout",
      	controller: 'LogoutCtrl',
      	template: "<ion-view cache-view=\"false\"></ion-vie>"
    })
    .state('splash', {
      	url: "/splash",
      	templateUrl: "templates/splash.html",
      	controller: 'SplashCtrl'
    });
  	// if none of the above states are matched, use this as the fallback
  	$urlRouterProvider.otherwise('/splash');
});
