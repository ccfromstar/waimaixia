<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" >
<head>
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>MultiUpload Demo</title>
<link href="<?php echo Yii::app()->request->baseUrl;?>/css/multiupload.css" rel="stylesheet" type="text/css" />

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/jquery-1.4.2.min.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/swfupload/swfupload.js"></script>
<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/swfupload/multiupload.js"></script>
<script type="text/javascript">
$(window).load(pageInit);
function pageInit()
{
	var uploadurl='<?php echo $this->createUrl("default/mupload");?>',ext='所有文件 (*.*)',size='<?PHP echo Yii::app()->params[source_size];?> KB',count=5,useget=0,params={}//默认值

	uploadurl=getQuery('uploadurl')||uploadurl;
	ext=getQuery('ext')||ext;
	size=getQuery('size')||size;
	count=getQuery('count')||count;
	useget=getQuery('useget')||useget;
	var tmpParams=getQuery('params');
	if(tmpParams)
	{
		try{
			eval("tmpParams=" + tmpParams);
		}catch(ex){}
		params=$.extend({},params,tmpParams);
	}
	ext=ext.match(/([^\(]+?)\s*\(\s*([^\)]+?)\s*\)/i);
	setTimeout(fixHeight,10);
	swfu = new SWFUpload({
		// Flash组件
		flash_url : "<?php echo Yii::app()->request->baseUrl;?>/js/swfupload/swfupload.swf",
		//prevent_swf_caching : true,//是否缓存SWF文件

		// 服务器端
		upload_url: uploadurl,
		file_post_name : "Filedata",
		post_params: params,//随文件上传一同向上传接收程序提交的Post数据
		use_query_string : useget=='1'?true:false,//是否用GET方式发送参数

		// 文件设置
		file_types : ext[2],//文件格式限制
		file_types_description : ext[1],//文件格式描述
		file_size_limit : size,	// 文件大小限制
		file_upload_limit : count,//上传文件总数
		file_queue_limit:0,//上传队列总数
		custom_settings : {
			test : "aaa"
		},

		// 事件处理
		file_queued_handler : fileQueued,//添加成功
		file_queue_error_handler : fileQueueError,//添加失败
		upload_start_handler : uploadStart,//上传开始
		upload_progress_handler : uploadProgress,//上传进度
		upload_error_handler : uploadError,//上传失败
		upload_success_handler : uploadSuccess,//上传成功
		upload_complete_handler : uploadComplete,//上传结束

		// 按钮设置
		button_placeholder_id : "divAddFiles",
		button_width: 69,
		button_height: 17,
		button_window_mode: SWFUpload.WINDOW_MODE.TRANSPARENT,
		button_cursor: SWFUpload.CURSOR.HAND,
		button_image_url : "<?php echo Yii::app()->request->baseUrl;?>/images/swfupload/add.gif",
		button_text: '<span class="theFont">添加文件</span>',
		button_text_style: ".theFont { font-size: 12px; }",
		button_text_left_padding: 20,
		button_text_top_padding: 0,

		// 调试设置
		debug: false
	});
}
function fixHeight(){
	$('#listArea').css('height',(document.body.clientHeight-56)+'px');
}

function getQuery(item){
	var svalue = location.search.match(new RegExp('[\?\&]' + item + '=([^\&]*)(\&?)','i'));
	return svalue?decodeURIComponent(svalue[1]):'';
}
</script>
</head>
<body>
	<div id="upload">
		<div id="buttonArea">
			<div id="controlBtns" style="display:none;"><a href="javascript:void(0);" id="btnClear" onclick="removeFile();" class="btn" style="display:none;"><span><strong  class="delbtn">删除文件</strong></span></a> <a href="javascript:void(0);" id="btnStart" onclick="startUploadFiles();" class="btn"><span><strong class="startbtn">开始上传</strong></span></a></div>
			<a href="javascript:void(0);" id="addFiles" class="btn"><span><div id="divAddFiles">添加文件</div></span></a>
                        <br class="clear" />
                </div>
		<div id="listArea">
			<table width="100%" border="0" cellpadding="0" cellspacing="0">
				<thead id="listTitle"><tr><td width="53%">文件名</td><td width="25%">大小</td><td width="22%">状态</td></tr></thead>
				<tbody id="listBody">
				</tbody>
			</table>
		</div>
		<div id="progressArea">
			<div id="progressBar"><span>0%</span><div id="progress" style="width:1px;"></div></div>
		</div>
	</div>
</body>
</html>
