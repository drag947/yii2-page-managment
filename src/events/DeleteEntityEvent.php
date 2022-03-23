<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace drag947\pm\events;

/**
 * Description of DeleteEntityEvent
 *
 * @author Илья
 */
class DeleteEntityEvent extends \yii\base\Event {
    const NAME = 'delete_entity_pm_event';
    
    public $id;
    public $key;
}
