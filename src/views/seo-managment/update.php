<?php

$this->title = Yii::t('backend', 'Update {modelClass}', [
    'modelClass' => 'Meta tags page: '.$model->page->path,
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Page managment'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php echo $this->render('_form', [
    'model' => $model,
]) ?>

