<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:manage_categories'); ?>
</h1>
<p>
<?php Lang::out('info:categories_about'); ?>
</p>
<br />
<?php if(isset($this->error)): ?>
	<div class = "alert alert-error">
		<?php foreach($this->error as $error): ?>
			<?php echo $error; ?><br />
		<?php endforeach; ?>
	</div>
	<br />
<?php endif; ?>
<?php if($this->create_success): ?>
	<p class = "alert alert-success">
		<?php Lang::out('msg:category_created'); ?>
	</p>
	<br />
<?php endif; ?>
<?php if($this->delete_success): ?>
	<p class = "alert alert-success">
		<?php Lang::out('msg:category_deleted'); ?>
	</p>
	<br />
<?php endif; ?>
<form method = "post" action = "" class = "pull-right">
		<label>
				<?php Lang::out('lbl:new_category'); ?>
			</label>
			<input type = "text" name = "new_category" value = "<?php echo htmlentities($this->new_category); ?>" />
			<br />
			<input type = "submit" name = "new_category_submit" class = "btn" value = "<?php Lang::outSafe('btn:create_category'); ?>" />
	</ul>
</form>
<div class = "pull-left" style = "width:50%;">
	<?php if(!count($this->categories)): ?>
		<div class = "alert alert-info">
			<?php Lang::out('msg:no_categories'); ?>
		</div>
	<?php else: ?>
		<table class = "table table-striped">
			<thead>
				<tr>
					<th colspan = "2">
						<?php Lang::out('lbl:category'); ?>
					</th>
				</tr>
			</thead>
			<tbody>
				<?php foreach($this->categories as $Category): ?>
					<tr>
						<td>
							<?php echo $Category->name_hr; ?>
						</td>
						<td>
							<form method = "post" action = "">
								<div>
									<input type = "hidden" name = "id" value = "<?php echo htmlentities($Category->id); ?>" />
									<input type = "submit" name = "delete" class = "btn btn-danger" value = "<?php Lang::outSafe('btn:delete_category'); ?>" />
								</div>
							</form>
							
						</td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	<?php endif; ?>
</div>
<?php include 'foot.tpl.php'; ?>