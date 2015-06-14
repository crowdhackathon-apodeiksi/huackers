app.controller('RegisterBussinessCtrl', function($scope){
	$scope.user = {
		"afm" : "",
		"email" : "",
		"password" : "",
		"taxisUsername" : "",
		"taxisPassword" : ""
	}

	$scope.register = function (form) { 
		if(!form.$valid) {
			form.email.$pristine=false;
			form.password.$pristine=false;
			form.afm.$pristine=false;
			form.taxisUsername.$pristine=false;
			form.taxisPassword.$pristine=false;
			return false;
		}

		/*Authentication.registerBusiness($scope.user)
      	.then(function(response){  debugger;
      		$ionicHistory.nextViewOptions({
			    disableAnimate: true,
			    disableBack: true
			});
      		$window.location.href = '#/app/camera';
      	}, function(error){ debugger;
      		$ionicPopup.show({
	    		template: 'Παρουσιάστηκε κάποιο πρόβλημα κατά την εγγραφή σας στην εφαρμογή, παρακαλούμε δοκιμάστε ξανα.',
	    		title: 'Πρόβλημα Εγγραφής',
	    		scope: $scope,
	    		buttons: [ { 
	    			text: 'OK',
	    			type: 'button-positive'
	    		} ]
	      	});
      	
      	});*/
	}

	
});