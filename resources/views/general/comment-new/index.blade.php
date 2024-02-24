<h4 class="card-title">Recent Comments</h4>
<hr>
<p ng-hide="getCount(comments)">
    No comments added. Be the first to add a comment.
</p>
<div class="profiletimeline" ng-repeat="comment in comments" ng-show="comments">
    <div class="comment" data-id="@{{ comment.id }}">
        <div class="sl-item comment-block" data-value="@{{ comment.comment }}">
            <div class="sl-left">
                <img src="{{ asset('images/users/1.jpg') }}" alt="user" class="img-circle">
            </div>
            <div class="sl-right">
                <div>
                    <a href="#" class="link">@{{ comment.user ? comment.user.name : '' }}</a>
                    <span class="sl-date">
                            @{{ comment.created }}
                        </span>
                    <p class="comment-item">@{{comment.comment }}</p>
                    <span class="action-icons">
                            <a href="javascript:void(0)" class="clickEdit" ng-click="editClick($event.currentTarget)"
                               data-id="@{{ comment.id }}"
                               data-value="@{{ selectedStaff }}"><i class="ti-pencil-alt"></i> Edit</a>
                            <a href="javascript:void(0)" ng-click="deleteClick($event.currentTarget)" class="clickDelete" data-id="@{{ comment.id }}"><i
                                        class="ti-close"></i> Delete</a>
                        </span>
                </div>
            </div>
        </div>
        <hr>
    </div>
</div>

{{--{!! form()->model($model, ['url' => route('comment.create', $model), 'method' => 'POST']) !!}--}}
<form action="#" id="commentForm" ng-submit="submitForm($event)">
    <div class="comment-panel clearfix"
         style="background-color: rgba(153, 171, 180, 0.27058823529411763); padding: 10px;">
        <div class="form-group required comment-text">
        <textarea rows="3" name="comment" ng-model="commentText" class="form-control form-control-line"
                  placeholder="enter your comments here..."></textarea>
            <p class="form-control-feedback"></p>
        </div>
        <input type="hidden" name="model" ng-model="modelName" value="App\Staff">
        <input type="hidden" name="model_id" ng-model="mdoel_id" value="@{{ selectedStaff.id }}">
        <button class="btn btn-success btn-sm pull-right"><i class="fa fa-send"></i> Submit</button>
    </div>
</form>
{{--{!! form()->close() !!}--}}

<div class="comment-update-panel hidden">
    <div class="comments" update-form>
        <form action="" class="box-body" method="post"
              style="background-color: rgba(153, 171, 180, 0.27058823529411763); padding: 10px;">
            {!! csrf_field() !!}
            <input type="hidden" name="current_model" value="@{{ 'App\\Staff' }}">
            <input type="hidden" name="comment_id">
            <textarea name="comment" class="form-control form-control-line" placeholder="enter your comments here..."
                      rows="3"></textarea>
            <p class="help-block-custom"></p>
            <button type="button" class="btn btn-primary btn-sm updateClick" ng-click="update()"><i
                        class="fa fa-edit"></i> Update
            </button>
            <button type="button" class="btn btn-danger btn-sm editCancel" ng-click="editCancel()">Cancel</button>
        </form>
    </div>
</div>