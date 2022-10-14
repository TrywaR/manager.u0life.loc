<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'actions': # Элементы управления
    $sResultHtml = '';
    $sResultHtml .= '
      <div class="btn-group">
        <a data-action="notices_views" data-animate_class="animate__flipInY" data-elem=".reward" data-form="form" data-filter="true" href="javascript:;" class="btn btn-dark content_loader_show">
          <span class="_icon"><i class="fas fa-plus-circle"></i></span>
          <span class="_text">' . $oLang->get("Add") . '</span>
        </a>
      </div>
      ';

    notification::send( $sResultHtml );
    break;

  case 'show': # Вывод элементов
    if ( ! $oLock->check('NoticesShow') ) notification::error($oLang->get('AccessesDenied'));

    $oNoticeView = new notice_view( $_REQUEST['id'] );

    if ( $_REQUEST['from'] ) $oNoticeView->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oNoticeView->limit = $_REQUEST['limit'];
    $arrNoticesViews = $oNoticeView->get_notices_views();

    notification::send($arrNoticesViews);
    break;

  case 'form': # Форма добавления / редактирования
    if ( ! $oLock->check('NoticesForm') ) notification::error($oLang->get('AccessesDenied'));

    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oNoticeView = new notice_view( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oNoticeView = new notice_view();
      $oNoticeView->user_id = $_SESSION['user']['id'];
      $oNoticeView->add();
    }

    // Поля для добавления
    $oForm->arrFields = $oNoticeView->fields();

    $oForm->arrFields['form'] = ['value'=>'save','type'=>'hidden'];
    $oForm->arrFields['action'] = ['value'=>'notices_views','type'=>'hidden'];
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
    $arrResults['data'] = $oNoticeView->get_notices_views();
    $arrResults['action'] = 'notices_views';

    notification::send($arrResults);
    break;

  case 'save': # Сохранение изменений
    if ( ! $oLock->check('NoticesSave') ) notification::error($oLang->get('AccessesDenied'));

    $arrResult = [];
    $oNoticeView = $_REQUEST['id'] ? new notice_view( $_REQUEST['id'] ) : new notice_view();
    $oNoticeView->arrAddFields = $_REQUEST;

    // Если копирование
    if ( isset($_REQUEST['content_loader_copy']) ) {
      unset($oNoticeView->arrAddFields['id']);
    }

    if ( $_REQUEST['id'] && ! isset($_REQUEST['content_loader_copy']) ) {
      $arrResult['event'] = 'save';
      $oNoticeView->save();
    }
    else {
      $arrResult['event'] = 'add';
      $oNoticeView->add();
    }

    $oNoticeView = new notice_view( $oNoticeView->id );
    $arrResult['data'] = $oNoticeView->get_notices_views();
    $arrResult['text'] = $oLang->get("ChangesSaved");

    notification::success($arrResult);
    break;

  case 'del': # Удаление
    if ( ! $oLock->check('NoticesDel') ) notification::error($oLang->get('AccessesDenied'));

    $oNoticeView = new notice_view( $_REQUEST['id'] );
    $oNoticeView->del();
    $arrResult = [];
    $arrResult['event'] = 'del';
    $arrResult['text'] = $oLang->get("DeleteSuccess");
    notification::success($arrResult);
    break;
}
