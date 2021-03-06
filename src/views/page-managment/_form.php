<?php
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use rmrevin\yii\fontawesome\FAS;
use yii\helpers\ArrayHelper;
?>
<?php $form = ActiveForm::begin(); ?>
<div class="card">
    <div class="card-body">
        <?= $form->errorSummary($model) ?>
        
        <?= $form->field($model, 'route')->textInput() ?>
        <?php if (!$model->is_group) : ?>
        <?= $form->field($model, 'group_id')->dropdownList(ArrayHelper::map($groups, 'id', 'route'), ['prompt' => '']) ?>
        <?php endif; ?>
    </div>
    <div class="card-footer">
        <?php echo Html::submitButton(
            $model->isNewRecord? FAS::icon('save').' '.Yii::t('backend', 'Create'):FAS::icon('save').' '. Yii::t('backend', 'Save Changes'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>
</div>
<?php ActiveForm::end() ?>