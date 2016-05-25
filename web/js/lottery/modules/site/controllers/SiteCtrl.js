app
    .controller('SiteLogin', ['$scope', 'rest', 'toaster', '$window', function ($scope, rest, toaster, $window) {

        rest.path = 'v1/user/login';

        var errorCallback = function (data) {
            toaster.clear();
            delete $window.sessionStorage._auth;
            angular.forEach(data, function (error) {
                toaster.pop('error', "Field: " + error.field, error.message);
            });
        };

        $scope.login = function () {
            rest.postModel($scope.model).success(function (data) {
                $window.sessionStorage._auth = data;
                toaster.pop('success', "Success");
                $window.setTimeout(function () {
                    $window.location = '/';
                }, 1000);
            }).error(errorCallback);
        };


    }]);
app
    .controller('SiteIndex', ['$scope', 'rest', 'toaster', '$window', '$http', 'Picks', function ($scope, rest, toaster, $window, $http, Picks) {
        $http.get('/lottery/init').success(function (data) {
            console.log(JSON.stringify(data));
            console.log(data.period.current.period);
            Picks.current_period = data.period.current.period;
            if (data.user) {
                $scope.points = data.user.points;
                $scope.times = parseInt(data.user.points / 10);
            }
        });
    }]);