<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'actions': # Элементы управления
    $sResultHtml = '';

    $sResultHtml .= '
      <div class="btn-group">
        <a data-action="currencies" data-animate_class="animate__flipInY" data-elem=".client" data-form="form" href="javascript:;" class="btn btn-dark content_loader_show">
          <span class="_icon"><i class="fas fa-plus-circle"></i></span>
          <span class="_icon">' . $oLang->get("Add") . '</span>
        </a>
      </div>
      ';

    notification::send( $sResultHtml );
    break;

  case 'form': # Форма добавления / редактирования
    if ( ! $oLock->check('CurrencyFrom') ) notification::error($oLang->get('AccessesDenied'));

    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oCurrency = new currency( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oCurrency = new currency();
      // Случайное имя для корректной работы
      $arrDefaultsNames = array(
        'Currencies for reflection',
        'The best way',
        'Good name, good job',
      );
      // Создаем элемент
      $oCurrency->title = $arrDefaultsNames[array_rand($arrDefaultsNames, 1)];
      $oCurrency->user_id = $_SESSION['user']['id'];
      $oCurrency->active = 1;
      $oCurrency->add();
    }
    // Поля для добавления
    $oForm->arrFields = $oCurrency->fields();
    $oForm->arrFields['form'] = ['value'=>'save','type'=>'hidden'];
    $oForm->arrFields['action'] = ['value'=>'currencies','type'=>'hidden'];
    $oForm->arrFields['app'] = ['value'=>'app','type'=>'hidden'];
    $oForm->arrFields['session'] = ['value'=>$_SESSION['session'],'type'=>'hidden'];

    // Настройки шаблона
    $oForm->arrTemplateParams['id'] = 'content_loader_save';
    $oForm->arrTemplateParams['title'] = $oLang->get('Currencies');
    $oForm->arrTemplateParams['button'] = 'Save';
    $sFormHtml = $oForm->show();

    // Вывод результата
    $arrResults['form'] = $sFormHtml;
    $arrResults['data'] = $oCurrency->get_currency();

    $arrResults['action'] = 'currencies';
    notification::send($arrResults);
    break;

  case 'show': # Вывод элементов
    if ( ! $oLock->check('CurrencyShow') ) notification::error($oLang->get('AccessesDenied'));

    $oCurrency = new currency( $_REQUEST['id'] );
    if ( $_REQUEST['from'] ) $oCurrency->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oCurrency->limit = $_REQUEST['limit'];

    // Показ не активных
    $oFilter = new filter();
    $oCurrency->query .= $oFilter->get();

    $arrCurrents = $oCurrency->get_currencies();
    notification::send($arrCurrents);
    break;

  case 'save': # Сохранение изменений
    if ( ! $oLock->check('CurrencySave') ) notification::error($oLang->get('AccessesDenied'));

    $arrResult = [];
    $oCurrency = new currency( $_REQUEST['id'] );
    $oCurrency->arrAddFields = $_REQUEST;
    if ( $_REQUEST['id'] ) $oCurrency->save();
    else $oCurrency->add();

    $arrResult['data'] = $oCurrency->get_currency();

    if ( $_REQUEST['id'] ) $arrResult['event'] = 'save';
    else $arrResult['event'] = 'add';

    $arrResult['text'] = $oLang->get("ChangesSaved");
    notification::success($arrResult);
    break;

  case 'del': # Удаление
    if ( ! $oLock->check('CurrencyDel') ) notification::error($oLang->get('AccessesDenied'));

    $oCurrency = new currency( $_REQUEST['id'] );
    $oCurrency->del();
    break;
}
