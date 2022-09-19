<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Subscriptions')?>
    </h1>

    <div class="_buttons btn-group">
      <a class="btn" target="_blank" href="/info/docs/subscriptions/">
        <i class="fa-solid fa-circle-info"></i>
      </a>
      <?include 'core/templates/elems/filter_button.php'; # Кнопка фильтрации?>
    </div>
  </div>

  <div class="_block_content" id="shower">
    <!-- Фильтр -->
    <form class="content_filter __no_ajax" action="" id="content_filter" data-content_filter_block="#subscriptions" data-content_filter_status="#content_filter_show">
      <div class="input-group _filter_block">
        <div class="_filter_input">
          <div class="input_checkbox form-check">
            <input
              type="checkbox"
              class="form-check-input"
              name="no_active_show"
              id="checkbox_noactiveshow"
            >
            <label class="form-check-label" for="checkbox_noactiveshow">
              <?=$oLang->get('ShowNoActive')?>
            </label>
          </div>
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
  $arrTemplateParams['sum'] = '._price';
  include 'core/templates/elems/content_manager_block.php';
  ?>

  <ol
    id="subscriptions"
    class="block_subscriptions block_elems block_content_loader list-group"
    data-content_loader_table="subscriptions"
    data-content_loader_form="show"
    data-content_loader_limit="15"
    data-content_loader_scroll_nav="0"
    <?php if ($_REQUEST['sort']): ?>
      data-content_loader_sort="<?=$_REQUEST['sort']?>"
      data-content_loader_sortdir="<?=$_REQUEST['sortdir']?>"
    <?php endif; ?>
    <?php if ($_REQUEST['filter']): ?>
      data-content_loader_parents="<?=$_REQUEST['filter_value']?>"
    <?php endif; ?>
    data-content_loader_template_selector=".block_template"
    data-content_loader_scroll_block="#subscriptions"
    data-content_loader_show_class="_show_"
    >
  </ol>
  <script>
    $(function(){
      // $(document).find('#subscriptions').content_loader()
      $(document).find('#content_filter').content_filter()
      // $(document).find('#content_manager_buttons').content_manager()
      // $(document).find('#footer_actions').content_actions( {'action':'subscriptions'} )
    })
  </script>
</div>

<div class="block_template">
    <div class="list-group-item _elem d-flex subscription progress_block _card_show_{{card_show}} _paid_show_{{paid_show}} _paid_need_show_{{paid_need_show}} _active_show_{{active_show}}" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
      <span class="d-flex w-100">
        <span class="col-12 col-md-6 mb-2">
          <span class="_head">
            <span class="_price">
              {{price}}
            </span>

            <span class="_title">
              {{title}}
            </span>
          </span>
        </span>

        <span class="col-12 col-md-6 d-flex justify-content-end align-items-center">
          <span class="btn-group">
            <a href="#" class="btn btn-dark content_manager_switch switch_icons">
              <span class="">
                <i class="far fa-square"></i>
              </span>

              <span class="">
                <i class="fas fa-square"></i>
              </span>
            </a>

            <a data-action="subscriptions" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem=".money_subscription" data-form="form" href="javascript:;" class="btn btn-dark content_loader_show _edit">
              <i class="fas fa-pen-square"></i>
            </a>

            <!-- <a href="#" class="btn btn-dark content_download" data-id="{{id}}" data-action="subscriptions" data-form="del" data-elem=".list-group-item">
              <i class="fas fa-minus-square"></i>
            </a> -->
          </span>
        </span>
      </span>

      <span class="progress">
        <span class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></span>
      </span>
    </div>
  </div>
