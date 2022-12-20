<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'show': # Вывод элементов
    $oTaskList = $_REQUEST['id'] ? new task_list( $_REQUEST['id'] ) : new task_list();

    if ( $_REQUEST['from'] ) $oTaskList->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oTaskList->limit = $_REQUEST['limit'];

    $oTaskList->sortname = 'date_update';
    $oTaskList->sortdir = 'DESC';
    // $oTaskList->sortMulti = '`sort` DESC, `date_update` DESC';
    $oTaskList->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];

    $oFilter = new filter();
    $oFilter->arrParamsIngores['no_active_show'] = true;
    $oTaskList->query .= $oFilter->get();
    if ( ! $oFilter->get_val('no_active_show') ) $oTaskList->active = true;

    $arrTasksLists = $oTaskList->get_task_lists();
    notification::send($arrTasksLists);
    break;

  case 'form': # Форма добавления / редактирования
    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oTaskList = new task_list( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oTaskList = new task_list();

      // Случайное имя для корректной работы
      $arrDefaultsNames = array(
        'Task for reflection',
        'The best way',
        'Good name, good job',
      );

      // Создаем элемент
      $oTaskList->title = $arrDefaultsNames[array_rand($arrDefaultsNames, 1)];
      $oTaskList->user_id = $_SESSION['user']['id'];
      $oTaskList->active = 1;
      $oTaskList->add();
    }

    // Поля для добавления
    $oForm->arrFields = $oTaskList->fields();

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
    $oForm->arrFields['action'] = ['value'=>'tasks_lists','type'=>'hidden'];
    $oForm->arrFields['app'] = ['value'=>'app','type'=>'hidden'];
    if ( $_REQUEST['data'] && $_REQUEST['data']['success_click'] ) $oForm->arrFields['success_click'] = ['value'=>$_REQUEST['data']['success_click'],'type'=>'hidden'];
    $oForm->arrFields['session'] = ['value'=>$_SESSION['session'],'type'=>'hidden'];

    // Настройки шаблона
    $oForm->arrTemplateParams['id'] = 'content_loader_save';
    $oForm->arrTemplateParams['title'] = $oLang->get('Lists');
    $oForm->arrTemplateParams['button'] = 'Save';
    $sFormHtml = $oForm->show();

    // Вывод результата
    $arrResults['form'] = $sFormHtml;
    $arrResults['data'] = $oTaskList->get_task_list();

    $arrResults['action'] = 'tasks_lists';
    notification::send($arrResults);
    break;

  case 'save': # Сохранение изменений
    $arrResult = [];

    $oTaskList = $_REQUEST['id'] ? new task_list( $_REQUEST['id'] ) : new task_list();
    $oTaskList->arrAddFields = $_REQUEST;
    $oTaskList->arrAddFields['date_update'] = date("Y-m-d H:i:s");

    if ( $_REQUEST['id'] ) {
      $arrResult['event'] = 'save';
      $oTaskList->save();
    }
    else {
      $arrResult['event'] = 'add';
      $oTaskList->add();
    }

    $oTaskList = new task_list( $oTaskList->id );
    $arrResult['data'] = $oTaskList->get_task_list();
    $arrResult['text'] = $oLang->get("ChangesSaved");

    notification::success($arrResult);
    break;

  case 'del': # Удаление
    $oTaskList = new task_list( $_REQUEST['id'] );
    $oTaskList->del();
    $arrResult['text'] = $oLang->get("DeleteSuccess");
    notification::success($arrResult);
    break;
}
