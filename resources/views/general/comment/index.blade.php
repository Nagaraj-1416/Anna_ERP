<?php
$model->load('comments');
$comments = $model->getRelation('comments')->where('parent_id', null)->sortByDesc('id')
?>
<h4 class="card-title">Recent Comments</h4>
<hr>
@if(!$comments->count())
    No comments added. Be the first to add a comment.
@endif
<div class="profiletimeline">
    @foreach($comments as $comment)
        <div class="comment" data-id="{{ $comment->id }}">
            <div class="sl-item comment-block" data-value="{{ $comment->comment }}">
                <div class="sl-left">
                    <img src="{{ asset('images/users/1.jpg') }}" alt="user" class="img-circle">
                </div>
                <div class="sl-right">
                    <div>
                        <a href="#" class="link">{{ $comment->user->name }}</a>
                        <span class="sl-date">
                            @if($comment->created_at->diffInSeconds(carbon()->now()) < 40)
                                Just now
                            @else
                                {{ carbon()->now()->sub($comment->created_at->diff(carbon()->now()))->diffForHumans() }}
                            @endif
                        </span>
                        <p class="comment-item">{!! nl2br($comment->comment) !!}</p>
                        <span class="action-icons">
                            <a href="javascript:void(0)" class="clickEdit" data-id="{{ $comment->id }}" data-value="{{ $model }}"><i class="ti-pencil-alt"></i> Edit</a>
                            <a href="javascript:void(0)" class="clickDelete" data-id="{{ $comment->id }}"><i class="ti-close"></i> Delete</a>
                        </span>
                    </div>
                </div>
            </div>
            <hr>
        </div>
    @endforeach
</div>

{!! form()->model($model, ['url' => route('comment.create', $model), 'method' => 'POST']) !!}
<div class="comment-panel clearfix" style="background-color: rgba(153, 171, 180, 0.27058823529411763); padding: 10px;">
    <div class="form-group required route-drop-down {{ ($errors->has('comment')) ? 'has-danger' : '' }}">
        <textarea rows="3" name="comment" class="form-control form-control-line" placeholder="enter your comments here..."></textarea>
        <p class="form-control-feedback">{{ ($errors->has('comment') ? $errors->first('comment') : '') }}</p>
    </div>
    <input type="hidden" name="model" value="{{ get_class($model) }}">
    <input type="hidden" name="model_id" value="{{ $model->id }}">
    <button class="btn btn-success btn-sm pull-right"><i class="fa fa-send"></i> Submit</button>
</div>
{!! form()->close() !!}

<div class="comment-update-panel hidden">
    <div class="comments">
        <form action="" class="box-body" method="post" style="background-color: rgba(153, 171, 180, 0.27058823529411763); padding: 10px;">
            {!! csrf_field() !!}
            <input type="hidden" name="current_model" value="{{ str_replace('App\\', '', $model) }}">
            <input type="hidden" name="comment_id">
            <textarea name="comment" class="form-control form-control-line" placeholder="enter your comments here..." rows="3"></textarea>
            <p class="help-block-custom"></p>
            <button type="button" class="btn btn-primary btn-sm updateClick"><i class="fa fa-edit"></i> Update</button>
            <button type="button" class="btn btn-danger btn-sm editCancel">Cancel</button>
        </form>
    </div>
</div>