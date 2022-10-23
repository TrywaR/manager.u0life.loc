<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Clients')?>
    </h1>

    <div class="_buttons btn-group">
      <?include 'core/templates/elems/filter_button.php'; # Кнопка фильтрации?>
    </div>
  </div>

  <div class="_block_content" id="shower">
    <!-- Фильтр -->
    <form class="content_filter __no_ajax" action="" id="content_filter" data-content_filter_block="#clients" data-content_filter_status="#content_filter_show">
      <div class="input-group _filter_block">
        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="fa-solid fa-font"></i>
            </span>
          </span>
          <input type="text" name="title" class="form-control" placeholder="<?=$olang->get('Title')?>" value="">
        </div>

        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="fa-solid fa-phone"></i>
            </span>
          </span>
          <input type="text" name="phone" class="form-control" placeholder="<?=$olang->get('Phone')?>" value="">
        </div>

        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="fa-solid fa-envelope"></i>
            </span>
          </span>
          <input type="text" name="email" class="form-control" placeholder="<?=$olang->get('Email')?>" value="">
        </div>

      </div>

      <div class="input-group _filter_block">
        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="fa-brands fa-instagram"></i>
            </span>
          </span>
          <input type="text" name="instagram" class="form-control" placeholder="<?=$olang->get('Instagram')?>" value="">
        </div>

        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="fa-brands fa-telegram"></i>
            </span>
          </span>
          <input type="text" name="telegram" class="form-control" placeholder="<?=$olang->get('Telegram')?>" value="">
        </div>
      </div>

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
  $arrTemplateParams['item'] = '.client';
  include 'core/templates/elems/content_manager_block.php';
  ?>

  <div
    id="clients"
    class="block_clients block_elems block_content_loader"
    data-content_loader_table="clients"
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
    data-content_loader_scroll_block="#clients"
    data-content_loader_show_class="_show_"
    ></div>

  <script>
    $(function(){
      // $(document).find('#clients').content_loader( 'start' )
      $(document).find('#content_filter').content_filter()
      // $(document).find('#content_manager_buttons').content_manager()
      // $(document).find('#footer_actions').content_actions( {'action':'clients'} )
    })
  </script>
</div>

<div class="block_template">
  <div class="client _elem progress_block _active_show_{{active_show}}" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-xl-6">
            <small>№{{sort}}</small>
            <small>#{{id}}</small>
            <h5 class="card-title">{{title}}</h5>
            <p class="card-text">{{description}}</p>

            <div class="btn-group">
              <a target="_blank" href="tel:{{phone}}" class="btn __icon content_ignore d-none{{phone}}">
                <span class="_icon">
                  <i class="fa-solid fa-phone"></i>
                </span>
                <span class="_text">
                  {{phone}}
                </span>
              </a>

              <a target="_blank" href="mailto:{{email}}" class="btn __icon content_ignore d-none{{email}}">
                <span class="_icon">
                  <i class="fa-solid fa-envelope"></i>
                </span>
                <span class="_text">
                  {{email}}
                </span>
              </a>

              <a target="_blank" href="https://t.me/{{telegram}}" class="btn __icon d-none{{telegram}}">
                <span class="_icon">
                  <i class="fa-brands fa-telegram"></i>
                </span>
                <span class="_text">
                  {{telegram}}
                </span>
              </a>

              <a target="_blank" href="https://instagram.com/{{instagram}}" class="btn __icon d-none{{instagram}}">
                <span class="_icon">
                  <i class="fa-brands fa-instagram"></i>
                </span>
                <span class="_text">
                  {{instagram}}
                </span>
              </a>
            </div>
          </div>
          <div class="col-12 col-xl-6 d-flex justify-content-end align-items-start">
            <div class="btn-group" role="group">
              <a href="#" class="btn content_manager_switch switch_icons">
                <div class="">
                  <i class="far fa-square"></i>
                </div>
                <div class="">
                  <i class="fas fa-square"></i>
                </div>
              </a>

              <a href="/clients/analytics/?client_id={{id}}" class="btn">
                <i class="fas fa-chart-area"></i>
              </a>

              <a href="/projects/?client_id={{id}}" class="btn">
                <i class="fa-solid fa-folder-tree"></i>
              </a>

              <a href="/tasks/?client_id={{id}}" class="btn">
                <i class="fa-solid fa-person-digging"></i>
              </a>

              <a data-action="clients" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem=".client" data-form="form" href="javascript:;" class="btn content_loader_show">
                <i class="fas fa-pen-square"></i>
              </a>

              <!-- <a href="#" class="btn content_download" data-id="{{id}}" data-elem=".client" data-action="clients" data-form="del" data-animate_class="animate__fadeOutRightBig">
                <i class="fas fa-minus-square"></i>
              </a> -->
            </div>
          </div>
        </div>
      </div>
      <div class="progress">
        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
      </div>
    </div>
  </div>
</div>
