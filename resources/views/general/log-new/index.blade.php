<h4 class="card-title">Recent Logs</h4>
<hr>
<div class="message-box">
    <div class="message-widget">
        <a href="#" ng-show="logs" ng-repeat="log in logs">
            <div class="user-img">
                <img src="{{ asset('images/users/1.jpg') }}" alt="user" class="img-circle">
            </div>
            <div class="mail-contnet">
                <h5>@{{ baseName + ' ' +  capitalize(log.description)  }}</h5>
                <span class="mail-desc">by @{{ log.causer ? log.causer.name : 'System' }}</span>
                <span class="time text-muted">@{{ log.created }}</span>
            </div>
        </a>
        <p ng-hide="getCount(logs)">No logs found.</p>
    </div>
</div>