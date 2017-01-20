angular.module('app')
    .factory('util', function () {
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
        function textSearch(queryString, limit, skip) {
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

        function filterSearch(params,limit,skip){
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

        return {
            textSearch: textSearch,
            filterSearch: filterSearch
        }
    })
    .factory('placesConnection', function ($http, $q, $timeout,lodash) {
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

        function getDay(params) {
            //TODO
            var rawResponse = {
                morning: {
                    name: 'Name',
                    category_name: 'Category Name',
                    url: 'http://google.com',
                    phone: '(054) 7564553',
                    address: 'TAU',
                    image: 'https://bower.io/img/bower-logo.png'
                },
                lunch :{
                    name: 'Name',
                    category_name: 'Category Name',
                    url: 'http://google.com',
                    phone: '(054) 7564553',
                    address: 'TAU',
                    image: 'https://bower.io/img/bower-logo.png'
                },
                dinner: {
                    name: 'Name',
                    category_name: 'Category Name',
                    url: 'http://google.com',
                    phone: '(054) 7564553',
                    address: 'TAU',
                    image: 'https://bower.io/img/bower-logo.png'
                },
                night: {
                    name: 'Name',
                    category_name: 'Category Name',
                    url: 'http://google.com',
                    phone: '(054) 7564553',
                    address: 'TAU',
                    image: 'https://bower.io/img/bower-logo.png'
                }
            };
            var myResponse = lodash.map(rawResponse,function(place,key){//converts to array with type inside
                place._placeType = key;
                return place;
            });
            return $q.resolve(myResponse);
        }

        /**
         *
         * @param params {Object} same as params of getDay
         * @param replaceType {string} "Enum" representing if it's evening, breakfast etc.
         */
        function getReplacement(params, replaceType) {
            //TODO
            //mock
            var mock = {
                name: 'replacement',
                category_name: 'Category Name',
                url: 'http://google.com',
                phone: '(054) 7564553',
                address: 'TAU',
                image: 'https://bower.io/img/bower-logo.png'
            };
            mock = [1, 2, 3, 4, 5].map(function () {
                return mock;
            });
            var data = mock;
            //real code
            data.forEach(function(place){
                place._placeType = replaceType;
            })
            return $q.resolve(data)
        }

        return {
            getById: getById,
            addReview: addReview,
            addLikeToReview: addLikeToReview,
            refreshPlaceData: refreshPlaceData,
            getDay:getDay,
            getReplacement:getReplacement
        }
    })
