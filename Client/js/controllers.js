angular.module('app')
    .controller('appCtrl', function ($scope) {

    })
    .controller('headerCtrl', function ($scope) {

    })
    .controller('tripCtrl', function ($scope,tripPlanner) {
        $scope.trip = tripPlanner.getCurrentTrip();
    })