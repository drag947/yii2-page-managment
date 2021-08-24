<?php

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Meta tags',
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Page managment'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php echo $this->render('_form', [
    'model' => $model,
    'pages' => $pages
]) ?>

