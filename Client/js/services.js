angular.module('app')
    .factory('util',function(){
        return {
            /*imgObjToUrl : function(imageObj){
                var size = Math.min(imageObj.width,imageObj.height);
                return imageObj.prefix + size + 'x' + size + imageObj.suffix;
            }*/
        }
    })
    .factory('server', function ($http, config) {
        var server = {};
        var url = server.url = config.server.url + ':' + config.server.port + "/";

        server.getEnums = function getEnums() {
            return $http.get(url + "enums");
        };
        server.findTrip = function findTrip(params) {
            return $http.get(url + 'trip', {
                params: params
            })
        };

        return server;
    })
    .factory('tripPlanner', function (server) {
        var planner = {};
        var trip;

        function resetTrip() {
            trip = null;
        }

        planner.findTrip = function findTrip(params) {
            return server.findTrip(params)
                .then(function (_trip) {
                    trip = _trip;
                    return trip;
                });
        };
        planner.getCurrentTrip = function getCurrentTrip() {
            return trip;
        };
        planner.getModifiedTrip = function getModifiedTrip(params) {
            // if (trip) {
            //     params.last_trip_id = trip.id;
            //     return findTrip(params);
            // }
            // else {
            //     //TODO
            // }
        };
        //init
        resetTrip();
        return planner;
    })
    .factory('state', function () {
        return {
            currentPage: 'day',//or 'places'
            placeCurrentPage: 'search',//or 'place'
            ll: {
                lon: 0,
                lat: 0
            },
            resultsQuery: '',
            searchResults: null
        }
    })
    .factory('geoGetter', function ($geolocation) {
        function get() {
            console.log("Trying to get user location from browser");
            return $geolocation.getCurrentPosition({
                enableHighAccuracy: true
            })
                .then(function (location) {
                    var lat = location.coords.latitude;
                    var lon = location.coords.longitude;
                    return {
                        lat: lat,
                        lon: lon
                    }
                })
        }

        return {
            get: get
        }
    })
    .factory('searchPlaces', function ($http, $q, $timeout) {
        /**
         * query string is search text
         */
        return function searchPlaces(queryString, limit, skip) {
            //mock
            var places = [];
            for (var i = skip; i < skip + limit; i++) {
                places.push({
                    id: 'mockId' + i,
                    name: 'Name' + i,
                    description: 'Desc'
                })
            }
            return $timeout(angular.noop, 350).then(function () {
                return places;
            })
        }
    })
    .factory('placeConnection', function ($http, $q, $timeout) {
        function getById(id) {
            //mock
            return $timeout(angular.noop, 150)
                .then(function () {
                    return {
                        name: 'Place Name',
                        categoryName: "Category Name",
                        state: 'NY',
                        city: 'New York',
                        address: '1st Avenue 5342543',
                        url: 'http://www.google.com',
                        phone: '054-7564553',
                        price: 3,
                        reviews: [
                            {
                                text: 'lorem ipsum',
                                likes: 5
                            }
                        ],
                        image: 'https://irs0.4sqi.net/img/general/500x500/11449240_IgInJOEVwqZhbTA90Dx-M7S0Kmuz3bAGm_uiP5w3LFg.jpg'
                    }
                })
        }

        function addReview(placeId, review) {
            //TODO
            return $q.resolve(review);
        }

        function addLikeToReview(placeId, reviewId) {
            return $q.resolve();
        }

        function refreshPlaceData(id) {
            //TODO make the server refresh likes to this place
        }

        return {
            getById: getById,
            addReview: addReview,
            addLikeToReview: addLikeToReview,
            refreshPlaceData: refreshPlaceData
        }
    })
