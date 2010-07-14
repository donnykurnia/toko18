<div id="message_area">
<?php if ( ! $this->is_ajax_request ): ?>
  <?php $this->load->view('_display_message'); ?>
<?php endif; ?>
</div>

<?php echo form_open(current_url(), array('id' => 'user_form', 'class' => 'form')); ?>
<fieldset>
  <legend>
    <?php echo ucwords($page_title); ?>
  </legend>
  <div class="grid-12-12">
  <?php if ( $mode == 'add' ): ?>
    <label for="username" class="form-lbl">Username <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="20"
           id="username" name="username" value="<?php echo set_value('username', ($user_detail AND isset($user_detail['username'])) ? $user_detail['username'] : ''); ?>" />
  <?php else: ?>
    <label for="username" class="form-lbl">Username</label>
    <?php echo $user_detail['username']; ?>
  <?php endif; ?>
  </div>
  <div class="grid-12-12 field-clear">
    <label for="user_password" class="form-lbl">Password<?php if ( $mode == 'add' ): ?> <em class="form-req">*</em><?php endif; ?></label>
    <input type="password" class="form-txt"
           id="user_password" name="user_password" value="" />
    <?php if ( $mode == 'edit' ): ?>
      <br/><span>Leave empty to kept current password</span>
    <?php endif; ?>
  </div>
  <div class="grid-12-12 field-clear">
    <label for="user_password_@" class="form-lbl">Repeat Password<?php if ( $mode == 'add' ): ?> <em class="form-req">*</em><?php endif; ?></label>
    <input type="password" class="form-txt"
           id="user_password_2" name="user_password_2" value="" />
  </div>
  <div class="grid-12-12">
    <label for="user_email" class="form-lbl">Email <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="127"
           id="user_email" name="user_email" value="<?php echo set_value('user_email', ($user_detail AND isset($user_detail['user_email'])) ? $user_detail['user_email'] : ''); ?>" />
  </div>
  <div class="grid-12-12">
    <label for="user_fullname" class="form-lbl">Fullname <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="255"
           id="user_fullname" name="user_fullname" value="<?php echo set_value('user_fullname', ($user_detail AND isset($user_detail['user_fullname'])) ? $user_detail['user_fullname'] : ''); ?>" />
  </div>
  <?php if ( $this->user_model->check_is_administrator() AND isset($user_role_options) ): ?>
  <div class="grid-12-12">
    <label for="user_role" class="form-lbl">Role <em class="form-req">*</em></label>
    <ul class="form-list-rdo">
    <?php foreach ( $user_role_options as $user_role ): ?>
      <li>
        <?php echo form_radio('user_role', $user_role, ($user_detail ? $user_detail['user_role'] == $user_role : 'user' == $user_role), 'id="user_role_'.$user_role.'"'); ?>
        <label class="form-lbl" for="user_role_<?php echo $user_role; ?>"><?php echo ucwords(str_replace('_', ' ', $user_role)); ?></label>
      </li><br class="clear" />
    <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>
  <div class="grid-12-12 field-clear field-button">
    <input class="form-button form-right" type="submit" title="Submit" value="Submit" />
  </div>
</fieldset>
<?php echo form_hidden('ajax', ''); ?>
<?php echo form_close(); ?>
<script type="text/javascript">//<![CDATA[
var form = "#user_form";
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var message_area = "#message_area";
var content_placeholder = "#main_content";
var load_message = "Sending...";
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/ajax_form.js"></script>
