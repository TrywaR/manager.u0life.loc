<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Rewards')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <?
  $arrTemplateParams = [];
  $arrTemplateParams['action'] = 'rewards';
  $arrTemplateParams['block'] = 'rewards';
  $arrTemplateParams['sum'] = '._days ._val';
  include 'core/templates/elems/content_manager_block.php';
  ?>

  <ul
    id="rewards"
    class="block_rewards block_elems list-group block_content_loader"
    data-content_loader_table="rewards"
    data-content_loader_form="show"
    data-content_loader_limit="15"
    data-content_loader_scroll_nav="0"
    data-content_loader_template_selector=".block_template"
  ></ul>
  <script>
    $(function(){
      $(document).find('#rewards').content_loader()
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
            <div class="_level pe-2">
              <small>Level</small>
              {{level}}
            </div>
            <div class="_days">
              <small>Days free</small>
              <span class="_val">{{days}}</span>
            </div>
          </div>
          <div class="col-12">
            <div class="_description" style="opacity:.5">
              {{description}}
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

          <a data-action="rewards" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem=".reward" data-form="form" href="javascript:;" class="btn btn-dark content_loader_show" title="Reward edit">
            <i class="fas fa-pen-square"></i>
          </a>
        </span>
      </div>
    </div>
  </div>
</div>
