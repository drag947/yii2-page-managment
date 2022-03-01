<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use rmrevin\yii\fontawesome\FAS;

$this->title = Yii::t('pm', 'Create slug');
$this->params['breadcreambs'][] = ['label' => Yii::t('pm', 'Slugs'), 'url' => Url::to(['index'])];
$this->params['breadcreambs'][] = $this->title;

?>

<div class="card">
    <?php $form = ActiveForm::begin();?>
    <div class="card-body">
        <?= $form->field($model, 'param')->textInput() ?>
        
        <?= $form->field($model, 'key')->textInput() ?>
        
        <?= $form->field($model, 'value')->textInput() ?>
    </div>
    
    <div class="card-footer">
        <?php echo Html::submitButton(
            $model->isNewRecord? FAS::icon('save').' '.Yii::t('backend', 'Create'):FAS::icon('save').' '. Yii::t('backend', 'Save Changes'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>
    <?php $form::end(); ?>
</div>

