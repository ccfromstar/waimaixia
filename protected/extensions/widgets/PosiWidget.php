<?php
class PosiWidget extends CWidget
{
    public $limit = 3;
    public $titleLen = 10;
    public $descLen = 50;
    public $posid = null;
    public $class = '';
    public $wpElement = 'ul';
    public $itemTemplate = '<li><a href="{link}"> {title}</a></li>';

    private $ids = array();
    private $table = null;

    public function init() {
        if (!$this->posid){
            echo CHtml::tag('div', array('id' => $this->getId()), '未指定要显示的推荐位');
            return;
        }

        $model = Position::model()->findByPk($this->posid);

        if (!$model) {
            echo CHtml::tag('div', array('id' => $this->getId()), '指定的推荐位不存在');
            return;
        }

        if (!$this->limit) {
            $this->limit = $model->nums;
        }

        $this->table = $model->type;

        $posidata = Posidata::model()->findAll(array(
            'select' => 'artid', 
            'condition' => 'status = 1 and posid = :posid',
            'params' => array(':posid' => $model->id),
            'limit' => $this->limit,
            'order' => 'artid desc'
        ));

        unset($model);
        if (!$posidata) {
            echo CHtml::tag($this->wpElement, array('class' => $this->class));
            unset($posidata);
            return;
        } else {
            foreach($posidata as $pd) {
                $this->ids[] = $pd->artid;
            }
            unset($posidata);
        }

    }

    public function run() {
        $criteria = new CDbCriteria();
        $criteria->condition = 'status = 1';
        $criteria->select = 'id, title, artid,thumb,url, description,keywords,updatetime ';
        $criteria->compare('posid', $this->posid);
         $criteria->limit = $this->limit;
        $criteria->order = 'updatetime asc';
        $list = CActiveRecord::model('Posidata')->findAll($criteria);
        
        if (!$list) {
            echo CHtml::tag($this->wpElement, array('class' => $this->class));
        } else {
            $li = '';
            foreach($list as $item) {
                if(!$item->url):
                $link = $this->controller->createUrl($this->table.'/show', array('id' => $item->artid));
            else:
                $link=$item->url;
            endif;
            
                //$style = $item->color ? ' style="color:'.$item->color.';"' : '';
                $title = cutstr($item->title, $this->titleLen);
                $desc = cutstr($item->description, $this->descLen);
                $keywords=$item->keywords;
                $thumb=$item->thumb;
                $li .= str_replace(
                            array('{link}',  '{title}','{thumb}', '{desc}','{keywords}'), 
                            array($link, $title ,$thumb, $desc,$keywords), 
                            $this->itemTemplate
                        );
            }
            echo CHtml::tag($this->wpElement, array('class' => $this->class), $li);
        }
    }
}