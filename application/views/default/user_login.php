<div id="message_area">
<?php if ( ! $this->is_ajax_request ): ?>
  <?php $this->load->view('_display_message'); ?>
<?php endif; ?>
</div>
<?php echo form_open(current_url(), array('id' => 'login_form', 'class' => 'form')); ?>
<fieldset>
<legend>Login</legend>
  <div class="grid-12-12">
    <label for="login_username" class="form-lbl">Username <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="255"
           id="login_username" name="username" value="<?php echo set_value('username'); ?>" />
  </div>
  <div class="grid-12-12 field-clear">
    <label for="login_password" class="form-lbl">Password <em class="form-req">*</em></label>
    <input type="password" class="form-txt"
           id="login_password" name="user_password" value="" />
  </div>
  <div class="grid-12-12 field-clear field-button">
    <input class="form-button form-right" type="submit" title="Login" value="Login" />
  </div>
</fieldset>
<?php echo form_hidden('ajax', ''); ?>
<?php echo form_close(); ?>
<script type="text/javascript">//<![CDATA[
var form = "#login_form";
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var message_area = "#message_area";
var content_placeholder = "#main_content";
var load_message = "Logging in...";
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/ajax_form.js"></script>
