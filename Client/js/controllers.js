angular.module('app')
    .controller('appCtrl', function ($scope) {

    })
    .controller('headerCtrl', function ($scope) {

    })
    .controller('tripCtrl', function ($scope,tripPlanner) {
        $scope.trip = tripPlanner.getCurrentTrip();
        $scope.types = [
            {
                name: 'Solo',
                id: 0
            },
            {
                name: 'Group',
                id: 1
            }
        ];
        $scope.type = 1;

        $scope.findTrip = function(){
            //TODO
        }
    })