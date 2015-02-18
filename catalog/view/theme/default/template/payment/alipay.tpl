<?php if (isset($payurl)) { ?>
<a href="<?php echo $payurl; ?>" class="btn btn-primary button btn-green"><?php echo $button_confirm; ?></a>
<?php } else { ?>
<form action="<?php echo $action; ?>" method="post">
	<?php echo $alipay; ?>
	<div class="buttons">
		<div class="pull-right">
			<input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary button btn-green" />
		</div>
	</div>
</form>
<?php } ?>