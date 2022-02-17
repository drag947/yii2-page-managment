<?php

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('pm', 'Slugs');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="card">
    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('backend', 'Create'), Url::to(['create']), ['class'=>'btn btn-success']) ?>
        </p>
    </div>
    <div class="card-body p-0">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'options' => [
                'class' => ['gridview', 'table-responsive'],
            ],
            'tableOptions' => [
                'class' => ['table', 'text-nowrap', 'table-striped', 'table-bordered', 'mb-0', 'table-sm'],
            ],
            'columns' => [
                [
                    'attribute' => 'id',
                    'options' => ['style' => 'width: 5%'],
                ],
                [
                    'attribute' => 'param',
                    'options' => ['style' => 'width: 5%'],
                ],
                [
                    'attribute' => 'key',
                    'options' => ['style' => 'width: 5%'],
                ],
                [
                    'attribute' => 'value',
                    'options' => ['style' => 'width: 5%'],
                ],
                [
                    'class' => \common\widgets\ActionColumn::class,
                    'options' => ['style' => 'width: 5%'],
                    'template' => '{view}',
                ],
            ],
        ]) ?>
    </div>
</div>