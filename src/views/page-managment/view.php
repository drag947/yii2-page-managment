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
    <?php if (!$page->is_group) : ?>
    <li class="nav-item">
      <a class="nav-link <?= $type == 'alias' ? 'active' : ''?>" href="<?=Url::to(['page-managment/alias', 'id'=>$page->id])?>"><?=Yii::t('backend', 'Alias')?></a>
    </li>
    <?php endif;?>
</ul>

<div class="tab-content">  
    <?php if ($type == 'page') : ?>
        <div class="tab-pane show active">
            <?php echo $this->render('_form', [
                'model' => $page,
                'groups' => $groups
            ]) ?>
        </div>
    <?php elseif($type == 'meta_tags') : ?>
        <div class="tab-pane show active">
            <?php if ($model) : ?>
                <p>
                    <div class="dropdown">
                        <a class="btn btn-info" data-toggle="dropdown" href="#"><?=Yii::t('backend', 'Choosing the language of meta tags')?></a>
                        <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                            <?php foreach (Yii::$app->params['availableLocales'] as $key => $lang) : ?>
                                <li><a class="dropdown-item" href="<?=Url::to(['page-managment/meta-tags', 'id'=>$page->id, 'lang'=>$key])?>"><?=$lang?></a></li>

                            <?php endforeach;?>
                        </ul>
                    </div>
                </p>
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


