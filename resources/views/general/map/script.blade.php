@include('layouts._inc.map-script')
<script>
    var markers = [];
    var map;
    var startLat = '{{ $startLat }}';
    var startLng = '{{ $startLng }}';
    var startInfo = @json($startInfo);
    var endLat = '{{ $endLat }}';
    var endLng = '{{ $endLng }}';
    var endInfo = @json($endInfo);
    var directionsDisplay;
    var infowindow = [];
    var labelIndex = 0;
    var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

    // initial function calling after google map api responded
    function initialize() {
        var startlocation = new google.maps.LatLng(9.714147, 80.105442);
        var myOptions = {
            zoom: 12,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
            draggableCursor: "pointer",
            center: startlocation,
        };
        // map
        map = new google.maps.Map(document.getElementById("map"), myOptions);

        if (startLat && startLng) {
            var markerPoint = getLngLat(startLat, startLng);
            addMarker(markerPoint);
        }
        if (endLat && endLng) {
            var markerPoint2 = getLngLat(endLat, endLng);
            addMarker(markerPoint2, true);
        }

    }

    var icon = '{{ asset('images/icon/cart.png') }}';

    function addMarker(latLng, end) {
        var marker = new google.maps.Marker({
            position: latLng,
            map: map,
            label: labels[labelIndex++ % labels.length],
            animation: google.maps.Animation.DROP,
            draggable: false,
            icon: end ? icon : ''
        });

        markers.push(marker);

        var contentString = '';
        contentString = getContent(contentString, end);
        addInfo(marker, contentString);
        marker.addListener('click', function () {
            infowindow[markers.indexOf(marker)].open(map, marker);
        });
    }

    function getContent(contentString, end) {
        if (startInfo && !end) {
            contentString = '<div style="width: 350px;" id="content">' +
                '<h1 id="firstHeading" style="font-size: 16px !important;font-weight: 600;line-height: 25px;" class="firstHeading">' + startInfo.heading + '</h1>' +
                '<div id="bodyContent">';
            $.each(startInfo, function (k, v) {
                if (k !== 'heading') {
                    contentString += '<p>' + v + ' </p> ';
                }
            });
            contentString += '</div> </div>';
        }
        if (end && endInfo) {
            contentString = '<div style="width: 350px;" id="content">' +
                '<h1 id="firstHeading" style="font-size: 16px !important;font-weight: 600;line-height: 25px;" class="firstHeading">' + endInfo.heading + '</h1>' +
                '<div id="bodyContent">';
            $.each(endInfo, function (k, v) {
                if (k !== 'heading') {
                    contentString += '<span>' + v + ' </span> <br />';
                }
            });
            contentString += '</div> </div>';
        }
        return contentString;
    }

    function getLngLat(lat, lng) {
        return new google.maps.LatLng(lat, lng);
    }

    function addInfo(marker, contentString) {
        if (contentString) {
            var info = new google.maps.InfoWindow({
                content: contentString
            });
            info.open(map, marker);
            infowindow[markers.indexOf(marker)] = info;
        }
    }

    function serializeLatLng(ll) {
        if (ll) {
            return {
                lat: ll.lat(),
                lng: ll.lng()
            }
        }
    }
</script>