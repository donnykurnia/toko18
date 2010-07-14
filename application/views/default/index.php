<p>Selamat datang di Toko18!</p>
<?php if ( $this->session->userdata('email') === FALSE ): ?>
  <p>Silahkan <?php echo anchor('user/login', 'login'); ?> terlebih dahulu!</p>
<?php else: ?>
  Buka halaman <?php echo anchor('dashboard/index', 'dashboard'); ?>.
<?php endif; ?>
