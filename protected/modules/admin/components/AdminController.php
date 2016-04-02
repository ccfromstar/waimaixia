<?php
class AdminController extends RController {
    public $breadcrumbs = array();
    public $super = 0;

    public function init() {
        //define('CUR_URL','');
        parent::init();
        if(!Yii::app()->user->isGuest && !Yii::app()->user->checkAccess('Admin.Default.*'))
            $this->redirect(array('/site/index'));

        Yii::app()->user->loginUrl = array('/admin/default/login');
        Yii::app()->errorHandler->errorAction = '/admin/default/error';
        $this->layout = 'main';

        if(Yii::app()->user->checkAccess('Admin'))
            $this->super = 1;

        $allMenu = array(
            'default' => array(
                array(
                    'title' => '个人信息',
                    'link' => array(
                        array('text' => '欢迎页面', 'node' => 'Admin.Default.welcome'),
                        array('text' => '修改密码', 'node' => 'Admin.Default.repass'),
                    ),
                ),
                array(
                    'title' => '网站设置',
                    'link' => array(
                        //array('text' => '基本信息',     'node' => 'Admin.Config.setting', 'params' => array('type' => 'base')),
                        //array('text' => '联系方式',     'node' => 'Admin.Config.setting', 'params' => array('type' => 'contact')),
                        array('text' => '特殊地址',     'node' => 'Admin.Config.setting', 'params' => array('type' => 'expaddr')),
                        array('text' => '交易设置',     'node' => 'Admin.Config.setting', 'params' => array('type' => 'trade')),
                    ),
                ),
            ),

			'ticket' => array(
				array(
					'title' => '优惠券管理',
					'link' => array(
						array('text'=>'优惠券列表','node'=>'Admin.Ticket.list'),
						array('text'=>'优惠券发放','node'=>'Admin.Ticket.sendList'),
					),
				),	
			),

			'menu' => array(
				array(
					'title' => '菜品管理',
					'link' => array(
						array('text'=>'菜品列表','node'=>'Admin.Menu.list'),
						array('text'=>'菜品分类','node'=>'Admin.Menu.category'),
					),
				),	
			),

			'order' => array(
				array(
					'title' => '订单管理',
					'link' => array(
						array('text'=>'列表列表','node'=>'Admin.Order.list'),
						array('text'=>'400订单','node'=>'Admin.Order.manlist'),
					),
				),	
			),

			'finance' => array(
				array(
					'title' => '财务管理',
					'link' => array(
						array('text'=>'日报表','node'=>'Admin.Finance.daily'),
						array('text'=>'月报表','node'=>'Admin.Finance.month'),
						array('text'=>'年报表','node'=>'Admin.Finance.year'),
						array('text'=>'自定义报表','node'=>'Admin.Finance.custom'),
						array('text'=>'套餐统计','node'=>'Admin.Finance.menu'),
						array('text'=>'菜单明细统计','node'=>'Admin.Finance.menudetail'),
					),
				),	
			),

            'power' => array(
                array(
                    'title' => '系统权限',
                    'link' => array(
                        array('text' => '用户管理', 'node' => 'Rights.Assignment.view'),
                        array('text' => '权限管理', 'node' => 'Rights.AuthItem.permissions'),
                        array('text' => '角色管理', 'node' => 'Rights.AuthItem.roles'),
                        array('text' => '任务管理', 'node' => 'Rights.AuthItem.tasks'),
                        array('text' => '操作管理', 'node' => 'Rights.AuthItem.operations'),
                    ),
                ),
            ),
        );

        if (!Yii::app()->user->isGuest &&  (YII_DEBUG || !Yii::app()->user->hasState('menu'))) { //开启DEBUG时不缓存菜单，方便开发
            $leftMenu = array();
            foreach($allMenu as $k => $v) {
                $parent = array();

                foreach($v as $links) {
                    $sublink = array(
                        'title' => $links['title'],
                        'link' => array(), 
                    );
                    foreach($links['link'] as $node) {
                        if (Yii::app()->user->checkAccess($node['node'])) {
                            $sublink['link'][] = $node;
                        } else {
                            $nodelist = explode('.', $node['node']);
                            if (Yii::app()->user->checkAccess($nodelist[0].'.'.$nodelist[1].'.*')) {
                                $sublink['link'][] = $node;
                            }
                        }
                    }
                    if (count($sublink['link'])) {
                        $parent[] = $sublink;
                    }
                }

                if (count($parent)) {
                    $leftMenu[$k] = $parent;
                }
            }
            Yii::app()->user->setState('menu', $leftMenu, array());
        }

    }

    public function actionIndex() {
        if(!Yii::app()->user->checkAccess('Admin.Default.*')){
            $this->redirect(array('/site/index'));
        }

        $this->layout = 'index';
        $this->render('index');
    }

    public function actionGetkws() {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $data = $_POST['data'];
        $kws = Yii::app()->kws->getKws($data, 8);
        echo $kws;
        Yii::app()->end();
    }

    public function actionGetarea() {
        if (!Yii::app()->request->isAjaxRequest)
            throw new CHttpException(404);

        $pid = intval($_POST['pid']);

        echo CHtml::tag('option', array('value' => ''), '请选择', true);
        
        $data = Area::model()->findAll('pid=:pid', array(':pid' => $pid));
        if (!count($data)) {
            Yii::app()->end();
        } else {
            $data = CHtml::listData($data, 'id', 'name');
            foreach ($data as $value => $name) {
                echo CHtml::tag('option', array('value' => $value), CHtml::encode($name), true);
            }
            Yii::app()->end();
        }
    }

    public function allowedActions() {
        return 'index, getkws, getarea';
    }

    public function filters() {
        return array(
            'rights',
        );
    }


    /**
     * 插入新tag标签
     * @param  string $tags  
     * @param  integer $forid 
     * @param  string $table 
     * @return [type]        [description]
     */
    protected function insertTags($tags, $forid, $table) {
        $tags = trim($tags);
        $forid = intval($forid);
        $tags = str_replace(',', ' ', $tags);
        $tags = str_replace('，', ' ', $tags);
        $tags = str_replace('　', ' ', $tags);
        $tags = explode(' ', $tags);
        $model = new Tags;
        foreach($tags as $tag) {
            $model->setIsNewRecord(true);
            $model->tag = $tag;
            $model->forid = $forid;
            $model->table = $table;
            $model->save();
            $model->primaryKey++;
        }
    }
}