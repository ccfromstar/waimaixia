<?php

class MenuController extends AdminController{

	public function actions() {
		return array(
			'pic' => array(
				'class' => 'application.controllers.actions.UploadAction',
				'is_cover' => true,
				'sub_dir' => 'menu',
				'thumbs' => array(
					array('height' => 410, 'width' => 750),
				),
			),
		);
	}

	public function actionList(){
		$model = new Menu;
		$model->unsetAttributes();

		$this->render('list',array('model'=>$model));
	}

	public function actionUpdate($id=0){
		if($id>0)
			$model = Menu::model()->findByPk($id);
		else{
			$model = new Menu;
			$cf = Configs::model()->find('`key`="df_stock"');
			if($cf && $cf->value!=''){
				$model->orgstock = $cf->value;
			}
		}
			

		if(isset($_POST['Menu'])){
			$model->setAttributes($_POST['Menu']);
			$model->updtime = time();
			if($model->validate() && $model->save()){
				$model->refresh();

				//为当前菜单添加当日库存设置
				$ms = new MenuStock;
				$ms->mid = $model->id;
				$ms->stock = $model->orgstock;
				$ms->stock_date = date("Y-m-d");
				if($ms->validate()){
					$ms->save();
				}

				Yii::app()->cache->set('menusBl',null);
				Yii::app()->cache->set('menusJk',null);

				Yii::app()->user->setFlash('success','保存成功');
				$this->redirect($this->createUrl('/admin/menu/list'));
			}else{
				var_dump($model->getErrors());
			}
			Yii::app()->end();
		}
		
		$this->render('update',array(
			'model'=>$model	
		));
	}

	public function actionCategory(){
		$model = new MenuCategory;
		$model->unsetAttributes();

		$this->render('category',array(
			'model' => $model,	
		));
	}

	public function actionUpdateCategory($id=0){
		if($id>0){
			$model = MenuCategory::model()->findByPk($id);
		}else{
			$model = new MenuCategory;
		}

		if(isset($_POST['MenuCategory'])){
			$model->setAttributes($_POST['MenuCategory']);
			
			if($model->validate() && $model->save()){
				$model->refresh();

				Yii::app()->cache->set('menusBl',null);
				Yii::app()->cache->set('menusJk',null);

				Yii::app()->user->setFlash('success','保存成功');
				$this->redirect($this->createUrl('/admin/menu/category'));
			}else{
				var_dump($model->getErrors());
			}
			Yii::app()->end();
		}

		$this->render('updateCategory',array(
			'model' => $model,	
		));
	}

	public function actionDelete($id=0){
		if(MenuCategory::model()->deleteByPk($id)){
			Menu::model()->deleteAll('cid='.$id);
			Yii::app()->user->setFlash('success','分类删除成功');
		}else{
			Yii::app()->user->setFlash('error','分类删除失败');
		}

		$this->redirect(Yii::app()->createUrl('/admin/menu/category'));
	}
}