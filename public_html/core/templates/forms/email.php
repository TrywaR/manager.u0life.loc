<?
// $arrTemplateParams = [];
// $arrTemplateParams['title'] = '';
// $arrTemplateParams['name'] = '';
// $arrTemplateParams['plaseholder'] = '';
// $arrTemplateParams['value'] = '';
// $arrTemplateParams['required'] = '';
// $arrTemplateParams['class'] = '';
?>
<div class="input-group mb-2 <?=$arrTemplateParams['class']?>">
  <!-- <label
    for="form_input_<?=$arrTemplateParams['name']?>"
   class="form-label">
    <?=$arrTemplateParams['title']?>
  </label> -->

  <span class="input-group-text" >
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
  </span>

  <input
    type="email"
    class="input form-control"
    id="form_input_<?=$arrTemplateParams['name']?>"
    name="<?=$arrTemplateParams['name']?>"
    placeholder="<?=$arrTemplateParams['plaseholder']?$arrTemplateParams['plaseholder']:$arrTemplateParams['name']?>"
    value="<?=$arrTemplateParams['value']?>"
    <?if ( $arrTemplateParams['disabled'] ) echo 'disabled="disabled"'?>
    <?if ( $arrTemplateParams['required'] ) echo 'required="required"'?>
  >
</div>
