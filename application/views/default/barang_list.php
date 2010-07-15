<div id="message_area">
<?php if ( ! $this->is_ajax_request ): ?>
  <?php $this->load->view('_display_message'); ?>
<?php endif; ?>
</div>
<a href="<?php echo site_url('barang/form'); ?>" class="noprint">+ Barang Baru</a>
<?php echo form_open(current_url(), array('id' => 'delete_barang')); ?>
<?php if ( ! $barang_list ): ?>
  <p class="no_data">Belum ada data barang!</p>
<?php else: ?>
  <table class="box-table-a" id="barang_table" summary="Tabel Barang" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th scope="col" class="tright">#</th>
      <th scope="col">Kode Barang</th>
      <th scope="col">Nama Barang</th>
      <th scope="col">Spesifikasi Barang</th>
      <th scope="col">Qty</th>
      <th scope="col">Satuan Barang</th>
      <th scope="col">Input oleh</th>
      <th scope="col" class="noprint">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php $i = $start+1; ?>
  <?php foreach ( $barang_list as $row ): ?>
    <tr>
      <td class="tright"><?php echo $i++; ?></td>
      <td><?php echo $row['kode_barang']; ?></td>
      <td><?php echo $row['nama_barang']; ?></td>
      <td><?php echo nl2br($row['spesifikasi_barang']); ?></td>
      <td><?php echo $row['qty']; ?></td>
      <td><?php echo $row['satuan_barang']; ?></td>
      <td><?php echo $row['username']; ?></td>
      <td class="noprint">
        <a href="<?php echo site_url("barang/form/{$row['id']}"); ?>">Edit Barang</a>
        |<br/><a href="javascript:;" class="delete {'barang_id': <?php echo $row['id']; ?>}">Hapus Barang</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  </table>

  <?php if ( isset($paging) AND $paging ): ?>
  <div id="paging_area"><?php echo $paging; ?></div>
  <?php endif; ?>
<?php endif; ?>
<?php echo form_hidden('barang_id', 0); ?>
<?php echo form_hidden('ajax', ''); ?>
<?php echo form_close(); ?>
<script type="text/javascript">//<![CDATA[
var form = "#delete_barang";
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var message_area = "#message_area";
var content_placeholder = "#main_content";
var load_message = "Menghapus...";
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/ajax_form.js"></script>
<script type="text/javascript">//<![CDATA[
var dataTableSource = "<?php echo site_url('barang/datatable'); ?>";
var start = <?php echo number_format($start, 0); ?>;
var per_page = <?php echo number_format($per_page, 0, '', ''); ?>;
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/barang_datatable.js"></script>