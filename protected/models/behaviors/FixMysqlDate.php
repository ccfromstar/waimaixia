<?php
class FixMysqlDate extends CActiveRecordBehavior
{
    public function beforeSave($event){
        parent::beforeSave($event);
        if (!$this->owner->birthday) {
            $this->owner->birthday = date('Y-m-d', strtotime('-18 years'));
        }
    }

}