<?php
class RegisterBehavior extends CActiveRecordBehavior
{
    public function beforeSave($event){
        parent::beforeSave($event);

        $this->owner->password = md5($this->owner->password);

        return true;
    }

}