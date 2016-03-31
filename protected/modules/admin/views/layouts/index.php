<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" class="off">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?php echo Yii::app()->name;?></title>
<link href="/css/reset.css" rel="stylesheet" type="text/css" />
<link href="/css/admin.css" rel="stylesheet" type="text/css" />
<style type="text/css">
.objbody{overflow:hidden}
</style>
</head>
<body scroll="no" class="objbody">
<?php
if (!function_exists('lcfirst')) {
    function lcfirst($word) {
        $len = strlen($word);
        return strtolower($word{0}) . substr($word, 1, $len-1);
    }
}
?>
<div class="header">
	<div class="logo lf"><a href="#" target="_blank"><span class="invisible"><?php echo Yii::app()->name;?></span></a></div>
    <div class="rt-col">
    	<div class="tab_style white cut_line text-r">

        </div>
    </div>
    <div class="col-auto">

    	<div class="log white cut_line">您好！<?php echo Yii::app()->user->name;?><span>|</span><?php echo CHtml::link('[前台首页]',array('/site'),array('target'=>'_blank'));?><span>|</span>
    		<a href="<?php echo $this->createAbsoluteUrl('/admin/default/repass');?>" target="right" id="site_homepage">[修改密码]</a><span>|</span><?php echo CHtml::link('[安全退出]',array('/admin/default/logout'));?>
    	</div>

    <?php $leftMenus = Yii::app()->user->getState('menu');?>
    <?php $this->widget('zii.widgets.CMenu',array(
            'id' => 'top_menu',
            'activeCssClass' => 'on',
            'activateParents' => 'true',
            'htmlOptions' => array('class'=>'nav white'),
                'items'=>array(
                    array('label'=>'站点设置', 'url'=>array('/admin/default/index'), 'visible' => isset($leftMenus['default'])),
                    array('label'=>'菜品管理', 'url'=>array('/admin/menu/index'), 'visible' => isset($leftMenus['menu'])),
                    array('label'=>'订单管理', 'url'=>array('/admin/order/index'), 'visible' => isset($leftMenus['order'])),
                    array('label'=>'财务管理', 'url'=>array('/admin/finance/index'), 'visible' => isset($leftMenus['finance'])),
                    array('label'=>'优惠券管理', 'url'=>array('/admin/ticket/index'), 'visible' => isset($leftMenus['ticket'])),
                    array('label'=>'权限管理', 'url'=>array('/admin/power/index'), 'visible' => isset($leftMenus['power'])),
                )
         ));?>

    </div>
</div>
<div id="content">
	<div class="col-left left_menu">
    	<div id="Scroll">
            <div id="leftMain">
            <?php $current_page = '';?>

            <?php foreach($leftMenus[$this->id] as $leftMenu):?>
                <h3 class="f14" style="cursor:pointer">
                    <span class="switchs cu on" title="展开与收缩"></span>
                    <?php echo $leftMenu['title'];?>
                </h3>
                <ul>
                    <?php foreach($leftMenu['link'] as $link):?>

                    <?php
                    $nodes = explode('.', $link['node']);
                    $nodes = '/'.lcfirst($nodes[0]).'/'.lcfirst($nodes[1]).'/'.lcfirst($nodes[2]);
                    $params = array();
                    if (isset($link['params']))
                        $params = $link['params'];
                    $href = $this->createUrl($nodes, $params);
                    if($current_page=='') {
                        $current_page = $href;
                    }
                    ?>

                    <li class="sub_menu">
                        <a href="<?php echo $href;?>" target="right" hidefocus="true" style="outline:none;"><?php echo $link['text'];?></a>
                    </li>

                    <?php endforeach;?>
                </ul>
            <?php endforeach;?>
            </div>
        </div>
        <a href="javascript:;" id="openClose" style="outline-style: none; outline-color: invert; outline-width: medium;" hideFocus="hidefocus" class="open" title="展开与关闭"><span class="hidden">展开</span></a>
    </div>
	<div class="col-1 lf cat-menu" id="display_center_id" style="display:none" height="100%">

	<div class="content">
        	<iframe name="center_frame" id="center_frame" src="" frameborder="false" scrolling="auto" style="border:none" width="100%" height="auto" allowtransparency="true"></iframe>
            </div>
        </div>
    <div class="col-auto mr8">
        <div class="crumbs">
            	<?php $this->widget('zii.widgets.CBreadcrumbs', array(
					'homeLink' => CHtml::link('首页',array('/admin/default')),
                    'links'=>$this->breadcrumbs,
                )); ?><!-- breadcrumbs -->
        </div>
    	<div class="col-1">
        	<div class="content" style="position:relative; overflow:hidden">
                <iframe name="right" id="rightMain" src="<?php echo $current_page;?>" frameborder="false" scrolling="auto" style="border:none; margin-bottom:30px" width="100%" height="auto" allowtransparency="true"></iframe>
                <div class="fav-nav">
					<div id="panellist">
                        欢迎使用 <?php echo Yii::app()->name;?>
                    </div>
				</div>
        	</div>
        </div>

    </div>
