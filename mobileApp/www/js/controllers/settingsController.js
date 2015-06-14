app.controller('SettingsCtrl', function($scope, SocialNetworks){
	$scope.ggps = false;
	$scope.facebook = SocialNetworks.isFacebookEnabled();
	$scope.twitter = SocialNetworks.isTwitterEnabled();

	$scope.toggleFacebook = function() {
		if ($scope.facebook) {
			SocialNetworks.disableFacebook();
			$scope.facebook = false;
		} else {
			SocialNetworks.enableFacebook();
			$scope.facebook = true;
		}
	};

	$scope.toggleTwitter = function() {
		if ($scope.twitter) {
			SocialNetworks.disableTwitter();
			$scope.twitter = false;
		} else {
			SocialNetworks.enableTwitter();
			$scope.twitter = true;
		}
	}

});