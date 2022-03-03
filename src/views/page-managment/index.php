<?php
use yii\grid\GridView;
use yii\bootstrap4\Html;
use yii\helpers\Url;

$this->title = Yii::t('backend', 'Page managment');
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
            'filterModel' => $searchModel,
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
                    'attribute' => 'path',
                    'options' => ['style' => 'width: 5%'],
                ],
                [
                    'class' => \common\widgets\ActionColumn::class,
                    'options' => ['style' => 'width: 5%'],
                    'template' => '{view} {delete}',
                ],
            ],
        ]) ?>
    </div>
</div>