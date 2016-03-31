<?php
class AutoLogin extends CUserIdentity {
    private $_id;

    public function authenticate() {
        $userid = Yii::app()->user->getState('autologin_uid');
        $user = User::model()->findByPk($userid);

        if (!$user) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else if ($user->status!=1) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            $this->_id = $user->id;
            $this->username = $user->username;
            Yii::app()->user->nickname = $user->nickname;
            Yii::app()->user->avatar = $user->avatar;
            $this->errorCode = self::ERROR_NONE;
        }

        return $this->errorCode == self::ERROR_NONE;
    }

    public function getId(){
        return $this->_id;
    }

}