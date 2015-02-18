<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cod" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cod" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_trade_type; ?></label>
            <div class="col-sm-10">
				<input type="radio" id="alipay_trade_type1" name="alipay_trade_type" value="trade_create_by_buyer"<?php if ($alipay_trade_type=='trade_create_by_buyer') { ?> checked<?php } ?> />
				<label for="alipay_trade_type1"><?php echo $entry_trade_type1; ?></label>
				<input type="radio" id="alipay_trade_type2" name="alipay_trade_type" value="create_direct_pay_by_user"<?php if ($alipay_trade_type=='create_direct_pay_by_user') { ?> checked<?php } ?> />
				<label for="alipay_trade_type2"><?php echo $entry_trade_type2; ?></label>
				<input type="radio" id="alipay_trade_type3" name="alipay_trade_type" value="create_partner_trade_by_buyer"<?php if ($alipay_trade_type=='create_partner_trade_by_buyer') { ?> checked<?php } ?>  />
				<label for="alipay_trade_type3"><?php echo $entry_trade_type3; ?></label>
				<?php if ($error_trade_type) { ?><span class="text-danger"><?php echo $error_trade_type; ?></span><?php } ?>
			</div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_partner; ?></label>
            <div class="col-sm-10">
				<input type="text" name="alipay_partner" value="<?php echo $alipay_partner; ?>" placeholder="<?php echo $entry_partner; ?>" id="input-partner" class="form-control" />
				<?php if ($error_partner) { ?><span class="text-danger"><?php echo $error_partner; ?></span><?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_security_code; ?></label>
            <div class="col-sm-10">
				<?php if ($user->hasPermission('modify', 'payment/alipay')) { ?>
				<input type="text" name="alipay_security_code" value="<?php echo $alipay_security_code; ?>" placeholder="<?php echo $entry_security_code; ?>" id="input-security-code" class="form-control" />
				<?php if ($error_secrity_code) { ?>
				<span class="text-danger"><?php echo $error_secrity_code; ?></span>
				<?php } } else { ?>
				<input type="hidden" name="alipay_security_code" value="" size="50" />
				<div style="font-weight:bold;color:#FF0000;">You do not have permission to view that item content!</div>
				<?php } ?>
			</div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_seller_email; ?></label>
            <div class="col-sm-10">
				<input type="text" name="alipay_seller_email" value="<?php echo $alipay_seller_email; ?>" placeholder="<?php echo $entry_seller_email; ?>" id="input-seller-email" class="form-control" />
				<?php if ($error_email) { ?><span class="text-danger"><?php echo $error_email; ?></span><?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="alipay_total" value="<?php echo $alipay_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-order-status"><span data-toggle="tooltip" title="<?php echo $help_status; ?>"><?php echo $entry_order_status; ?></span></label>
            <div class="col-sm-10">
              <select name="alipay_order_status_id" id="input-order-status" class="form-control">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $alipay_order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="alipay_geo_zone_id" id="input-geo-zone" class="form-control">
                <option value="0"><?php echo $text_all_zones; ?></option>
                <?php foreach ($geo_zones as $geo_zone) { ?>
                <?php if ($geo_zone['geo_zone_id'] == $alipay_geo_zone_id) { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="alipay_status" id="input-status" class="form-control">
                <?php if ($alipay_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
            <div class="col-sm-10">
              <input type="text" name="alipay_sort_order" value="<?php echo $alipay_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
	<div style="text-align:center;font-weight:bold;margin-top:7px;">
		<a href="mailto:hoojar@163.com">Technical Support</a> &nbsp;&nbsp;| &nbsp;&nbsp;
		<a href="http://www.hecart.com" target="_blank">Official Site</a> &nbsp;&nbsp;| &nbsp;&nbsp;
		<a href="http://www.hecart.com/category/6.html" target="_blank">More Extensions</a>
	</div>
</div>
<?php echo $footer; ?> 