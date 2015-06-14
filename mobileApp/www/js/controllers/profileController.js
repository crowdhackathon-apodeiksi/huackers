app.controller('ProfileCtrl', function($scope, $ionicPopup, Profile, $rootScope,Authentication){

	$scope.getAmountPerCategory = function() {
		var userData = Authentication.getUser();

		Profile.getAmountPerCategory(userData)
			.then(function (response) {
				$scope.categories_raw = angular.copy(response.costs);
				$scope.categories = angular.copy(response.costs);
				//$scope.total_counter = response.costs[0].numoftickets;
			}, function (error) {
				console.log('error in get receipts')
			});
	};

	$scope.getMonthlyAmountPerCategory = function() {
		var userData = Authentication.getUser();

		Profile.getMonthlyAmountPerCategory(userData)
			.then(function (response) {
				$scope.categories_raw_monthly = angular.copy(response.costs);
				$scope.total_counter_monthly = response.costs[0].numoftickets;
			}, function (error) {
				console.log('error in get receipts')
			});
	};

	$scope.getMonthlyAmountPerCategory();
	$scope.getAmountPerCategory();

	$scope.showAllTabData = function () {
		$scope.categories = angular.copy($scope.categories_raw);
	};
	$scope.showMonthTabData = function () {
		$scope.categories = angular.copy($scope.categories_raw_monthly);
	};




		$scope.user = {
		email : "imktks@gmail.com",
		photo: "./img/venkman.jpg"
	}

	$scope.data = [
      {
        value: 1,
        color:'#F7464A',
        highlight: '#FF5A5E'
      },
      {
        value: 5,
        color: '#46BFBD',
        highlight: '#5AD3D1'
      },
      {
        value: 45,
        color: '#FDB45C',
        highlight: '#FFC870'
      }
    ];

	$scope.change_avatar = function(){
		alert("test");
	};

    
    $scope.options =  {		// Chart.js Options
      	responsive: true,
      	segmentShowStroke : true,
      	segmentStrokeColor : '#fff',
      	segmentStrokeWidth : 2,
      	percentageInnerCutout : 80, // This is 0 for Pie charts
      	animationSteps : 100,
      	animationEasing : 'easeInOutQuad',
      	animateRotate : true,
      	animateScale : false,
      	tooltipEvents: [],
		showTooltips: true,
      	legendTemplate : ' ',
      	onAnimationComplete: function(){ 
      		$rootScope.$broadcast('animation_completed');
      	}
    };
});