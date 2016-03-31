<?php

/**
 * 微信公众平台API
 * Created by JetBrains PhpStorm.
 * User: 楼教主
 * Date: 2013-08-16 11:22
 */
class wechatApi
{
    private $token = "";         // 公众平台 TOKEN 值

    private $message = null;    // 用户发送的信息对象

    private $isdebug = false;   // 是否开启调试, 开启后将会按日期生成xml日志，形如: wechat_log_2013-08-16.xml
    private $logpath = "./";     // 调试日志目录 必须是一个可写目录，否则无法生成日志
    private $logfile = "";       // 调试日志
	
	const API_GATE = "https://api.weixin.qq.com/cgi-bin/";
	const URL_CREATE_MENU = "menu/create";
	const URL_GET_ACCESS_TOKEN = "token";

    /**
     * 构造方法
     * @param String $token 公众平台 TOKEN
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * 获取用户发送给我们的信息
     * @return SimpleXMLElement $message 用户信息
     */
    public function getMessage()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        //if (empty($postStr) || !$this->checkSignature()) die("bad request"); // 如果数字签名不对就抛弃

        $this->message = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);

        if ($this->isdebug) { //输出调试信息
            $this->logfile = $this->logpath . "wechat_log_" . date('Y-m-d') . ".xml";
            @file_put_contents($this->logfile, "POST:\n" . $postStr . "\n", FILE_APPEND); //写入得到的XML
            @file_put_contents($this->logfile, '$_GET:' . json_encode($_GET) . "\n\n", FILE_APPEND); //写入得到的 $_GET
        }

        return $this->message;
    }


    /**
     * 回复文本信息
     * @param String $message
     */
    public function replyText($message)
    {
        $textTpl = <<<XML
<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%s</CreateTime>
    <MsgType><![CDATA[text]]></MsgType>
    <Content><![CDATA[%s]]></Content>
</xml>
XML;

        $ret = sprintf($textTpl, $this->message->FromUserName, $this->message->ToUserName, time(), $message);
        echo $ret;

        if ($this->isdebug) { //输出调试信息
            @file_put_contents($this->logfile, "Reply:\n" . $ret . "\n\n\n", FILE_APPEND); //写入得到的XML
        }
    }


    /**
     * 回复图文信息
     * @param mixed $item 图文一维数组，或者图文二维数组
     * @example
     * array(
     *     "title" => "标题",
     *     "description" => "描述",
     *     "pic" => "图片绝对地址",
     *     "url" => "图文页面地址"
     * );
     */
    public function replyNews($item)
    {
        $itemTpl = <<<XML
<item>
    <Title><![CDATA[%s]]></Title>
    <Description><![CDATA[%s]]></Description>
    <PicUrl><![CDATA[%s]]></PicUrl>
    <Url><![CDATA[%s]]></Url>
</item>
XML;

        $items = isset($item["title"]) ? array($item) : $item;
        $item_str = "";
        foreach ($items as $val) {
            $item_str .= sprintf($itemTpl, $val["title"], $val["description"], $val["pic"], $val["url"]);
        }

        $newsTpl = <<<XML
<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%d</CreateTime>
    <MsgType><![CDATA[news]]></MsgType>
    <ArticleCount>%d</ArticleCount>
    <Articles>%s</Articles>
</xml>
XML;
        $ret = sprintf($newsTpl, $this->message->FromUserName, $this->message->ToUserName, time(), count($items), $item_str);
        echo $ret;

        if ($this->isdebug) { //输出调试信息
            @file_put_contents($this->logfile, "Reply:\n" . $ret . "\n\n\n", FILE_APPEND); //写入得到的XML
        }
    }

    /**
     * 回复音乐信息
     * @param Array $music 音乐信息
     * @example
     * array(
     *     "title" => "121314主题曲", //标题
     *     "description" => "121314主题曲详细描述", //描述
     *     "url" => "http://m.121314.com/121314.mp3", //普通音质
     *     "hdurl" => "http://m.121314.com/121314_hd.mp3" //高清音质(wifi下默认播放这个)
     * );
     */
    public function replyMusic($music)
    {
        $musicTmp = <<<XML
<xml>
    <ToUserName><![CDATA[%s]]></ToUserName>
    <FromUserName><![CDATA[%s]]></FromUserName>
    <CreateTime>%d</CreateTime>
    <MsgType><![CDATA[music]]></MsgType>
    <Music>
        <Title><![CDATA[%s]]></Title>
        <Description><![CDATA[%s]]></Description>
        <MusicUrl><![CDATA[%s]]></MusicUrl>
        <HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
    </Music>
</xml>
XML;
        $ret = sprintf($musicTmp, $this->message->FromUserName, $this->message->ToUserName, time(), $music["title"], $music["description"], $music["url"], $music["hdurl"]);
        echo $ret;

        if ($this->isdebug) { //输出调试信息
            @file_put_contents($this->logfile, "Reply:\n" . $ret . "\n\n\n", FILE_APPEND); //写入得到的XML
        }
    }

    /**
     * 开启调试，生成调试日志
     * @param bool $opne
     */
    public function debug($opne = true)
    {
        $this->isdebug = $opne;
    }

    /**
     * 设置调试日志输出目录
     * @param string $path
     */
    public function debugFilePath($path = "./")
    {
        $lastchar = substr($path, -1);
        if ($lastchar != "/" && $lastchar != "\\") {
            $path .= DIRECTORY_SEPARATOR;
        }
        $this->logpath = $path;
    }

    /**
     * 首次使用验证 (接口绑定到你的网站验证)
     */
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    /**
     * 信息安全性验证
     * @return bool 数字签名是否正确
     */
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = $this->token;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}