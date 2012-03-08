<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:members'); ?>
</h1>
<p>
	<h3>
		<a href = "<?php echo DIR_WEB; ?>/members/order/new/">
			<?php Lang::out('menu:place_order'); ?>&hellip;
		</a>
	</h3>
</p>
<p>
	<h3>
		<a href = "<?php echo DIR_WEB; ?>/members/order/view/">
			<?php Lang::out('menu:view_current_order'); ?>
		</a>
	</h3>
</p>
<?php if(!$this->is_producer): ?>
	<hr />
	<p>
		<h3>
			<a href = "<?php echo DIR_WEB; ?>/signup/producer/">
				<?php Lang::out('menu:signup_producer'); ?>
			</a>
		</h3>
	</p>
<?php endif; ?>
<?php include 'foot.tpl.php'; ?>