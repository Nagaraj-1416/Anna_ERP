@if($address)
    <p class="text-muted">
        <small>
            {{ $address->street_one }}
            @if($address->street_two)
                {{ $address->street_two }},
            @endif
            @if($address->city)
                {{ $address->city }},
            @endif
            @if($address->province)
                <br/>{{ $address->province }},
            @endif
            @if($address->postal_code)
                {{ $address->postal_code }},
            @endif
            @if($address->country)
                <br/>{{ $address->country->name }}.
            @endif
        </small>
    </p>
@endif