</div>

<div class="scroll"><a href="javascript:;" class="per" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(1);"></a><a href="javascript:;" class="next" title="使用鼠标滚轴滚动侧栏" onclick="menuScroll(2);"></a></div>

<?php
Yii::app()->clientScript->registerCoreScript('jquery');
?>

<script type="text/javascript">
if(!Array.prototype.map)
Array.prototype.map = function(fn,scope) {
  var result = [],ri = 0;
  for (var i = 0,n = this.length; i < n; i++){
	if(i in this){
	  result[ri++]  = fn.call(scope ,this[i],i,this);
	}
  }
return result;
};

var getWindowSize = function(){
return ["Height","Width"].map(function(name){
  return window["inner"+name] ||
	document.compatMode === "CSS1Compat" && document.documentElement[ "client" + name ] || document.body[ "client" + name ]
});
}
window.onload = function (){
	if(!+"\v1" && !document.querySelector) { // for IE6 IE7
	  document.body.onresize = resize;
	} else {
	  window.onresize = resize;
	}
	function resize() {
		wSize();
		return false;
	}
}
function wSize(){
	//这是一字符串
	var str=getWindowSize();
	var strs= new Array(); //定义一数组
	strs=str.toString().split(","); //字符分割
	var heights = strs[0]-150,Body = $('body');$('#rightMain').height(heights);
	//iframe.height = strs[0]-46;
	if(strs[1]<980){
		$('.header').css('width',980+'px');
		$('#content').css('width',980+'px');
		Body.attr('scroll','');
		Body.removeClass('objbody');
	}else{
		$('.header').css('width','auto');
		$('#content').css('width','auto');
		Body.attr('scroll','no');
		Body.addClass('objbody');
	}

	var openClose = $("#rightMain").height()+39;
	$('#center_frame').height(openClose+9);
	$("#openClose").height(openClose+30);
	$("#Scroll").height(openClose-20);
	windowW();
}
wSize();
function windowW(){
	if($('#Scroll').height()<$("#leftMain").height()){
		$(".scroll").show();
	}else{
		$(".scroll").hide();
	}
}
windowW();

$(function(){
	//默认载入左侧菜单
	//$("#rightMain").load($('#leftMain').find('li:first').find('a').attr('href'));
    $('#leftMain').find('li:first > a').click();
})


//左侧开关
$("#openClose").click(function(){
	if($(this).data('clicknum')==1) {
		$("html").removeClass("on");
		$(".left_menu").removeClass("left_menu_on");
		$(this).removeClass("close");
		$(this).data('clicknum', 0);
		$(".scroll").show();
	} else {
		$(".left_menu").addClass("left_menu_on");
		$(this).addClass("close");
		$("html").addClass("on");
		$(this).data('clicknum', 1);
		$(".scroll").hide();
	}
	return false;
});

(function(){
    var addEvent = (function(){
             if (window.addEventListener) {
                return function(el, sType, fn, capture) {
                    el.addEventListener(sType, fn, (capture));
                };
            } else if (window.attachEvent) {
                return function(el, sType, fn, capture) {
                    el.attachEvent("on" + sType, fn);
                };
            } else {
                return function(){};
            }
        })(),
    Scroll = document.getElementById('Scroll');
    // IE6/IE7/IE8/Opera 10+/Safari5+
    addEvent(Scroll, 'mousewheel', function(event){
        event = window.event || event ;
		if(event.wheelDelta <= 0 || event.detail > 0) {
				Scroll.scrollTop = Scroll.scrollTop + 29;
			} else {
				Scroll.scrollTop = Scroll.scrollTop - 29;
		}
    }, false);

    // Firefox 3.5+
    addEvent(Scroll, 'DOMMouseScroll',  function(event){
        event = window.event || event ;
		if(event.wheelDelta <= 0 || event.detail > 0) {
				Scroll.scrollTop = Scroll.scrollTop + 29;
			} else {
				Scroll.scrollTop = Scroll.scrollTop - 29;
		}
    }, false);

})();
function menuScroll(num){
	var Scroll = document.getElementById('Scroll');
	if(num==1){
		Scroll.scrollTop = Scroll.scrollTop - 60;
	}else{
		Scroll.scrollTop = Scroll.scrollTop + 60;
	}
}

//左侧菜单点击样式
$("#leftMain h3.f14").each(function(i){
	var ul = $(this).next();
	$(this).click(
	function(){
		if(ul.is(':visible')){
			ul.hide();
			$(this).find('span').removeClass('on');
				}else{
			ul.show();
			$(this).find('span').addClass('on');
		}
	})

	$('li.sub_menu').click(function (){
		$('li.sub_menu').removeClass('on');
		$(this).addClass('on');
	});
});
</script>
</body>
</html>
