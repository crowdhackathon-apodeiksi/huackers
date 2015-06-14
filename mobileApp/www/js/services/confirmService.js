app.factory('Confirm', function($http, $q, transformRequestAsFormPost) {
    var receipt = {};

    return {

        postConfirmedReceipt : function (confirmed_data) {
            console.log(confirmed_data);
            var deferred = $q.defer();
            $http({
                method: 'POST',
                url: 'http://83.212.118.7/camelot/api/user_api/userticket',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'},
                 data: transformRequestAsFormPost.transform (confirmed_data)
            }).success(function(data) {
                alert("Receipt Added");
                $window.location.href='#/app/receipts';
                deferred.resolve(data);
            }).error(function(data,status){
                alert("Some Error in reception add");
                $window.location.href='#/app/receipts';
                //alert("IN ERROR" + data);
                console.log(JSON.stringify(data));
            });

            return deferred.promise;
        },
        getReceipt : function () { return receipt; },
        clearReceipt : function () { receipt = {}; },
        setReceipt : function (newReceipt) { debugger; receipt = angular.copy(newReceipt); },
        isEmpty : function () {
            if (angular.equals({}, receipt))
                return true;

            return false;
        }


    }
});