@include('layouts._inc.map-script')
<script>
    var directionsService;

    function initialize() {
        directionsService = new google.maps.DirectionsService();
    }

    function getLngLat(lat, lng) {
        return new google.maps.LatLng(lat, lng);
    }

    function getDistance(startLat, startLng, destinationLat, destinationLng, route) {
        var origin = getLngLat(startLat, startLng);
        var destination = getLngLat(destinationLat, destinationLng);
        var distance = google.maps.geometry.spherical.computeDistanceBetween(origin, destination);
        if (route) {
            updateDistance(route, distance);
        }
        return (distance / 1000);
    }

    function getDistanceNoRoute(startLat, startLng, destinationLat, destinationLng) {
        var origin = getLngLat(startLat, startLng);
        var destination = getLngLat(destinationLat, destinationLng);
        var distance = google.maps.geometry.spherical.computeDistanceBetween(origin, destination);
        return (distance / 1000);
    }

    function updateDistance(route, distance) {
        $.ajax({
            method: "POST",
            url: route,
            data: {distance: (distance / 1000), "_token": "{{ csrf_token() }}"}
        }).done(function (msg) {
            console.log(msg);
        });
    }
</script>