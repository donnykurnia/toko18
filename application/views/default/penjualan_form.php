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
  <div class="grid-12-12">
    <label for="barang_id" class="form-lbl">Barang <em class="form-req">*</em></label>
    <?php echo form_dropdown('barang_id', $barang_options, ($penjualan_detail AND isset($penjualan_detail['barang_id'])) ? $penjualan_detail['barang_id'] : array(), 'id="barang_id" class="form-small"'); ?>
  </div>
  <div class="grid-12-12">
    <label for="qty" class="form-lbl">Qty <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="11"
           id="qty" name="qty" value="<?php echo set_value('qty', ($penjualan_detail AND isset($penjualan_detail['qty'])) ? $penjualan_detail['qty'] : ''); ?>" />
  </div>
  <div class="grid-12-12">
    <label for="harga_satuan" class="form-lbl">Harga Satuan <em class="form-req">*</em></label>
    <span class="fleft field_lbl">Rp</span>
    <input type="text" class="form-txt" maxlength="16"
           id="harga_satuan" name="harga_satuan" value="<?php echo set_value('harga_satuan', ($penjualan_detail AND isset($penjualan_detail['harga_satuan'])) ? $penjualan_detail['harga_satuan'] : ''); ?>" />
  </div>
  <div class="grid-12-12">
    <label for="diskon" class="form-lbl">Diskon</label>
    <span class="fleft field_lbl">Rp</span>
    <input type="text" class="form-txt" maxlength="18"
           id="diskon" name="diskon" value="<?php echo set_value('diskon', ($penjualan_detail AND isset($penjualan_detail['diskon'])) ? $penjualan_detail['diskon'] : ''); ?>" />
  </div>
  <div class="grid-12-12">
    <label for="keterangan_transaksi" class="form-lbl">Keterangan Transaksi</label>
    <textarea rows="" cols="" class="form-txt"
              id="keterangan_transaksi" name="keterangan_transaksi"
              ><?php echo set_value('keterangan_transaksi', ($penjualan_detail AND isset($penjualan_detail['keterangan_transaksi'])) ? $penjualan_detail['keterangan_transaksi'] : ''); ?></textarea>
  </div>
  <div class="grid-12-12 field-clear field-button">
    <input class="form-button form-right" type="submit" title="Submit" value="Simpan" />
  </div>
</fieldset>
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
