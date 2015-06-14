app.controller('ReceiptsCtrl', function($http, $scope, $ionicPopup, Receipt, Authentication, transformRequestAsFormPost){
	$scope.isReceiptShown = function(receipt) { return $scope.shownReceipt === receipt; };
	$scope.toggleReceipt = function(receipt) {
    	if ($scope.isReceiptShown(receipt))
      		$scope.shownReceipt = null;
    	else
      		$scope.shownReceipt = receipt;
  	};



	var transformData = function(data) {

		var result = [];
		for (var i=0; i<data.tickets.length; i++) {

			result.push(
			{
				name : data.tickets[i].onomasia,
					afm : data.tickets[i].afm,
					synolo : data.tickets[i].amount,
				items : [
					{ name : "ΔΙΕΥΘΥΝΣΗ", value : data.tickets[i].postal_address },
					//{ name : "ΤΗΛΕΦΩΝΟ", value : data.tickets[i].postal_address },
					{ name : "ΑΦΜ", value : data.tickets[i].afm },
					{ name : "ΔΟΥ", value : data.tickets[i].doy_descr },
					{ name : "ΗΜΕΡΟΜΗΝΙΑ", value : data.tickets[i].ticket_date },
					{ name : "ΚΑΤΗΓΟΡΙΑ", value : $scope.categories[data.tickets[i].ticket_type -1] },
					//{ name : "ΦΠΑ", value : data.tickets[i].afm	},
					{ name : "ΣΥΝΟΛΟ", value : data.tickets[i].amount }
				]
			});

		}

		return result;


	};



	$scope.getReceipts = function() {
		var userData = Authentication.getUser();

		Receipt.getReceipts(userData)
			.then(function (response) {
				$scope.receipts_raw = transformData(angular.copy(response));
				$scope.receipts = angular.copy($scope.receipts_raw);
				console.log($scope.receipts);
			}, function (error) {
				console.log('error in get receipts')
			});
	};

		$scope.getReceiptsMonthly = function(){
			var userData = Authentication.getUser();

			Receipt.getReceiptsMonthly(userData)
				.then(function(response){
					$scope.receipts_raw_monthly = transformData(angular.copy(response));
					$scope.receipts_monthly=angular.copy($scope.receipts_raw_monthly);
				}, function(error){
					console.log('error in get receipts')
				});
	};

	$scope.getReceipts();
	$scope.getReceiptsMonthly();

/*  	var tt = Authentication.getUser();
  	debugger;
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

	function applyFilter() {
 		var filteredReceipts = [];
 		for(var i=0; i<$scope.receipts_raw.length; i++) {
 			for(var j=0; j<$scope.receipts_raw[i].items.length; j++) {
 				if (angular.equals($scope.receipts_raw[i].items[j].name, "ΚΑΤΗΓΟΡΙΑ") && angular.equals($scope.receipts_raw[i].items[j].value, $scope.selectedCategory))
 					filteredReceipts.push($scope.receipts_raw[i]);
 			}
 		}

 		$scope.receipts=angular.copy(filteredReceipts);
 	};

	$scope.showCategoriesPopup = function () {
 		$scope.categoriesPopup = $ionicPopup.show({
 			cssClass: 'category-popup',
    		template: '<ion-scroll zooming="false" direction="y" style="height: 150px;"><ul class="list"><li class="item" ng-repeat="category in categories" ng-click="getCategoryFilter($index);modal.hide()">{{category}}</li></ul></ion-scroll>',
    		title: 'Επιλέξτε κατηγορία',
    		scope: $scope,
    		buttons: [ {
    			text: 'Καθαρισμός' ,
    			onTap: function(e) {debugger;
		          	$scope.clearFilter();
		          	$scope.categoriesPopup.close();
		        }
    		}]
      	});
 	};

 	$scope.getCategoryFilter = function (index) {
 		$scope.selectedCategory = $scope.categories[index];
 		applyFilter();
   		$scope.categoriesPopup.close();
	};

	$scope.clearFilter = function () {
 		$scope.selectedCategory = null;
 		$scope.receipts = angular.copy($scope.receipts_raw);
 	};



	$scope.showAllTabData = function () {
		$scope.receipts = angular.copy($scope.receipts_raw);
	}
	$scope.showMonthTabData = function () {
		$scope.receipts = angular.copy($scope.receipts_raw_monthly);
	}
});