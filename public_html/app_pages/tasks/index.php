<?
$oTask = new task();

$oClient = new client();
$oClient->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
$arrClients = $oClient->get_clients();

$oProject = new project();
$oProject->sort = 'sort';
$oProject->sortDir = 'ASC';
$oProject->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
$oProject->active = true;
$arrProjects = $oProject->get_projects();
?>

<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Tasks')?>
    </h1>

    <div class="_buttons btn-group">
      <a class="btn" target="_blank" href="/info/docs/tasks/">
        <i class="fa-solid fa-circle-info"></i>
      </a>
      <?include 'core/templates/elems/filter_button.php'; # Кнопка фильтрации?>
    </div>
  </div>

  <div class="_block_content" id="shower">
    <!-- Фильтр -->
    <form class="content_filter __no_ajax" action="" id="content_filter" data-content_filter_block="#tasks" data-content_filter_status="#content_filter_show">
      <div class="input-group _filter_block">
        <div class="_filter_input">
          <span class="input-group-text">
            <i class="fa-solid fa-folder"></i>
          </span>
          <select name="client_id" class="form-select">
            <option value="0" selected><?=$oLang->get('Client')?></option>
            <?php foreach ($arrClients as $iIndex => $arrClient): ?>
              <?php if ( $_REQUEST['client_id'] && $_REQUEST['client_id'] == $arrClient['id'] ): ?>
                <option selected="selected" value="<?=$arrClient['id']?>"><?=$arrClient['title']?></option>
              <?php else: ?>
                <option value="<?=$arrClient['id']?>"><?=$arrClient['title']?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="_filter_input">
          <span class="input-group-text">
            <i class="fa-solid fa-folder-tree"></i>
          </span>
          <select name="project_id" class="form-select">
            <option value="0" selected><?=$oLang->get('Project')?></option>
            <?php foreach ($arrProjects as $iIndex => $arrProject): ?>
              <?php if ( $_REQUEST['project_id'] && $_REQUEST['project_id'] == $arrProject['id'] ): ?>
                <option selected="selected" value="<?=$arrProject['id']?>"><?=$arrProject['title']?></option>
              <?php else: ?>
                <option value="<?=$arrProject['id']?>"><?=$arrProject['title']?></option>
              <?php endif; ?>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="input-group _filter_block">
        <div class="_filter_input">
          <span class="input-group-text">
            <i class="fas fa-spinner"></i>
          </span>
          <select name="status" class="form-select">
            <?php foreach ($oTask->arrStatus as $arrStatus): ?>
              <?php if ( $_REQUEST['status'] && $_REQUEST['status'] == $arrStatus['id'] ): ?>
                <option data-color="<?=$arrStatus['color']?>" selected="selected" value="<?=$arrStatus['id']?>"><?=$arrStatus['name']?></option>
              <?php else: ?>
                <option data-color="<?=$arrStatus['color']?>" value="<?=$arrStatus['id']?>"><?=$arrStatus['name']?></option>
              <?php endif; ?>
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

<section class="main_content">
  <?
  $arrTemplateParams = [];
  $arrTemplateParams['item'] = '.task';
  include 'core/templates/elems/content_manager_block.php';
  ?>

  <div
    id="tasks"
    class="block_tasks block_elems block_content_loader"
    data-content_loader_table="tasks"
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
    data-content_loader_scroll_block="#tasks"
    data-content_loader_show_class="_show_"
  ></div>

  <script>
    $(function(){
      // $(document).find('#tasks').content_loader()
      $(document).find('#content_filter').content_filter()
      // $(document).find('#content_manager_buttons').content_manager()
      // $(document).find('#footer_actions').content_actions( {'action':'tasks'} )
    })
  </script>
</section>

<section class="block_template">
  <div class="task _elem progress_block _time_show_{{time_show}} _description_show_{{description_show}}  _money_show_{{money_show}} _status_show_{{status_show}} _client_show_{{client_show}} _project_show_{{project_show}} _active_show_{{active_show}}" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
    <div class="card w-100">
      <div class="card-header position-relative">
        <div class="row">
          <div class="col-12 col-md-8 d-flex align-items-center">
            <span class="_title">
              {{title}}
              <small>
                #{{id}}
              </small>
            </span>
          </div>

          <div class="col-12 col-md-4 d-flex justify-content-end align-items-center">
            <div class="btn-group">
              <a href="#" class="btn content_manager_switch switch_icons">
                <div class="">
                  <i class="far fa-square"></i>
                </div>
                <div class="">
                  <i class="fas fa-square"></i>
                </div>
              </a>

              <a data-action="tasks" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem=".task" data-form="form" href="javascript:;" class="btn content_loader_show _edit">
                <i class="fas fa-pen-square"></i>
              </a>

              <!-- <a href="#" class="btn content_download" data-id="{{id}}" data-action="tasks" data-form="del" data-elem=".task" data-animate_class="animate__fadeOutRightBig"><i class="fas fa-minus-square"></i></a> -->
            </div>
          </div>
        </div>

        <div class="progress">
          <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
        </div>
      </div>

      <div class="card-body">
        <div class="_path btn-group">
          <a href="/clients/analytics/?client_id={{client_id}}" class="btn __icon _client">
            <span class="_icon">
              <i class="fa-solid fa-folder"></i>
            </span>
            <span class="_text">
              {{client.title}}
            </span>
          </a>

          <a href="/projects/analytics/?project_id={{project_id}}" class="btn __icon _project">
            <span class="_icon">
              <i class="fa-solid fa-folder-tree"></i>
            </span>
            <span class="_text">
              {{project.title}}
            </span>
          </a>
        </div>

        <div class="_sub">
          <div class="_status" style="background: {{status_color}}">
            {{status_val}}
          </div>

          <div class="_time">
            <a href="/times/?task_id={{id}}&project_id={{project_id}}&client_id={{project.client_id}}&category_id=4" class="btn mx-2">
              <i class="fas fa-clock"></i>
            </a>

            {{time_really}} <span>/</span>
            <small>{{time_planned}}</small>
          </div>

          <div class="_money">
            <a href="/moneys/?task_id={{id}}&project_id={{project_id}}&client_id={{project.client_id}}&category_id=4" class="btn mx-2">
              <i class="fas fa-wallet"></i>
            </a>

            {{price_really}} <span>/</span>
            <small>{{price_planned}}</small>
          </div>

          <div class="_info">
            <div class="btn-group">
              <button type="button" class="btn _description_show" name="button" data-shower="#desc_{{id}}" data-shower_class="animate__fadeIn">
                <i class="fa-solid fa-align-left"></i>
              </button>

              <button type="button" class="btn _lists_show __gradient" name="button" data-shower="#lists_{{id}}" data-shower_class="animate__fadeIn" onclick="lists_show( $(document).find('#lists_{{id}}') )">
                <i class="fa-solid fa-list-check"></i>
              </button>
            </div>
          </div>
        </div>

        <div class="_description_prev animate__animated" id="desc_{{id}}">
          {{description_prev}}
        </div>

        <div class="_lists_prev animate__animated" id="lists_{{id}}" data-task_id="{{id}}">
        </div>
      </div>
    </div>

    <div class="_client_background">
      <div class="_color" style="background: radial-gradient({{client.color}} 0%,rgba(0,0,0,0) 70%)"></div>
    </div>
    <div class="_project_background">
      <div class="_color" style="background: radial-gradient({{project.color}} 0%,rgba(0,0,0,0) 70%)"></div>
    </div>
  </div>
</section>
