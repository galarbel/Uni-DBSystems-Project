angular.module('app')
    .factory('server', function ($http, config) {
        var server = {};
        var url = config.server.url + ':' + config.server.port + "/";

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
            currentPage: 'day',
            ll: {
                lon: 0,
                lat: 0
            }
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
    });
