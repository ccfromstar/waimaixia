$(function(){
	//session中有选中地址，则直接可点餐
	//if($("#curAddr").size()>0){
		$(".cart_add").addClass('can_cart_add').removeClass('cart_add');
		$(".i_qrbtn").addClass('can_i_qrbtn').removeClass('i_qrbtn');
	//}

	//日期选择控件
	$('.form_datetime-4').datetimepicker({
        format: 'yyyy-mm-dd',
		autoclose : true,
		minView : 2,
		language:  'zh-CN'
    });

	$('.form_datetime-4').datetimepicker('setStartDate',new Date());
	$('.form_datetime-4').datetimepicker('setDaysOfWeekDisabled', [0,6]);

	//周五上午12点为送餐时间临界点
	if(nowDayOfWeek>=5 || nowDayOfWeek==0){
		if(nowDayOfWeek==5){
			if(nowHour>=12){
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
		//当前上午12点之后只能选择第二天
		if(nowHour>=12){
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
	/*
	//如果是从APP链接过来的价格都减1元
	var url = window.location.href;
	if(url.indexOf("?path=HuaiHaiApp")){
		$('.i_price').each(function(){
			var n = ($(this).html());
			var arr1 = n.split("￥");
			n = Number(arr1[1]);
			n = n - 1;
			n = "￥" + n + ".00";
			$(this).html(n);
		});
	}*/
});

//index_yk 游客--遮罩层
window.onload=$(function(){
   $(".yk_box").fadeIn(200);
});

$(function(){
	$.ajax({
		url : '/site/ajaxIsLogin.html',
		success : function(res){
			if(res=='false'){
				if($('.i_ltxt')){
					$('.i_ltxt').html("请登录");
				}	
			}
		}	
	});

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

		var d = new Date();
		var today = d.Format("yyyy-MM-dd");
		var h = d.getHours();

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

			if(sendDate==today){
				if(h == 10){
					if(sendTime !='o' && sendTime !='m'){
						layer.open({
							content: '当前可选配送时间段为12:00—12:30；12:30—13:00',
							style: 'background-color:#09C1FF; color:#fff; border:none;',
							time: 2
						});
						return false;
					}
				}else if(h == 11){
					if(sendTime !='n'){
						layer.open({
							content: '当前可选配送时间段为13:00—14:00',
							style: 'background-color:#09C1FF; color:#fff; border:none;',
							time: 2
						});
						return false;
					}
				}
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

							//location.reload();
							//begin
	//保存菜品选择结果的JSON格式
	var jsonStr = '{';
	//总共选择的菜品数量
	var sum = 0;

	$(".i_item").each(function(){
		var tmpMenuId = $(this).find("input[name='menuid']").val();
		var tmpNum = parseInt($(this).find('.cart_number').text());

		if(tmpNum>0){
			sum += tmpNum;
			jsonStr += '"' + tmpMenuId + '":'+tmpNum+',';
		}
	});

	if(jsonStr.indexOf(",")>-1){
		jsonStr = jsonStr.substring(0,jsonStr.length-1);
	}

	jsonStr += '}';

	if(sum>0){
		//下单之前，先检查地址范围，黄浦区外至内环线10份起送
		$.ajax({
			url : '/site/getLngLat.html',
			dataType : 'json',
			async : false,
			success : function(res){
				checkHp(res.address,res.lng,res.lat,jsonStr,sum);
			}
		});
	}else{
		layer.open({
			content: '请先选餐',
			style: 'background-color:#09C1FF; color:#fff; border:none;',
			time: 2
		});
	}
							//end
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
										//location.reload();
										//begin
	//保存菜品选择结果的JSON格式
	var jsonStr = '{';
	//总共选择的菜品数量
	var sum = 0;

	$(".i_item").each(function(){
		var tmpMenuId = $(this).find("input[name='menuid']").val();
		var tmpNum = parseInt($(this).find('.cart_number').text());

		if(tmpNum>0){
			sum += tmpNum;
			jsonStr += '"' + tmpMenuId + '":'+tmpNum+',';
		}
	});

	if(jsonStr.indexOf(",")>-1){
		jsonStr = jsonStr.substring(0,jsonStr.length-1);
	}

	jsonStr += '}';

	if(sum>0){
		//下单之前，先检查地址范围，黄浦区外至内环线10份起送
		$.ajax({
			url : '/site/getLngLat.html',
			dataType : 'json',
			async : false,
			success : function(res){
				checkHp(res.address,res.lng,res.lat,jsonStr,sum);
			}
		});
	}else{
		layer.open({
			content: '请先选餐',
			style: 'background-color:#09C1FF; color:#fff; border:none;',
			time: 2
		});
	}
							//end
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

	var n_lng = [121.41992,121.416471,121.445216,121.470513,121.484311,121.49006,121.529729,121.548701,121.572848,121.580896,121.545827,121.507882,121.447516,121.431993];
    var n_lat = [31.212308,31.235036,31.261216,31.26566,31.276031,31.289857,31.290844,31.263191,31.24541,31.214778,31.202424,31.210825,31.183643,31.188585];
    
    for(var i=0;i<n_lng.length;i++){
    	var pt = new BMap.Point(n_lng[i],n_lat[i]);
    	pts.push(pt);
    }

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

Date.prototype.Format = function(fmt) {
	var d = this;
	var o = {
		"M+": d.getMonth() + 1, //月份
		"d+": d.getDate(), //日
		"h+": d.getHours(), //小时
		"m+": d.getMinutes(), //分
		"s+": d.getSeconds(), //秒
		"q+": Math.floor((d.getMonth() + 3) / 3), //季度
		"S": d.getMilliseconds() //毫秒
	};
	if (/(y+)/.test(fmt)) {
		fmt = fmt.replace(RegExp.$1, (d.getFullYear() + "").substr(4 - RegExp.$1.length));
	}
	for (var k in o) {
		if (new RegExp("(" + k + ")").test(fmt)) {
			fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
		}
	}
	return fmt;
}

//检查是否在陆家嘴地区
function checkLJZ(lng,lat){
	var npts = [];
    var n_lng = [121.513919,121.501271,121.553875,121.573422,121.581471,121.545539];
    var n_lat = [31.210084,31.243928,31.25924,31.243681,31.214037,31.203166];
    
    for(var i=0;i<n_lng.length;i++){
    	var pt = new BMap.Point(n_lng[i],n_lat[i]);
    	npts.push(pt);
    }

    var ply = new BMap.Polygon(npts);
    
	var pt =new BMap.Point(lng,lat);
    
    var result = BMapLib.GeoUtils.isPointInPolygon(pt, ply);
	
    return result; 
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
