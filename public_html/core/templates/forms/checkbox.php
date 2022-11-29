<?
// $arrTemplateParams = [];
// $arrTemplateParams['title'] = '';
// $arrTemplateParams['name'] = '';
// $arrTemplateParams['value'] = '';
// $arrTemplateParams['icon'] = '';
// $arrTemplateParams['disabled'] = '';
// $arrTemplateParams['required'] = '';
// $arrTemplateParams['class'] = '';
?>
<div class="input_checkbox form-check mb-2 <?=$arrTemplateParams['class']?>">
  <input
    type="checkbox"
    class="form-check-input"
    name="<?=$arrTemplateParams['name']?>"
    <?if ( $arrTemplateParams['disabled'] ) echo 'disabled="disabled"'?>
    <?if ( $arrTemplateParams['required'] ) echo 'required="required"'?>
    <?php if ((int)$arrTemplateParams['value']): ?>
      checked
      value="true"
    <?php else: ?>
      value="false"
    <?php endif; ?>
    id="checkbox_<?=$arrTemplateParams['name']?>"
  >
  <label class="form-check-label" for="checkbox_<?=$arrTemplateParams['name']?>">
    <?php if ( isset($arrTemplateParams['icon']) ): ?>
      <span class="_icon">
        <?=$arrTemplateParams['icon']?>
      </span>
    <?php endif; ?>
    <?php if ( isset($arrTemplateParams['title']) ): ?>
      <span class="_text" title="<?=$arrTemplateParams['title']?>">
        <?=$arrTemplateParams['title']?>
      </span>
    <?php endif; ?>
  </label>
</div>
