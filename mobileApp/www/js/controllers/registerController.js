app.controller('RegisterCtrl', function($scope, $ionicHistory, $ionicPopup, Authentication){
	$scope.user = {
		"afm" : "",
		"email" : "",
		"password" : ""
	}

	$scope.register = function (form) { 
		if(!form.$valid) {
			form.email.$pristine=false;
			form.password.$pristine=false;
			form.afm.$pristine=false;
			return false;
		}

		Authentication.register($scope.user)
      	.then(function(response){  debugger;
      		$ionicHistory.nextViewOptions({
			    disableAnimate: true,
			    disableBack: true
			});
      		$window.location.href = '#/app/camera';
      	}, function(error){ debugger;
      		$scope.categoriesPopup = $ionicPopup.show({
	    		template: 'Παρουσιάστηκε κάποιο πρόβλημα κατά την εγγραφή σας στην εφαρμογή, παρακαλούμε δοκιμάστε ξανα.',
	    		title: 'Πρόβλημα Εγγραφής',
	    		scope: $scope,
	    		buttons: [ { 
	    			text: 'OK',
	    			type: 'button-positive'
	    		} ]
	      	});
      	
      	});
	}
});