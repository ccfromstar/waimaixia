<!--在这里编写你的代码-->
<section>
  <?php if(isset($tickets)&&count($tickets)){?>
  <div class="user_yhq">

  <?php foreach($tickets as $v):?>
  <div class="user_yhq user_yhq1">
    <img src="/resource/i/images/<?php if($v->status){
			echo "yhqbg2.png";
		}else{
			echo "yhqbg1.png";
		}
	?>">
    <div class="user_yhq_price <?php if($v->status) echo 'gray';?>"><span>￥</span><?php echo $v->ticket->worth;?></div>
    <div class="user_yhq_text <?php if($v->status) echo 'gray';?>">
      <span class="span1"><?php echo $v->ticket->name;?></span>
      <span class="span2">
        <i>·</i>无门槛使用<br>
        <i>·</i>仅限本账号使用<br>
        <i>·</i><span>有效期至：<?php echo $v->deadline;?></span>
      </span>
    </div>
	<?php if($v->status):?>
		<div class="user_gqicon"><img src="/resource/i/images/gq.png"></div>
	<?php endif;?>
  </div>
  <?php endforeach;?>
  <?php }else{?>
	<div style="text-align:center;padding:10px;font-weight:bold;">您还没有卡券！</div>
  <?php }?>
</section>