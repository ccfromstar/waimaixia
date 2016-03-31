<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl;?>/js/DatePicker/WdatePicker.js"></script>

<h3 class="title">
    <?php echo CHtml::link('返回列表', array('/admin/order/manlist')); ?>
    <?php echo $operate;?>
</h3>

<p class="formhead"><?php echo $operate;?></p>

<?php
	$form = $this->beginWidget('CActiveForm',array(
		'id' => 'manorder_form',
		'enableClientValidation' => true,
		'clientOptions' => array(
			'validateOnSubmit' => true,
		)
	));
?>

<?php
	$this->widget('zii.widgets.jui.CJuiTabs',array(
		'tabs'=>array(
			'基本信息'=>$this->renderPartial('_baseinfo',array('form'=>$form, 'model'=>$model,'addr'=>$addr),true),
			'菜品选择'=>$this->renderPartial('_menuinfo',array('menu'=>$menu),true),
		),
		'options'=>array(
			'collapsible'=>true,
		),
	));
?>

<div class="row buttons" style="height:40px;border:none;">
	<label>&nbsp;</label>
	<?php echo CHtml::button('确认提交', array('id' => 'btnSubmit', 'style' => 'cursor:pointer;', 'onclick'=>'dosub()')); ?>
</div>

<?php $this->endWidget();?>

<script>
var curlng,curlat;
var neihuanFlag = false;
var hpqFlag = false;
var errMsg = '';

function dosub(){
	var count = 0;
	//检查有没有选择菜品
	$("#menuinfo .cusinput").each(function(){
		count += parseInt($(this).val());
	});

	if(count<=0){
		alert('请选择要购买的菜品');
		return false;
	}else{
		var address = $.trim($("#Address_address").val());
		
		if(address!==''){
			//检查特殊地址
			if(checkSpec(address)){
				addorder();
			}else{	
				//检查地址是否内环线内
				checkAddr(address);

				if(neihuanFlag){
					//检查是否在黄浦区
					checkHp(address,curlng,curlat,count);
				}else{
					alert(errMsg)
				}
			}
		}else{
			$("#Address_address_em_").text('送餐地址不能为空').show();
			return false;
		}
	}
}

function checkSpec(address){
	var flag = false;
	$.ajax({
		async : false,
		url : '<?php echo $this->createUrl("/site/ajaxCheckSpec");?>',
		data : {addr:address},
		type : 'post',
		success : function(res){
			if(res=='success'){
				flag = true;
			}
		}
	});
	return flag;
}

//正则校验手机号
function checkPhone(phone){
    var isPhone = /^([0-9]{3,4}-)?[0-9]{7,8}$/;
    var isMob=/^((\+?86)|(\(\+86\)))?(13[012356789][0-9]{8}|15[012356789][0-9]{8}|18[012356789][0-9]{8}|147[0-9]{8}|1349[0-9]{7}|177[0-9]{8})$/;
    
	if(isMob.test(phone) || isPhone.test(phone)){
        return true;
    }else{
        return false;
    }
}

///////////////// 地图相关  /////////////////////////
//点在多边形内
function ptInPolygon(lng,lat){
    var pts = [];
	var pt1 = new BMap.Point(121.386,31.238);
	var pt2 = new BMap.Point(121.522,31.306);
	var pt3 = new BMap.Point(121.580,31.215);
	var pt4 = new BMap.Point(121.439,31.186);
     
    pts.push(pt1);
    pts.push(pt2);
    pts.push(pt3);
    pts.push(pt4);

    var ply = new BMap.Polygon(pts);
    
	var pt =new BMap.Point(lng,lat);
    
    var result = BMapLib.GeoUtils.isPointInPolygon(pt, ply);
	
    if(result == true){
		curlng = lng;
		curlat = lat;
		neihuanFlag = true;
    } else {
        curlng = '';
		curlat = '';
		errMsg = "抱歉，内环线外暂不支持配送服务";
		neihuanFlag = false;
    }     
}

function checkAddr(addr){
	$.ajax({
		url : '/site/addrToLngAndLat.html',
		data : {address:addr},
		dataType : 'json',
		async : false,
		success : function(res){
			if(res.success){
				ptInPolygon(res.lng,res.lat);
			}else{
				errMsg = "抱歉，地址信息获取失败";
			}
		}
	});
}

//检测是否在黄浦区
function checkHp(address,lng,lat,sum){
	// 创建地理编码实例
	var myGeo = new BMap.Geocoder();
	// 根据坐标得到地址描述
	myGeo.getLocation(new BMap.Point(lng,lat), function(result){
		var addr = result.address;

		if(addr.indexOf("黄浦区")==-1){
			//不在黄埔区
			if(sum<10){
				alert("黄浦区外内环线内10份起送");
				return false;
			}else{
				addorder();
			}
		}else{
			addorder();
		}
	});
}

function addorder(){
	var phoneNumb = $.trim($("#Address_mobile").val());

	if(phoneNumb!==''){
		if(checkPhone(phoneNumb)){
			$("#manorder_form").submit();
		}else{
			$("#Address_mobile_em_").text('联系电话格式不对').show()
		}
	}else{
		$("#Address_mobile_em_").text('联系电话不能为空').show()
		return false;
	}
}

</script>

<?php
	Yii::app()->clientScript->registerScriptFile('http://api.map.baidu.com/api?v=1.2',CClientScript::POS_END);
	Yii::app()->clientScript->registerScriptFile('http://api.map.baidu.com/library/GeoUtils/1.2/src/GeoUtils_min.js',CClientScript::POS_END);
?>