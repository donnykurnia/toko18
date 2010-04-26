<?php if ( $this->session->flashdata('error_message') !== FALSE OR
           ( isset($error_message) AND $error_message ) OR
           $this->session->flashdata('success_message') !== FALSE OR
           ( isset($success_message) AND $success_message ) ): ?>
<div class="ui-widget noprint">
  <?php if ( $this->session->flashdata('error_message') !== FALSE ): ?>
    <div class="ui-state-error ui-corner-all">
      <span class="ui-icon ui-icon-alert"></span>
      <p>
        <?php echo $this->session->flashdata('error_message'); ?>
      </p>
    </div>
  <?php endif; ?>
  <?php if ( isset($error_message) AND $error_message ): ?>
    <div class="ui-state-error ui-corner-all">
      <span class="ui-icon ui-icon-alert"></span>
      <p>
        <?php echo $error_message; ?>
      </p>
    </div>
  <?php endif; ?>
  <?php if ( $this->session->flashdata('success_message') !== FALSE ): ?>
    <div class="ui-state-highlight ui-corner-all">
      <span class="ui-icon ui-icon-info"></span>
      <p>
        <?php echo $this->session->flashdata('success_message'); ?>
      </p>
    </div>
  <?php endif; ?>
  <?php if ( isset($success_message) AND $success_message ): ?>
    <div class="ui-state-highlight ui-corner-all">
      <span class="ui-icon ui-icon-info"></span>
      <p>
        <?php echo $success_message; ?>
      </p>
    </div>
  <?php endif; ?>
</div>
<?php endif; ?>
