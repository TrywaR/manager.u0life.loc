<?
$olang = new lang();

switch ($_REQUEST['form']) {
  case 'actions': # Элементы управления
    $sResultHtml = '';
    $sResultHtml .= '
      <div class="btn-group">
        <a data-action="moneys" data-animate_class="animate__flipInY" data-elem=".money" data-form="form" data-filter="true" href="javascript:;" class="btn btn-dark content_loader_show">
          <span class="_icon"><i class="fas fa-plus-circle"></i></span>
          <span class="_text">' . $oLang->get("Add") . '</span>
        </a>
      </div>
      ';

    notification::send( $sResultHtml );
    break;

  case 'show': # Вывод элементов
    $oMoney = new money( $_REQUEST['id'] );

    if ( $_REQUEST['from'] ) $oMoney->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oMoney->limit = $_REQUEST['limit'];

    $oMoney->show_card = true;
    $oMoney->show_to_card = true;
    $oMoney->show_category = true;
    $oMoney->show_project = true;
    $oMoney->show_task = true;
    $oMoney->show_subscription = true;

    $oMoney->sortMulti = ' `date` DESC, `date_update` DESC ';
    $oMoney->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];

    $oFilter = new filter();
    $oMoney->query .= $oFilter->get();

    $arrMoneys = $oMoney->get_moneys();

    notification::send($arrMoneys);
    break;

  case 'form': # Форма добавления / редактирования
    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oMoney = new money( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oMoney = new money();
      // Случайное имя для корректной работы
      // $arrDefaultsNames = array(
      //   'Money for reflection',
      //   'The best way',
      //   'Good name, good job',
      // );
      // // Создаем элемент
      // $oMoney->title = $arrDefaultsNames[array_rand($arrDefaultsNames, 1)];
      $oMoney->user_id = $_SESSION['user']['id'];
      $oMoney->date = date('Y-m-d');
      $oMoney->active = 1;
      $oMoney->add();
      $oMoney = new money( $oMoney->id );
    }

    // Поля для добавления
    $oForm->arrFields = $oMoney->fields();

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
    $oForm->arrFields['action'] = ['value'=>'moneys','type'=>'hidden'];
    $oForm->arrFields['app'] = ['value'=>'app','type'=>'hidden'];
    if ( $_REQUEST['data'] && $_REQUEST['data']['success_click'] ) $oForm->arrFields['success_click'] = ['value'=>$_REQUEST['data']['success_click'],'type'=>'hidden'];
    $oForm->arrFields['session'] = ['value'=>$_SESSION['session'],'type'=>'hidden'];

    // Настройки шаблона
    $oForm->arrTemplateParams['id'] = 'content_loader_save';
    $oForm->arrTemplateParams['title'] = $olang->get('Moneys');
    // $oForm->arrTemplateParams['button'] = 'Save';
    $oForm->arrTemplateParams['button_copy'] = true;
    $sFormHtml = $oForm->show();

    $oMoney->show_card = true;
    $oMoney->show_to_card = true;
    $oMoney->show_category = true;
    $oMoney->show_project = true;
    $oMoney->show_task = true;
    $oMoney->show_subscription = true;

    // Вывод результата
    $arrResults['form'] = $sFormHtml;
    $arrResults['data'] = $oMoney->get_money();
    $arrResults['action'] = 'moneys';

    notification::send($arrResults);
    break;

  case 'save': # Сохранение изменений
    $arrResult = [];
    $iPriceOld = 0;

    $oMoney = new money( $_REQUEST['id'] );
    $oMoney->arrAddFields['date_update'] = date("Y-m-d H:i:s");
    $oMoney->arrAddFields = $_REQUEST;

    // Если обновление цены
    if ( $oMoney->price ) $iPriceOld = $oMoney->price;

    // Если копирование
    if ( isset($_REQUEST['content_loader_copy']) ) {
      unset($oMoney->arrAddFields['id']);
    }

    // Определяем событие
    if ( $_REQUEST['id'] && ! isset($_REQUEST['content_loader_copy']) ) {
      $arrResult['event'] = 'save';
      $oMoney->save();
    }
    else {
      $arrResult['event'] = 'add';
      $oMoney->add();
    }

    // Получаем всю инфу об оплате
    $oMoney = new money( $oMoney->id );
    $oMoney->show_card = true;
    $oMoney->show_to_card = true;
    $oMoney->show_category = true;
    $oMoney->show_project = true;
    $oMoney->show_task = true;
    $oMoney->show_subscription = true;

    $arrResult['data'] = $oMoney->get_moneys();
    $arrResult['text'] = $oLang->get("ChangesSaved");

    // Обновление карты
    switch ( (int)$oMoney->type ) {
      case 1: # Расход
        if ( (int)$oMoney->card ) {
          $oCard = new card( $oMoney->card );
          $oCard->balance_remove( $oMoney->price );
          if ( (int)$oMoney->category == 2 ) $oCard->commission_add( $oMoney->price ); # Если комиссия
          if ( $iPriceOld ) $oCard->balance_add( $iPriceOld ); # Возвращяем то что было, если обновление
        }

        if ( (int)$oMoney->to_card ) {
          $oCardTo = new card( $oMoney->to_card );
          $oCardTo->balance_add( $oMoney->price );
          if ( (int)$oMoney->category == 2 ) $oCardTo->commission_remove( $oMoney->price ); # Если комиссия
          if ( $iPriceOld ) $oCardTo->balance_remove( $iPriceOld ); # Возвращяем то что было, если обновление
        }
        break;

      case 2: # Зачисления
        if ( (int)$oMoney->to_card ) {
          $oCardTo = new card( $oMoney->to_card );
          $oCardTo->balance_add( $oMoney->price );
          if ( (int)$oMoney->category == 2 ) $oCardTo->commission_remove( $oMoney->price ); # Если комиссия
          if ( $iPriceOld ) $oCardTo->balance_remove( $iPriceOld ); # Возвращяем то что было, если обновление
        }
        break;
    }


    notification::success($arrResult);
    break;

  case 'del': # Удаление
    $oMoney = new money( $_REQUEST['id'] );
    $arrResult = [];

    // Обновление карты
    switch ( (int)$oMoney->type ) {
      case 1: # Расход
        if ( (int)$oMoney->card ) {
          $oCard = new card( $oMoney->card );
          $oCard->balance_add( $oMoney->price );
          if ( (int)$oMoney->category == 2 ) $oCard->commission_remove( $oMoney->price ); # Если комиссия
        }
        if ( (int)$oMoney->to_card ) {
          $oCardTo = new card( $oMoney->to_card );
          $oCardTo->balance_remove( $oMoney->price );
          if ( (int)$oMoney->category == 2 ) $oCardTo->commission_add( $oMoney->price ); # Если комиссия
        }
        break;
      case 1: # Доход
        if ( (int)$oMoney->card ) {
          $oCardTo = new card( $oMoney->to_card );
          $oCardTo->balance_remove( $oMoney->price );
          if ( (int)$oMoney->category == 2 ) $oCardTo->commission_add( $oMoney->price ); # Если комиссия
        }
        break;
    }

    $oMoney->del();
    $arrResult['event'] = 'del';
    $arrResult['text'] = $oLang->get("DeleteSuccess");
    notification::success($arrResult);
    break;
}
