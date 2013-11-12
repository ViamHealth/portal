<?php


echo $modal_id;
/*****

modal_id
aria_labelledby
hide_close_button
header_title
modal_body_html
btn_primary_text
*****/
?>
<div id="<?php if(isset($modal_id)) echo $modal_id; ?>" class="modal hide fade" tabindex="-1" role="dialog" 
  aria-labelledby="<?php if(isset($aria_labelledby)) echo $aria_labelledby; ?>" aria-hidden="true">

  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <?php if(isset($hide_close_button) && $hide_close_button ): ?>
        <?php else: ?>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <?php endif ?>
        <?php if(isset($header_title)) echo $header_title; ?>
      </div>
      <div class="modal-body" itemid="">
        <?php if(isset($modal_body_html)) echo $modal_body_html; ?>
      </div><!-- /.modal-body -->
      <div class="modal-footer">
        <?php if(isset($footer_html)):?>
          <div class="row-fluid" style="text-align: left;">
            <?php echo $footer_html ?>
            
         

            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <?php if(isset($btn_primary_text)): ?>
            <button type="button"  id="save-cholesterol-reading" class="btn btn-primary"><?php echo $btn_primary_text ?></button>
            <?php endif ?>
          </div>
        <?php endif ?>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->