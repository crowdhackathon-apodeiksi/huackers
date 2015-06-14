app.factory('Profile', function($http, $q, transformRequestAsFormPost) {
    var receipt = {};

    return {
        getTotalAmount : function (userData) {
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
        getMonthlyAmount : function (userData) {
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
        getAmountPerCategory : function (userData) {
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: 'http://83.212.118.7/camelot/api/user_api/cost_per_category?'+transformRequestAsFormPost.transform(userData),
                headers: { 'Content-Type' : 'application/json' }/*,
                 data: transformRequestAsFormPost.transform(userData)*/
            }).success(function(data) {
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject(data);
            });

            return deferred.promise;
        },

        getMonthlyAmountPerCategory : function (userData) {
            var deferred = $q.defer();
            $http({
                method: 'GET',
                url: 'http://83.212.118.7/camelot/api/user_api/cost_per_category_per_month?'+transformRequestAsFormPost.transform(userData),
                headers: { 'Content-Type' : 'application/json' }/*,
                 data: transformRequestAsFormPost.transform(userData)*/
            }).success(function(data) {
                deferred.resolve(data);
            }).error(function(data){
                deferred.reject(data);
            });

            return deferred.promise;
        },

        isEmpty : function () {
            if (angular.equals({}, receipt))
                return true;

            return false;
        }


    }
});