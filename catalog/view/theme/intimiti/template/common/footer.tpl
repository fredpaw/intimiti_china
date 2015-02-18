<footer>
  <div class="container">
    <div class="row">
      <div class="col-sm-3">
        <h4><?php echo $categories[0]['name']; ?></h4>
        <ul class="list-unstyled">
          <?php foreach (array_chunk($categories[0]['children'], ceil(count($categories[0]['children']) / $categories[0]['column'])) as $children) { ?>
            <?php foreach ($children as $child) { ?>
            <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
            <?php } ?>
          <?php } ?>
        </ul>
      </div>
      <div class="col-sm-3">
        <h4><?php echo $text_service; ?></h4>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
	  <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h4><?php echo $text_information; ?></h4>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3 follow">
<!--    <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>	-->
		<h4><?php echo $text_follow; ?></h4>
		<div class="row">
			<img src="catalog/view/theme/intimiti/image/social/2dcode.jpg" class="2dscanner" alt="2dcode" height="100" width="100" style="margin-bottom:10px"/>
		</div>
		<div class="row">
			<a href="weixin://addfriend/intimiti-china"><img src="catalog/view/theme/intimiti/image/social/weixin.png" alt="weixin" style="margin:0 5px;" height="22px" width="22px" /></a>
			<a href="http://weibo.com/u/5304496783"><img src="catalog/view/theme/intimiti/image/social/weibo.png" alt="weibo" style="margin:0 5px;" height="22px" width="22px" /></a>
			<a href="http://t.qq.com/intimiti"><img src="catalog/view/theme/intimiti/image/social/tencent.png" alt="tencent weibo" style="margin:0 5px;" height="22px" width="22px" /></a>
		</div>
      </div>
    </div>
    <div class="row">
	<p style="text-align:center; font-weight:bold;">Intimiti &copy; 1999 - 2014</p>
    </div>
  </div>
</footer>
</body></html>