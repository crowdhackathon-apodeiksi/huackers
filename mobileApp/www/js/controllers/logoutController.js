app.controller('LogoutCtrl', function($scope, Authentication){
	Authentication.logout();
});