<h1 class="monthly-title-section mb-3">
  <?php echo __( 'Import profiles of coaches',RIPOC_TEXT_DOMAIN ); ?>
</h1>

<div class="ripoc">
<div class="ripoc_in">
<form method="post"  action="" enctype="multipart/form-data">
<div class="card">
  <div class="card-header">
   <span> <?php echo __( 'Select a template excel file. ',RIPOC_TEXT_DOMAIN ); ?></span>
   <span><?php _e('Download template is here. ',RIPOC_TEXT_DOMAIN); ?><a href="<?php echo RIPOC_URL; ?>/includes/phpexcel/ratemycollege-import.xlsx" download><?php _e('Click for download',RIPOC_TEXT_DOMAIN); ?></a></span>
  </div>
  <div class="card-body">
   
  <div class="form-group">
    <label for="exampleInputEmail1"><?php echo __( 'File',RIPOC_TEXT_DOMAIN ); ?></label>
    <input type="file" class="form-control" name="ripoc_template_file"  id="ripoc_template_file"  >
  </div>
  <div class="form-group">
	<button type="submit" class="btn btn-lg btn-success"><span class="fa fa-floppy-o mr-2"></span>Import</button>
    </div>
  </div>
</div>
</form>
</div>
</div>