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
        function extractData(response) {
            console.log(response.config.url);
            console.dir(response);
            console.dir(response.data);
            return response.data;
        }

        var server = {};
        var url = server.url = config.server.url;

        server.getCities = function getCities() {
            return $http.get(url + 'get_all_cities.php').then(extractData);
        };
        server.getCategories = function getCategories() {
            return $http.get(url + 'get_all_categories.php').then(extractData);
        };

        server.textSearch = function (searchText) {
            return $http.get(url + 'search_venue.php', {
                params: {
                    text: searchText
                }
            }).then(extractData)

        };

        server.day = function (params) {
            return $http.get(url + 'get_day_plan.php', {
                params: params
            }).then(extractData)
        };
        server.replace = function (params) {
            return $http.get(url + 'get_day_plan_options.php', {
                params: params
            }).then(extractData)
        }

        return server;
    })
    .factory('state', function () {
        return {
            currentPage: 'day',//or 'places'
            placeCurrentPage: 'search',//or 'place'
            ll: {
                lon: -74,
                lat: 41
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
    .factory('placesConnection', function ($http, $q, $timeout, lodash, server) {
        function _transformPlace(place) {
            return {
                id: place.place_id,
                name: place.place_name,
                city: place.city_name,
                state: place.state_code,
                image: place.photo_url,
                country: place.country_name,
                address: place.address,
                rating: place.rating
            }
        }

        function _transformPlaces(places) {
            return places.map(_transformPlace);
        }

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

        function _transformDayParams(params) {
            return {
                latitude: params.lat,
                longitude: params.lon,
                price: params.price,
                night_person: params.night ? 1 : 0,
                force_morning: params.nightAndBreakfast ? 1 : 0,
                distance: params.distance
            }
        }

        function getDay(params) {
            return server.day(_transformDayParams(params))
                .then(function (day) {
                    // var myResponse = lodash.map(rawResponse, function (place, key) {//converts to array with type inside
                    //     place._placeType = key;
                    //     return place;
                    // });
                    debugger;
                    return day;
                })

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
                lunch: {
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
            var myResponse = lodash.map(rawResponse, function (place, key) {//converts to array with type inside
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
            data.forEach(function (place) {
                place._placeType = replaceType;
            });
            return $q.resolve(data)
        }

        function refreshStatsFromFourSquare(placeId) {
            return $q.resolve({});
        }

        function getCityRecommendation(categoryId, price) {
            return $q.resolve();
        }


        /**
         * query string is search text
         */
        function textSearch(queryString, limit, skip) {
            return server.textSearch(queryString)
                .then(_transformPlaces)
                .catch(function (err) {
                    console.error(err);
                })

            //mock
            // var places = [];
            // for (var i = skip; i < skip + limit; i++) {
            //     places.push({
            //         id: 'mockId' + i,
            //         name: 'Name' + i,
            //         description: 'Desc'
            //     })
            // }
            // return $timeout(angular.noop, 350).then(function () {
            //     return places;
            // })
        }

        function filterSearch(params, limit, skip) {
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
            getById: getById,
            addReview: addReview,
            addLikeToReview: addLikeToReview,
            refreshPlaceData: refreshPlaceData,
            getDay: getDay,
            getReplacement: getReplacement,
            refreshStatsFromFourSquare: refreshStatsFromFourSquare,
            getCityRecommendation: getCityRecommendation,
            textSearch: textSearch,
            filterSearch: filterSearch
        }
    })
    .factory('staticServerData', function (server) {
        /*function error(e){
         console.error("Error loading resource",e)
         }*/
        function error() {
            //mock returner
            return [{
                id: 1,
                name: "Name"
            }];
        }

        return {
            cities: server.getCities().catch(error),
            categories: server.getCategories().catch(error),
        }
    });
