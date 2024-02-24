<h4 class="card-title">Recent Logs</h4>
<hr>
<div class="message-box" id="logScroll">
    <div class="message-widget">
        @if(count($model->auditLog))
            @foreach($model->auditLog->sortByDesc('id') as $key => $log)
                @if(isDirectorLevelStaff() || isAccountLevelStaff())
                <a href="{{ route('setting.audit.log.show', [$log]) }}">
                @else
                <a href="#">
                @endif
                    <div class="user-img">
                        <img src="{{route('setting.staff.image', [$log->causer ? $log->causer->staffs()->first() : ''])}}" alt="user" class="img-circle">
                    </div>
                    <div class="mail-contnet">
                        <h5>{{ $modelName.' '.ucfirst($log->description) }}</h5>
                        <span class="mail-desc">by {{ $log->causer->name or 'System' }}</span>
                        <span class="time text-muted">{{ carbon()->now()->sub($log->created_at->diff(carbon()->now()))->diffForHumans() }}</span>
                    </div>
                </a>
            @endforeach
        @else
            <p>No logs found.</p>
        @endif
    </div>
</div>