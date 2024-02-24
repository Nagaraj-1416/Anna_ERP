<h4 class="card-title">Contact Persons</h4>
<hr>
<div class="message-box">
    <div class="message-widget">
        @if(count($contacts))
            @foreach($contacts as $contact)
            <!-- Message -->
            <a href="#">
                <div class="mail-contnet">
                    <h5>{{ $contact->full_name }}</h5>
                    <span class="mail-desc">{{ $contact->phone }} | {{ $contact->mobile }} | {{ $contact->email }}</span>
                    <span class="time text-muted">{{ $contact->designation }} | {{ $contact->department }}</span>
                </div>
            </a>
            @endforeach
        @else
            <p>No Contact Persons Found</p>
        @endif
    </div>
</div>