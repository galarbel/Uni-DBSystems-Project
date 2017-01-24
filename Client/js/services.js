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
            return $http.get(url + 'search_place_by_text.php', {
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
            return $http.get(url + 'get_meal_replacement_options.php', {
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
            currentPage: 'day',//or 'places' or 'travel'
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
                rating: Math.round(10*place.rating)/10,
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
                    if(!city){
                        return null;
                    }
                    return {
                        name: city.city_name,
                        state: city.state_code,
                        country: city.country_name,
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
        }

        function filterSearch(params) {
            return server.filterSearch({
                city: params.city,
                category: params.category,
                price: params.price,
                meal: params.type,
                m_rating: params.minRating
            }).then(_transformPlaces)
                .catch(_logAndThrow);

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
    .factory('staticServerData', function (server, lodash, $q, $rootScope) {
        function error(e) {
            console.error("Error loading resource", e);
            $rootScope.errToast("Problem loading options for form")
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
