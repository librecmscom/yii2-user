<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ListView;
use yii\bootstrap\Nav;
use yuncms\user\models\User;

/**
 * @var \yii\web\View $this
 * @var  User $model
 */
$this->context->layout = 'space';
$this->params['user'] = $model;

switch ($model->action) {
    case 'follow_stream':
        echo Yii::t('app', 'Concerned about live');
        break;
    default:
        echo $model->action;
        break;
}

if (!Yii::$app->user->isGuest && Yii::$app->user->id == $model->id) {//Me
    Yii::t('user', 'My');
} else {
    Yii::t('user', 'His');
}
$this->title = empty($model->profile->name) ? Html::encode($model->username) : Html::encode($model->profile->name);
?>

@section('seo_title')

@if(Auth()->check() && Auth()->user()->id === $userInfo->id)
我
@else
他
@endif
收藏的
@if($source_type==='questions')
问题
@else
文章
@endif - {{ Setting()->get('website_name') }}
@endsection

@section('space_content')
<div class="stream-following">

    <?= Nav::widget([
        'options' => ['class' => 'nav nav-tabs'],
        'items' => [
            //问答
            ['label' => Yii::t('app', 'Collection of questions'), 'url' => ['/user/profile/collect', 'id' => $model->id, 'type' => 'questions'], 'visible' => Yii::$app->hasModule('question')],
            //文章
            ['label' => Yii::t('app', 'Collection of articles'), 'url' => ['/user/profile/collect', 'id' => $model->id, 'type' => 'articles'], 'visible' => Yii::$app->hasModule('article')],
        ],
    ]); ?>

    <div class="stream-list question-stream mt-10">
        @foreach($collections as $collection)

        @if($source_type==='questions')
        <section class="stream-list-item">
            <div class="bookmark-rank">
                <div class="collections">
                    {{ $collection['info']->collections }}
                    <small>收藏</small>
                </div>
            </div>

            <div class="summary">
                <ul class="author list-inline">
                    <li>
                        <a href="{{ route('auth.space.index',['user_id'=>$collection['info']->user->id]) }}">{{
                            $collection['info']->user->name }}</a>
                        <span class="split"></span>
                        {{ timestamp_format($collection['info']->created_at) }}
                    </li>
                </ul>
                <h2 class="title">
                    <a href="{{ route('ask.question.detail',['id'=>$collection['info']->id]) }}">{{
                        $collection->subject }}</a>
                </h2>
            </div>
        </section>
        @else
        <section class="stream-list-item">
            <div class="bookmark-rank">
                <div class="collections">
                    {{ $collection['info']->collections }}
                    <small>收藏</small>
                </div>
            </div>

            <div class="summary">
                <ul class="author list-inline">
                    <li>
                        <a href="{{ route('auth.space.index',['user_id'=>$collection['info']->user->id]) }}">{{
                            $collection['info']->user->name }}</a>
                        <span class="split"></span>
                        {{ timestamp_format($collection['info']->created_at) }}
                    </li>
                </ul>
                <h2 class="title">
                    <a href="{{ route('blog.article.detail',['id'=>$collection['info']->id]) }}">{{
                        $collection->subject }}</a>
                </h2>
            </div>
        </section>
        @endif
        @endforeach
    </div>

    <div class="text-center">
        {!! str_replace('/?', '?', $collections->render()) !!}
    </div>
</div>

@endsection





