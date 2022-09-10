<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'actions': # Элементы управления
    $sResultHtml = '';
    $sResultHtml .= '
      <div class="btn-group">
        <a data-action="accesses" data-animate_class="animate__flipInY" data-elem=".access" data-form="form" data-filter="true" href="javascript:;" class="btn btn-dark content_loader_show">
          <span class="_icon"><i class="fas fa-plus-circle"></i></span>
          <span class="_text">' . $oLang->get("Add") . '</span>
        </a>
      </div>
      ';

    notification::send( $sResultHtml );
    break;

  case 'show': # Показ
      $oAccess = new access( $_REQUEST['id'] );

      if ( $_REQUEST['from'] ) $oAccess->from = $_REQUEST['from'];
      if ( $_REQUEST['limit'] ) $oAccess->limit = $_REQUEST['limit'];

      $oAccess->sortname = 'date_stop';
      $oAccess->sortdir = 'ASC';

      if ( ! $oLock->check('AccessShow') )
        $oAccess->query = ' AND `user_id` = ' . $_SESSION['user']['id'];

      $oAccess->show_user = true;

      $arrAccesses = $oAccess->get_accesses();
      notification::send($arrAccesses);
    break;

  case 'form': # Форма добавления / редактирования
    if ( ! $oLock->check('AccessForm') ) notification::error($oLang->get('AccessesDenied'));

    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oAccess = new access( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oAccess = new access();
      $oAccess->user_id = $_SESSION['user']['id'];
      $oAccess->add();
    }

    // Поля для добавления
    $oForm->arrFields = $oAccess->fields();

    $oForm->arrFields['form'] = ['value'=>'save','type'=>'hidden'];
    $oForm->arrFields['action'] = ['value'=>'accesses','type'=>'hidden'];
    $oForm->arrFields['app'] = ['value'=>'app','type'=>'hidden'];
    if ( $_REQUEST['data'] && $_REQUEST['data']['success_click'] ) $oForm->arrFields['success_click'] = ['value'=>$_REQUEST['data']['success_click'],'type'=>'hidden'];
    $oForm->arrFields['session'] = ['value'=>$_SESSION['session'],'type'=>'hidden'];

    // Настройки шаблона
    $oForm->arrTemplateParams['id'] = 'content_loader_save';
    $oForm->arrTemplateParams['title'] = $oLang->get('Rewards');
    // $oForm->arrTemplateParams['button'] = 'Save';
    // $oForm->arrTemplateParams['button_copy'] = true;
    $sFormHtml = $oForm->show();

    // Вывод результата
    $arrResults['form'] = $sFormHtml;
    $arrResults['data'] = $oAccess->get_accesses();
    $arrResults['action'] = 'accesses';

    notification::send($arrResults);
    break;

  case 'save': # Сохранение изменений
    if ( ! $oLock->check('AccessSave') ) notification::error($oLang->get('AccessesDenied'));

    $arrResult = [];
    $oAccess = $_REQUEST['id'] ? new access( $_REQUEST['id'] ) : new access();
    $oAccess->arrAddFields = $_REQUEST;

    // Если копирование
    if ( isset($_REQUEST['content_loader_copy']) ) {
      unset($oAccess->arrAddFields['id']);
    }

    if ( $_REQUEST['id'] && ! isset($_REQUEST['content_loader_copy']) ) {
      $arrResult['event'] = 'save';
      $oAccess->save();
      if ( $oAccess->date_stop == '0000-00-00' ) {
        $oAccess->set_access();
      }
    }
    else {
      $arrResult['event'] = 'add';
      $oAccess->add();
    }

    $oAccess = new access( $oAccess->id );
    $arrResult['data'] = $oAccess->get_accesses();
    $arrResult['text'] = $oLang->get("ChangesSaved");

    notification::success($arrResult);
    break;

  case 'del': # Удаление
    if ( ! $oLock->check('AccessDel') ) notification::error($oLang->get('AccessesDenied'));

    $oAccess = new access( $_REQUEST['id'] );
    $oAccess->del();
    $arrResult = [];
    $arrResult['event'] = 'del';
    $arrResult['text'] = $oLang->get("DeleteSuccess");
    notification::success($arrResult);
    break;
}
