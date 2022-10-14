<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('NoticesViews')?>
    </h1>

    <div class="_buttons btn-group">
      <!-- <a class="btn" target="_blank" href="/info/docs/moneys/">
        <i class="fa-solid fa-circle-info"></i>
      </a> -->
      <?include 'core/templates/elems/filter_button.php'?>
    </div>
  </div>

  <div class="_block_content" id="shower">
    <!-- Фильтр -->
    <form class="content_filter __no_ajax" action="" id="content_filter" data-content_filter_block="#notices_views" data-content_filter_status="#content_filter_show">
      <div class="input-group _filter_block">
        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="fa-solid fa-user"></i>
            </span>
          </span>
          <input type="number" name="user_id" class="form-control" placeholder="<?=$olang->get('User')?>" value="">
        </div>

        <div class="block_buttons __end">
          <button class="btn btn-dark" type="submit">
            Go
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<div class="main_content">
  <?
  $arrTemplateParams = [];
  $arrTemplateParams['action'] = 'notices_views';
  $arrTemplateParams['block'] = 'notices_views';
  include 'core/templates/elems/content_manager_block.php';
  ?>

  <ul
    id="notices_views"
    class="block_notices_views block_elems list-group block_content_loader"
    data-content_loader_table="notices_views"
    data-content_loader_form="show"
    data-content_loader_limit="15"
    data-content_loader_scroll_nav="0"
    data-content_loader_template_selector=".block_template"
  ></ul>

  <script>
    $(function(){
      // $(document).find('#notices_views').content_loader()
      $(document).find('#content_filter').content_filter()
    })
  </script>
</div>

<div class="block_template">
  <div class="list-group-item _elem" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
    <div class="row">
      <div class="col-6">
        {{user_id}}
      </div>
      <div class="col-6">
        {{notice_id}}
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

          <a data-action="notices_views" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem="._elem" data-form="form" href="javascript:;" class="btn btn-dark content_loader_show" title="Edit">
            <i class="fas fa-pen-square"></i>
          </a>

          <a href="#" class="btn btn-dark content_download" data-id="{{id}}" data-action="notices_views" data-form="del" data-elem="._elem" data-animate_class="animate__fadeOutRightBig"><i class="fas fa-minus-square"></i></a>
        </span>
      </div>
    </div>
  </div>
</div>
