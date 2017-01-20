angular.module('app')
    .directive('mapToLl', function (lodash, NgMap, config) {
        return {
            restrict: 'E',
            templateUrl: 'html/directives/mapToLL.html',
            scope: {
                lat: '=',
                lon: '='
            },
            link: function (scope) {
                scope.API_KEY = config.googleMapsApiKey;
                scope.$watchGroup(['lat', 'lon'], function () {
                    NgMap.getMap().then(function (map) {
                        map.panTo({
                            lat: scope.lat,
                            lng: scope.lon
                        })
                    })
                })
                scope.mapClicked = function (ev) {
                    var ll = {
                        lon: ev.latLng.lng(),
                        lat: ev.latLng.lat()
                    };
                    lodash.extend(scope, ll);
                }
            }
        }
    })
    .directive('placeListItemContent', function () {
        return {
            restrict: 'A',
            templateUrl: 'html/directives/placeListItemContent.html',
            scope: {
                place: '=placeListItemContent'
            }
        }
    })