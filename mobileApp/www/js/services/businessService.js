app.factory('Business', function($q, $http, transformRequestAsFormPost) {

    return {
        getReceipts: function(businessData) {
            var deferred = $q.defer();
			
			$http({
				method: 'GET',
				url: 'http://83.212.118.7/camelot/api/business_api/companyticketsnumpermonth?'+transformRequestAsFormPost.transform(businessData),
				headers: { 'Content-Type' : 'application/json' }
			}).success(function(data) {
				deferred.resolve(data);
			}).error(function(data){
				deferred.reject(data);
			});

			return deferred.promise;
        },
        getOffers: function(businessData) {
            var deferred = $q.defer();
			
			$http({
				method: 'GET',
				url: 'http://83.212.118.7/camelot/api/user_api/alluserticketscurrentmonth?'+transformRequestAsFormPost.transform(businessData),
				headers: { 'Content-Type' : 'application/json' }
			}).success(function(data) {
				deferred.resolve(data);
			}).error(function(data){
				deferred.reject(data);
			});

			return deferred.promise;
        }
    }
});