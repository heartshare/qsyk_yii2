/**
 * Created by cx on 2016/5/17.
 */
app
    .controller('AwardIndex', ['$scope', 'rest', 'toaster', '$sce', function ($scope, rest, toaster, $sce) {

        rest.path = 'bettings';

        var errorCallback = function (data, data2) {
            console.log(data);
            console.log(data2);
            toaster.clear();
            toaster.pop('error', "Error!");
        };

        rest.models().success(function (data) {
            $scope.awards = data;
        }).error(errorCallback);

    }]);