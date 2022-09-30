<?
$oCard = new card();
$oCard->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
$oCard->active = true;
$arrCards = $oCard->get_cards();

$oTask = new task();
$oTask->sort = 'sort';
$oTask->sortDir = 'ASC';
$oTask->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
$oTask->query .= ' AND `status` = 2';
$oTask->active = true;
$arrTasks = $oTask->get_tasks();
$arrTasksId = [];
foreach ($arrTasks as $arrTask) $arrTasksId[$arrTask['id']] = $arrTask;
if ( $_REQUEST['task_id'] ) {
  if ( ! isset($arrTasksId[$_REQUEST['task_id']]) ) {
    $oTask = new task( $_REQUEST['task_id'] );
    $arrTask = $oTask->get_task();
    $arrTasksId[$arrTask['id']] = $arrTask;
  }
}

$oCategory = new category();
$oCategory->sort = 'sort';
$oCategory->sortDir = 'ASC';
$oCategory->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
$oCategory->query .= ' AND `active` > 0';
$arrCategories = $oCategory->get_categories();

// Берём конфики костомных категорий пользователя
$oCategoryConf = new category_config();
$arrCategories = $oCategoryConf->update_categories($arrCategories);
// Вычищаем не активные
$arrCategories = $oCategoryConf->update_categories_active($arrCategories);
$arrCategoriesFilter = [];
// $arrCategoriesFilter[] = array('id'=>0,'name'=>'...');
foreach ($arrCategories as $arrCategory) $arrCategoriesFilter[] = array('id'=>$arrCategory['id'],'name'=>$arrCategory['title'],'color'=>$arrCategory['color']);

$oProject = new project();
$oProject->sort = 'sort';
$oProject->sortDir = 'ASC';
$oProject->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
$oProject->active = true;
$arrProjects = $oProject->get_projects();

$oSubscriptions = new subscription();
$oSubscriptions->sort = 'sort';
$oSubscriptions->sortDir = 'ASC';
$oSubscriptions->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
$oSubscriptions->active = true;
$arrSubscriptions = $oSubscriptions->get_subscriptions();
foreach ($arrSubscriptions as $arrSubscription) $arrSubscriptionsFilter[] = array('id'=>$arrSubscription['id'],'name'=>$arrSubscription['title']);

$arrTypes = [
  array('id'=>1,'title'=>$oLang->get('Spend')),
  array('id'=>2,'title'=>$oLang->get('Replenish')),
];
?>

