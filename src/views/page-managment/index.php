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
            
            <?= Html::a(Yii::t('pm', 'Create group'), Url::to(['create-group']), ['class'=>'btn btn-success']) ?>
        
            <?= Html::a(Yii::t('pm', 'Create possible pages'), Url::to(['create-possible-pages']), ['class'=>'btn btn-info']) ?>
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
                    'label' => Yii::t('pm', 'Path')
                ],
                [
                    'attribute' => 'group_id',
                    'options' => ['style' => 'width: 5%'],
                    'label' => Yii::t('pm', 'Group'),
                    'value' => function ($data) {
                        return $data->group ? $data->group->route : '';
                    }
                ],
                [
                    'attribute' => 'is_active',
                    'options' => ['style' => 'width: 5%'],
                    'label' => Yii::t('pm', 'Active'),
                    'value' => function ($data) {
                        return $data->is_active ? Yii::t('yii', 'Yes') : Yii::t('yii', 'No');
                    }
                ],
                [
                    'class' => \common\widgets\ActionColumn::class,
                    'options' => ['style' => 'width: 5%'],
                    'template' => '{view} {delete}',
                ],
            ],
        ]) ?>
        
         <?= GridView::widget([
            'dataProvider' => $dataGroup,
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
                    'attribute' => 'route',
                    'options' => ['style' => 'width: 5%'],
                    'label' => Yii::t('pm', 'Label')
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