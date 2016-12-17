angular.module('app')
/*    .config(["$locationProvider", function ($locationProvider) {
        $locationProvider.html5Mode(true);
    }])*/
    .config(function ($stateProvider, $urlRouterProvider) {
        $urlRouterProvider.when('', '/day-trip');
        $urlRouterProvider.otherwise('/day-trip');//or maybe add 404 page?
        $stateProvider
            .state('app', {
                views: {
                    'header': {
                        templateUrl: '/html/sections/header.html',
                        controller: 'headerCtrl'
                    },
                    'main': {
                        template: '<div ui-view></div>'
                    }
                }
            })
            .state('app.dayTrip', {
                url: '/day-trip',
                templateUrl: '/html/views/trip-planner.html'
            })
            .state('app.find', {
                url: '/find',
                templateUrl: '/html/views/find.html'
            })

    })
