$(function(){
	//session中有选中地址，则直接可点餐
	if($("#curAddr").size()>0){
		$(".cart_add").addClass('can_cart_add').removeClass('cart_add');
		$(".i_qrbtn").addClass('can_i_qrbtn').removeClass('i_qrbtn');
	}

	//日期选择控件
	$('.form_datetime-4').datetimepicker({
        format: 'yyyy-mm-dd',
		autoclose : true,
		minView : 2,
		language:  'zh-CN'
    });

	$('.form_datetime-4').datetimepicker('setStartDate',new Date());
	$('.form_datetime-4').datetimepicker('setDaysOfWeekDisabled', [0,6]);

	//周五上午10点为送餐时间临界点
	if(nowDayOfWeek>=5 || nowDayOfWeek==0){
		if(nowDayOfWeek==5){
			if(nowHour>=10){
				$('.form_datetime-4').datetimepicker('setStartDate',getNextWeekStartDate());
				$('.form_datetime-4').datetimepicker('setEndDate',getNextWeekEndDate());
			}else{
				$('.form_datetime-4').datetimepicker('setEndDate',getWeekEndDate());
			}
		}else if(nowDayOfWeek==6){
			$('.form_datetime-4').datetimepicker('setEndDate',getNextWeekEndDate());
		}else{
			$('.form_datetime-4').datetimepicker('setEndDate',getWeekEndDate());
		}
	}else{
		//当前上午10点之后只能选择第二天
		if(nowHour>=10){
			$('.form_datetime-4').datetimepicker('setStartDate',new Date(nowYear, nowMonth, nowDay+1));
		}

		$('.form_datetime-4').datetimepicker('setEndDate',getWeekEndDate());
	}

	//首页切换
	$(".i_tab2 li:first").css("display","block");
	$(".i_tab1 li").click(function(){
		$(this).addClass("current1").siblings(".i_tab1 li").removeClass("current1");
		var $cde=$(this).index(".i_tab1 li");
		$(".i_tab2 li:animated").stop(false,true);
		$(".i_tab2 li:eq("+$cde+")").fadeIn(300).siblings(".i_tab2 li").hide();
	});
});

//index_yk 游客--遮罩层
window.onload=$(function(){
   $(".yk_box").fadeIn(200);
});

