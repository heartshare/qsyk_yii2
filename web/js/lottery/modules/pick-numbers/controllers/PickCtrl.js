/**
 * Created by cx on 2016/5/17.
 */
app.controller('PickNumbers', ['$scope', 'rest', 'toaster', '$window', 'Picks', '$timeout', '$filter', 'UserApi', '$http', '$log', '$uibModal', function ($scope, rest, toaster, $window, Picks, $timeout, $filter, UserApi, $http, $log, $uibModal ) {

    $scope.reds = Picks.init_red();
    $scope.selectedReds = [];

    $scope.blues = Picks.init_blue();
    $scope.selectedBlues = [];

    $scope.bluestyle = "blue-dark";
    $scope.redstyle = "red-dark";

    $scope.period = Picks.current_period;
    $log.log($scope.period);
    $scope.blueToggle = function (data) {

        if (Picks.in_array(data, $scope.selectedBlues)) {
            $scope.selectedBlues.pop(data);
        } else {

            $scope.selectedBlues.push(data);
            console.log($scope.selectedBlues.length);
            console.log(params.blueLimit);
            if ($scope.selectedBlues.length > params.blueLimit) {
                $scope.selectedBlues.shift();
            }
        }
    };

    $scope.redToggle = function (data) {
        if (Picks.in_array(data, $scope.selectedReds)) {
            $scope.selectedReds.pop(data);
        } else {
            $scope.selectedReds.push(data);
            if ($scope.selectedReds.length > params.redLimit) {
                $scope.selectedReds.shift();
            }

        }
    };

    $scope.inArray = function (item, array) {
        return Picks.in_array(item, array);
    };

    $scope.randomSelected = function () {
        var randoms = Picks.random_nums(6, 35);
        $scope.selectedReds = randoms;
        randoms = Picks.random_nums(1, 12);
        $scope.selectedBlues = randoms;

    };

    $scope.animationsEnabled = true;
    $scope.isSubmit = false;
    $scope.submitPick = function () {
        $scope.isSubmit = true;
        //$uibModal.open({
        //    animation: $scope.animationsEnabled,
        //    templateUrl: 'modalContent.html',
        //    //controller: 'ModalInstanceCtrl',
        //    size: 'sm'
        //    //resolve: {
        //    //    items: function () {
        //    //        return $scope.items;
        //    //    }
        //    //}
        //});
        var verify = Picks.verify($scope.selectedBlues, $scope.selectedReds);
        if (verify.status) {
            // verify failed
            alert(verify.message);
            return;
        }

        //Basic.show();


        var now = $filter('date')(new Date(), 'yyyy-MM-dd HH:mm:ss');
        var pickNumbers = [];
        $scope.selectedReds.forEach(function (red) {
            pickNumbers.push(red);
        });
        console.log(pickNumbers);
        $scope.selectedBlues.forEach(function (blue) {
            pickNumbers.push(blue);
        });
        console.log(pickNumbers);
        console.log(now);
        console.log($scope.period.toString());



        $http.post(
            '/betting/bet',
            {
                pick_time: now,
                period: $scope.period.toString(),
                numbers: pickNumbers

            }

        ).success(function (data) {
            console.log(JSON.stringify(data));
            var modalInstance = $uibModal.open({
                animation: true,
                templateUrl: 'myModalContent.html',
                controller: 'ModalInstanceCtrl',
                windowTemplateUrl : '/lib/angular-bootstrap/template/modal/window.html',
                size: 'sm',
                resolve: {
                    message: function () {
                        return data.message;
                    },
                    status: function () {
                        return data.status;
                    }
                }
            });
           
        });


    }


    }]);

app.controller('ModalInstanceCtrl', function ($scope, $uibModalInstance, message, status, $window) {
    if (message != '') {
        $scope.message = message;
    } else {
        $scope.message = '投注成功';
    }
    //$scope.selected = {
    //    item: $scope.items[0]
    //};
    $scope.status = status;
    $scope.ok = function () {
        $uibModalInstance.close();
        $window.history.back();
    };

    $scope.cancel = function () {
        $uibModalInstance.dismiss('cancel');
        $window.history.back();
    };
});