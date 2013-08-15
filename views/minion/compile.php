Compiling production assets...
<?php foreach ($content as $type => $val): ?>
<?php echo $type ?>

<?php foreach ($val as $profile => $profile_info): ?>
	<?php echo $profile ?>

<?php foreach ($profile_info['files'] as $file): ?>
		<?php if ($file['prefix']) echo $file['prefix'] ?><?php echo $file['name'] ?>

<?php endforeach ?>
<?php endforeach ?>
<?php endforeach ?>
