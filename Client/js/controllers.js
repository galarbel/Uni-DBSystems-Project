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
        $scope.gotoPlace = function (placeId) {
            state.currentPage = 'places';
            state.placeCurrentPage = 'place';
            state.currentPlaceId = placeId;
        }
    })
    .controller('dayCtrl', function ($scope, $mdDialog, state, lodash, placesConnection) {
        $scope.state = state;
        $scope.$watch('state.ll', function (v) {
            $scope.userLocation = v;
            console.log(v)
        }, true);

        $scope.openLocationDialog = function (ev) {
            $mdDialog.show({
                controller: 'locationFinderCtrl',
                templateUrl: 'html/sections/locationFinder.html',
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
        };
        $scope.openReplaceDialog = function (ev, replaceType) {
            placesConnection.getReplacement($scope.params, replaceType)
                .then(function (replacements) {
                    return $mdDialog.show({
                        controller: 'replaceDialogCtrl',
                        templateUrl: 'html/sections/replaceDialog.html',
                        targetEvent: ev,
                        locals: {
                            replacements: replacements
                        }
                    })
                })
                .then(function (replacement) {
                    var indexOfPlace = $scope.day.reduce(function (i, place, currentIndex) {
                        if (place._placeType == replacement._placeType) {
                            i = currentIndex;
                        }
                        return i;
                    }, -1);
                    if(indexOfPlace === -1){
                        console.error(replacement);
                        throw new Error("Can't find place to insert replacement");
                    }
                    $scope.day.splice(indexOfPlace, 1, replacement);//model change to trigger view change
                })
                .catch(function (err) {
                    console.error(err);
                });
        };

        $scope.findDay = function () {
            $scope.day = [];
            placesConnection.getDay($scope.params)
                .then(function (day) {
                    $scope.day = day;
                })
        };

        $scope.params = {
            price: 1,
            distance: 100,
            night: false,
            nightAndBreakfast: false
        }
    })
    .controller('locationFinderCtrl', function ($scope, $mdDialog, ll, geoGetter) {
        $scope.ll = ll;
        $scope.cancel = function () {
            $mdDialog.cancel();
        };
        $scope.save = function (data) {
            $mdDialog.hide(data);
        };
        $scope.setLocationFromBrowser = function () {
            geoGetter.get().then(function (ll) {
                $scope.ll = ll;
            })
        };
    })
    .controller('replaceDialogCtrl', function ($scope, $mdDialog, replacements) {
        $scope.replacements = replacements;
        $scope.cancel = function () {
            $mdDialog.cancel();
        };
        $scope.choose = function (data) {
            $mdDialog.hide(data);
        };
    })
    .controller('placesCtrl', function ($scope, state) {

    })
    .controller('placesSearchCtrl', function ($scope, state, $q, searchPlaces) {
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
                .then(function (places) {
                    $scope.places = places;
                    state.resultsQuery = searchedText;
                })
        }
    })
    .controller('placeDetailsCtrl', function ($scope, state, placesConnection) {
        $scope.init = function () {
            return placesConnection.getById(state.currentPlaceId)
                .then(function (place) {
                    $scope.imgStyle = imgStyle(place);
                    return $scope.place = place;
                })
                .catch(function (err) {
                    //TODO
                })
        }
        function imgStyle(place) {
            if (!place) {
                return {};
            }
            return {
                'background-image': 'url("' + place.image + '")'
            }
        }


    })