$(function(){
	$(".yk_close").click(function(){
		$(".yk_box").css('display', 'none');
	});

	$(".i_location").click(function(){
		$.ajax({
			url : '/site/ajaxIsLogin.html',
			success : function(res){
				if(res=='false'){
					location.href = "/site/login.html";
				}else{
					$(".zc_box").fadeIn(200);
					//没有地址的时候，直接显示添加地址
					if($(".i_jticon").hasClass('no-addr')){
						$(".yk_box_center").css('display', 'none');
						$(".new_add_center").fadeIn(200);
					}else{
						$(".yk_box_center").css('display', 'block');
						$(".new_add_center").fadeOut(200);
					}	
				}
			}
		});
	});

	$(".yk_close").click(function(){
		$(".zc_box").css('display', 'none');
	});

	$(".yk-btn").click(function(){
		var addrId = $(this).data('addr');
		var uname = $(this).parent().find("input[name='uname']").val();
		var mobile = $(this).parent().find("input[name='mobile']").val();
		var address = $(this).parent().find("input[name='address']").val();

		var sendDate = $(this).parent().find('.i_date').val();
		var sendTime = $(this).parent().find('select').val();

		if(sendDate==''){
			layer.open({
				content: '请选择送餐日期',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
			
			return false;
		}else if($.trim(uname)==''){
			layer.open({
				content: '收货人不能为空',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
			
			return false;
		}else if($.trim(mobile)==''){
			layer.open({
				content: '电话不能为空',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
			
			return false;
		}else if($.trim(address)==''){
			layer.open({
				content: '地址不能为空',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
			
			return false;
		}else{
			if(!checkPhone(mobile)){
				layer.open({
					content: '请填写正确的联系电话',
					style: 'background-color:#09C1FF; color:#fff; border:none;',
					time: 2
				});
				
				return false;
			}
			//查询地址是否在内环线内
			if(checkSpec(address)){
				curlng = '10';
				curlat = '10';
			}else{
				checkAddr(address);
			}
			
			if(curlng!='' && curlat!==''){
				//更新地址
				$.ajax({
					url : '/site/ajaxAddAddr.html',
					data : {curlng:curlng,curlat:curlat,id:$(this).data('addr'),uname:uname,mobile:mobile,address:address,sendDate:sendDate,sendTime:sendTime},
					type : 'post',
					dataType : 'json',
					success : function(res){
						if(res.success){
							//当前选中地址ID、配送时间保存到session
							$.ajax({
								url : '/member/ajaxSet.html',
								data : {selectedAddr:addrId,sendDate:sendDate,sendTime:sendTime},
								type : 'post',
							});

							location.reload();
						}else{
							layer.open({
								content: res.msg,
								style: 'background-color:#09C1FF; color:#fff; border:none;',
								time: 2
							});
						}
					}
				});
			}
		}
	});

	$(".zc-add-btn").click(function(){
		$(".yk_box_center").css('display', 'none');
		$(".new_add_center").fadeIn(200);
	});

	$(".old_add_lj").click(function(){
		var obj = this;
		layer.open({
			content: '确定要删除当前地址吗',
			btn: ['确认', '取消'],
			shadeClose: false,
			async : false,
			yes: function(){
				$.ajax({
					url : '/member/ajaxDelAddr.html',
					data : {id:$(obj).data("addr")},
					dataType : 'json',
					success : function(res){
						layer.open({
							content: res.msg,
							style: 'background-color:#09C1FF; color:#fff; border:none;',
							time: 2
						});

						if(res.success){
							if($(obj).data("addr")==$("#default_addr").find(".yk-btn").data("addr")){
								location.reload();
							}else{
								$(obj).parents(".old_add_cc").remove();
							}
						}
					}
				});
			},
			on: function(){
				return false;
			}
		});
	});

	$(".my_add_lj").click(function(){
		var obj = this;
		layer.open({
			content: '确定要删除当前地址吗',
			btn: ['确认', '取消'],
			shadeClose: false,
			async : false,
			yes: function(){
				$.ajax({
					url : '/member/ajaxDelAddr.html',
					data : {id:$(obj).data("addr")},
					dataType : 'json',
					success : function(res){
						layer.open({
							content: res.msg,
							style: 'background-color:#09C1FF; color:#fff; border:none;',
							time: 2
						});

						if(res.success){
							$(obj).parents(".add_ns").remove();
						}
					}
				});
			},
			on: function(){
				return false;
			}
		});
	});

	$(document).delegate('.my_add_bj','click',function(){
		var obj = this;
		layer.open({
			content: '确定提交数据吗',
			btn: ['确认', '取消'],
			shadeClose: false,
			yes: function(){
				uname = $(obj).parent().parent().find("input[name='uname']").val();
				mobile = $(obj).parent().parent().find("input[name='mobile']").val();
				address = $(obj).parent().parent().find("input[name='address']").val();

				if($.trim(uname)=='' || $.trim(mobile)=='' || $.trim(address)==''){
					layer.open({
						content: "信息不完善",
						style: 'background-color:#09C1FF; color:#fff; border:none;',
						time: 2
					});
					return false;
				}else{
					if(!checkPhone(mobile)){
						layer.open({
							content: "请填写正确的手机号码",
							style: 'background-color:#09C1FF; color:#fff; border:none;',
							time: 2
						});
						return false;
					}else{
						if(checkSpec(address)){
							curlng = '10';
							curlat = '10';
						}else{
							checkAddr(address);
						}

						if(curlng!='' && curlat!=''){
							var aid = $(obj).data("addr");

							$.ajax({
								url : '/site/ajaxAddAddr.html',
								data : {id:aid,uname:uname,mobile:mobile,address:address,curlng:curlng,curlat:curlat},
								type : 'post',
								dataType : 'json',
								success : function(res){
									layer.open({
										content: res.msg,
										style: 'background-color:#09C1FF; color:#fff; border:none;',
										time: 2
									});
								}
							});
						}
					}
				}
			},
			no: function(){return false;}
		});
	});

	$(".old_add_bj").click(function(){
		$(this).parents(".odd_add_icon").prev(".new_add_slide").toggle();
		$(this).parent().parent().parent().find('.yk-btn').toggle();
		$('.new_add_center').find('.yk_cc').toggle();
	});
	
	//新增地址
	$(".zc-add-btn2").click(function(){
		var uname = $("#newAddrForm input[name='uname']").val();
		var mobile = $("#newAddrForm input[name='mobile']").val();
		var address = $("#newAddrForm input[name='address']").val();
		var sendDate = $("#newAddrForm input[name='sendDate']").val();
		var sendTime = $("#newAddrForm select[name='sendTime']").val();

		if($.trim(uname)==''){
			$("#newAddrForm input[name='uname']").focus();
			return false;
		}else if($.trim(mobile)==''){
			$("#newAddrForm input[name='mobile']").focus();
			return false;
		}else if($.trim(address)==''){
			$("#newAddrForm input[name='address']").focus();
			return false;
		}else if(sendDate==''){
			layer.open({
				content: '请选择送餐日期',
				style: 'background-color:#09C1FF; color:#fff; border:none;',
				time: 2
			});
			return false;
		}else{
			if(!checkPhone(mobile)){
				layer.open({
					content: '号码格式错误',
					style: 'background-color:#09C1FF; color:#fff; border:none;',
					time: 2
				});
				return false;
			}else{
				layer.open({
					content: '确定保存该地址吗',
					btn: ['确认', '取消'],
					shadeClose: false,
					yes: function(){
						if(checkSpec(address)){
							curlng = '10';
							curlat = '10';
						}else{
							checkAddr(address);
						}

						if(curlng!='' && curlat!=''){
							$.ajax({
								url : '/site/ajaxAddAddr.html',
								data : {curlng:curlng,curlat:curlat,uname:uname,mobile:mobile,address:address,sendDate:sendDate,sendTime:sendTime},
								type : 'post',
								dataType : 'json',
								success : function(res){
									layer.open({
										content: res.msg,
										style: 'background-color:#09C1FF; color:#fff; border:none;',
										time: 2
									});

									if(res.success){
										location.reload();
									}
								}
							});
						}
					}, no: function(){
						return false;
					}
				});
			}
		}
	});
});

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
		return false;
    } else {
        curlng = '';
		curlat = '';
        layer.open({
			content: '抱歉，内环线外暂不支持配送服务',
			style: 'background-color:#09C1FF; color:#fff; border:none;',
			time: 2
		});
		return false;
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
				layer.open({
					content: '抱歉，地址信息获取失败',
					style: 'background-color:#09C1FF; color:#fff; border:none;',
					time: 2
				});

				return false;
			}
		}
	});
}

function keyPress(obj) {  
	var keyCode = event.keyCode;  
	if((keyCode >= 48 && keyCode <= 57)){
		event.returnValue = true;  
	}else{  
		event.returnValue = false;  
    }  
}  
