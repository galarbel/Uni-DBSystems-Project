angular.module('app')
    .controller('appCtrl', function ($scope,$rootScope, state, $mdToast) {
        $scope.state = state;
        var lastPage;
        $scope.$watch('state.currentPage', function (v) {
            lastPage = $scope.currentPage;
            $scope.currentPage = v;
        })
        $scope.goto = function (page) {
            state.currentPage = page;
            state.placeCurrentPage = 'search';
        }
        $scope.gotoPlace = function gotoPlace(placeId) {
            // if(!placeId) throw new Error('Missing place id in gotoPlace');
            state.currentPage = 'places';
            state.placeCurrentPage = 'place';
            state.currentPlaceId = placeId;
        }

        $rootScope.errToast = function (msg) {
            $mdToast.show(
                $mdToast.simple()
                    .textContent(msg)
                    .position('top right')
                    .hideDelay(0)
                    .action('ok')
                    // .capsule(true)
                    .theme('md-accent')
                    .highlightAction(true)
            );
        };
    })
    .controller('dayCtrl', function ($scope, $mdDialog, state, lodash, placesConnection, $mdToast) {
        $scope.state = state;
        $scope.$watch('state.ll', function (v) {
            $scope.userLocation = angular.extend({}, v);
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
            $scope.fetching = true;
            placesConnection.getReplacement($scope.params, replaceType)
                .then(function (replacements) {
                    $scope.fetching = false;
                    if (!replacements || !replacements.length) {
                        $scope.errToast("No Replacements Found");
                        return
                    }
                    return $mdDialog.show({
                        controller: 'replaceDialogCtrl',
                        templateUrl: 'html/sections/replaceDialog.html',
                        targetEvent: ev,
                        locals: {
                            replacements: replacements
                        }
                    });
                })
                .then(function (replacement) {
                    var indexOfPlace = $scope.day.reduce(function (i, place, currentIndex) {
                        if (place._placeType == replacement._placeType) {
                            i = currentIndex;
                        }
                        return i;
                    }, -1);
                    if (indexOfPlace === -1) {
                        console.error(replacement);
                        throw new Error("Can't find place to insert replacement");
                    }
                    $scope.day.splice(indexOfPlace, 1, replacement);//model change to trigger view change
                })
                .catch(function (err) {
                    console.error(err);
                    $scope.errToast("Something bad happened");
                });
        };

        $scope.findDay = function () {
            $scope.day = [];
            $scope.fetching = true;
            angular.extend($scope.params, $scope.userLocation);
            placesConnection.getDay($scope.params)
                .then(function (day) {
                    $scope.fetching = false;
                    $scope.day = day;
                })
                .catch(function (err) {
                    console.error(err);
                    $scope.errToast("Something bad happened");
                })
        };

        $scope.params = {
            price: 2,
            distance: 500,
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
    .controller('placesSearchCtrl', function ($scope, state, $q, placesConnection, staticServerData) {

        staticServerData.cities.then(function (cities) {
            $scope.cities = cities;
        });
        staticServerData.categories.then(function (categories) {
            $scope.categories = categories;
        });
        $scope.matchCity = function (query) {
            return $q.resolve($scope.cities)
                .then(function (cities) {
                    return cities.filter(function (city) {
                        return city.name.toLowerCase().indexOf(query.toLowerCase()) > -1;
                    })
                })
        }
        $scope.resetSearch = function () {
            $scope.fetching = false;
            $scope.currentPage = 0;
            $scope.searchText = '';
            $scope.showResults = false;
            $scope.params = {
                price: 3,
                minRating: 6.0
            };
            $scope.selectedCity = null;
            $scope.citySearchText = '';
            if ($scope.filtersForm) {
                $scope.filtersForm.$setPristine();
                $scope.filtersForm.$setUntouched();
            }

        };

        $scope.textSearch = function () {
            if (!$scope.searchText) {
                return; //todo report to user that there must be search text
            }
            $scope.showResults = false;
            var searchedText = $scope.searchText;

            $scope.places = [];
            $scope.fetching = true;
            placesConnection.textSearch($scope.searchText)
                .then(resultsHandler).then(function () {
                $scope.resultsQuery = searchedText;
            }).catch(function (err) {
                console.error(err);
                $scope.errToast("Something bad happened");
            })
        };
        $scope.filterSearch = function () {
            if ($scope.filtersForm.$invalid) {
                return; //todo report to user that there must be search text
            }
            $scope.fetching = true;
            placesConnection.filterSearch($scope.params)
                .then(resultsHandler)
                .catch(function (err) {
                    console.error(err);
                    $scope.errToast("Something bad happened");
                })
        };

        function resultsHandler(places) {
            $scope.places = places;
            $scope.showResults = true;
            $scope.fetching = false;
            return places;
        }

    })
    .controller('placeDetailsCtrl', function ($scope, state, placesConnection, $mdToast) {
        var newReview = {//const
            firstName: '',
            lastName: '',
            text: ''
        };
        $scope.newReview = angular.extend({}, newReview);

        $scope.init = function () {
            $scope.fetching = true;
            return placesConnection.getById(state.currentPlaceId)
                .then(function (place) {
                    $scope.fetching = false;
                    $scope.imgStyle = imgStyle(place);
                    return $scope.place = place;
                })
                .catch(function (err) {
                    $scope.errToast("Something bad happened");
                    console.error(err);
                })
        };


        function imgStyle(place) {
            if (!place) {
                return {};
            }
            return {
                'background-image': 'url("' + place.image + '")'
            }
        }

        $scope.addReview = function () {
            $scope.sendingReview = true;
            var sentReview = angular.extend({}, $scope.newReview);
            placesConnection.addReview($scope.place.id, sentReview)
                .then(function () {
                    sentReview.likes = 0;
                    sentReview.createdAt = new Date();
                    $scope.place.reviews.unshift(sentReview);
                    $scope.newReview = angular.extend({}, newReview)
                })
                .catch(function (err) {
                    console.error(err);
                    $scope.errToast("Something bad happened");
                })
                .finally(function () {
                    $scope.sendingReview = false;
                })

        };
        $scope.arrayRepeat = function (n) {
            var r = [];
            for (var i = 0; i < n; i++) {
                r.push(i);
            }
            return r;
        }
        $scope.addLike = function (review) {
            review._likeAdded = true;
            review.likes++;
            placesConnection.addLikeToReview(review.id)
                .then(function () {
                    //well done, nothing to do here
                })
                .catch(function (err) {
                    console.error(err);
                    $scope.errToast("Something bad happened");
                    review.likes--;
                })
        };

        $scope.refreshStatsFromFourSquare = function (placeId) {
            $scope.fetching = true;
            placesConnection.refreshStatsFromFourSquare(placeId)
                .then(function (newData) {

                    $scope.fetching = false;
                    angular.extend($scope.place, newData);
                })
                .catch(function (error) {
                    $scope.errToast("Something bad happened");
                    console.error(error);
                })
        }


    })
    .controller('travelCtrl', function ($scope, placesConnection, staticServerData) {
        staticServerData.categories.then(function (categories) {
            $scope.categories = categories;
        });

        $scope.init = function () {
            $scope.categoryId = null;
            $scope.price = 2;
            $scope.city = null;
        };

        $scope.getCity = function () {
            if (!$scope.categoryId) {
                return;
            }
            placesConnection.getCityRecommendation($scope.categoryId, $scope.price)
                .then(function (city) {
                    $scope.city = city;
                })
                .catch(function (err) {
                    console.error(err);
                    $scope.errToast("Something bad happened");
                })
        }
    });