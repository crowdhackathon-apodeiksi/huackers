app.factory('Receipt', function($http, $q, transformRequestAsFormPost) {
    var receipt = {};

    return {

		getReceipts : function (userData) {
			var deferred = $q.defer();
			$http({
				method: 'GET',
				url: 'http://83.212.118.7/camelot/api/user_api/allusertickets?'+transformRequestAsFormPost.transform(userData),
				headers: { 'Content-Type' : 'application/json' }/*,
				data: transformRequestAsFormPost.transform(userData)*/
			}).success(function(data) {
				deferred.resolve(data);
			}).error(function(data){
				deferred.reject(data);
			});

			return deferred.promise;
		},
		getReceiptsMonthly : function (userData) {
			var deferred = $q.defer();
			$http({
				method: 'GET',
				url: 'http://83.212.118.7/camelot/api/user_api/alluserticketscurrentmonth?'+transformRequestAsFormPost.transform(userData),
				headers: { 'Content-Type' : 'application/json' }/*,
				 data: transformRequestAsFormPost.transform(userData)*/
			}).success(function(data) {
				deferred.resolve(data);
			}).error(function(data){
				deferred.reject(data);
			});

			return deferred.promise;
		},
    	getReceipt : function () { return receipt; },
    	clearReceipt : function () { receipt = {}; },
    	setReceipt : function (newReceipt) { receipt = angular.copy(newReceipt); },
    	isEmpty : function () {
    		if (angular.equals({}, receipt))
    			return true;
    		
    		return false; 
    	}
    

    }
});