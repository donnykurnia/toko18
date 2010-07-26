<div id="message_area">
<?php if ( ! $this->is_ajax_request ): ?>
  <?php $this->load->view('_display_message'); ?>
<?php endif; ?>
</div>
<?php echo form_open(current_url(), array('id' => 'transaksi_form', 'class' => 'form')); ?>
<fieldset>
  <legend>
    <?php echo ucwords($page_title); ?>
  </legend>
  <table class="box-table-a" id="pembelian_table" summary="Tabel Pembelian Barang" cellspacing="0" cellpadding="0">
    <thead>
      <tr>
        <th scope="col" class="tright">#</th>
        <th scope="col">Tanggal Transaksi</th>
        <th scope="col">Barang</th>
        <th scope="col">Qty</th>
        <th scope="col">Harga Satuan (Rp)</th>
        <th scope="col">Diskon (Rp)</th>
        <th scope="col">Keterangan Transaksi</th>
      </tr>
    </thead>
    <tbody>
    <?php for ( $i = 1; $i <= 10; $i++ ): ?>
      <tr>
        <td class="tright"><?php echo $i; ?></td>
        <td>
          <input type="text" class="form-txt" maxlength="10"
                 id="tanggal_transaksi_<?php echo $i; ?>" name="tanggal_transaksi[<?php echo $i; ?>]" value="<?php echo set_value("tanggal_transaksi[{$i}]", date('Y-m-d')); ?>" />
        </td>
        <td>
          <?php echo form_dropdown("barang_id[{$i}]", $barang_options, array(), 'id="barang_id_'.$i.'" class="form-txt"'); ?>
        </td>
        <td>
          <input type="text" class="form-txt" maxlength="11"
                 id="qty_<?php echo $i; ?>" name="qty[<?php echo $i; ?>]" value="<?php echo set_value("qty[{$i}]", ''); ?>" />
        </td>
        <td>
          <input type="text" class="form-txt" maxlength="16"
                 id="harga_satuan_<?php echo $i; ?>" name="harga_satuan[<?php echo $i; ?>]" value="<?php echo set_value("harga_satuan[{$i}]", ''); ?>" />
        </td>
        <td>
          <input type="text" class="form-txt" maxlength="16"
                 id="diskon_<?php echo $i; ?>" name="diskon[<?php echo $i; ?>]" value="<?php echo set_value("diskon[{$i}]", '0'); ?>" />
        </td>
        <td>
          <textarea rows="" cols="" class="form-txt"
                    id="keterangan_transaksi_<?php echo $i; ?>" name="keterangan_transaksi[<?php echo $i; ?>]"><?php echo set_value("keterangan_transaksi[{$i}]", ''); ?></textarea>
        </td>
      </tr>
    <?php endfor; ?>
    </tbody>
  </table>
  <div class="grid-12-12 field-clear field-button">
    <input class="form-button form-right" type="submit" title="Submit" value="Simpan" />
  </div>
</fieldset>
<?php echo form_hidden('field_row', '10'); ?>
<?php echo form_hidden('ajax', ''); ?>
<?php echo form_close(); ?>
<script type="text/javascript">//<![CDATA[
var form = "#transaksi_form";
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var message_area = "#message_area";
var content_placeholder = "#main_content";
var load_message = "Menyimpan data...";
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/ajax_form.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui-1.7.2.custom.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/pembelian_form_multi.js"></script>
