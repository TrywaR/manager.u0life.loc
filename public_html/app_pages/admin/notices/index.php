<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Notices')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <?
  $arrTemplateParams = [];
  $arrTemplateParams['action'] = 'notices';
  $arrTemplateParams['block'] = 'notices';
  include 'core/templates/elems/content_manager_block.php';
  ?>

  <ul
    id="notices"
    class="block_notices block_elems list-group block_content_loader"
    data-content_loader_table="notices"
    data-content_loader_form="show"
    data-content_loader_limit="15"
    data-content_loader_scroll_nav="0"
    data-content_loader_template_selector=".block_template"
  ></ul>
  <script>
    $(function(){
      $(document).find('#notices').content_loader()
    })
  </script>
</div>

<div class="block_template">
  <div class="list-group-item _elem reward" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
    <div class="row">
      <div class="col-2 d-flex justify-content-center align-items-center">
        <div class="_icon">
          {{icon}}
        </div>
      </div>

      <div class="col-10">
        <div class="row">
          <div class="col-12 d-flex pb-2">
            <div class="_title pe-2">
              <small>Title</small>
              {{title}}
            </div>
          </div>
          <div class="col-12">
            <div class="_description" style="opacity:.5">
              {{content}}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12 d-flex justify-content-end">
        <span class="btn-group">
          <a href="#" class="btn btn-dark content_manager_switch switch_icons">
            <div class="">
              <i class="far fa-square"></i>
            </div>
            <div class="">
              <i class="fas fa-square"></i>
            </div>
          </a>

          <a data-action="notices" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem=".reward" data-form="form" href="javascript:;" class="btn btn-dark content_loader_show" title="Edit">
            <i class="fas fa-pen-square"></i>
          </a>
        </span>
      </div>
    </div>
  </div>
</div>
