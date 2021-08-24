<?php
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('backend', 'View {modelClass}', [
    'modelClass' => 'Page: '.$page->path,
]);

$this->params['breadcrumbs'][] = ['label' => Yii::t('backend', 'Page managment'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<ul class="nav nav-tabs">
    <li class="nav-item">
      <a class="nav-link <?= $type == 'page' ? 'active' : ''?>" href="<?=Url::to(['page-managment/view', 'id'=>$page->id])?>"><?=Yii::t('backend', 'Main')?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $type == 'meta_tags' ? 'active' : ''?>" href="<?=Url::to(['page-managment/meta-tags', 'id'=>$page->id])?>"><?=Yii::t('backend', 'Meta tags')?></a>
    </li>
    <li class="nav-item">
      <a class="nav-link <?= $type == 'alias' ? 'active' : ''?>" href="<?=Url::to(['page-managment/alias', 'id'=>$page->id])?>"><?=Yii::t('backend', 'Alias')?></a>
    </li>
</ul>

<div class="tab-content">  
    <?php if ($type == 'page') : ?>
        <div class="tab-pane show active">
            <?php echo $this->render('_form', [
                'model' => $page,
            ]) ?>
        </div>
    <?php elseif($type == 'meta_tags') : ?>
        <div class="tab-pane show active">
            <?php if ($model) : ?>
                <?= $this->render('../seo-managment/_form', [
                    'model' => $model,
                ]) ?>
            <?php endif;?>
        </div>
    <?php elseif($type == 'alias') : ?>
        <div class="tab-pane show active" >
            <?php echo $this->render('_alias', [
                'dataProvider' => $dataProvider,
                'page' => $page
            ]) ?>
        </div>
    <?php endif; ?>
</div>  


