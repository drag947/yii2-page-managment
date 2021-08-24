<?php

$this->title = Yii::t('backend', 'Update {modelClass}', [
    'modelClass' => 'Alias: '.$model->url,
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Page managment'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Page: ').$page->path, 'url' => ['alias', 'id'=>$page->id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php echo $this->render('_alias_form', [
    'model' => $model,
]) ?>

