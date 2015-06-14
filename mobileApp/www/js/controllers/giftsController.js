app.controller('GiftsCtrl', function($scope, $ionicPopup, $cordovaSocialSharing, Authentication, SocialNetworks, Offer){
	$scope.facebook = SocialNetworks.isFacebookEnabled();
	$scope.twitter = SocialNetworks.isTwitterEnabled();

	$scope.shareViaTwitter = function(gift, name) { debugger;
		message = "Μόλις κέρδισα " + gift + "απο το κατάστημα " + name; 
        $cordovaSocialSharing.canShareVia("twitter", message, null, null).then(function(result) {
			$cordovaSocialSharing.shareViaTwitter(message, null, null);
		}, function(error) {
			alert(error);
		});
    }


    var user = Authentication.getUser();
    Offer.getOffers(user)
    .then(function(response){  debugger;
  		$scope.gifts = response;


  	}, function(error){
  		
  	});

	$scope.receipts = [
		{
			name : "ΑΡΤΟΣ & ΤΕΧΝΗ",
			sale : "20% Έκπτωση"
		},
		{
			name : "ΑΡΤΟΣ & ΤΕΧΝΗ",
			sale : "2 καφέδες δώρο"
		},
	]; 
});