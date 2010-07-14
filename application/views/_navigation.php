<?php $CI =& get_instance(); ?>
<?php $CI->load->model('user_model'); ?>
<?php if ( ! $CI->user_model->check_login() ): ?>
  <a class="right noprint" href="<?php echo site_url('user/login'); ?>">Login</a>
  <hr class="clear" />
  <br class="clear" /><br/>
<?php else: ?>
  <a class="right noprint" href="<?php echo site_url('user/logout'); ?>">Logout</a>
  <hr class="clear" />
  <div class="info left w500">
    Selamat datang, <?php echo $CI->user_model->session_username(); ?>.
  </div>
  <br class="clear" /><br/>
  <div id="tabs" class="noprint">
    <ul>
      <li<?php if ( isset($in_dashboard) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('dashboard/index'); ?>"><span>Dashboard</span></a>
      </li>
      <li<?php if ( isset($in_barang) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('barang/index'); ?>"><span>Barang</span></a>
      </li>
      <li<?php if ( isset($in_pembelian) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('pembelian/index'); ?>"><span>Pembelian</span></a>
      </li>
      <li<?php if ( isset($in_penjualan) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('penjualan/index'); ?>"><span>Penjualan</span></a>
      </li>
      <li<?php if ( isset($in_report) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('report/index'); ?>"><span>Report</span></a>
      </li>
    </ul>
  </div>
  <br class="clear" />
<?php endif; ?>
