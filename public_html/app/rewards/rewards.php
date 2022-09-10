<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'actions': # Элементы управления
    $sResultHtml = '';
    $sResultHtml .= '
      <div class="btn-group">
        <a data-action="rewards" data-animate_class="animate__flipInY" data-elem=".reward" data-form="form" data-filter="true" href="javascript:;" class="btn btn-dark content_loader_show">
          <span class="_icon"><i class="fas fa-plus-circle"></i></span>
          <span class="_text">' . $oLang->get("Add") . '</span>
        </a>
      </div>
      ';

    notification::send( $sResultHtml );
    break;

  case 'show': # Вывод элементов
    if ( ! $oLock->check('RewardsShow') ) notification::error($oLang->get('AccessesDenied'));

    $oReward = $_REQUEST['id'] ? new reward( $_REQUEST['id'] ) : new reward();

    if ( $_REQUEST['from'] ) $oReward->from = $_REQUEST['from'];
    if ( $_REQUEST['limit'] ) $oReward->limit = $_REQUEST['limit'];

    $arrRewards = $oReward->get_rewards();

    notification::send($arrRewards);
    break;

  case 'form': # Форма добавления / редактирования
    if ( ! $oLock->check('RewardsForm') ) notification::error($oLang->get('AccessesDenied'));

    // Параметры
    $arrResults = [];
    $oForm = new form();

    // Если редактировани
    if ( $_REQUEST['id'] ) {
      $arrResults['event'] = 'edit';
      $oReward = new reward( $_REQUEST['id'] );
    }
    // Если добавление
    else {
      $arrResults['event'] = 'add';
      $oReward = new reward();
      $oReward->user_id = $_SESSION['user']['id'];
      $oReward->add();
    }

    // Поля для добавления
    $oForm->arrFields = $oReward->fields();

    $oForm->arrFields['form'] = ['value'=>'save','type'=>'hidden'];
    $oForm->arrFields['action'] = ['value'=>'rewards','type'=>'hidden'];
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
    $arrResults['data'] = $oReward->get_rewards();
    $arrResults['action'] = 'rewards';

    notification::send($arrResults);
    break;

  case 'save': # Сохранение изменений
    if ( ! $oLock->check('RewardsSave') ) notification::error($oLang->get('AccessesDenied'));

    $arrResult = [];
    $oReward = $_REQUEST['id'] ? new reward( $_REQUEST['id'] ) : new reward();
    $oReward->arrAddFields = $_REQUEST;

    // Если копирование
    if ( isset($_REQUEST['content_loader_copy']) ) {
      unset($oReward->arrAddFields['id']);
    }

    if ( $_REQUEST['id'] && ! isset($_REQUEST['content_loader_copy']) ) {
      $arrResult['event'] = 'save';
      $oReward->save();
    }
    else {
      $arrResult['event'] = 'add';
      $oReward->add();
    }

    $oReward = new reward( $oReward->id );
    $arrResult['data'] = $oReward->get_rewards();
    $arrResult['text'] = $oLang->get("ChangesSaved");

    notification::success($arrResult);
    break;

  case 'del': # Удаление
    if ( ! $oLock->check('RewardsDel') ) notification::error($oLang->get('AccessesDenied'));

    $oReward = new reward( $_REQUEST['id'] );
    $oReward->del();
    $arrResult = [];
    $arrResult['event'] = 'del';
    $arrResult['text'] = $oLang->get("DeleteSuccess");
    notification::success($arrResult);
    break;
}
