app.controller('ConfirmCtrl', function($scope, $ionicPopup,$cordovaGeolocation, Confirm,Authentication){
	if (Confirm.isEmpty())
		$scope.title = "Προσθήκη απόδειξης";
	else
		$scope.title = "Επιβεβαίωση στοιχείων";
	
	$scope.receipt = {};
	$scope.receipt.date = new Date();
	$scope.receipt =Confirm.getReceipt();
	debugger;

	var posOptions = {timeout: 10000, enableHighAccuracy: false};
	$cordovaGeolocation
		.getCurrentPosition(posOptions)
		.then(function (position) {
			var lat  = position.coords.latitude;
			var long = position.coords.longitude;
			console.log(lat+" "+long);
			$scope.lat_long = lat+" "+long;
		}, function(err) {
			// error
		});


	$scope.send_confirmation = function(){
		console.log("IN CONFIRM");
		var user = Authentication.getUser();
		post_data=
			{
				afm:"62993766",
				api_key:"015a39b5c9fe8036b1d3e251d6eb4a814f671b8c",
				bus_afm: $scope.receipt.afm,
				date: "20150612",
				am: "1",
				total: "1",
				category: "1",
				aa: "123223",
				lat_lon:$scope.lat_long
			};
		console.log(post_data);
		debugger;
		Confirm.postConfirmedReceipt(post_data);
	}

/*
	$scope.addReceipt = function (form) {
		if(!form.$valid) {
			form.sname.$pristine=false;
			form.address.$pristine=false;
			form.afm.$pristine=false;
			form.doy.$pristine=false;
			form.am.$pristine=false;
			form.aa.$pristine=false;
			//form.date.$pristine=false;
			form.category.$pristine=false;
			form.fpa.$pristine=false;
			form.total.$pristine=false;
			debugger;
			return false;
		}
		var tt= $scope.receipt;
		
	}
*/

	$scope.categories = [ 
		"Είδη διατροφής", 
		"Οινοπνευµατώδη ποτά & καπνός", 
		"Είδη ένδυσης & υπόδησης", 
		"Στέγαση", 
		"∆ιαρκή αγαθά",
		"Υγεία", 
		"Μεταφορές", 
		"Επικοινωνίες",
		"Αναψυχή & πολιτισµός", 
		"Εκπαίδευση",
		"Ξενοδοχεία, καφενεία & εστιατόρια",
		"∆ιάφορα αγαθά & υπηρεσίες"
	];

 	$scope.getCategory = function (index) { debugger;
 		$scope.receipt.category = index;
   		$scope.categoriesPopup.close();
	};

 	$scope.showCategories = function () {
 		$scope.categoriesPopup = $ionicPopup.show({
 			cssClass: 'category-popup',
    		template: '<ion-scroll zooming="false" direction="y" style="height: 150px;"><ul class="list"><li class="item" ng-repeat="category in categories" ng-click="getCategory($index);modal.hide()">{{category}}</li></ul></ion-scroll>',
    		title: 'Επιλέξτε κατηγορία',
    		scope: $scope,
    		buttons: [ { text: 'Ακύρωση' } ]
      	});
 	};
});