<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'actions': # Элементы управления
    $sResultHtml = '';
    $sResultHtml .= '
      <div class="btn-group">
        <a data-action="tasks_templates" data-animate_class="animate__flipInY" data-elem=".task" data-form="form" data-filter="true" href="javascript:;" class="btn btn-dark content_loader_show">
          <span class="_icon"><i class="fas fa-plus-circle"></i></span>
          <span class="_text">' . $oLang->get("Add") . '</span>
        </a>
      </div>
      ';

    notification::send( $sResultHtml );
    break;

  case 'active': # Активные тикеты
    $arrResults = [];
    $oTaskTemplate = $_REQUEST['id'] ? new task_template( $_REQUEST['id'] ) : new task_template();

    if ( $_REQUEST['from'] ) $oTaskTemplate->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oTaskTemplate->limit = $_REQUEST['limit'];

    $oTaskTemplate->sortMulti = ' `sort` DESC, `date_update` DESC ';
    $oTaskTemplate->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
    $oTaskTemplate->query .= ' AND `status` = 2';
    $oTaskTemplate->active = true;

    $oFilter = new filter();
    $oTaskTemplate->query .= $oFilter->get();

    $arrTasksTemplates = $oTaskTemplate->get_task_templates();

    $arrResults['tasks_templates'] = $arrTasksTemplates;
    notification::send($arrResults);
    break;

  case 'show': # Вывод элементов
    $oTaskTemplate = $_REQUEST['id'] ? new task_template( $_REQUEST['id'] ) : new task_template();

    if ( $_REQUEST['from'] ) $oTaskTemplate->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oTaskTemplate->limit = $_REQUEST['limit'];

    $oTaskTemplate->sortname = 'date_update';
    $oTaskTemplate->sortdir = 'DESC';
    // $oTaskTemplate->sortMulti = '`sort` DESC, `date_update` DESC';
    $oTaskTemplate->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];

    $oFilter = new filter();
    $oFilter->arrParamsIngores['no_active_show'] = true;
    $oTaskTemplate->query .= $oFilter->get();
    if ( ! $oFilter->get_val('no_active_show') ) $oTaskTemplate->active = true;

    $arrTasksTemplates = $oTaskTemplate->get_task_templates();
    notification::send($arrTasksTemplates);
    break;

  case 'form': # Форма добавления / редактирования
    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oTaskTemplate = new task_template( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oTaskTemplate = new task_template();

      // Случайное имя для корректной работы
      $arrDefaultsNames = array(
        'Task for reflection',
        'The best way',
        'Good name, good job',
      );

      // Создаем элемент
      $oTaskTemplate->title = $arrDefaultsNames[array_rand($arrDefaultsNames, 1)];
      $oTaskTemplate->user_id = $_SESSION['user']['id'];
      $oTaskTemplate->active = 1;
      $oTaskTemplate->add();
    }

    // Поля для добавления
    $oForm->arrFields = $oTaskTemplate->fields();

    // Сразу заполняем некоторые данне если переданны
    if ( $_REQUEST['data'] ) {
      foreach ($_REQUEST['data'] as $key => $value) {
        if ( isset($oForm->arrFields[$key]) ) {
          $oForm->arrFields[$key]['value'] = $value;
          // $oForm->arrFields[$key]['type'] = 'hidden';
        }
      }
    }

    $oForm->arrFields['form'] = ['value'=>'save','type'=>'hidden'];
    $oForm->arrFields['action'] = ['value'=>'tasks_templates','type'=>'hidden'];
    $oForm->arrFields['app'] = ['value'=>'app','type'=>'hidden'];
    if ( $_REQUEST['data'] && $_REQUEST['data']['success_click'] ) $oForm->arrFields['success_click'] = ['value'=>$_REQUEST['data']['success_click'],'type'=>'hidden'];
    $oForm->arrFields['session'] = ['value'=>$_SESSION['session'],'type'=>'hidden'];

    // Настройки шаблона
    $oForm->arrTemplateParams['id'] = 'content_loader_save';
    $oForm->arrTemplateParams['title'] = $oLang->get('TemplatesTasks');
    $oForm->arrTemplateParams['button'] = 'Save';
    $sFormHtml = $oForm->show();

    // Вывод результата
    $arrResults['form'] = $sFormHtml;
    $arrResults['data'] = $oTaskTemplate->get_task_template();

    $arrResults['action'] = 'tasks_templates';
    notification::send($arrResults);
    break;

  case 'save': # Сохранение изменений
    $arrResult = [];

    $oTaskTemplate = $_REQUEST['id'] ? new task_template( $_REQUEST['id'] ) : new task_template();
    $oTaskTemplate->arrAddFields = $_REQUEST;
    $oTaskTemplate->arrAddFields['date_update'] = date("Y-m-d H:i:s");

    if ( $_REQUEST['id'] ) {
      $arrResult['event'] = 'save';
      $oTaskTemplate->save();
    }
    else {
      $arrResult['event'] = 'add';
      $oTaskTemplate->add();
    }

    $oTaskTemplate = new task_template( $oTaskTemplate->id );
    $arrResult['data'] = $oTaskTemplate->get_task_template();
    $arrResult['text'] = $oLang->get("ChangesSaved");

    notification::success($arrResult);
    break;

  case 'del': # Удаление
    $oTaskTemplate = new task_template( $_REQUEST['id'] );
    $oTaskTemplate->del();
    $arrResult['text'] = $oLang->get("DeleteSuccess");
    notification::success($arrResult);
    break;
}
