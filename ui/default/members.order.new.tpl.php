<?php include 'head.tpl.php'; ?>

<h1>
	<?php Lang::out('ttl:new_order'); ?>
</h1>
<p>
	<?php Lang::out('info:order'); ?>
</p>
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
<?php endif;
if($this->order_created): ?>
	<div class = "alert alert-success">
		<?php Lang::out('msg:order_placed',array('%ID%'=>$this->order_id)); ?>
	</div>
<?php endif; ?>
<?php if($this->active_cycles): ?>
		<form class = "well form-inline" action = "" method = "post">
			<select id = "cycle-chooser" name = "cycle">
				<option value = "-1"><?php Lang::out('lbl:cycle_choose'); ?></option>
				<?php foreach($this->active_cycles as $id=>$name): ?>
					<option value = "<?php echo htmlentities($id); ?>" 
								<?php if($id == $this->active_cycle): ?>selected = "selected"<?php endif; ?>
						><?php echo $name; ?></option>
					<?php endforeach; ?>
			</select>
		</form>
<?php else: ?>
	<div class = "alert">
		<?php Lang::out('info:order_cycle_none'); ?>
	</div>
<?php endif;
if($this->already_ordered)
	Lang::out('msg:order_already');
elseif($this->products)
	include 'order_form.tpl.php';
?>
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

<?php include 'foot.tpl.php';?>
