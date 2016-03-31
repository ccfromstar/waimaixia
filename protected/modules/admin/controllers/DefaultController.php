<?php

class DefaultController extends AdminController {
    public function actions() {
        return array(
            // captcha action renders the CAPTCHA image displayed on the contact page
            'captcha' => array(
				'class' => 'CCaptchaAction',
                'backColor' => 0xFFFFFF,
                'maxLength' => 4,
                'minLength' => 4,
                'width' => 70,
                'height' => 40,
				'transparent'=>true,
            ),
            'mupload' => array(
                'class' => 'application.controllers.actions.UploadAction',
                'return' => 'source',
                'refmt' => 'json',
            ),
        );
    }

    public function allowedActions() {
        return 'multiupload,mupload,login,logout,error,captcha';
    }

    public function actionMultiupload(){
        $this->renderPartial('multiupload');
    }

    public function actionWelcome()
    {
        $uid = Yii::app()->user->getId();
        $roles = Rights::getAssignedRoles($uid);
        
        $role = array();

        foreach($roles as $r)
            $role[] = $r->description.' ';

        $this->render('welcome',array('role' => implode(', ', $role)));
    }

	public function actionRepass(){
		if(isset($_POST['password'])){

			if($_POST['password']=='' || $_POST['repassword']==''){
				Yii::app()->user->setFlash('error','请将表单填写完整后再提交！');
			}elseif($_POST['password'] !== $_POST['repassword']){
				Yii::app()->user->setFlash('error','两次输入的密码不一致，请重新输入！');
			}else{
                Yii::app()->db->createCommand()
					->update('user',array('password'=>md5($_POST['password'])),'id=:id',array(
						':id' => Yii::app()->user->getId(),
					));
                
				Yii::app()->user->setFlash('success','恭喜，您的登录密码已更新成功，下次请使用新密码登录！');
			}
		}

		$this->render('repass');
	}

    public function actionError()
	{
		$this->layout= false;
        if (Yii::app()->request->isAjaxRequest) {
            echo $error['message'];
        } else {
            $error = Yii::app()->errorHandler->error;
            if ($error)
                $this->render('error', $error);
        }
    }

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
        if(!Yii::app()->user->isGuest){
            $this->redirect('/site/index');
        }

        $this->layout = 'login';
        $model = new LoginAdmin;

        if (isset($_POST['ajax']) && $_POST['ajax'] === 'login-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }

        if (isset($_POST['LoginAdmin'])) {
            $model->attributes = $_POST['LoginAdmin'];
            if ($model->validate() && $model->login()){
                $this->redirect(array('/admin/default/index'));
            }
        }
        $this->render('login', array('model' => $model));
	}

	public function actionLogout(){
		Yii::app()->user->logout(false);
		$this->redirect(array('/admin/default/login'));
	}

}