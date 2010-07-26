<div id="message_area">
<?php if ( ! $this->is_ajax_request ): ?>
  <?php $this->load->view('_display_message'); ?>
<?php endif; ?>
</div>
<a href="<?php echo site_url('pembelian/form'); ?>" class="noprint">+ Pembelian Baru</a><br/>
<a href="<?php echo site_url('pembelian/form_multi'); ?>" class="noprint">+ Pembelian Baru Multiple</a>
<?php echo form_open(current_url(), array('id' => 'delete_pembelian')); ?>
<?php if ( ! $pembelian_list ): ?>
  <p class="no_data">Belum ada data pembelian barang!</p>
<?php else: ?>
  <table class="box-table-a" id="pembelian_table" summary="Tabel Pembelian Barang" cellspacing="0" cellpadding="0">
  <thead>
    <tr>
      <th scope="col" class="tright">#</th>
      <th scope="col">Tanggal Transaksi</th>
      <th scope="col">Barang</th>
      <th scope="col">Qty</th>
      <th scope="col">Harga Satuan</th>
      <th scope="col">Diskon</th>
      <th scope="col">Harga Total</th>
      <th scope="col">Input oleh</th>
      <th scope="col" class="noprint">Action</th>
    </tr>
  </thead>
  <tbody>
  <?php $i = $start+1; ?>
  <?php foreach ( $pembelian_list as $row ): ?>
    <tr>
      <td class="tright"><?php echo $i++; ?></td>
      <td><?php echo date('d M Y', mysql_to_unix($row['tanggal_transaksi'])); ?></td>
      <td>[<?php echo $row['kode_barang']; ?>] <?php echo $row['nama_barang']; ?></td>
      <td><?php echo $row['qty']; ?></td>
      <td>Rp. <?php echo number_format($row['harga_satuan'], 2); ?></td>
      <td>Rp. <?php echo number_format($row['diskon'], 2); ?></td>
      <td>Rp. <?php echo number_format(($row['harga_total']), 2); ?></td>
      <td><?php echo $row['username']; ?></td>
      <td class="noprint">
        <a href="<?php echo site_url("pembelian/form/{$row['id']}"); ?>">Edit Pembelian</a>
        |<br/><a href="javascript:;" class="delete {'pembelian_id': <?php echo $row['id']; ?>}">Hapus Pembelian</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  </table>

  <?php if ( isset($paging) AND $paging ): ?>
  <div id="paging_area"><?php echo $paging; ?></div>
  <?php endif; ?>
<?php endif; ?>
<?php echo form_hidden('pembelian_id', 0); ?>
<?php echo form_hidden('ajax', ''); ?>
<?php echo form_close(); ?>
<script type="text/javascript">//<![CDATA[
var form = "#delete_pembelian";
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var message_area = "#message_area";
var content_placeholder = "#main_content";
var load_message = "Menghapus...";
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/ajax_form.js"></script>
<script type="text/javascript">//<![CDATA[
var dataTableSource = "<?php echo site_url('pembelian/datatable'); ?>";
var start = <?php echo number_format($start, 0); ?>;
var per_page = <?php echo number_format($per_page, 0, '', ''); ?>;
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/pembelian_datatable.js"></script>