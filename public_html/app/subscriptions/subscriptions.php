<?
// Язык
$olang = new lang();

// Валюты
$oLock = new lock();
$bCurrency = $oLock->check('Currency');
$oCurrency = new currency();
$sCurrencyUser = $oCurrency->get_currency_user();

switch ($_REQUEST['form']) {
  case 'actions': # Элементы управления
    $sResultHtml = '';
    $sResultHtml .= '
      <div class="btn-group">
        <a data-action="subscriptions" data-animate_class="animate__flipInY" data-elem=".money_subscription" data-form="form" href="javascript:;" class="btn btn-dark content_loader_show">
          <span class="_icon"><i class="fas fa-plus-circle"></i></span>
          <span class="_text">' . $oLang->get("Add") . '</span>
        </a>
      </div>
      ';

    notification::send( $sResultHtml );
    break;

  case 'month': # Статистика за месяц
    $arrResults = [];

    // Месяц
    $iYear = (int)$_REQUEST['year'] ? $_REQUEST['year'] : date('Y');
    $iMonth = (int)$_REQUEST['month'] ? $_REQUEST['month'] : date('m');
    $dMonth = $iYear . '-' . sprintf("%02d", $iMonth);

    // Подписки
    $oSubscription = new subscription();
    $oSubscription->show_card = true;
    $oSubscription->show_category = true;
    $oSubscription->show_paid = true;
    $oSubscription->show_currency = true;
    $oSubscription->active = true;
    $oSubscription->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
    $oSubscription->sDateQuery = $dMonth;
    // $oSubscription->show_query = true;
    $arrResults['subscriptions'] = $oSubscription->get_subscriptions();
    $arrResults['subscriptions_sum'] = 0;
    foreach ( $arrResults['subscriptions'] as $arrSubscription ) {
      if ( ! $arrSubscription['paid'] ) {
        if ( $arrSubscription['paid_sum'] ) $arrResults['subscriptions_sum'] = (int)$arrResults['subscriptions_sum'] + (int)$arrSubscription['paid_need'];
        else $arrResults['subscriptions_sum'] = (int)$arrResults['subscriptions_sum'] + (int)$arrSubscription['price'];
      }
    }
    $arrResults['subscriptions_sum'] = floor($arrResults['subscriptions_sum']);

    notification::send($arrResults);
    break;

  case 'show': # Вывод элементов
    $oSubscription = new subscription( $_REQUEST['id'] );
    $oSubscription->show_card = true;
    $oSubscription->show_category = true;
    $oSubscription->show_paid = true;
    $oSubscription->show_currency = true;

    if ( $_REQUEST['from'] ) $oSubscription->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oSubscription->limit = $_REQUEST['limit'];

    $oSubscription->sortname = 'sort';
    $oSubscription->sortdir = 'ASC';
    $oSubscription->query = ' AND `user_id` = ' . $_SESSION['user']['id'];

    // Показ не активных
    $oFilter = new filter();
    // $oCategory->query .= $oFilter->get();
    if ( ! $oFilter->get_val('no_active_show') ) $oSubscription->active = true;

    $arrSubscriptions = $oSubscription->get_subscriptions();

    notification::send($arrSubscriptions);
    break;

  case 'analytics_month': # Статистика за месяц
    $arrResults = [];

    // Месяц
    $iYear = (int)$_REQUEST['year'] ? $_REQUEST['year'] : date('Y');
    $iMonth = (int)$_REQUEST['month'] ? $_REQUEST['month'] : date('m');
    $dMonth = $iYear . '-' . sprintf("%02d", $iMonth);
    $dDay = date('d');

    // Подписки
    $oSubscription = new subscription();
    $oSubscription->show_currency = true;
    $oSubscription->sDateQuery = $dMonth;
    $arrResults = $oSubscription->get_month();


    if ( ! count($arrResults['subscriptions']) ) {
      ob_start();
      ?>
        <div class="block_text_alert">
          <div class="_icon">
            <i class="fa-regular fa-face-smile"></i>
          </div>
          <div class="">
            <p>
              <?=$oLang->get('SubscriptionSumClear')?>
            </p>
          </div>
        </div>
      <?
      $sResultHtml = ob_get_contents();
      ob_end_clean();
      return $sResultHtml;
    }

    ob_start();
    ?>
    <div class="_analytics_date">
      <div class="_year">
        <?=$iYear?>
      </div>
      <div class="_sep">
        .
      </div>
      <div class="_month">
        <?=$iMonth?>
      </div>
      <?php if ( $iYear == date('Y') && $iMonth == date('m') ): ?>
        <div class="_sep">
          .
        </div>
        <div class="_day">
          <?=$dDay?>
        </div>
      <?php endif; ?>
    </div>

    <div class="_analytics_sums">
      <div class="_sum">
        <strong><?=$arrResults['subscriptions_sum']?></strong>
        <small><?=$oLang->get('SubscriptionSum')?></small>
      </div>

      <div class="_sum_paid">
        <strong><?=$arrResults['subscriptions_sum_paid']?></strong>
        <small><?=$oLang->get('SubscriptionSumPaid')?></small>
      </div>

      <div class="_sum_need">
        <strong><?=$arrResults['subscriptions_sum_need']?></strong>
        <small><?=$oLang->get('SubscriptionSumNeedPaid')?></small>
      </div>
    </div>

    <div class="_analytics_sep">
      <div class="_title">
        <?=$oLang->get('SubscriptionDayPaid')?>
      </div>
    </div>

    <?php foreach ( $arrResults['subscriptions_dates'] as $iDay => $arrSubscriptions ): ?>
      <?
      $sPassedClass = '';
      if ( $iYear == date('Y') && $iMonth == date('m') )
      if ( $dDay > $iDay ) $sPassedClass = '__passed';
      ?>
      <div class="_analytics_day <?=$sPassedClass?>">
        <div class="_date">
          <div class="_value">
            <?=$iDay?>
          </div>
        </div>

        <div class="_items">
          <?php foreach ($arrSubscriptions as $arrSubscription): ?>
            <?
            $sClassPaid = '';
            if ( (int)$arrSubscription['paid'] ) $sClassPaid = '__paid';
            ?>
            <div class="_item <?=$sClassPaid?>">
              <div class="_title">
                <?=$arrSubscription['title']?>

                <?php if ( $sClassPaid ): ?>
                  <div class="_icon">
                    <i class="fa-regular fa-circle-check"></i>
                  </div>
                <?php endif; ?>
              </div>

              <div class="_info">
                <div class="_price">
                  <small><?=$oLang->get('SubscriptionSum')?></small>
                  <?=$arrSubscription['price']?>
                  <?php if ( $bCurrency ): ?>
                    <span style="opacity: .7;">
                      <?=$sCurrencyUser?>
                    </span>
                  <?php endif; ?>
                </div>


                <?php if ( (int)$arrSubscription['paid_sum'] ): ?>
                  <div class="_paid">
                    <small><?=$oLang->get('SubscriptionSumPaid')?></small>
                    <?=$arrSubscription['paid_sum']?>
                    <?php if ( $bCurrency ): ?>
                      <span style="opacity: .7;">
                        <?=$sCurrencyUser?>
                      </span>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>

                <?php if ( (int)$arrSubscription['paid_need'] ): ?>
                  <div class="_need">
                    <small><?=$oLang->get('SubscriptionSumNeedPaid')?></small>
                    <?=$arrSubscription['paid_need']?>
                    <?php if ( $bCurrency ): ?>
                      <span style="opacity: .7;">
                        <?=$sCurrencyUser?>
                      </span>
                    <?php endif; ?>
                  </div>
                <?php endif; ?>

                <?php if ( isset($arrSubscription['currency_price']) && $bCurrency ): ?>
                  <div class="_currency">
                    <div class="badge bg-secondary _currency d-none<?=$arrSubscription['currency_user']?> mx-2">
                      <?=$arrSubscription['currency_price']?>
                      <span style="opacity: .7; font-size: .8em; font-weight: normal;">
                        <?=$arrSubscription['currency']?>
                      </span>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <?
    $sResultHtml = ob_get_contents();
    ob_end_clean();
    die($sResultHtml);
    return $sResultHtml;
    break;

  case 'form': # Форма добавления / редактирования
    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oSubscription = new subscription( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oSubscription = new subscription();
      // Случайное имя для корректной работы
      $arrDefaultsNames = array(
        'Money for reflection',
        'The best way',
        'Good name, good job',
      );
      // Создаем элемент
      $oSubscription->title = $arrDefaultsNames[array_rand($arrDefaultsNames, 1)];
      $oSubscription->user_id = $_SESSION['user']['id'];
      $oSubscription->add();
      $oSubscription->active = 1;
      $oSubscription->save();
    }

    // Поля для добавления
    $oForm->arrFields = $oSubscription->fields();

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
    if ( $_REQUEST['data'] && $_REQUEST['data']['success_click'] ) $oForm->arrFields['success_click'] = ['value'=>$_REQUEST['data']['success_click'],'type'=>'hidden'];
    $oForm->arrFields['action'] = ['value'=>'subscriptions','type'=>'hidden'];
    $oForm->arrFields['app'] = ['value'=>'app','type'=>'hidden'];
    $oForm->arrFields['session'] = ['value'=>$_SESSION['session'],'type'=>'hidden'];

    // Настройки шаблона
    $oForm->arrTemplateParams['id'] = 'content_loader_save';
    $oForm->arrTemplateParams['title'] = $olang->get('Subscriptions');
    $oForm->arrTemplateParams['button'] = 'Save';
    $sFormHtml = $oForm->show();

    // Вывод результата
    $arrResults['form'] = $sFormHtml;
    $arrResults['data'] = $oSubscription->get_subscriptions();
    $arrResults['action'] = 'subscriptions';

    notification::send($arrResults);
    break;

  case 'save': # Сохранение изменений
    $arrResult = [];
    $oSubscription = $_REQUEST['id'] ? new subscription( $_REQUEST['id'] ) : new subscription();
    $oSubscription->arrAddFields = $_REQUEST;

    if ( $_REQUEST['id'] ) {
      $arrResult['event'] = 'save';
      $oSubscription->save();
    }
    else {
      $arrResult['event'] = 'add';
      $oSubscription->add();
    }

    $oSubscription = new subscription( $oSubscription->id );
    $arrResult['data'] = $oSubscription->get_subscriptions();
    $arrResult['text'] = $olang->get('ChangesSaved');

    notification::success($arrResult);
    break;

  case 'del': # Удаление
    $oSubscription = new subscription( $_REQUEST['id'] );
    $oSubscription->del();
    break;
}
