<?php

class ConfigController extends AdminController{
	public function actions(){
		return array(	
            'casevedio' => array(
                'class' => 'application.controllers.actions.VedioAction',
                'return' => 'source',
                'sub_dir' => 'video',
                'is_cover' => false,
                'no_thumb' => true,
            )
		);
	}

	public function actionSetting($type = 'base') {
		$title = Yii::app()->params['configs'][$type]['title'];
		$options = Yii::app()->params['configs'][$type]['options'];
		$keys = array_keys($options);
		$models = array();

		foreach($keys as $key) {
			$models[$key] = Configs::getByKey($key,'model');
		}

        if(isset($_POST['Configs'])) {
            foreach($keys as $key) {
                if(isset($_POST['Configs'][$key]))
                    $models[$key]->setAttributes($_POST['Configs'][$key]);
                if($models[$key]->validate())
                    $models[$key]->save();
            }
            Yii::app()->user->setFlash('success', '参数更新成功！');
            $this->refresh();
        }

        $this->render('setting', array('models' => $models, 'title' => $title, 'options' => $options));
	}
	
	public function actionAjaxUpdateStock(){
		$sql = "update `menu_stock` left join `menu` on `menu_stock`.mid=`menu`.id set `menu_stock`.`stock`=`menu`.orgstock where `menu_stock`.mid=`menu`.id";
		$cmd = Yii::app()->db->createCommand($sql);
		$c = $cmd->execute();

		echo CJSON::encode(array('c'=>$c));
		exit;
	}
}