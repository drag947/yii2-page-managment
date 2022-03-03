<?php
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use richardfan\sortable\SortableGridView;
?>
<div class="card">
    <div class="card-header">
        <p>
            <?= Html::a(Yii::t('backend', 'Create'), Url::to(['alias-create', 'id'=>$page->id]), ['class'=>'btn btn-success']) ?>
        </p>
    </div>
    <div class="card-body p-0">
         <?= SortableGridView::widget([
            'sortUrl' => Url::to(['sort-alias']),
            'dataProvider' => $dataProvider,
            'options' => [
                'class' => ['gridview', 'table-responsive'],
            ],
            'tableOptions' => [
                'class' => 'table text-nowrap table-striped table-bordered mb-0 table-sm',
            ],
            'columns' => [
                [
                    'attribute' => 'sort',
                    'options' => ['style' => 'width: 5%'],
                ],
                [
                    'attribute' => 'url',
                    'options' => ['style' => 'width: 5%'],
                ],
                [
                    'attribute' => 'route',
                    'options' => ['style' => 'width: 5%'],
                ],
                [
                    'class' => \common\widgets\ActionColumn::class,
                    'options' => ['style' => 'width: 5%'],
                    'template' => '{alias-update} {alias-delete}',
                    'buttons' => [
                        'alias-delete' => function ($url, $model, $key) { // <--- here you can override or create template for a button of a given name
                            return Html::a('<span class="fa-fw fas fa-trash" aria-hidden="true"></span>', Url::to(['alias-delete', 'id' => $model->id]), ['class' => 'btn btn-danger btn-xs']);
                        },
                        'alias-update' => function ($url, $model, $key) { // <--- here you can override or create template for a button of a given name
                            return Html::a('<span class="fa-fw fas fa-edit" aria-hidden="true"></span>', Url::to(['alias-update', 'alias_id' => $model->id]), ['class' => 'btn btn-warning btn-xs']);
                        },
                    ],
                ],
            ],
        ]) ?>
    </div>
</div>