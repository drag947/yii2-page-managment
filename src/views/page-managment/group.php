<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\ArrayHelper;

if($model->isNewRecord) {
    $this->title = Yii::t('backend', 'Create {modelClass}', [
        'modelClass' => 'Group',
    ]);
}else{
    $this->title = Yii::t('backend', 'Update {modelClass}', [
        'modelClass' => 'Group',
    ]);
}

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Page managment'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin(); ?>
<div class="card">
    <div class="card-body">
        <?= $form->errorSummary($model) ?>
        
        <?= $form->field($model, 'label')->textInput() ?>
        
        <?php foreach ($pages as $page) : ?>
            <?= $form->field($model, 'page_'.$page->id)->checkbox()->label($page->path)?>
        <?php endforeach; ?>
    </div>
    <div class="card-footer">
        <?php echo Html::submitButton(
            $model->isNewRecord? FAS::icon('save').' '.Yii::t('backend', 'Create'):FAS::icon('save').' '. Yii::t('backend', 'Save Changes'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>
</div>
<?php ActiveForm::end() ?>