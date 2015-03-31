<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 31.03.2015
 * Time: 17:15
 */

use yii\grid\GridView;
$this->title = \Yii::$app->name;
?>

<div class="site-index">
    <div class="page-header">
        <h1>Статусы писем</h1>
    </div>


    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'options'=>['class'=>'table-responsive'],
    'tableOptions'=>['class'=>'table table-striped'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        //'id',
        [
            'class'=>'yii\grid\DataColumn',
            'attribute'=>'created',
            'format'=>'datetime',
            'filter'=>false,
        ],
        [
            'class'=>'yii\grid\DataColumn',
            'label'=>\Yii::t('app', 'Recipient'),
            'value'=>function ($model){
                return $model->emailMessage['recipient'];
            },
        ],
        [
            'class'=>'yii\grid\DataColumn',
            'label'=>\Yii::t('app', 'Name'),
            'value'=>function ($model){
                return $model->emailMessage['name'];
            },
        ],
        [
            'class'=>'yii\grid\DataColumn',
            'label'=>\Yii::t('app', 'Email'),
            'value'=>function ($model){
                return $model->emailMessage['email'];
            },
        ],
        [
            'class'=>'yii\grid\DataColumn',
            'label'=>\Yii::t('app', 'Phone'),
            'value'=>function ($model){
                return $model->emailMessage['phone'];
            },
        ],
        [
            'class'=>'yii\grid\DataColumn',
            'attribute'=>'received',
            'format'=>'boolean',
            'filter'=>[0=>\Yii::t('app', 'No'), 1=>\Yii::t('app', 'Yes')],
        ],
    ],
]); ?>