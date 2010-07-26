<?php if ( ! $this->is_ajax_request ): ?>
<div id="message_area">
  <?php $this->load->view('_display_message'); ?>
</div>
<?php echo form_open(current_url(), array('method' => 'get', 'id' => 'filter_form', 'class' => 'form w300')); ?>
<fieldset>
  <legend>Filter</legend>
  <div class="grid-12-12">
    <label for="start_date" class="form-lbl">Dari Tanggal <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="10"
           id="start_date" name="start_date" value="<?php echo set_value('start_date', ''); ?>" />
  </div>
  <div class="grid-12-12">
    <label for="end_date" class="form-lbl">Sampai Tanggal <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="10"
           id="end_date" name="end_date" value="<?php echo set_value('end_date', ''); ?>" />
  </div>
  <div class="grid-12-12">
    <label for="barang_id" class="form-lbl">Barang <em class="form-req">*</em></label>
    <?php echo form_dropdown('barang_id', $barang_options, array(), 'id="barang_id"'); ?>
  </div>
  <div class="grid-12-12">
    <label for="jenis_transaksi" class="form-lbl">Jenis Transaksi <em class="form-req">*</em></label>
    <?php echo form_dropdown('jenis_transaksi', $jenis_transaksi_options, array(), 'id="jenis_transaksi"'); ?>
  </div>
  <div class="grid-12-12 field-clear field-button">
    <input class="form-button form-right" type="submit" title="Filter" value="Filter" />
  </div>
</fieldset>
<?php echo form_hidden('ajax', ''); ?>
<?php echo form_close(); ?>
<div id="report_area">
<?php endif; ?>
<?php if ( ! $report_list ): ?>
  <p class="no_data"><?php $filter ? 'Data transaksi tidak ditemukan!' : ''; ?></p>
<?php else: ?>
  <table class="box-table-a" id="report_table" summary="Tabel Report" cellspacing="0" cellpadding="0">
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
    </tr>
  </thead>
  <tbody>
  <?php $page_total = 0; ?>
  <?php $i = $start+1; ?>
  <?php foreach ( $report_list as $row ): ?>
    <tr>
      <td class="tright"><?php echo $i++; ?></td>
      <td><?php echo date('d M Y', mysql_to_unix($row['tanggal_transaksi'])); ?></td>
      <td>[<?php echo $row['kode_barang']; ?>] <?php echo $row['nama_barang']; ?></td>
      <td class="tright"><?php echo $row['qty']; ?></td>
      <td class="tright">Rp. <?php echo number_format($row['harga_satuan'], 2); ?></td>
      <td class="tright">Rp. <?php echo number_format($row['diskon'], 2); ?></td>
      <td class="tright">
        Rp. <?php echo number_format(($row['harga_total']), 2); ?>
        <?php $page_total += $row['harga_total']; ?>
      </td>
      <td><?php echo $row['username']; ?></td>
    </tr>
  <?php endforeach; ?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="6" class="tright">Total di halaman ini</td>
      <td class="tright">Rp. <?php echo number_format(($page_total), 2); ?></td>
      <td>&nbsp;</td>
    </tr>
    <tr>
      <td colspan="6" class="tright">Total seluruh halaman</td>
      <td class="tright">Rp. <?php echo number_format(($report_sum_total), 2); ?></td>
      <td>&nbsp;</td>
    </tr>
  </tfoot>
  </table>

  <?php if ( isset($paging) AND $paging ): ?>
  <div id="paging_area"><?php echo $paging; ?></div>
  <?php endif; ?>
<?php endif; ?>
<script type="text/javascript">//<![CDATA[
//]]></script>
<?php if ( ! $this->is_ajax_request ): ?>
</div>
<script type="text/javascript">//<![CDATA[
var form = "#filter_form";
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var message_area = "#message_area";
var content_placeholder = "#report_area";
var load_message = "Loading data...";
var dataTableSource = "<?php echo site_url('report/datatable'); ?>";
var start = <?php echo number_format($start, 0); ?>;
var per_page = <?php echo number_format($per_page, 0, '', ''); ?>;
//]]></script>
<?php endif; ?>