<?
// $arrTemplateParams = [];
// $arrTemplateParams['action'] = '';
// $arrTemplateParams['block'] = '';
// $arrTemplateParams['item'] = '';
// $arrTemplateParams['button'] = '';
// $arrTemplateParams['sum'] = '';
// include 'core/templates/elems/content_manager_block.php';
/*
<?include 'core/templates/elems/content_manager_block.php'?>
*/

if ( ! $arrTemplateParams ) $arrTemplateParams = [];

if ( ! $arrTemplateParams['action'] )
  if ( $_REQUEST['path'] )
    $arrTemplateParams['action'] = str_replace('/','',$_REQUEST['path']);

if ( ! $arrTemplateParams['block'] )
  if ( $_REQUEST['path'] )
    $arrTemplateParams['block'] = '#' . str_replace('/','',$_REQUEST['path']);

if ( ! $arrTemplateParams['item'] )
  $arrTemplateParams['item'] = '.list-group-item';

if ( ! $arrTemplateParams['button'] )
  $arrTemplateParams['button'] = '.content_manager_switch';
?>
<div
    id="content_manager_buttons"
    class="content_manager_buttons _hide_"
    data-content_manager_action="<?=$arrTemplateParams['action']?>"
    data-content_manager_block="<?=$arrTemplateParams['block']?>"
    data-content_manager_item="<?=$arrTemplateParams['item']?>"
    data-content_manager_button="<?=$arrTemplateParams['button']?>"
    <?if($arrTemplateParams['sum']){?>
      data-content_manager_sum="<?=$arrTemplateParams['sum']?>"
    <?}?>
  >
  <?if($arrTemplateParams['sum']){?>
    <div class="content_manager_sum">0</div>
  <?}?>
  <button type="button" name="button" class="btn btn-danger del">
    <i class="fa-solid fa-circle-minus"></i>
  </button>
</div>
