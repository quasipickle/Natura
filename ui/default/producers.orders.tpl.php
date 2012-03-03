<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:purchase_orders'); ?>
</h1>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif; ?>
<?php if($this->old_cycles): ?>
	<form method = "get" action = "" class = "well form-inline">
		<div>
			<select name = "cycle" id = "cycle-chooser">
				<option value = "-1"><?php Lang::out('lbl:cycles_past_view') ?></option>
				<?php foreach($this->old_cycles as $Cycle): ?>
					<option value = "<?php echo $Cycle->id; ?>" <?php if($this->cycle_id === $Cycle->id): ?>selected="selected"<?php endif; ?>><?php echo $Cycle->name.' ('.$Cycle->start.' - '.$Cycle->end.')'; ?></option>
				<?php endforeach; ?>
			</select>
		</div>
	</form>
<?php endif; ?>	

<?php if($this->active_cycle): ?>
	<div class = "alert">
		<?php Lang::out('info:order_amounts_not_final'); ?>
	</div>
<?php endif; ?>

<?php if(isset($this->amounts) && count($this->amounts)): ?>
	<pre><?php include 'orders.txt.tpl.php'; ?></pre>
	<div class = "form-actions" style = "text-align:right;">
		<a href = "<?php echo DIR_WEB; ?>/producers/orders/?cycle=<?php echo $this->cycle_id; ?>&amp;download&amp;format=txt" class = "btn"><?php Lang::out('lbl:orders_download_txt'); ?></a>
		<a href = "<?php echo DIR_WEB; ?>/producers/orders/?cycle=<?php echo $this->cycle_id; ?>&amp;download&amp;format=csv" class = "btn"><?php Lang::out('lbl:orders_download_csv'); ?></a>
	</div>
<?php elseif($this->cycle_id): ?>
	<?php Lang::out('msg:purchase_orders_none'); ?>
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