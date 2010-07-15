<div id="message_area">
<?php if ( ! $this->is_ajax_request ): ?>
  <?php $this->load->view('_display_message'); ?>
<?php endif; ?>
</div>

<?php echo form_open(current_url(), array('id' => 'barang_form', 'class' => 'form')); ?>
<fieldset>
  <legend>
    <?php echo ucwords($page_title); ?>
  </legend>
  <div class="grid-12-12">
    <label for="kode_barang" class="form-lbl">Kode Barang <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="20"
           id="kode_barang" name="kode_barang" value="<?php echo set_value('kode_barang', ($barang_detail AND isset($barang_detail['kode_barang'])) ? $barang_detail['kode_barang'] : ''); ?>" />
  </div>
  <div class="grid-12-12">
    <label for="nama_barang" class="form-lbl">Nama Barang <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="50"
           id="nama_barang" name="nama_barang" value="<?php echo set_value('nama_barang', ($barang_detail AND isset($barang_detail['nama_barang'])) ? $barang_detail['nama_barang'] : ''); ?>" />
  </div>
  <div class="grid-12-12">
    <label for="spesifikasi_barang" class="form-lbl">Spesifikasi Barang</label>
    <textarea rows="" cols="" class="form-txt"
              id="spesifikasi_barang" name="spesifikasi_barang"
              ><?php echo set_value('spesifikasi_barang', ($barang_detail AND isset($barang_detail['spesifikasi_barang'])) ? $barang_detail['spesifikasi_barang'] : ''); ?></textarea>
  </div>
  <div class="grid-12-12">
    <label for="satuan_barang" class="form-lbl">Satuan Barang <em class="form-req">*</em></label>
    <input type="text" class="form-txt" maxlength="20"
           id="satuan_barang" name="satuan_barang" value="<?php echo set_value('satuan_barang', ($barang_detail AND isset($barang_detail['satuan_barang'])) ? $barang_detail['satuan_barang'] : ''); ?>" />
  </div>
  <div class="grid-12-12 field-clear field-button">
    <input class="form-button form-right" type="submit" title="Submit" value="Simpan" />
  </div>
</fieldset>
<?php echo form_hidden('ajax', ''); ?>
<?php echo form_close(); ?>
<script type="text/javascript">//<![CDATA[
var form = "#barang_form";
var blockui_image = "<?php echo base_url(); ?>assets/images/loading-dark.gif";
var message_area = "#message_area";
var content_placeholder = "#main_content";
var load_message = "Menyimpan data...";
//]]></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/apps/ajax_form.js"></script>
