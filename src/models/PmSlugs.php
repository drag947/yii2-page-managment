<?php

namespace drag947\pm\models;

use Yii;
use drag947\pm\models\PmAlias;
/**
 * Description of PmSlugs
 *
 * @author ilya
 */
class PmSlugs extends \yii\db\ActiveRecord {
    
    public static function tableName() {
        return '{{%pm_slugs}}';
    }
    
    public function rules() {
        return [
            [['key', 'value', 'param'], 'required'],
            [['value', 'param'], 'string', 'max' => 255],
            [['param'], 'unique', 'targetAttribute' => ['param', 'key']]
        ];
    }
    
    public function attributeLabels() {
        return [
            'key' => Yii::t('pm', 'Real value'),
            'value' => Yii::t('pm', 'Slug'),
            'param' => Yii::t('pm', 'Parameter')
        ];
    }
    
    public function afterSave($insert, $changedAttributes) {
        
        parent::afterSave($insert, $changedAttributes);
    }
}
