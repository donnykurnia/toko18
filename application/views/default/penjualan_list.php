<div id="message_area">
<?php if ( ! $this->is_ajax_request ): ?>
  <?php $this->load->view('_display_message'); ?>
<?php endif; ?>
</div>
<a href="<?php echo site_url('penjualan/form'); ?>" class="noprint">+ Penjualan Baru</a>
<?php echo form_open(current_url(), array('id' => 'delete_penjualan')); ?>
<?php if ( ! $penjualan_list ): ?>
  <p class="no_data">Belum ada data penjualan barang!</p>
<?php else: ?>
  <table class="box-table-a" id="penjualan_table" summary="Tabel Penjualan Barang" cellspacing="0" cellpadding="0">
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
  <?php foreach ( $penjualan_list as $row ): ?>
    <tr>
      <td class="tright"><?php echo $i++; ?></td>
      <td><?php echo date('d M Y H:i', mysql_to_unix($row['tanggal_transaksi'])); ?></td>
      <td>[<?php echo $row['kode_barang']; ?>] <?php echo $row['nama_barang']; ?></td>
      <td><?php echo $row['qty']; ?></td>
      <td>Rp. <?php echo number_format($row['harga_satuan'], 2); ?></td>
      <td>Rp. <?php echo number_format($row['diskon'], 2); ?></td>
      <td>Rp. <?php echo number_format(($row['harga_total']), 2); ?></td>
      <td><?php echo $row['username']; ?></td>
      <td class="noprint">
        <a href="<?php echo site_url("penjualan/form/{$row['id']}"); ?>">Edit Penjualan</a>
        |<br/><a href="javascript:;" class="delete {'penjualan_id': <?php echo $row['id']; ?>}">Hapus Penjualan</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  </table>

  <?php if ( isset($paging) AND $paging ): ?>
  <div id="paging_area"><?php echo $paging; ?></div>
  <?php endif; ?>
<?php endif; ?>
<?php echo form_hidden('penjualan_id', 0); ?>
<?php echo form_hidden('ajax', ''); ?>
<?php echo form_close(); ?>
<script type="text/javascript">//<![CDATA[
var form = "#delete_penjualan";
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var message_area = "#message_area";
var content_placeholder = "#main_content";
var load_message = "Menghapus...";
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/ajax_form.js"></script>
<script type="text/javascript">//<![CDATA[
var dataTableSource = "<?php echo site_url('penjualan/datatable'); ?>";
var start = <?php echo number_format($start, 0); ?>;
var per_page = <?php echo number_format($per_page, 0, '', ''); ?>;
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/penjualan_datatable.js"></script>