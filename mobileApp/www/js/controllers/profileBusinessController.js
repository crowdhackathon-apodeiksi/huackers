app.controller('ProfileBusinessCtrl', function($scope, Business, Authentication){
	
	$scope.user= Authentication.getUser();
	Business.getReceipts($scope.user)
	.then(function(response){  debugger;
  		$scope.chartData = [];

  		for (var j=1; j<13; j++){
  			var receiptsNum = 0;
  			for (var i=0; i<response.tickets.length; i++){
  				if (j==parseInt(response.tickets[i].month_num)){
  					receiptsNum = parseInt(response.tickets[i].num_of_tickets);
  				}
  			}

  			$scope.chartData.push(receiptsNum)
  		}


  		$scope.data = {
      	labels: ['Ιανουάριος', 'Φεβρουάριος', 'Μάρτιος', 'Απρίλιος', 'Μάιος', 'Ιούνιος', 'Ιουλιος', 'Αύγουστος', 'Σεπτέμβριος', 'Οκτώβριος', 'Νοέμβριος', 'Δεκέμβριος'],
      	datasets: [
        {
	          label: 'My Second dataset',
	          fillColor: 'rgba(151,187,205,0.2)',
	          strokeColor: 'rgba(151,187,205,1)',
	          pointColor: 'rgba(151,187,205,1)',
	          pointStrokeColor: '#fff',
	          pointHighlightFill: '#fff',
	          pointHighlightStroke: 'rgba(151,187,205,1)',
	          data: $scope.chartData/*[228, 48, 40, 19, 96, 27, 100]*/
	        }
	      ]
	    };

	    // Chart.js Options
	    $scope.options =  {
	      responsive: true,
	      scaleShowLine : true,
	      angleShowLineOut : true,
	      scaleShowLabels : false,
	      scaleBeginAtZero : true,
	      angleLineColor : 'rgba(0,0,0,.1)',
	      angleLineWidth : 1,
	      pointLabelFontFamily : '"Arial"',
	      pointLabelFontStyle : 'normal',
	      pointLabelFontSize : 10,
	      pointLabelFontColor : '#666',
	      pointDot : true,
	      pointDotRadius : 3,
	      pointDotStrokeWidth : 1,
	      pointHitDetectionRadius : 20,
	      datasetStroke : true,
	      datasetStrokeWidth : 2,
	      datasetFill : true,
	      legendTemplate : ' '
	    };


  	}, function(error){
  		
  	});


    
});