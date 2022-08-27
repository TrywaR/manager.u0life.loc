<?
// $arrTemplateParams = [];
// $arrTemplateParams['title'] = '';
// $arrTemplateParams['name'] = '';
// $arrTemplateParams['value'] = '';
// $arrTemplateParams['options'] = '';
// $arrTemplateParams['search'] = '';
// $arrTemplateParams['required'] = '';
// $arrTemplateParams['class'] = '';
?>
<div class="input-group mb-2 input-group-select2 <?=$arrTemplateParams['class']?>">
  <!-- <label
  for="form_input_<?=$arrTemplateParams['name']?>"
  class="form-label"
  >
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

  <select
    class="input form-control"
    placeholder="<?=$arrTemplateParams['name']?>"
    id="form_input_<?=$arrTemplateParams['name']?>"
    name="<?=$arrTemplateParams['name']?>"

    <? if ( empty($arrTemplateParams['search']) ): ?>
      data-minimum-results-for-search="Infinity"
    <? endif; ?>

    <?if ( $arrTemplateParams['disabled'] ) echo 'disabled="disabled"'?>>
    <?php foreach ($arrTemplateParams['options'] as $arrOption): ?>
      <option data-color="<?=$arrOption['color']?>" value="<?=$arrOption['id']?>" <?if($arrTemplateParams['value'] == $arrOption['id']) {echo 'selected';}?>><?=$arrOption['name']?></option>
    <?php endforeach; ?>
  </select>

  <script>
    $(function(){
      $(document).find('#form_input_<?=$arrTemplateParams['name']?>').select2({
        selectionCssClass: ':all:',
        dropdownParent: $("#fttm_modal"),
        templateSelection: function( data ){
          if (!data.id) {
            return data.text;
          }

          if (data.element) {
            if ( $(data.element).data().color ) {
              var $state = $(
                '<span class="_color" style="background:' + $(data.element).data().color + ';"></span><span class="_text">' + data.text + '</span>'
              )
              return $state
            }
          }

          return data.text;
        },
        templateResult: function (data, container) {
          if (data.element) {
            if ( $(data.element).data().color ) {
              var $state = $(
                '<span class="_color" style="background:' + $(data.element).data().color + ';"></span><span class="_text">' + data.text + '</span>'
              )
              return $state
            }
          }
          return data.text
        }
      })
    })
  </script>
</div>
