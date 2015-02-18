<?php echo $header; ?><?php echo $column_left; ?>
<div id='content'>
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-categoryproduct" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div id='container-fluid'>
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
	    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-categoryproduct" class="form-horizontal">
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-choose_from"><?php echo $entry_choose_from; ?></label>
            <div class="col-sm-10">
              <select name="choose_from" id="input-choose_from" class="form-control">
                <?php if ($choose_from) { ?>
				<option value="0">Category</option>
                <option value="1" selected="selected">Selected</option>
                <?php } else { ?>
                <option value="0" selected="selected">Category</option>
                <option value="1">Selected</option>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group" id="select-product">
            <label class="col-sm-2 control-label" for="input-product"><?php echo $entry_product; ?></label>
            <div class="col-sm-10">
              <input type="text" name="product" value="" placeholder="<?php echo $entry_product; ?>" id="input-product" class="form-control" />
              <div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($products as $product) { ?>
                <div id="featured-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $product['name']; ?>
                  <input type="hidden" name="product[]" value="<?php echo $product['product_id']; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
		  <div class="form-group" id="select-category">
            <label class="col-sm-2 control-label" for="input-category"><?php echo $entry_category; ?></label>
            <div class="col-sm-10">
              <select name="category" id="input-category" class="form-control">
				<option>Select a category</option>
                <?php foreach ($categories as $category_info) { ?>
                <?php if ($category_info['category_id'] == $category_id) { ?>
                <option value="<?php echo $category_info['category_id']; ?>" selected="selected"><?php echo $category_info['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $category_info['category_id']; ?>"><?php echo $category_info['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group" id="select-sort">
            <label class="col-sm-2 control-label" for="input-sort"><?php echo $entry_sort_by; ?></label>
            <div class="col-sm-10">
              <select name="sort_by" id="input-sort" class="form-control">
				<option value="">choose a attribute</option>
                <?php foreach($sorts as $sort) { ?>
                <?php if ($sort['value'] == $sort_by) { ?>
                <option value="<?php echo $sort['value']; ?>" selected><?php echo $sort['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $sort['value']; ?>"><?php echo $sort['text']; ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <div class="col-sm-10">
              <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
              <?php if ($error_width) { ?>
              <div class="text-danger"><?php echo $error_width; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
              <?php if ($error_height) { ?>
              <div class="text-danger"><?php echo $error_height; ?></div>
              <?php } ?>
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-custom_title"><?php echo $entry_custom_title; ?></label>
            <div class="col-sm-10">
              <input type="text" name="custom_title" value="<?php echo $custom_title; ?>" placeholder="<?php echo $entry_custom_title; ?>" id="input-custom_title" class="form-control" />
            </div>
          </div>
		  <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
		</form>
	  </div>
	</div>
  </div>
  <script type="text/javascript"><!--
$(document).ready(function(){
	if($('#input-choose_from').val() == 1){
		$('#select-product').show();
		$('#select-category').hide();
		$('#select-sort').hide();
	} else {
		$('#select-category').show();
		$('#select-sort').show();
		$('#select-product').hide();
	}
});
  
$('input[name=\'product\']').autocomplete({
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['product_id']
					}
				}));
			}
		});
	},
	select: function(item) {
		$('input[name=\'product\']').val('');
		
		$('#featured-product' + item['value']).remove();
		
		$('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product[]" value="' + item['value'] + '" /></div>');	
	}
});
	
$('#featured-product').delegate('.fa-minus-circle', 'click', function() {
	$(this).parent().remove();
});

$('#input-choose_from').change(function(){
	if($('#input-choose_from').val() == 1){
		$('#select-product').show();
		$('#select-category').hide();
		$('#select-sort').hide();
	} else {
		$('#select-category').show();
		$('#select-sort').show();
		$('#select-product').hide();
	}
});
  --></script>
</div>
<?php echo $footer; ?>