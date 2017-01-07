angular.module('app')
    .directive('mapToLl', function (lodash,NgMap) {
        return {
            restrict: 'E',
            templateUrl: 'html/directives/mapToLL.html',
            scope: {
                lat: '=',
                lon: '='
            },
            link: function(scope){
                scope.$watchGroup(['lat', 'lon'],function(){
                    NgMap.getMap().then(function(map){
                        map.panTo({
                            lat: scope.lat,
                            lng: scope.lon
                        })
                    })
                })
                scope.mapClicked = function(ev){
                    var ll = {
                        lon: ev.latLng.lng(),
                        lat: ev.latLng.lat()
                    };
                    lodash.extend(scope,ll);
                }
            }
        }
    })