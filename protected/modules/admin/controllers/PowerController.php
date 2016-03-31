<?php
class PowerController extends AdminController
{
    public function actionError() {
        if (Yii::app()->request->isAjaxRequest) {
            echo $error['message'];
        } else {
            $error = Yii::app()->errorHandler->error;
            if ($error)
                $this->render('error', $error);
        }
    }
}
