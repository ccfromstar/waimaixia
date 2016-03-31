<tbody id="order<?php echo $data->order_sn ?>" class="order closed-order">
	<tr class="sep-row" style="border:none!important"><td colspan="8" tyle="border:none!important"></td></tr>
	<tr class="order-hd">
		<td colspan="8">
			<input class="selector" type="checkbox" id="<?php echo $data->order_sn; ?>" name="ids[]" value="<?php echo $data->id?>">
			<div class="basic-info">
				<span class="order-num">
					<label for="<?php echo $data->order_sn; ?>">
						订单编号：<?php echo $data->order_sn; ?>
					</label>
				</span>
				<span class="deal-time"><label>成交时间：<?php echo date("Y-m-d H:i:s",$data->order_time);?></label></span>
				<span class="buyer-name"><?php if(isset($data->buyer)):?>
				<label>买家：<?php echo $data->buyer->username.($data->bak ? CHtml::link("买家附言", "javascript:;", array("title" => $data->bak, "class" => "tip btip")) : ""); ?></label><?php endif;?></span>
				<span class="buyer-info"><?php if(isset($data->addr)):?><label>收货信息：<?php echo $data->addr->uname.','.$data->addr->mobile.','.date("Y-m-d",strtotime($data->req_date)).' '.$data->req_time; ?></label><?php endif;?></span>
			</div>
		</td>  
	</tr>

	<tr>
		<td align='center'>商品</td>
		<td align='center'>单价</td>
		<td align='center'>购买数量</td>
		<td align='center'>支付状态</td>
		<td align='center'>订单状态</td>
		<td align='center'>发货状态</td>
		<td align='center'>订单合计</td>
		<td align='center'>操作</td>
	</tr>

	<!--start 子订单list start-->
	<?php foreach($data->details as $k =>$detail):?>
	<tr id="item<?php echo $data->order_sn; ?>" class="order-item">
		<td class="item">
			<div class="pic-info">
				<div class="pic s50">
					<img style="height:50px;" alt="查看宝贝详情" src="<?php echo $detail->menu->showpic;?>">
				</div>
			</div> 
			<div class="txt-info">
				<div class="desc">
					<?php echo $detail->menu->name;?>
				</div>
			</div>
		</td>  
		<td class="price"><?php echo $detail->menu->price;?></td>
		<td class="num" title="<?php echo $detail->quantity;?>"><?php echo $detail->quantity;?></td>
		<?php if($k==0):?>
		<td class="trade-status" rowspan="<?php echo count($data->details); ?>">
			<strong class="J_TradeStatus status closed">
			<?php 
				echo Yii::app()->params["pay_status"][$data->pay_status];
			?>
			</strong>
		</td>
		
		<td class="trade-status" rowspan="<?php echo count($data->details); ?>">
			<strong class="J_TradeStatus status closed"><?php echo Yii::app()->params["order_status"][$data->order_status]; ?></strong>
		</td>
		
		<td class="trade-status" rowspan="<?php echo count($data->details); ?>">
			<strong class="J_TradeStatus status closed"><?php echo $data->deliver_status?"已发货":"未发货"; ?></strong>
		</td>
		
		<td class="order-price" rowspan="<?php echo count($data->details); ?>">
			<?php
				$order_real_amount = 0;
				foreach($data->details as $gv){
					$order_real_amount += $gv->menu->price;
				}
				$order_real_amount = sprintf('%0.2f',$order_real_amount);
			?>
			<strong class="J_OrderPrice"><?php echo $data->realpay; ?></strong>
			<?php if(isset($data->freight)):?>
			<div class="post-info">
				(含<span class="post-type">快递</span>:<span class="J_PostFee">
				<?php echo $data->freight; ?></span>) 
			</div>
			<?php endif;?>
			<div class="order_from"></div>
		</td>
		<td class="remark" rowspan="<?php echo count($data->details); ?>">
		<?php echo CHtml::link(CHtml::image(Yii::app()->baseUrl."/images/admin_img/view.png","查看"),array("order/manview","id"=>$data->id))?>
		
		<?php echo CHtml::Link(CHtml::image(Yii::app()->baseUrl."/images/admin_img/delete.png",'删除'),array("order/mandelete","id"=>$data->id),array('class'=>'Order_delete'))?>
		</td>
		<?php endif;?>
	</tr>
<?php endforeach;?>
</tbody>