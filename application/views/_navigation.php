<?php if ( $this->session->userdata('email') === FALSE ): ?>
  <a class="right noprint" href="<?php echo site_url('user/login'); ?>">Login</a>
  <hr class="clear" />
  <br class="clear" /><br/>
<?php else: ?>
  <a class="right noprint" href="<?php echo site_url('user/logout'); ?>">Logout</a>
  <hr class="clear" />
  <div class="info left w500">
    Welcome back, <?php echo $this->session->userdata('email'); ?>
  </div>
  <br class="clear" /><br/>
  <div id="tabs" class="noprint">
    <ul>
      <li<?php if ( isset($in_dashboard) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('dashboard/index'); ?>"><span>Dashboard</span></a>
      </li>
    <?php if ( ! $this->user_model->check_is_admin() ): ?>
      <li<?php if ( isset($in_reseller) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('reseller/index'); ?>"><span>Reseller</span></a>
      </li>
      <li<?php if ( isset($in_user) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('user/form'); ?>"><span>Profile</span></a>
      </li>
      <li<?php if ( isset($in_links) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('links/index'); ?>"><span>Links</span></a>
      </li>
    <?php else: ?>
      <li<?php if ( isset($in_country) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('country/index'); ?>"><span>Country</span></a>
      </li>
      <li<?php if ( isset($in_product) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('product/index'); ?>"><span>Product</span></a>
      </li>
      <li<?php if ( isset($in_reseller_type) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('reseller/type_list'); ?>"><span>Reseller Type</span></a>
      </li>
      <li<?php if ( isset($in_reseller) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('reseller/index'); ?>"><span>Reseller</span></a>
      </li>
      <li<?php if ( isset($in_user) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('user/index'); ?>"><span>User</span></a>
      </li>
      <li<?php if ( isset($in_links) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('links/index'); ?>"><span>Links</span></a>
      </li>
      <li<?php if ( isset($in_log) ): ?> class="on"<?php endif; ?>>
        <a href="<?php echo site_url('links/log'); ?>"><span>Links Log</span></a>
      </li>
    <?php endif; ?>
    </ul>
  </div>
  <br class="clear" />
<?php endif; ?>
