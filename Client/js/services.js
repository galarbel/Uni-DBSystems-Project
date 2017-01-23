angular.module('app')
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
        };

        server.filterSearch = function (params) {
            return $http.get(url + 'get_places_by_parameters.php', {
                params: params
            }).then(extractData)
        };

        server.placeDetails = function (placeId) {
            return $http.get(url + 'get_location_page_details.php', {
                params: {
                    place_id: placeId
                }
            }).then(extractData)
        };
        server.placeDetailsRefresh = function (placeId) {
            return $http.get(url + 'update_place_stats.php', {
                params: {
                    place_id: placeId
                }
            }).then(extractData)
        };

        server.recommendCity = function (params) {
            return $http.get(url + 'get_recommended_city.php', {
                params: params
            }).then(extractData)
        };

        server.addReview = function (id, review) {
            var body = angular.extend({}, review, {place_id: id});
            return $http.post(url + 'add_review.php', body)
        };

        server.addLike = function (reviewId) {
            return $http.get(url + 'add_review_like.php', {
                params: {
                    review_id: reviewId
                }
            })
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

    .factory('placesConnection', function ($http, $q, $timeout, lodash, server) {
        function _transformPlace(place) {
            if (place.reviews) {
                place.reviews = place.reviews.map(function (review) {
                    return {
                        id: review.review_id,
                        likes: review.likes,
                        firstName: review.first_name || undefined,
                        lastName: review.last_name || undefined,
                        createdAt: new Date(review.createdAt),
                        text: review.content
                    }
                })
            }
            return {
                id: place.place_id,
                name: place.place_name,
                city: place.city_name,
                state: place.state_code,
                image: place.photo_url || place.photo,
                country: place.country_name,
                address: place.address,
                rating: place.rating,
                _placeType: place._placeType,
                categoriesArray: place.categoriesArray,
                reviews: place.reviews,
                phone: place.phone,
                price: place.price,
                checkins: place.checkins_count,
                users: place.users_count,
                likes: place.likes,
                url: place.url,
                lastUpdated: place.stats_last_update
            }
        }

        function _transformPlaces(places) {
            return places.map(_transformPlace);
        }

        function _logAndThrow(err) {
            console.error(err);
            throw err;
        }

        function getById(id) {
            return server.placeDetails(id)
                .then(_transformPlace)
                .catch(_logAndThrow);
            /* //mock
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
             })*/
        }

        function addReview(placeId, review) {
            return server.addReview(placeId, {
                review_text: review.text,
                first_name: review.firstName,
                last_name: review.lastName || undefined
            })
                .catch(_logAndThrow);
        }

        function addLikeToReview(reviewId) {
            return server.addLike(reviewId);
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
                    return lodash.map(day, function (place, key) {//converts to array with type inside
                        place._placeType = key;
                        return place;
                    });
                })
                .then(_transformPlaces)
                .catch(_logAndThrow);

            /* //TODO
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
             return $q.resolve(myResponse);*/
        }

        /**
         *
         * @param params {Object} same as params of getDay
         * @param replaceType {string} "Enum" representing if it's evening, breakfast etc.
         */
        function getReplacement(params, replaceType) {
            var typeEnum = {
                morning: 1,
                lunch: 2,
                dinner: 3,
                night: 4
            };
            return server.replace(angular.extend(_transformDayParams(params), {
                meal: typeEnum[replaceType]
            }))
                .then(_transformPlaces)
                .then(function (replacements) {
                    replacements.forEach(function (place) {
                        place._placeType = replaceType;
                    });
                    return replacements
                })
        }

        function refreshStatsFromFourSquare(placeId) {
            return server.placeDetailsRefresh(placeId)
                .then(_transformPlace)
        }

        function getCityRecommendation(categoryId, price) {
            return server.recommendCity({
                categoryId: categoryId,
                price: price
            })
                .then(function (city) {
                    return {
                        name: city.city_name,
                        score: city.avg_score
                    }
                })
                .catch(_logAndThrow)
        }


        /**
         * query string is search text
         */
        function textSearch(queryString) {
            return server.textSearch(queryString)
                .then(_transformPlaces)
                .catch(_logAndThrow);

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
            return server.filterSearch({
                city: params.city,
                category: params.category,
                price: params.price,
                meal: params.type,
                m_rating: params.minRating
            });
            //mock
            /*var places = [];
             for (var i = skip; i < skip + limit; i++) {
             places.push({
             id: 'mockId' + i,
             name: 'Name' + i,
             description: 'Desc'
             })
             }
             return $timeout(angular.noop, 350).then(function () {
             return places;
             })*/
        }

        return {
            getById: getById,
            addReview: addReview,
            addLikeToReview: addLikeToReview,
            getDay: getDay,
            getReplacement: getReplacement,
            refreshStatsFromFourSquare: refreshStatsFromFourSquare,
            getCityRecommendation: getCityRecommendation,
            textSearch: textSearch,
            filterSearch: filterSearch
        }
    })
    .factory('staticServerData', function (server, lodash, $q) {
        function error(e) {
            console.error("Error loading resource", e)
        }

        /*        function error() {
         //mock returner
         return [{
         id: 1,
         name: "Name"
         }];
         }*/

        var getters = {
            cities: server.getCities().catch(error)
                .then(function (cities) {
                    return cities.map(function (city) {
                        return {
                            name: city.city_name,
                            id: city.city_id
                        }
                    })
                }),
            categories: server.getCategories().catch(error)
                .then(function (cats) {
                    return cats.map(function (cat) {
                        return {
                            name: cat.category_name,
                            id: cat.category_id
                        }
                    })
                }),
        };
        lodash.forEach(getters, function (getter, getterName) {
            getter.then(function (response) {
                getters[getterName] = $q.resolve(response);
            })
        })
        return getters;
    });
