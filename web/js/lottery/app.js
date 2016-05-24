var app = angular.module('myApp', ['ngRoute', 'ngAnimate', 'toaster', 'ngSanitize', 'mgcrea.ngStrap', 'ui.bootstrap']);

app.config(['$locationProvider', '$routeProvider', '$httpProvider', function ($locationProvider, $routeProvider, $httpProvider) {

    var modulesPath = '/js/lottery/modules';

    $routeProvider

        .when('/award/history', {
            templateUrl: modulesPath + '/award/views/history.html',
            controller: 'AwardIndex'

        })


        .when('/betting/index', {
            templateUrl: modulesPath + '/betting/views/index.html',
            controller: 'BettingIndex'

        })

        .when('/betting/detail/:id', {
            templateUrl: modulesPath + '/betting/views/detail.html',
            controller: 'BettingDetail'

        })


        .when('/pick/index', {
            templateUrl: modulesPath + '/pick-numbers/views/index.html',
            controller: 'PickNumbers'

        })


        .when('/', {
            templateUrl: modulesPath + '/site/views/main.html',
            controller: 'SiteIndex'
        })

        .when('/login', {
            templateUrl: modulesPath + '/site/views/login.html',
            controller: 'SiteLogin'
        })

        .when('/post/published', {
            templateUrl: modulesPath + '/post/views/index.html',
            controller: 'PostIndex',
            resolve: {
                status: function () {
                    return 2;
                }
            }
        })

        .when('/post/draft', {
            templateUrl: modulesPath + '/post/views/index.html',
            controller: 'PostIndex',
            resolve: {
                status: function () {
                    return 1;
                }
            }
        })

        .when('/post/create', {
            templateUrl: modulesPath + '/post/views/form.html',
            controller: 'PostCreate'
        })

        .when('/post/:id/edit', {
            templateUrl: modulesPath + '/post/views/form.html',
            controller: 'PostEdit'
        })

        .when('/post/:id/delete', {
            templateUrl: modulesPath + '/post/views/delete.html',
            controller: 'PostDelete'
        })

        .when('/post/:id', {
            templateUrl: modulesPath + '/post/views/view.html',
            controller: 'PostView'
        })

        .when('/404', {
            templateUrl: '404.html'
        })

        .otherwise({redirectTo: '/404'})
    ;

    $locationProvider.html5Mode(false).hashPrefix('!');
    $httpProvider.interceptors.push('authInterceptor');
}]);

app.constant('params', {
    redLimit: 6,
    blueLimit: 1,
    baseUrl: 'http://qy1.appcq.cn:8085/'
});

app.factory('authInterceptor', function ($q, $window) {
    return {
        request: function (config) {
            if ($window.sessionStorage._auth && config.url.substring(0, 4) == 'http') {
                config.params = {'access-token': $window.sessionStorage._auth};
            }
            return config;
        },
        responseError: function (rejection) {
            if (rejection.status === 401) {
                $window.setTimeout(function () {
                    $window.location = '/#!/login';
                }, 1000);
            }
            return $q.reject(rejection);
        }
    };
});

app.value('app-version', '0.0.3');

// Need set url REST Api in controller!
app.service('rest', function ($http, $location, $routeParams) {

    return {

        baseUrl: 'http://qy1.appcq.cn:8085/',
        path: undefined,

        models: function () {
            if (this.path.indexOf('?') > -1) {
                return $http.jsonp(this.baseUrl + this.path + location.search + '&callback=JSON_CALLBACK');
            } else {
                return $http.jsonp(this.baseUrl + this.path + location.search + '?callback=JSON_CALLBACK');
            }

        },

        model: function () {
            if ($routeParams.expand != null) {
                return $http.jsonp(this.baseUrl + this.path + "/" + $routeParams.id + '?expand=' + $routeParams.expand + '&callback=JSON_CALLBACK');
            }
            return $http.jsonp(this.baseUrl + this.path + "/" + $routeParams.id);
        },

        get: function () {
            return $http.jsonp(this.baseUrl + this.path);
        },

        postModel: function (model) {
            return $http.post(this.baseUrl + this.path, model);
        },

        putModel: function (model) {
            return $http.put(this.baseUrl + this.path + "/" + $routeParams.id, model);
        },

        deleteModel: function () {
            return $http.delete(this.baseUrl + this.path);
        }
    };

});

app.factory("Picks", function () {
    return {
        current_period:'',
        init_red: function () {
            var input = [];
            var tmp = [];
            for (var i = 1; i <= 40; i++) {
                if (i < 10) {
                    tmp.push('0' + i);
                } else {
                    tmp.push(i);
                }
                if (i % 6 == 0) {
                    input.push(tmp);
                    tmp = [];
                }
            }
            input.push(tmp);
            return input;
        },

        init_blue: function () {
            var input = [];
            var tmp = [];
            for (var i = 1; i <= 16; i++) {
                if (i < 10) {
                    tmp.push('0' + i);
                } else {
                    tmp.push(i);
                }
                if (i % 6 == 0) {
                    input.push(tmp);
                    tmp = [];
                }
            }
            input.push(tmp);
            return input;
        },
        random_nums: function (count, max) {
            var arr = [];
            while (arr.length < count) {
                var randomnumber = Math.ceil(Math.random() * max);
                var found = false;
                for (var i = 0; i < arr.length; i++) {
                    if (arr[i] == randomnumber) {
                        found = true;
                        break
                    }
                }
                if (!found) {
                    if (randomnumber < 10) {
                        arr[arr.length] = '0' + randomnumber;
                    } else {
                        arr[arr.length] = randomnumber;
                    }

                }
            }
            return arr;
        },
        verify: function (blues, reds) {
            if (blues.length < 1) {
                return {
                    status: -1,
                    message: "蓝球至少选择一个"
                };
            }
            if (reds.length < 5) {
                return {
                    status: -1,
                    message: "红球至少选择一个"
                };
            }
            return {
                status: 0,
                message: ""
            };

        },
        submit: function () {


        },
        in_array: function (item, array) {
            return (-1 !== array.indexOf(item));
        }
    };
});
app.factory("UserApi", function (params, $http, $window) {
    return {
        get:function (url, data, successCallback, failCallback) {
            var token = 'qYk7qTrnrwbEDE4RyjHV-JWHj2Rlx9Us';
            console.log(token);
            var params = JSON.stringify(data);
            var config = {
                headers: {
                    "Content-Type":"application/json",
                    "Authorization": "Bearer " + token
                }
            };
            $http.jsonp(url + '?callback=JSON_CALLBACK&data=' + params, config).then(successCallback, failCallback);

        }


    };

});
app
    .directive('login', ['$http', function ($http) {
        return {
            transclude: true,
            link: function (scope, element, attrs) {
                scope.isGuest = window.sessionStorage._auth == undefined;
            },

            template: '<a href="login" ng-if="isGuest">Login</a>'
        }
    }])
    .filter('checkmark', function () {
        return function (input) {
            return input ? '\u2713' : '\u2718';
        };
    });
