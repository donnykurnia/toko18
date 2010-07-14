<div id="message_area">
<?php if ( ! $this->is_ajax_request ): ?>
  <?php $this->load->view('_display_message'); ?>
<?php endif; ?>
</div>
<div id="front_content">
  <p>Selamat datang di Toko18!</p>
  <?php $CI =& get_instance(); ?>
  <?php $CI->load->model('user_model'); ?>
  <?php if ( $CI->user_model->check_login() ): ?>
    Buka halaman <?php echo anchor('dashboard/index', 'dashboard'); ?>.
  <?php else: ?>
    <p>Silahkan <?php echo anchor('user/login', 'login'); ?> terlebih dahulu!</p>
  <?php endif; ?>
</div>
