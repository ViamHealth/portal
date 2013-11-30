<?php

/*****

modal_id
hide_close_button
header_title
modal_body_html
btn_primary_text
footer_html
disallow_close

make this a class now!!
*****/
?>
<div  id="<?php if(isset($modal_id)) echo $modal_id; ?>" class="modal fade" role="dialog" aria-labelledby="modaltitle">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <?php if(isset($hide_close_button) && $hide_close_button ): ?>
          
        <?php elseif(isset($disallow_close) && $disallow_close): ?>
        <?php else: ?>
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <?php endif ?>
        <?php if(isset($header_title)): ?>
          <h4 id="modaltitle" class="modal-title"><?php echo $header_title ?></h4>
        <?php endif ?>
      </div>
      <div class="modal-body">
       <?php if(isset($modal_body_html)) echo $modal_body_html; ?>
      </div>
      <?php if(isset($hide_footer) && $hide_footer): ?>
      <?php else: ?>
      <div class="modal-footer">
        <?php if(isset($footer_html)):?>
          <div class="row" style="text-align: left;">
          <?php echo $footer_html ?>
          <?php if(isset($disallow_close) && !$disallow_close): ?>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <?php endif ?>
          <?php if(isset($btn_primary_text)): ?>
          <button type="button" class="btn btn-primary btn-save"><?php echo $btn_primary_text ?></button>
          <?php endif ?>
          </div>
        <?php else: ?>
          <?php if(isset($disallow_close) && !$disallow_close): ?>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <?php endif ?>
          <?php if(isset($btn_primary_text)): ?>
          <button type="button" class="btn btn-primary btn-save"><?php echo $btn_primary_text ?></button>
          <?php endif ?>
        <?php endif ?>
      </div>
      <?php endif ?>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

