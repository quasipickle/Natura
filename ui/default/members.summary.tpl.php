<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:summaries'); ?>
</h1>
<p>
	<?php Lang::out('info:summaries'); ?>
</p>
<?php if($this->old_cycles): ?>
	<form class = "well form-inline" action = "" method = "post">
		<select id = "cycle-chooser" name = "cycle">
			<option value = "-1"><?php Lang::out('lbl:cycle_choose'); ?></option>
			<?php foreach($this->old_cycles as $Cycle): ?>
				<option value = "<?php echo htmlentities($Cycle->id); ?>"><?php echo $Cycle->name; ?></option>
			<?php endforeach; ?>
		</select>
	</form>
<?php else: ?>
	<div class = "alert">
		<?php Lang::out('info:no_past_order_summaries'); ?>
	</div>
<?php endif; ?>
<?php if($this->Order): ?>
	<div class = "alert">
		<?php Lang::out('info:text_price'); ?>
	</div>
	<pre><?php include 'summary.txt.tpl.php'; ?></pre>
	<div class = "form-actions" style = "text-align:right;">
		<a href = "<?php echo DIR_WEB; ?>/members/summary/?download=<?php echo $this->Order->id; ?>&amp;format=txt" class = "btn"><?php Lang::out('lbl:orders_download_txt'); ?></a>
		<a href = "<?php echo DIR_WEB; ?>/members/summary/?download=<?php echo $this->Order->id; ?>&amp;format=csv" class = "btn"><?php Lang::out('lbl:orders_download_csv'); ?></a>
	</div>
	<br />
<?php endif; ?>

<script type = "text/javascript">
	$(document).ready(function(){
		$("#cycle-chooser").on('change',function(){
			var id = $(this).val();
			if(id != '-1')
			{
				window.location.href = window.location.protocol+'//'+window.location.host+window.location.pathname+'?cycle='+id
			}
		});
	});
</script>

<?php include 'foot.tpl.php'; ?>