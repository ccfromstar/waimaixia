<?php
class Snippet extends CWidget
{
    public $aid = null;
    public $length = 0;
    
    private $article = null;

    public function init()
    {
        if(!$this->aid)
            throw new CHttpException(404,'未指定要选择的片断');
        $this->article = Article::model()->findByPk($this->aid);
        if(!$this->article)
            throw new CHttpException(404,'要截取的片断不存在');
    }

    public function run()
    {
        $snippet = $this->article->description;
        if($this->length > 0){
            $length = mb_strlen($snippet,'utf-8');
            $snippet = mb_substr($snippet,0,$this->length,'utf-8');
            if($length > $this->length)
                $snippet  .= '...';
        }
        echo $snippet;
    }
}