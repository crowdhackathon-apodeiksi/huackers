app.controller('OffersCtrl', function($scope, $ionicPopup, Receipt){
	$scope.offers= [
		{ name: "Έκπτωση 10% για όλες τις αγορές σας", tn: 0, enabled : 0}, 
		{ name: "Δώρο 2 καφέδες στην επόμενη επίσκεψη σας", tn: 25, enabled: 1}

	]
});