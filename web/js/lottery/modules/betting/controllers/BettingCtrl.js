/**
 * Created by cx on 2016/5/17.
 */
app
    .controller('BettingIndex', ['$scope', 'rest', 'toaster', '$sce', function ($scope, rest, toaster, $sce) {
        var periods = $scope.periods = [];
        var bettings = $scope.bettings = [];
        var currentPeriod = "";
        rest.path = 'bettings';

        var errorCallback = function (data, data2) {
            console.log(data);
            console.log(data2);
            toaster.clear();
            toaster.pop('error', "Error!");
        };

        rest.models().success(function (data) {
            angular.forEach(data, function (betting) {
                // var difference = betting.period - currentPeriod;
                if (betting.period != currentPeriod) {
                    console.log("periodStatusDesc");
                    console.log(Object.keys(betting));
                    console.log(betting.periodStatusDesc);
                    if (betting.periodStatus == 1) {
                        var d1 = Date.parse(betting.periodStatusDesc);
                        addPeriod(betting.periodDesc,d1.toString('dddd H:m') + '开奖');

                    } else {
                        addPeriod(betting.periodDesc,betting.periodStatusDesc);
                    }

                }
                currentPeriod = betting.period;
                console.log(betting.numbers);
                bettings.push(betting);
            })
        }).error(errorCallback);

        function addPeriod(period, periodStatusDesc) {
            bettings.push({
                isPeriod: true,
                period: period,
                statusDesc: periodStatusDesc
            });
            periods.push(period);
        }


    }]);

app
    .controller('BettingDetail', ['$scope', 'rest','$routeParams', function ($scope, rest, $routeParams) {
        //console.log($routeParams);
        rest.path = 'bettings' + '/' + $routeParams.id ;

        var errorCallback = function (data, data2) {
            console.log(data);
            console.log(data2);
        };

        rest.models().success(function (data) {
            console.log(data);
            $scope.item = data;
        });
    }]);