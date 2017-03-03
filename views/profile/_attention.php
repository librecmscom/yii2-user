<?php
?>

@foreach($attentions as $attention)
@if($source_type==='questions')
<li>
    <div class="row">
        <div class="col-md-10">
            <a class="stream-following-title"
               href="{{ route('ask.question.detail',['id'=>$attention->source_id]) }}">{{
                $attention['info']->title }}</a>
        </div>
        <div class="col-md-2 text-right">
            <span class="stream-following-followed mr-10">{{ $attention['info']->followers }} 关注</span>
            @if(Auth()->check() && Auth()->user()->isFollowed($attention->source_type,$attention->source_id))
            <button type="button" class="btn btn-default btn-xs followerUser active" data-source_type="question"
                    data-source_id="{{ $attention->source_id }}" data-show_num="false" data-toggle="tooltip"
                    data-placement="left" title="" data-original-title="关注后将获得更新提醒">取消关注
            </button>
            @else
            <button type="button" class="btn btn-default followerUser btn-xs" data-source_type="question"
                    data-source_id="{{ $attention->source_id }}" data-show_num="false" data-toggle="tooltip"
                    data-placement="left" title="" data-original-title="关注后将获得更新提醒">关注
            </button>
            @endif
        </div>
    </div>
</li>
@elseif($source_type==='users')
<li>
    <div class="row">
        <div class="col-md-10">
            <img class="avatar-32" src="{{ get_user_avatar($attention->source_id) }}"/>
            <div>
                <a href="{{ route('auth.space.index',['user_id'=>$attention->source_id]) }}">{{
                    $attention['info']->name }}</a> @if($attention['info']->title) <span
                    class="text-muted ml-5">- {{ $attention['info']->title  }}</span> @endif
                <div class="stream-following-followed">{{ $attention['info']->userData->supports }}赞同 / {{
                    $attention['info']->userData->followers }}关注 / {{ $attention['info']->userData->answers }}回答
                </div>
            </div>
        </div>
        <div class="col-md-2 text-right">
            @if(Auth()->check() && Auth()->user()->isFollowed($attention->source_type,$attention->source_id))
            <button type="button" class="btn btn-default btn-xs followerUser active" data-source_type="user"
                    data-source_id="{{ $attention->source_id }}" data-show_num="false" data-toggle="tooltip"
                    data-placement="left" title="" data-original-title="关注后将获得更新提醒">取消关注
            </button>
            @else
            <button type="button" class="btn btn-default followerUser btn-xs" data-source_type="user"
                    data-source_id="{{ $attention->source_id }}" data-show_num="false" data-toggle="tooltip"
                    data-placement="left" title="" data-original-title="关注后将获得更新提醒">关注
            </button>
            @endif
        </div>
    </div>
</li>
@else
<li>
    <div class="row">
        <div class="col-md-10">
            <a class="tag " href="/t/java" title="java">{{ $attention['info']->name }}</a>
            <div class="stream-following-desc">{{ $attention['info']->summary }}</div>
        </div>
        <div class="col-md-2 text-right">
            <span class="stream-following-followed mr-10">{{ $attention['info']->followers }} 关注</span>
            @if(Auth()->check() && Auth()->user()->isFollowed($attention->source_type,$attention->source_id))
            <button type="button" class="btn btn-default btn-xs followerUser active" data-source_type="tag"
                    data-source_id="{{ $attention->source_id }}" data-show_num="false" data-toggle="tooltip"
                    data-placement="left" title="" data-original-title="关注后将获得更新提醒">取消关注
            </button>
            @else
            <button type="button" class="btn btn-default followerUser btn-xs" data-source_type="tag"
                    data-source_id="{{ $attention->source_id }}" data-show_num="false" data-toggle="tooltip"
                    data-placement="left" title="" data-original-title="关注后将获得更新提醒">关注
            </button>
            @endif
        </div>
    </div>
</li>