<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Moneys')?>
    </h1>

    <div class="_buttons btn-group">
      <a class="btn" target="_blank" href="/info/docs/moneys/">
        <i class="fa-solid fa-circle-info"></i>
      </a>
      <?include 'core/templates/elems/filter_button.php'?>
    </div>
  </div>

  <div class="_block_content" id="shower">
    <!-- Фильтр -->
    <form class="content_filter __no_ajax" action="" id="content_filter" data-content_filter_block="#moneys" data-content_filter_status="#content_filter_show">
      <div class="input-group _filter_block">
        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="fas fa-wallet"></i>
            </span>
          </span>
          <select name="type" class="form-select">
            <option value=""><?=$oLang->get('Type')?></option>
            <?php foreach ($arrTypes as $iIndex => $arrType): ?>
              <option value="<?=$arrType['id']?>"><?=$arrType['title']?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="far fa-credit-card"></i>
            </span>
          </span>
          <select name="card" class="form-select">
            <option value=""><?=$oLang->get('Card')?></option>
            <?php foreach ($arrCards as $iIndex => $arrCard): ?>
              <option data-color="<?=$arrCard['color']?>" value="<?=$arrCard['id']?>"><?=$arrCard['title']?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="far fa-credit-card"></i>
            </span>
          </span>
          <select name="to_card" class="form-select">
            <option value=""><?=$oLang->get('ToCard')?></option>
            <?php foreach ($arrCards as $iIndex => $arrCard): ?>
              <option data-color="<?=$arrCard['color']?>" value="<?=$arrCard['id']?>"><?=$arrCard['title']?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="input-group _filter_block">
        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="far fa-folder"></i>
            </span>
          </span>
          <select name="project_id" class="form-select">
            <option value=""><?=$oLang->get('Project')?></option>
            <option value="0"><?=$olang->get('NoProject')?></option>
            <?php foreach ($arrProjects as $iIndex => $arrProject): ?>
              <option data-color="<?=$arrProject['color']?>" value="<?=$arrProject['id']?>"><?=$arrProject['title']?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="_filter_input">
          <span class="input-group-text">
            <i class="fas fa-wrench"></i>
          </span>
          <select name="task_id" class="form-select">
            <option value=""><?=$oLang->get('Task')?></option>
            <?php foreach ($arrTasksId as $iIndex => $arrTask): ?>
              <option data-color="<?=$arrTask['color']?>" value="<?=$arrTask['id']?>"><?=$arrTask['title']?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <div class="input-group _filter_block">
        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="fas fa-list-ul"></i>
            </span>
          </span>
          <select name="category" class="form-select">
            <option value=""><?=$oLang->get('Category')?></option>
            <?php foreach ($arrCategoriesFilter as $iIndex => $arrCategory): ?>
              <option data-color="<?=$arrCategory['color']?>" value="<?=$arrCategory['id']?>"><?=$arrCategory['name']?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="fa-solid fa-calendar-check"></i>
            </span>
          </span>
          <select name="subscription" class="form-select">
            <option value=""><?=$oLang->get('Subscriptions')?></option>
            <?php foreach ($arrSubscriptionsFilter as $iIndex => $arrSubscription): ?>
              <option value="<?=$arrSubscription['id']?>"><?=$arrSubscription['name']?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="_filter_input">
          <span class="input-group-text">
            <span class="_icon">
              <i class="far fa-calendar-alt"></i>
            </span>
          </span>
          <input type="date" name="date" class="form-control" placeholder="<?=$olang->get('Date')?>" value="">
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

  <div
    id="moneys"
    class="block_moneys block_elems list-group block_content_loader"
    data-content_loader_table="moneys"
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
    data-content_loader_scroll_block="#moneys"
    data-content_loader_show_class="_show_"
  ></div>
  <script>
    $(function(){
      // $(document).find('#moneys').content_loader()
      $(document).find('#content_filter').content_filter()
      $(document).find('#content_manager_buttons').content_manager()
      // $(document).find('#footer_actions').content_actions( {'action':'moneys'} )
    })
  </script>
</div>

<div class="block_template">
  <div class="list-group-item money _elem progress_block _type_{{type}}_ _category_show_{{category_show}} _project_show_{{project_show}} _task_show_{{task_show}} _card_show_{{card_show}} _cardto_show_{{cardto_show}} _subscription_show_{{subscription_show}}" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
    <div class="ms-2 me-auto">
      <div class="fw-bold mb-1 d-flex">
        <span class="_date">
          {{date}}
        </span>

        <span class="_card">
          <i class="fas fa-credit-card"></i> {{card_val.title}}
          <span class="_cardto"> <small>></small> <i class="fas fa-credit-card"></i> {{cardto_val.title}}</span>
          <span class="_subscription"> <small>></small> <i class="fas fa-check"></i> {{subscription_val.title}}</span>
        </span>
      </div>

      <div class="fw-bold d-flex align-items-center">
        <div class="badge bg-primary _price" style="font-size: 1rem; font-weight: normal; background: {{categroy_val.color}} ! important; margin-right:.5rem;">
          {{price}}
        </div>

        <span class="_title">
          {{title}}
        </span>

        <div class="_sub">
          <small class="_category">{{categroy_val.title}}</small>
          <small class="_project">> {{project_val.title}}</small>
          <small class="_task">> {{task_val.title}}</small>
        </div>
      </div>
    </div>

    <div class="btn-group">
      <a href="#" class="btn btn-dark content_manager_switch switch_icons">
        <div class="">
          <i class="far fa-square"></i>
        </div>
        <div class="">
          <i class="fas fa-square"></i>
        </div>
      </a>

      <a data-action="moneys" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem=".money" data-form="form" href="javascript:;" class="btn btn-dark content_loader_show _edit">
        <i class="fas fa-pen-square"></i>
      </a>

      <!-- <a href="#" class="btn btn-dark content_download" data-id="{{id}}" data-action="moneys" data-form="del" data-elem=".money" data-animate_class="animate__fadeOutRightBig"><i class="fas fa-minus-square"></i></a> -->
    </div>

    <div class="progress">
      <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100" style="width: 100%"></div>
    </div>
  </div>
</div>
