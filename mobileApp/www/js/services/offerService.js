app.factory('Offer', function($q, $http, transformRequestAsFormPost) {

    return {
        getOffers: function(userData) {
            var deferred = $q.defer();
			
			$http({
				method: 'GET',
				url: 'http://83.212.118.7/camelot/api/user_api/active_offers?'+transformRequestAsFormPost.transform(userData),
				headers: { 'Content-Type' : 'application/json' }
			}).success(function(data) {
				deferred.resolve(data);
			}).error(function(data){
				deferred.reject(data);
			});

			return deferred.promise;
        }
    }
})