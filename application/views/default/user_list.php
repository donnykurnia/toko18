<?php echo form_open(site_url("user/index"), array('id' => 'filter_user_role', 'method' => 'get')); ?>
<div class="w500">
  <span class="left w100">Filter</span><span class="left">:
  <?php echo form_dropdown('user_role', $user_role_options, $user_role, 'id="user_role"'); ?></span>
  <br class="clear"/>
</div>
<?php echo form_close(); ?>

<div id="message_area">
<?php if ( ! $this->is_ajax_request ): ?>
  <?php $this->load->view('_display_message'); ?>
<?php endif; ?>
</div>
<div id="NewUser">
  <div class="rnd_box">
    <div class="rnd_top"><div></div></div>
      <div class="rnd_content">
        <a href="<?php echo site_url('user/form'); ?>" class="noprint">+ New User</a>
      </div>
    <div class="rnd_bottom"><div></div></div>
  </div>
  <br/>
</div>
<?php echo form_open(current_url(), array('id' => 'delete_user')); ?>
<?php if ( ! $user_list ): ?>
  <p class="no_data">No User yet!</p>
<?php else: ?>
  <table class="box-table-a" id="user_table" summary="User list" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th scope="col" class="tright">#</th>
      <th scope="col">Username</th>
      <th scope="col">Email</th>
      <th scope="col">Full Name</th>
      <th scope="col">Role</th>
      <th scope="col">Last login</th>
      <th scope="col" class="noprint">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php $i = $start+1; ?>
  <?php foreach ( $user_list as $row ): ?>
    <tr>
      <td class="tright"><?php echo $i++; ?></td>
      <td><?php echo $row['username']; ?></td>
      <td><?php echo $row['user_email']; ?></td>
      <td><?php echo $row['user_fullname']; ?></td>
      <td><?php echo ucwords(str_replace('_', ' ', $row['user_role'])); ?></td>
      <td>
      <?php if ( $row['last_login_datetime'] ): ?>
        <?php echo date('d M Y H:i:s', mysql_to_unix($row['last_login_datetime'])); ?> from
        <?php echo inet_ntoa($row['last_login_ip']); ?>
      <?php else: ?>
        n/a
      <?php endif; ?>
      </td>
      <td class="noprint">
        <a href="<?php echo site_url("user/form/{$row['id']}"); ?>">Edit User</a>
        <?php if ( $this->session->userdata('user_id') != $row['id'] ): ?>
        | <a href="javascript:;" class="delete {'user_id': <?php echo $row['id']; ?>}">Delete User</a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  </table>
  <a href="<?php echo site_url("user/csv"); ?>">Download CSV</a>

  <div class="w500">
    <span class="left w100">Total Admin</span>
    <span class="left">: </span>
    <span class="left w100 tright"><?php echo number_format($total_administrator, 0); ?></span><br class="clear"/>
    <span class="left w100">Total User</span>
    <span class="left">: </span>
    <span class="left w100 tright"><?php echo number_format($total_user, 0); ?></span><br class="clear"/>
  </div>

  <?php if ( isset($paging) AND $paging ): ?>
  <div id="paging_area"><?php echo $paging; ?></div>
  <?php endif; ?>
<?php endif; ?>
<?php echo form_hidden('user_id', 0); ?>
<?php echo form_hidden('ajax', ''); ?>
<?php echo form_close(); ?>
<script type="text/javascript">//<![CDATA[
var form = "#delete_user";
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var message_area = "#message_area";
var content_placeholder = "#main_content";
var load_message = "Deleting...";
var filter_form = "#filter_user_role";
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/ajax_form.js"></script>
<script type="text/javascript">//<![CDATA[
var dataTableSource = "<?php echo site_url('user/datatable'); ?>";
var start = <?php echo number_format($start, 0); ?>;
var per_page = <?php echo number_format($per_page, 0); ?>;
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/user_datatable.js"></script>
