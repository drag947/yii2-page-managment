<?php
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use rmrevin\yii\fontawesome\FAS;
use yii\imperavi\Widget;
use yii\helpers\Url;
use trntv\filekit\widget\Upload;

?>
<?php $form = ActiveForm::begin(['method'=>'post', 'action'=>Url::to([($model->isNewRecord) ? 'seo-managment/create' : 'seo-managment/update', 'id'=>$model->id])]); ?>
<div class="card">
    <div class="card-body">
        <?php echo $form->errorSummary($model) ?>
        
        <?php if($model->isNewRecord) : ?>
            <?= $form->field($model, 'page_id')->dropdownList($pages)?>
        <?php endif;?>
        
        <?= $form->field($model, 'title')->textInput()?>
        
        <?= $form->field($model, 'h_one')->textInput()?>
        
        <?= $form->field($model, 'description')->textInput()?>
        
        <?= $form->field($model, 'keywords')->textarea()?>
        
        <?= $form->field($model, 'text')->widget(Widget::class,
            [
                'plugins' => ['fullscreen', 'fontcolor', 'video', 'fontsize', 'fontfamily'],
                'options' => [
                    'minHeight' => 400,
                    'maxHeight' => 400,
                    'buttonSource' => true,
                    'convertDivs' => false,
                    'removeEmptyTags' => true,
                    'imageUpload' => Yii::$app->urlManager->createUrl(['/file/storage/upload-imperavi']),
                ],
            ])?>
        
        <?= $form->field($model, 'image')->textInput()?>
        
        <?= $form->field($model, 'is_main')->dropdownList([Yii::t('backend', 'No'), Yii::t('backend', 'Yes')]) ?>
        
        <?= $form->field($model, 'lang')->dropdownList(Yii::$app->params['availableLocales']) ?>
    </div>
    <div class="card-footer">
        <?php echo Html::submitButton(
            $model->isNewRecord? FAS::icon('save').' '.Yii::t('backend', 'Create'):FAS::icon('save').' '. Yii::t('backend', 'Save Changes'),
            ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']
        ) ?>
    </div>
</div>
<?php ActiveForm::end() ?>

