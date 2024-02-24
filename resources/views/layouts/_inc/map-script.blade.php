@if(!request()->has('_googleMapIncScript'))
<script
    async
    defer
    src="https://maps.googleapis.com/maps/api/js?key={{env('GOOGLE_API_KEY', 'AIzaSyADxZHHeyV2FS1vc0G2eemP-bgmhS3SgC8')}}&callback=initialize">

</script>
@php
request()->merge(['_googleMapIncScript' => 1])
@endphp
@endif
