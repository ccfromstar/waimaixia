<?php
class selectMenu extends CWidget
{
    protected $cs;
    protected $assetUrl;

    public $style='popup';
    public $maxHeight = 0;
    
    public function init(){
        $this->cs = Yii::app()->clientScript;
        $this->assetUrl = Yii::app()->assetManager->publish(dirname(__FILE__).'/source');
        parent::init();
    }
    
    public function run(){
	$this->regFiles();
        $this->cs->registerScript('selectmenu',"
            $('select').selectmenu({style:'".$this->style."',maxHeight:".$this->maxHeight."});
        ");
    }
    
    protected function regFiles(){
        $this->cs->registerCoreScript('jquery');
        $this->cs->registerCoreScript('jquery.ui');
        $this->cs->registerScriptFile($this->assetUrl.'/ui.selectmenu.js');
        $this->cs->registerCssFile($this->assetUrl.'/ui.selectmenu.css');
    }
}