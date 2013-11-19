<?php

/*****

modal_id
hide_close_button
header_title
modal_body_html
btn_primary_text
footer_html
*****/
?>
<div  id="<?php if(isset($modal_id)) echo $modal_id; ?>" class="modal fade">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <?php if(isset($hide_close_button) && $hide_close_button ): ?>
        <?php else: ?>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <?php endif ?>
        <?php if(isset($header_title)): ?>
          <h4 class="modal-title"><?php echo $header_title ?></h4>
        <?php endif ?>
      </div>
      <div class="modal-body">
       <?php if(isset($modal_body_html)) echo $modal_body_html; ?>
      </div>
      
      <div class="modal-footer">
        <?php if(isset($footer_html)):?>
          <div class="row" style="text-align: left;">
          <?php echo $footer_html ?>

          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <?php if(isset($btn_primary_text)): ?>
          <button type="button" class="btn btn-primary"><?php echo $btn_primary_text ?></button>
          <?php endif ?>
        <?php else: ?>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <?php if(isset($btn_primary_text)): ?>
          <button type="button" class="btn btn-primary"><?php echo $btn_primary_text ?></button>
          <?php endif ?>
        <?php endif ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

