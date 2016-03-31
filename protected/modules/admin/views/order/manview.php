<style>
    .goodsinfo { border-collapse: collapse; border:1px solid #ddd; margin:10px auto; width:90%; }
    .goodsinfo td, .goodsinfo th { border-bottom:1px solid #ddd; height: 30px; }
    .goodsinfo th {background: #f8f8f8; text-align: center; font-weight: bold; }
    .operate {padding:10px; padding-top:3px;}
    .linkbtn {border:1px solid #ddd; background: #F8F8F8; padding:5px 15px; margin:10px 5px; border-radius: 2px; display: inline-block; }
    a.linkbtn:hover {background: #FFFAE8; }
</style>

<h3 class="title">
    <?php echo CHtml::link('返回列表', array("{$this->id}/manlist")); ?>
    订单详情
</h3>

<p class="formhead">订单信息</p>

<?php
$this->widget('zii.widgets.CDetailView', array(
    'data'=>$order,
    'attributes'=>array(
        'order_sn',
        array(
            'name' => 'order_time',
            'value' => date('Y-m-d H:i:s',$order->order_time),
        ),
        array(
            'name' => 'req_date',
            'value' => $order->req_date,
        ),
        array(
            'name' => 'req_time',
            'value' => $order->req_time,
        ),
        array(
            'name' => '买家账号',
            'value' => isset($order->buyer)?$order->buyer->username:"已注销",
        ),
        array(
            'name' => '买家电话',
            'value' => isset($order->buyer)?$order->buyer->username:"",
        ),
        array(
            'name' => '商品总额',
            'type' => 'raw',
            'value' => '<span style="color:#F30;">￥'.$order->amount.' 元</span>',
        ),

        array(
            'name' => '运费总额',
            'type' => 'raw',
            'value' => '<span style="color:#F30;">￥'.$order->freight.' 元</span>',
        ),

        array(
            'name' => 'ticketprice',
            'value' => sprintf("%.2f",$order->ticketprice),
        ),

        array(
            'name' => '实付金额',
            'type' => 'raw',
            'value' => '<span style="color:#F30;">￥'.$order->realpay.' 元</span>',
        ),
        array(
            'name' => 'pay_status',
            'value' => Yii::app()->params['pay_status'][$order->pay_status],
        ),
        array(
            'name' => 'pay_time',
            'value' => $order->pay_status > 0 ? $order->pay_time : '未付款',
        ),
        array(
            'name' => 'deliver_time',
            'value' => $order->deliver_status > 0 ? $order->deliver_time : '未发货',
        ),
        array(
            'name' => 'order_status',
            'value' => Yii::app()->params['order_status'][$order->order_status],
        ),
        array(
            'name' => '发票抬头',
            'value' => $order->bak
        ),
    ),
));
?>

<p class="formhead">寄送信息</p>
<?php
$shippingInfo = array(
    array(
        'name' => '收货人',
        'value' => isset($order->addr)?$order->addr->uname:"",
    ),
    array(
        'name' => '收货电话',
        'value' => isset($order->addr)?$order->addr->mobile:"",
    ),
    array(
        'name' => '收货地址',
        'value' => isset($order->addr)?$order->addr->address:"",
    ),
);

$this->widget('zii.widgets.CDetailView', array(
    'data'=>$order,
    'attributes'=> $shippingInfo,
));
?>

<p class="formhead">商品信息</p>
<table class="goodsinfo">
    <thead>
        <tr>
            <th>商品</th>
            <th>单价</th>
            <th>数量</th>
            <th>小计</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach($order->details as $k=>$detail):?>
        <tr>
            <td width="280"><img src="<?php echo $detail->menu->showpic;?>" width="50" height="50" style="margin:5px; padding:2px; border:1px solid #ddd;" alt="<?php echo $detail->menu->name;?>">
				<?php echo $detail->menu->name;?>
            </td>

            <td align="center">￥<?php echo $detail->menu->price;?></td>

            <td align="center"><?php echo $detail->quantity;?></td>
			
            <td align="center">￥<?php echo sprintf("%.2f",$detail->menu->price);?></td>
			
			<?php if($k==0):?>
            <td align="center" rowspan="<?php echo count($order->details);?>">
			
			<?php 
				if(!$order->deliver_status):
			echo CHtml::link("发货", $this->createUrl("order/manDeliver",array('orderid'=>$order->id)),array("style"=>"padding:3px 10px;margin:1px 0 0 8px;text-align:center;color:#069;width: 80px; height: 15px; border:1px solid #ddd;background:#EEF3F7;border-right:1px solid #555;border-bottom:1px solid #555; ","class"=>"linkbtn","title"=>'发货','id'=>"refund_".$detail->menu->id,"onclick"=>"js:dodeliver(this);return false;"));
				endif;
			?>

			<?php 
				if(!$order->pay_status):
			echo CHtml::link("已收款", $this->createUrl("order/manReceive",array('orderid'=>$order->id)),array("style"=>"padding:3px 10px;margin:1px 0 0 8px;text-align:center;color:#069;width: 80px; height: 15px; border:1px solid #ddd;background:#EEF3F7;border-right:1px solid #555;border-bottom:1px solid #555; ","class"=>"linkbtn","title"=>'已收款','id'=>"refund_".$detail->menu->id,"onclick"=>"js:receive(this);return false;"));
				endif;
			?>
			
			</td>
			<?php endif;?>
        </tr>
    <?php endforeach;?>
    </tbody>
</table>

<script>
function dodeliver(o){
	if(confirm("确定已经发货了吗")){
		location.href = o.href;
	}
	return false;
}

function receive(o){
	if(confirm("确定已经收到付款了吗")){
		location.href = o.href;
	}
	return false;
}
</script>