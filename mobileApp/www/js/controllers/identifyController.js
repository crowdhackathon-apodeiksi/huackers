app.controller('IdentifyCtrl', function($scope, $window){
	$scope.userRegister = function () {
		$window.location.href = '#/register';
	}

	$scope.businessRegister = function () {
		$window.location.href = '#/register_business';
	}
});