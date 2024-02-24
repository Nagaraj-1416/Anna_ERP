<div class="card border-info">
    <div class="card-body">
        <div class="d-flex no-block">
            <h4 class="card-title text-info">Monthly Purchase Progress</h4>
            <div class="ml-auto"></div>
        </div>
        <h6 class="card-subtitle">{{ carbon()->now()->format('F j, Y') }}</h6>
        <hr>
        <div>
            <canvas id="monthChart" width="400" height="150"></canvas>
        </div>
    </div>
</div>