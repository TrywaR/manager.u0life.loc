<?
$oClient = new client();
$oClient->sort = 'sort';
$oClient->sortDir = 'ASC';
$oClient->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
$oClient->active = 1;
$arrClients = $oClient->get_clients();
?>

<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Projects')?>
    </h1>

    <div class="_buttons btn-group">
      <a class="btn" target="_blank" href="/info/docs/projects/">
        <i class="fa-solid fa-circle-info"></i>
      </a>
      <?include 'core/templates/elems/filter_button.php'; # Кнопка фильтрации?>
    </div>
  </div>

  <div class="_block_content" id="shower">
    <!-- Фильтр -->
    <form class="content_filter __no_ajax" action="" id="content_filter" data-content_filter_block="#projects" data-content_filter_status="#content_filter_show">
      <div class="input-group _filter_block">
        <div class="_filter_input">
          <span class="input-group-text">
            <i class="far fa-folder"></i>
          </span>
          <select name="client_id" class="form-select">
            <option value="0" selected><?=$oLang->get('Client')?></option>
            <?php foreach ($arrClients as $iIndex => $arrClient): ?>
              <option value="<?=$arrClient['id']?>"><?=$arrClient['title']?></option>
            <?php endforeach; ?>
          </select>
        </div>

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
  $arrTemplateParams['item'] = '.project';
  include 'core/templates/elems/content_manager_block.php';
  ?>

  <div
    id="projects"
    class="block_projects block_elems block_content_loader"
    data-content_loader_table="projects"
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
    data-content_loader_scroll_block="#projects"
    data-content_loader_show_class="_show_"
  ></div>

  <script>
    $(function(){
      // $(document).find('#projects').content_loader( 'start' )
      $(document).find('#content_filter').content_filter()
      // $(document).find('#content_manager_buttons').content_manager()
      // $(document).find('#footer_actions').content_actions( {'action':'projects'} )
    })
  </script>
</div>

<div class="block_template">
    <div class="project _elem progress_block _active_show_{{active_show}}" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-12 col-xl-6">
              <small>
                №{{sort}}
              </small>
              <small>
                #{{id}}
              </small>
              <h5 class="card-title">
                {{title}}
              </h5>
              <p class="card-text">
                {{description}}
              </p>

              <div class="btn-group">
                <a href="/clients/analytics/?client_id={{client_id}}" class="btn __icon d-none{{client_id}}">
                  <span class="_icon">
                    <i class="fa-solid fa-folder"></i>
                  </span>
                  <span class="_text">
                    {{client.title}}
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

                <a href="/projects/analytics/?project_id={{id}}" class="btn">
                  <i class="fas fa-chart-area"></i>
                </a>

                <a href="/tasks/?project_id={{id}}" class="btn">
                  <i class="fa-solid fa-person-digging"></i>
                </a>

                <a data-action="projects" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem=".project" data-form="form" href="javascript:;" class="btn content_loader_show">
                  <i class="fas fa-pen-square"></i>
                </a>

                <!-- <a href="#" class="btn content_download" data-id="{{id}}" data-action="projects" data-form="del" data-elem=".project" data-animate_class="animate__fadeOutRightBig">
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
