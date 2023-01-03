<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'show': # Вывод элементов
    $oTaskListElem = $_REQUEST['id'] ? new task_list_elem( $_REQUEST['id'] ) : new task_list_elem();

    if ( $_REQUEST['from'] ) $oTaskListElem->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oTaskListElem->limit = $_REQUEST['limit'];

    $oTaskListElem->sortname = 'sort';
    $oTaskListElem->sortdir = 'ASC';
    // $oTaskListElem->sortMulti = '`sort` DESC, `date_update` DESC';
    $oTaskListElem->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];

    $oFilter = new filter();
    $oFilter->arrParamsIngores['no_active_show'] = true;
    $oTaskListElem->query .= $oFilter->get();
    if ( ! $oFilter->get_val('no_active_show') ) $oTaskListElem->active = true;

    $arrTasksListsElems = $oTaskListElem->get_task_list_elems();
    notification::send($arrTasksListsElems);
    break;

  case 'form': # Форма добавления / редактирования
    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oTaskListElem = new task_list_elem( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oTaskListElem = new task_list_elem();

      // Случайное имя для корректной работы
      // $arrDefaultsNames = array(
      //   'Task for reflection',
      //   'The best way',
      //   'Good name, good job',
      // );

      // Создаем элемент
      // $oTaskListElem->title = $arrDefaultsNames[array_rand($arrDefaultsNames, 1)];
      $oTaskListElem->user_id = $_SESSION['user']['id'];
      $oTaskListElem->add();
      $oTaskListElem->active = 1;
      $oTaskListElem->save();
    }

    // Поля для добавления
    $oForm->arrFields = $oTaskListElem->fields();

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
    $oForm->arrFields['action'] = ['value'=>'tasks_lists_elems','type'=>'hidden'];
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
    $arrResults['data'] = $oTaskListElem->get_task_list_elem();

    $arrResults['action'] = 'tasks_lists_elems';
    notification::send($arrResults);
    break;

  case 'add': # Добавление
    // Параметры
    $arrResults = [];
    $arrResults['event'] = 'add';
    $oTaskListElem = new task_list_elem();

    // Случайное имя для корректной работы
    // $arrDefaultsNames = array(
    //   'Task for reflection',
    //   'The best way',
    //   'Good name, good job',
    // );

    // Создаем элемент
    // $oTaskListElem->title = $arrDefaultsNames[array_rand($arrDefaultsNames, 1)];
    $oTaskListElem->user_id = $_SESSION['user']['id'];
    $oTaskListElem->list_id = $_REQUEST['list_id'];
    $oTaskListElem->sort = $_REQUEST['sort'];
    $oTaskListElem = new task_list_elem( $oTaskListElem->add() );
    $oTaskListElem->active = 1;
    $oTaskListElem->save();

    // Бережно отдаём данные
    $arrResults['data'] = $oTaskListElem->get_task_list_elem();
    notification::send($arrResults);
    break;

  case 'save': # Сохранение изменений
    $arrResult = [];

    $oTaskListElem = new task_list_elem( $_REQUEST['id'] );
    $oTaskListElem->date_update = date("Y-m-d H:i:s");
    $oTaskListElem->sort = $_REQUEST['sort'];
    $oTaskListElem->title = $_REQUEST['title'];

    if ( $_REQUEST['id'] ) {
      $arrResult['event'] = 'save';
      $oTaskListElem->save();
    }
    else {
      $arrResult['event'] = 'add';
      $oTaskListElem->add();
    }

    $oTaskListElem = new task_list_elem( $oTaskListElem->id );
    $arrResult['data'] = $oTaskListElem->get_task_list_elem();
    $arrResult['text'] = $oLang->get("ChangesSaved");

    notification::success($arrResult);
    break;

  case 'check': # Изменение статуса
    $arrResult = [];
    $arrResult['event'] = 'save';

    $oTaskListElem = new task_list_elem( $_REQUEST['id'] );
    $oTaskListElem->date_update = date("Y-m-d H:i:s");
    if ( (int)$_REQUEST['status'] ) $oTaskListElem->status = 1;
    else $oTaskListElem->status = 0;
    $oTaskListElem->save();

    $oTaskListElem = new task_list_elem( $oTaskListElem->id );
    $arrResult['data'] = $oTaskListElem->get_task_list_elem();

    notification::success($arrResult);
    break;

  case 'sort': # Изменение статуса
    $arrResult = [];
    $arrResult['event'] = 'sort';

    $oTaskListElem = new task_list_elem( $_REQUEST['id'] );
    $oTaskListElem->sort = $_REQUEST['sort'];
    $oTaskListElem->save();

    $oTaskListElem = new task_list_elem( $oTaskListElem->id );
    $arrResult['data'] = $oTaskListElem->get_task_list_elem();

    notification::success($arrResult);
    break;

  case 'del': # Удаление
    $oTaskListElem = new task_list_elem( $_REQUEST['id'] );
    $oTaskListElem->del();
    $arrResult['text'] = $oLang->get("DeleteSuccess");
    notification::success($arrResult);
    break;
}
