<div ng-cloak class="ng-isolate-scope">
    <ul class="pagination pagination-sm ng-scope">
        <li ng-class="prevPageDisabled()" class="page-item ng-scope">
            <a href ng-click="setPage(0)" class="page-link">‹‹</a>
        </li>
        <li ng-class="prevPageDisabled()" class="page-item ng-scope">
            <a href ng-click="prevPage()" class="page-link">‹</a>
        </li>
        <li ng-repeat="n in pages" class="page-item ng-scope"
            ng-class="{active: n  == currentPaginationPage}" ng-click="setPage(n)">
            <a href class="page-link">@{{n+1}}</a>
        </li>
        <li ng-class="nextPageDisabled()" class="page-item ng-scope">
            <a href ng-click="nextPage()" class="page-link">›</a>
        </li>
        <li ng-class="nextPageDisabled()" class="page-item ng-scope">
            <a href ng-click="setPage(pagination.last_page - 1)" class="page-link">››</a>
        </li>
    </ul>
</div>