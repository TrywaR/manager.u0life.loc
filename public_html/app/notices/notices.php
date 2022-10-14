<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'actions': # Элементы управления
    $sResultHtml = '';
    $sResultHtml .= '
      <div class="btn-group">
        <a data-action="notices" data-animate_class="animate__flipInY" data-elem=".reward" data-form="form" data-filter="true" href="javascript:;" class="btn btn-dark content_loader_show">
          <span class="_icon"><i class="fas fa-plus-circle"></i></span>
          <span class="_text">' . $oLang->get("Add") . '</span>
        </a>
      </div>
      ';

    notification::send( $sResultHtml );
    break;

  case 'show': # Вывод элементов
    if ( ! $oLock->check('NoticesShow') ) notification::error($oLang->get('AccessesDenied'));

    $oNotice = $_REQUEST['id'] ? new notice( $_REQUEST['id'] ) : new notice();

    if ( $_REQUEST['from'] ) $oNotice->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oNotice->limit = $_REQUEST['limit'];

    $arrNotices = $oNotice->get_notices();

    notification::send($arrNotices);
    break;

  case 'add_view': # Добавить просмотр
    $oLang = new lang();
    $oNotice = new notice( $_REQUEST['id'] );
    $oNotice->add_view();
    notification::success($oLang->get($oNotice->title));
    break;

  case 'show_user': # Показ пользователю что надо
    $oNotice = new notice();
    $arrNotices = $oNotice->get_notices();
    $arrNoticesValids = [];

    foreach ($arrNotices as $arrNotice) {
      $oNoticeView = new notice_view();
      $oNoticeView->query .= ' AND `notice_id` = ' . $arrNotice['id'];
      $oNoticeView->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
      $arrNoticeViews = $oNoticeView->get_notices_views();
      if ( count($arrNoticeViews) >= $arrNotice['views'] ) continue;

      $arrNoticesValids[] = $arrNotice;
      break;
    }

    notification::send($arrNoticesValids);
    break;

  case 'form': # Форма добавления / редактирования
    if ( ! $oLock->check('NoticesForm') ) notification::error($oLang->get('AccessesDenied'));

    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oNotice = new notice( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oNotice = new notice();
      $oNotice->user_id = $_SESSION['user']['id'];
      $oNotice->add();
    }

    // Поля для добавления
    $oForm->arrFields = $oNotice->fields();

    $oForm->arrFields['form'] = ['value'=>'save','type'=>'hidden'];
    $oForm->arrFields['action'] = ['value'=>'notices','type'=>'hidden'];
    $oForm->arrFields['app'] = ['value'=>'app','type'=>'hidden'];
    if ( $_REQUEST['data'] && $_REQUEST['data']['success_click'] ) $oForm->arrFields['success_click'] = ['value'=>$_REQUEST['data']['success_click'],'type'=>'hidden'];
    $oForm->arrFields['session'] = ['value'=>$_SESSION['session'],'type'=>'hidden'];

    // Настройки шаблона
    $oForm->arrTemplateParams['id'] = 'content_loader_save';
    $oForm->arrTemplateParams['title'] = $oLang->get('Rewards');
    // $oForm->arrTemplateParams['button'] = 'Save';
    $oForm->arrTemplateParams['button_copy'] = true;
    $sFormHtml = $oForm->show();

    // Вывод результата
    $arrResults['form'] = $sFormHtml;
    $arrResults['data'] = $oNotice->get_notices();
    $arrResults['action'] = 'notices';

    notification::send($arrResults);
    break;

  case 'save': # Сохранение изменений
    if ( ! $oLock->check('NoticesSave') ) notification::error($oLang->get('AccessesDenied'));

    $arrResult = [];
    $oNotice = $_REQUEST['id'] ? new notice( $_REQUEST['id'] ) : new notice();
    $oNotice->arrAddFields = $_REQUEST;

    // Если копирование
    if ( isset($_REQUEST['content_loader_copy']) ) {
      unset($oNotice->arrAddFields['id']);
    }

    if ( $_REQUEST['id'] && ! isset($_REQUEST['content_loader_copy']) ) {
      $arrResult['event'] = 'save';
      $oNotice->save();
    }
    else {
      $arrResult['event'] = 'add';
      $oNotice->add();
    }

    $oNotice = new notice( $oNotice->id );
    $arrResult['data'] = $oNotice->get_notices();
    $arrResult['text'] = $oLang->get("ChangesSaved");

    notification::success($arrResult);
    break;

  case 'del': # Удаление
    if ( ! $oLock->check('NoticesDel') ) notification::error($oLang->get('AccessesDenied'));

    $oNotice = new notice( $_REQUEST['id'] );
    $oNotice->del();
    $arrResult = [];
    $arrResult['event'] = 'del';
    $arrResult['text'] = $oLang->get("DeleteSuccess");
    notification::success($arrResult);
    break;
}
