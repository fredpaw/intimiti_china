<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content= "<?php echo $keywords; ?>" />
<?php } ?>
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
<script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
<link href="//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700" rel="stylesheet" type="text/css" />
<link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
<link href="catalog/view/theme/intimiti/stylesheet/stylesheet.css" rel="stylesheet">
<?php foreach ($styles as $style) { ?>
<link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script src="catalog/view/javascript/common.js" type="text/javascript"></script>
<?php foreach ($scripts as $script) { ?>
<script src="<?php echo $script; ?>" type="text/javascript"></script>
<?php } ?>
<?php echo $google_analytics; ?>
</head>
<body class="<?php echo $class; ?>">
<header>	
  <div class="container">
    <div class="row">
      <div class="col-sm-4">
		<div class="row" id="logo-row">
		<div class="col-sm-1 hidden-xs">
		</div>
		<div class="col-sm-11">
        <div id="logo">
          <?php if ($logo) { ?>
          <a href="index.php"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <?php } else { ?>
          <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
          <?php } ?>
        </div>
		</div>
		</div>
      </div>
	  <div class="col-sm-3 xs-hidden"></div>
	  <div class="col-sm-5">
	    <div class="row">
			<div class="col-sm-12">
			<nav id="top">
				<div id="top-links" class="nav">
				  <ul class="list-inline">
					<li class="dropdown"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_account; ?></span> <span class="caret"></span></a>
					  <ul class="dropdown-menu">
						<?php if ($logged) { ?>
						<li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
						<li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
						<li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
						<li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
						<li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
						<?php } else { ?>
						<li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
						<li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
						<?php } ?>
					  </ul>
					</li>
					<li><a href="<?php echo $wishlist; ?>" id="wishlist-total" title="<?php echo $text_wishlist; ?>"><i class="fa fa-heart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_wishlist; ?></span></a></li>
					<li><a href="<?php echo $shopping_cart; ?>" title="<?php echo $text_shopping_cart; ?>"><i class="fa fa-shopping-cart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_shopping_cart; ?></span></a></li>
					<li><a href="<?php echo $checkout; ?>" title="<?php echo $text_checkout; ?>"><i class="fa fa-share"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_checkout; ?></span></a></li>
				  </ul>
				  </div>
			</nav>
			</div>
		</div>
		<div class="row">
		  <div class="col-sm-6"><?php echo $search; ?></div>
          <div class="col-sm-6"><?php echo $cart; ?></div>
		</div>
	  </div>
    </div>
  </div>
</header>
<?php if ($categories) { ?>
<menu>
<div class="container">
  <nav id="menu" class="navbar">
    <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
      <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
    </div>
    <div class="collapse navbar-collapse navbar-ex1-collapse">
      <ul class="nav navbar-nav">
		<li><a href="index.php"><?php echo $text_home; ?></a></li>
        <?php foreach ($categories as $category) { ?>
        <?php if ($category['children']) { ?>
        <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle"><?php echo $category['name']; ?></a>
          <div class="dropdown-menu">
            <div class="dropdown-inner">
              <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
              <ul class="list-unstyled">
                <?php foreach ($children as $child) { ?>
                <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                <?php } ?>
              </ul>
              <?php } ?>
            </div>
        </li>
        <?php } else { ?>
        <?php } ?>
        <?php } ?>
		<li><a href="<?php echo $informations[4]['href']; ?>"><?php echo $informations[4]['title']; ?></a></li>
		<li><a href="<?php echo $informations[5]['href']; ?>"><?php echo $informations[5]['title']; ?></a></li>
		<li class="dropdown">
		  <a href="<?php echo $informations[6]['href']; ?>"><?php echo $informations[6]['title']; ?></a>
		    <div class="dropdown-menu">
		      <div class="dropdown-inner">
		        <ul class="list-unstyled">
		          <li><a href="<?php echo $informations[7]['href']; ?>"><?php echo $informations[7]['title']; ?></a></li>
		          <li><a href="<?php echo $informations[8]['href']; ?>"><?php echo $informations[8]['title']; ?></a></li>
		          <li><a href="<?php echo $informations[9]['href']; ?>"><?php echo $informations[9]['title']; ?></a></li>
		        </ul>
		      </div>
		    </div>
		</li>
		<li><a href="<?php echo $informations[0]['href']; ?>"><?php echo $informations[0]['title']; ?></a></li>
		<li class="dropdown"><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>
		  <div class="dropdown-menu">
		    <div class="dropdown-inner">
		      <ul class="list-unstyled">
		      	<li><a href="<?php echo $informations[10]['href']; ?>"><?php echo $informations[10]['title']; ?></a></li>
		      </ul>
		    </div>
		  </div>
		</li>
      </ul>
    </div>
  </nav>
</div>
</menu>
<?php } ?>