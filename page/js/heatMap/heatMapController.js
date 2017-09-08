var taxiData = [
    new google.maps.LatLng(4.651006, -74.066541),
    new google.maps.LatLng(4.677319, -74.110354),
    new google.maps.LatLng(4.674245, -74.104251),
    new google.maps.LatLng(4.689753, -74.068944),
    new google.maps.LatLng(4.738853, -74.090383),
    new google.maps.LatLng(4.739813, -74.087815),
    new google.maps.LatLng(4.733947, -74.094018),
    new google.maps.LatLng(4.734866, -74.097512),
    new google.maps.LatLng(4.735668, -74.095999),
    new google.maps.LatLng(4.738214, -74.099485)
];
angular.module('bipoApp').controller('HeatMapCtrl',['NgMap','heatMapResource',function (NgMap,heatMapResource) {
    heatMapResource.query(function (completed, headers) {
        console.log(completed);
    })
    var heatmap, vm = this;
    vm.googleMapsUrl="https://maps.googleapis.com/maps/api/js?key=AIzaSyDZm14lpvD7-Pahl6cCSwIXAlquw1p46-U&callback=initMap"

    NgMap.getMap().then(function(map) {
        vm.map = map;
        heatmap = vm.map.heatmapLayers.foo;
    });
    vm.toggleHeatmap= function(event) {
        heatmap.setMap(heatmap.getMap() ? null : vm.map);
        vm.changeGradient();
    };
    vm.changeGradient = function() {
        var gradient = [
            'rgba(0, 255, 255, 0)',
            'rgba(0, 255, 255, 1)',
            'rgba(0, 191, 255, 1)',
            'rgba(0, 127, 255, 1)',
            'rgba(0, 63, 255, 1)',
            'rgba(0, 0, 255, 1)',
            'rgba(0, 0, 223, 1)',
            'rgba(0, 0, 191, 1)',
            'rgba(0, 0, 159, 1)',
            'rgba(0, 0, 127, 1)',
            'rgba(63, 0, 91, 1)',
            'rgba(127, 0, 63, 1)',
            'rgba(191, 0, 31, 1)',
            'rgba(255, 0, 0, 1)'
        ]
        heatmap.set('gradient', heatmap.get('gradient') ? null : gradient);
    }
    vm.changeRadius = function() {
        heatmap.set('radius', heatmap.get('radius') ? null : 20);
    }
    vm.changeOpacity = function() {
        heatmap.set('opacity', heatmap.get('opacity') ? null : 0.2);
    }
}]);