<!--在这里编写你的代码-->
<section>
  <div class="user_head">
    <div class="user_headx"><img src="<?php echo $user->avatar;?>"></div>
    <div class="user_name"><?php echo $user->username;?></div>
    <div class="user_edit">[个人信息]</div>
  </div>
  <div class="user_list">
    <a href="/site/index.html"><img src="/resource/i/images/usericon4.png" class="right"><img src="/resource/i/images/usericon1.png">开始订餐</a>
  </div>
  <div class="user_list">
    <a href="/member/orders.html"><img src="/resource/i/images/usericon4.png" class="right"><img src="/resource/i/images/usericon1.png">我的订单</a>
  </div>
  <div class="user_list">
    <a href="/member/addr.html"><img src="/resource/i/images/usericon4.png" class="right"><img src="/resource/i/images/usericon2.png">我的地址</a>
  </div>
  <div class="user_list">
    <a href="/member/tickets.html"><img src="/resource/i/images/usericon4.png" class="right"><img src="/resource/i/images/usericon3.png">优惠信息</a>
  </div>
  <div class="user_list">
    <a href="/site/logout.html"><img src="/resource/i/images/usericon4.png" class="right"><img src="/resource/i/images/usericon3.png">退出登录</a>
  </div>
</section>