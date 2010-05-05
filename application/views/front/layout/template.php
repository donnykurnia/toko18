<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title><?php echo (isset($title) AND $title) ? "{$title} - " : ''; ?>Toko18</title>
<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" href="<?php echo base_url(); ?>assets/css/table-style.css" />
<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" href="<?php echo base_url(); ?>assets/css/form-style.css" />
<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" href="<?php echo base_url(); ?>assets/formee/css/form-structure.css" />
<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" href="<?php echo base_url(); ?>assets/formee/css/form-style.css" />
<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" href="<?php echo base_url(); ?>assets/css/styles.css" />
<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" href="<?php echo base_url(); ?>assets/cupertino/jquery-ui-1.7.2.custom.css" />
<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" href="<?php echo base_url(); ?>assets/css/blockUI.css" />
<link rel="stylesheet" type="text/css" charset="utf-8" media="screen" href="<?php echo base_url(); ?>assets/css/default.css" />
<?php if ( isset($additional_css) AND is_array($additional_css) ): ?>
<?php foreach ( $additional_css as $row ): ?>
  <link rel="stylesheet" type="text/css" charset="utf-8" media="screen" href="<?php echo $row; ?>" />
<?php endforeach; ?>
<?php endif; ?>
</head>
<body>
<!-- javascript -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-1.4.2.min.js"></script>
<div class="page_header">
  <?php $page_header->render(); ?>
</div>
<div id="main_content">
  <?php $main_content->render(); ?>
</div>
<div id="page_footer">
  <?php $page_footer->render(); ?>
</div>
<!-- javascript -->
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.metadata.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.livequery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/json2.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.form.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery.blockUI.min.js"></script>
<script type="text/javascript">//<![CDATA[
jQuery.blockUI.defaults.css = {};
jQuery.blockUI.defaults.overlayCSS = {};
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var content_placeholder = "#main_content";
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/message_handler.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/template.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/ajax_link.js"></script>
<?php if ( isset($additional_js) AND is_array($additional_js) ): ?>
<?php foreach ( $additional_js as $row ): ?>
  <script type="text/javascript" src="<?php echo $row; ?>"></script>
<?php endforeach; ?>
<?php endif; ?>
</body>
</html>