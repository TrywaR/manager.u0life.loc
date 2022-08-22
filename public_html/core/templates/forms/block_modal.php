<?
// $arrTemplateParams = [];
// $arrTemplateParams['id'] = '';
// $arrTemplateParams['title'] = '';
// $arrTemplateParams['content'] = '';
// $arrTemplateParams['button'] = '';
// $arrTemplateParams['button_copy'] = '';
$olang = new lang();
?>

<!-- <div class="modal" tabindex="-1"> -->
  <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg modal-fullscreen-lg-down" id="block_modal">
    <form class="modal-content" id="<?=$arrTemplateParams['id']?>" method="post">
      <?php if ( $arrTemplateParams['title'] != '' ): ?>
        <div class="modal-header">
          <h5 class="modal-title">
            <?=$arrTemplateParams['title']?>
          </h5>
          <button type="button" class="btn" data-bs-dismiss="modal" aria-label="Close">
            <i class="fa-solid fa-xmark"></i>
          </button>
        </div>
      <?php endif; ?>

      <div class="modal-body">
        <div class="row">
          <? if ( $arrTemplateParams['content'] ): ?>
            <?=$arrTemplateParams['content']?>
          <?php endif; ?>

          <? if ( $arrTemplateParams['html'] ): ?>
            <?=$arrTemplateParams['html']?>
          <? endif; ?>
        </div>
      </div>

      <div class="modal-footer">

        <?php if (isset($arrTemplateParams['button_clear'])): ?>
          <button type="button" class="btn form_reset">
            <div class="_icon">
              <i class="fas fa-window-close"></i>
            </div>
            <div class="_text">
              <?=$olang->get('Clear')?>
            </div>
          </button>
        <?php endif; ?>

        <!-- <div class="form-check form-switch">
          <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked" checked>
          <label class="form-check-label" for="flexSwitchCheckChecked">Not reset</label>
        </div> -->

        <?
        if ( isset($arrTemplateParams['button_copy']) ) {
          $arrTemplateParams['title'] = $olang->get('Copy');
          // $arrTemplateParams['class'] = 'btn';
          $arrTemplateParams['name'] = 'content_loader_copy';
          $arrTemplateParams['icon'] = '<i class="fa-solid fa-clone"></i>';
          include 'core/templates/forms/checkbox.php';
        }
        ?>

        <button class="button btn btn-dark" style="margin-left: auto;" type="submit" data-bs-dismiss="modal" aria-label="Close" title="<?=$olang->get('ButtonSaveAndClose')?>">
          <div class="_icon">
            <i class="fa-solid fa-square-plus"></i>
          </div>
          <div class="_text">
            <?
            if ( isset($arrTemplateParams['button']) ) echo $arrTemplateParams['button'];
            else echo $olang->get('ButtonSaveAndCloseMin');
            ?>
          </div>
        </button>
      </div>
    </form>
  </div>
<!-- </div> -->
