<?php

class VedioAction extends CAction {
    
    public $no_thumb = true;  //true时不缩略，只判断是否是符合尺寸 
    public $is_cover = true;       //是否允许缩略图覆盖原图，一般用于上传头像等
    public $return = 'thumb';  //上传成功后，返回缩略图或源文件的路径: thumb/source
    public $refmt = 'string';  //返回值类型，字符串或JSON: string/json
    public $sub_dir = null;    //自定义存储目录，为null是表示自动按年月日创建目录
    protected $handler = null;  //上传句柄

    protected $ext = '';
    protected $upload_dir = '';
    protected $thumb_dir = '';
    protected $year = '';
    protected $m = '';
    protected $d = '';

    protected function getUploadInstance() {
        $this->handler = CUploadedFile::getInstanceByName('filedata');
        if (!$this->handler) {
            $this->handler = CUploadedFile::getInstanceByName('Filedata');
        }
        if (!$this->handler)
            throw new CHttpException(403, '上传异常，请检查上传组件配置是否正确！');
    }

    /**
     * @param boolean $is_cover 缩略图是否覆盖原图
     * @param string $sub_dir 指定保存的目录，而不是自动生成
     * @param string $return 返回值类型 thumb/source
     */
    public function run($is_cover='', $sub_dir='', $return='') {
        if ($is_cover !== '') {
            $this->is_cover = (boolean) $is_cover;
        }
        if ($sub_dir !== '') {
            $this->sub_dir = $sub_dir;
        }
        if ($return != '') {
            $this->return = $return;
        }
        $this->getUploadInstance();

        //创建唯一文件名
        $ext = $this->handler->getExtensionName();
        $rand = rand(10000, 99999);
        $filename = md5(time() . $rand) . '.' . $ext;
        $this->createFullPath();
        $fullpath = $this->upload_dir . DS . $filename;

        $this->handler->saveAs($fullpath);
        $this->handler->reset();

        
        $subdir = str_replace(DS, '/', $this->sub_dir);

        $exp = '/thumb/';
        if ($this->is_cover || $this->return == 'source') {
            $exp = '/';
        }
        if($this->refmt == 'json'){
            //$json = array('msg'=>'','msg'=>$filename);
            if(isset($_POST['for_id'])){
                $model->refresh();
                $json = CJSON::encode(array(
                    'id' => $model->id,
                    'pic' => Yii::app()->params['upImgUrlPrefix'].Yii::app()->baseUrl.'/upload/'.$subdir. $exp .$filename,
                ));
                echo $json;
            }else{
                echo "{'err':'','msg':'!".Yii::app()->params['upImgUrlPrefix'].Yii::app()->baseUrl.'/upload/'.$subdir. $exp .$filename."'}";
            }
        }else{
            echo Yii::app()->params['upImgUrlPrefix'].Yii::app()->baseUrl . '/upload/' . $subdir . $exp . $filename;
        }
        Yii::app()->end();
    }

    /**
     * 按当前日期创建完整目录路径
     * 若目录不存在，则自动创建
     */
    protected function createFullPath() {
        $this->upload_dir = Yii::app()->basePath . DS . '..' . DS . 'upload';

        if (!$this->sub_dir) {
            $this->sub_dir = date('Y') . DS . date('m') . DS . date('d');
        }
        
        $this->upload_dir .= DS . $this->sub_dir;
        $this->thumb_dir = $this->upload_dir;
        if (!$this->is_cover) {
            $this->thumb_dir .= DS . 'thumb';
        }
        $tmp = explode(DS, $this->thumb_dir);
        $count = count($tmp);
        $cur_dir = '';
        for ($i = 0; $i < $count; $i++) {
            $cur_dir .= $tmp[$i] . DS;
            if (file_exists($cur_dir) && is_dir($cur_dir)) {
                continue;
            }
            @mkdir($cur_dir, 0777);
            file_put_contents($cur_dir . 'index.html', 'Access Denied!'); //创建目录安全文件
        }
    }
}
