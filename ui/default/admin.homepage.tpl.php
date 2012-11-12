<?php include 'head.tpl.php'; ?>
<h1>
	<?php Lang::out('ttl:homepage'); ?>
</h1>
<form method = "post" action = "">
	<textarea class = "editor" rows = "10" cols = "10" style = "width:100%;" name = "content"><?php echo $this->content; ?></textarea>
	<div class = "form-actions">
		<input type = "submit" class = "btn" value = "Save" name = "save" />
	</div>
</form>
<script type = "text/javascript" src = "<?php echo DIR_LIBRARY_WEB; ?>/ckeditor/ckeditor.js"></script>
<script type = "text/javascript" src = "<?php echo DIR_LIBRARY_WEB; ?>/ckeditor/adapters/jquery.js"></script>
<script type = "text/javascript" src = "<?php echo DIR_TEMPLATE_WEB; ?>/js/homepage.js"></script>
<?php include 'foot.tpl.php'; ?>