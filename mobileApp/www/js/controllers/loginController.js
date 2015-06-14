app.controller('LoginCtrl', function($scope, $ionicHistory, $ionicPopup, $window, Authentication){
	$scope.credentials = {
		email : "",
		password: ""
	};
	
	$scope.login = function (form) { 
		if(!form.$valid) {
			form.email.$pristine=false;
			form.password.$pristine=false;
			return false;
		}

		Authentication.login($scope.credentials)
      	.then(function(response){  debugger;
      		$ionicHistory.nextViewOptions({
			    disableAnimate: true,
			    disableBack: true
			});

      		if (window.localStorage['is_company']==1){
      			$window.location.href = '#/app_business/profile';
      		} else {
      			$window.location.href = '#/app/camera';

      		}
      	}, function(error){
      		$scope.categoriesPopup = $ionicPopup.show({
	    		template: 'Τα στοιχεία που εισάγατε δεν είναι σωστά, παρακαλούμε δοκιμάστε ξανα.',
	    		title: 'Πρόβλημα Σύνδεσης',
	    		scope: $scope,
	    		buttons: [ { 
	    			text: 'OK',
	    			type: 'button-positive'
	    		} ]
	      	});
      	});
	};
});