<?php

$this->title = Yii::t('backend', 'Create {modelClass}', [
    'modelClass' => 'Page',
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Page managment'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php echo $this->render('_form', [
    'model' => $model,
    'groups' => $groups
]) ?>