angular.module('app')
    .controller('appCtrl', function ($scope, state) {
        $scope.state = state;
        var lastPage;
        $scope.$watch('state.currentPage', function (v) {
            lastPage = $scope.currentPage;
            $scope.currentPage = v;
        })
        $scope.goto = function (page) {
            state.currentPage = page;
        }
    })
    /*    .controller('headerCtrl', function ($scope) {

     })*/
    .controller('dayCtrl', function ($scope, $mdDialog, state, lodash) {
        $scope.state = state;
        $scope.$watch('state.ll', function (v) {
            $scope.userLocation = v;
            console.log(v)
        }, true);

        $scope.openLocationDialog = function (ev) {
            $mdDialog.show({
                controller: 'locationFinderCtrl',
                templateUrl: 'html/directives/locationFinder.html',
                targetEvent: ev,
                locals: {
                    ll: lodash.extend({}, state.ll)
                },
                fullscreen: true
                // clickOutsideToClose:true
            })
                .then(function (ll) {
                    state.ll = ll;
                })
                .catch(function (err) {
                    console.log(err);
                });
        }
    })
    .controller('locationFinderCtrl', function ($scope, $mdDialog, ll, geoGetter) {
        $scope.ll = ll;
        $scope.cancel = function () {
            $mdDialog.cancel();
        }
        $scope.save = function (data) {
            $mdDialog.hide(data);
        }
        $scope.setLocationFromBrowser = function () {
            geoGetter.get().then(function (ll) {
                $scope.ll = ll;
            })
        }
    })
    .controller('placesCtrl', function ($scope, state) {
        $scope.state = state;

    })
    .controller('placesSearchCtrl', function ($scope, state, $q, searchPlaces) {
        $scope.state = state;
        var pageSize = 20;
        $scope.currentPage = 0;
        $scope.searchText = '';
        $scope.search = function (pageNumber) {
            if (!$scope.searchText) {
                return; //todo report to user that there must be search text
            }
            var searchedText = $scope.searchText;
            pageNumber = pageNumber || 0;
            $scope.currentPage = pageNumber;

            //mock creation
            $scope.places = [];
            searchPlaces($scope.searchText, pageSize, pageNumber * pageSize)
                .then(function (places) {//TODO
                    $scope.places = places;
                    state.resultsQuery = searchedText;
                })


        }

    })