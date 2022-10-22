<?
/**
 * Money
 */
class money extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $title = '';
  public static $project_id = '';
  public static $task_id = '';
  public static $price = '';
  public static $card = '';
  public static $category = '';
  public static $date = '';
  public static $type = ''; # хз пока что
  public static $value = ''; # minus - затраты
  public static $user_id = '';
  public static $to_card = ''; # на карту
  public static $subscription = ''; # на подписку
  public static $protected = ''; # защещённые данные

  public function get_money( $arrMoney = [] ){
    if ( ! $arrMoney['id'] ) $arrMoney = $this->get();
    $oLock = new lock();

    if ( $this->show_currency ) {
      $this->show_card = true;
      $this->show_to_card = true;
    }

    $arrDate = explode(' ', $arrMoney['date']);
    $arrMoney['date'] = $arrDate[0];
    $arrMoney['price'] = substr($arrMoney['price'], 0, -2);

    if ( $this->show_card )
    if ( (int)$arrMoney['card'] ) {
      $oCard = new card( $arrMoney['card'] );
      $arrMoney['card_val'] = (array)$oCard;
      $arrMoney['card_show'] = 'true';

      if ( $this->show_currency && $oLock->check('Currency') ) {
        $oCurrency = new currency();
        $arrMoney['currency_user'] = $oCurrency->get_currency_user();
        if ( $arrMoney['currency_user'] != $oCard->currency ) {
          $arrMoney['currency_price'] = round($arrMoney['price']);
          $arrMoney['currency_card'] = $oCard->currency;

          $arrMoney['price'] = $arrMoney['price'] / $oCurrency->get_val( $oCard->currency );
          $arrMoney['price'] = round($arrMoney['price']);
        }
        else $arrMoney['currency_user'] = '';
      }
    }

    if ( $this->show_to_card )
    if ( (int)$arrMoney['to_card'] ) {
      $oCardTo = new card( $arrMoney['to_card'] );
      $arrMoney['cardto_val'] = (array)$oCardTo;
      $arrMoney['cardto_show'] = 'true';

      if ( $this->show_currency && $oLock->check('Currency') ) {
        $oCurrency = new currency();
        $arrMoney['currency_user'] = $oCurrency->get_currency_user();
        if ( $arrMoney['currency_user'] != $oCardTo->currency ) {
          $arrMoney['currency_price'] = round($arrMoney['price']);
          $arrMoney['currency_card'] = $oCardTo->currency;

          $arrMoney['price'] = $arrMoney['price'] / $oCurrency->get_val( $oCardTo->currency );
          $arrMoney['price'] = round($arrMoney['price']);
        }
        else $arrMoney['currency_user'] = '';
      }
    }

    if ( $this->show_category )
    if ( (int)$arrMoney['category'] ) {
      $oCategory = new category( $arrMoney['category'] );
      $arrMoney['category_show'] = 'true';
      $arrMoney['categroy_val'] = $oCategory->get_category();
      $oLang = new lang();
      $arrMoney['categroy_val']['title'] = $oLang->get($arrMoney['categroy_val']['title']);
    }

    if ( $this->show_project )
    if ( (int)$arrMoney['project_id'] ) {
      $arrMoney['project_show'] = 'true';
      $oProject = new project( $arrMoney['project_id'] );
      $arrMoney['project_val'] = (array)$oProject;
    }

    if ( $this->show_task )
    if ( (int)$arrMoney['task_id'] ) {
      $arrMoney['task_show'] = 'true';
      $oTask = new task( $arrMoney['task_id'] );
      $arrMoney['task_val'] = (array)$oTask;
    }

    if ( $this->show_subscription )
    if ( (int)$arrMoney['subscription'] ) {
      $oSubscription = new subscription( $arrMoney['subscription'] );
      $arrMoney['subscription_val'] = (array)$oSubscription;
      $arrMoney['subscription_show'] = 'true';
    }

    return $arrMoney;
  }

  function get_moneys(){
    $arrMoneys = $this->get();
    if ( $arrMoneys['id'] ) $arrMoneys = $this->get_money( $arrMoneys );
    else foreach ($arrMoneys as &$arrMoney) $arrMoney = $this->get_money($arrMoney);
    return $arrMoneys;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры
    $arrFields['user_id'] = ['title'=>$oLang->get('User'),'type'=>'hidden','value'=>$_SESSION['user']['id']];

    $arrTypeFilter = [
      array('id'=>1,'name'=>$oLang->get('Spend')),
      array('id'=>2,'name'=>$oLang->get('Replenish')),
    ];
    $arrFields['type'] = ['class'=>'switch','title'=>$oLang->get('Type'),'type'=>'select','options'=>$arrTypeFilter,'value'=>$this->type];

    $arrFields['title'] = ['title'=>$oLang->get('Title'),'type'=>'text','value'=>$this->title,'plaseholder'=>$oLang->get('Title')];
    $arrFields['price'] = ['title'=>$oLang->get('Price'),'type'=>'number','value'=>substr($this->price, 0, -2),'step'=>'0.01'];

    $arrFields['date'] = ['title'=>$oLang->get('Date'),'type'=>'date','value'=>$this->date];

    $oCard = new card();
    $oCard->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
    $oCard->active = 1;
    $oCard->sortname = 'sort';
    $oCard->sortdir = 'ASC';
    $arrCards = $oCard->get_cards();
    $arrCardsFilter = [];
    $arrCardsFilter[] = array('id'=>0,'name'=>'...');
    foreach ($arrCards as $arrCard) $arrCardsFilter[] = array('id'=>$arrCard['id'],'name'=>$arrCard['title'],'color'=>$arrCard['color']);
    $arrFields['card'] = ['class'=>'switch_values switch_type-1','title'=>$oLang->get('FromCard'),'type'=>'select','options'=>$arrCardsFilter,'value'=>$this->card];
    $arrFields['to_card'] = ['title'=>$oLang->get('ToCard'),'type'=>'select','options'=>$arrCardsFilter,'value'=>$this->to_card];

    $oSubscription = new subscription();
    $oSubscription->sortname = 'sort';
    $oSubscription->sortdir = 'ASC';
    $oSubscription->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
    $oSubscription->active = true;
    $arrSubscriptions = $oSubscription->get_subscriptions();
    $arrSubscriptionsFilter = [];
    $arrSubscriptionsFilter[] = array('id'=>0,'name'=>'...');
    if ( $this->subscription_id ) {
      $oSubscription = new subscription( $this->subscription_id );
      $arrSubscriptionsFilter[] = array('id'=>$oSubscription->id,'name'=>$oSubscription->title);
    }
    foreach ($arrSubscriptions as $arrSubscription) {
      if ( $this->subscription_id && $arrSubscription['id'] == $this->subscription_id ) continue;
      $arrSubscriptionsFilter[] = array('id'=>$arrSubscription['id'],'name'=>$arrSubscription['title']);
    }
    $arrFields['subscription'] = ['section'=>2,'class'=>'switch_values switch_type-1','title'=>$oLang->get('Subscription'),'type'=>'select','options'=>$arrSubscriptionsFilter,'value'=>$this->subscription];

    $oCategory = new category();
    $oCategory->sortname = 'sort';
    $oCategory->sortdir = 'ASC';
    $oCategory->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . ' OR `user_id` = 0)';
    $oCategory->query .= ' AND `active` > 0';
    $arrCategories = $oCategory->get_categories();
    if ( $this->category ) $arrCategories = $oCategory->ckeck_categories($this->category,$arrCategories);
    // Берём конфики костомных категорий пользователя
    $oCategoryConf = new category_config();
    $arrCategories = $oCategoryConf->update_categories($arrCategories);
    // Вычищаем не активные
    $arrCategories = $oCategoryConf->update_categories_active($arrCategories);
    $arrCategoriesFilter = [];
    // $arrCategoriesFilter[] = array('id'=>0,'name'=>'...');
    foreach ($arrCategories as $arrCategory) $arrCategoriesFilter[] = array('id'=>$arrCategory['id'],'name'=>$arrCategory['title'],'color'=>$arrCategory['color']);
    $iSelectCategory = $this->category ? $this->category : 1;
    $arrFields['category'] = ['title'=>$oLang->get('Category'),'type'=>'select','options'=>$arrCategoriesFilter,'search'=>true,'value'=>$iSelectCategory];

    $oProject = new project();
    $oProject->sortname = 'sort';
    $oProject->sortdir = 'ASC';
    $oProject->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
    $arrProjects = $oProject->get();
    $arrProjectsFilter[] = array('id'=>0,'name'=>'...');
    $bProject = false;
    foreach ($arrProjects as $arrProject) {
      $arrProjectsFilter[] = array('id'=>$arrProject['id'],'name'=>$arrProject['title'],'color'=>$arrProject['color']);
      if ( $arrProject['id'] == $this->project_id ) $bProject = true;
    }
    if ( ! $bProject ) {
      $oProject = new project( $this->project_id );
      $arrProjectsFilter[] = array('id'=>$oProject->id,'name'=>$oProject->title,'color'=>$oProject->color);
    }
    $arrFields['project_id'] = ['section'=>2,'title'=>$oLang->get('Project'),'type'=>'select','options'=>$arrProjectsFilter,'search'=>true,'value'=>$this->project_id];

    $oTask = new task();
    $oTask->sortname = 'sort';
    $oTask->sortdir = 'ASC';
    $oTask->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
    $oTask->query .= ' AND `status` = 2';
    $oTask->active = true;
    $arrTasks = $oTask->get_tasks();

    $arrTaskId = [];
    $arrTasksFilter[] = array('id'=>0,'name'=>'...');
    $arrTaskCurrent = [];
    if ( (int)$this->task_id ) {
      $oTaskCurrent = new task( $this->task_id );
      $arrTaskCurrent = $oTaskCurrent->get_tasks();
      $arrTasksFilter[] = array('id'=>$arrTaskCurrent['id'],'name'=>$arrTaskCurrent['title']);
    }
    foreach ( $arrTasks as $arrTask ) {
      if ( $arrTaskCurrent['id'] && $arrTaskCurrent['id'] == $arrTask['id'] ) continue;
      $arrTasksFilter[] = array('id'=>$arrTask['id'],'name'=>$arrTask['title']);
    }
    $arrFields['task_id'] = ['section'=>2,'title'=>$oLang->get('Task'),'type'=>'select','options'=>$arrTasksFilter,'search'=>true,'value'=>$this->task_id];

    $arrFields['description'] = ['section'=>2,'title'=>$oLang->get('Description'),'type'=>'textarea','value'=>$this->description,'plaseholder'=>$oLang->get('Description')];

    // $arrFields['active'] = ['title'=>$oLang->get('Active'),'type'=>'hidden','value'=>$this->active];

    return $arrFields;
  }

  function __construct( $money_id = 0 )
  {
    $this->table = 'moneys';

    if ( $money_id ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $money_id . "'";
      $arrMoney = db::query($mySql);

      $this->id = $arrMoney['id'];
      $this->title = $arrMoney['title'];
      $this->project_id = $arrMoney['project_id'];
      $this->task_id = $arrMoney['task_id'];
      $this->price = $arrMoney['price'];
      $this->card = $arrMoney['card'];
      $this->category = $arrMoney['category'];
      $this->date = $arrMoney['date'];
      $this->type = $arrMoney['type'];
      $this->value = $arrMoney['value'];
      $this->user_id = $arrMoney['user_id'];
      $this->to_card = $arrMoney['to_card'];
      $this->subscription = $arrMoney['subscription'];
      $this->protected = $arrMoney['protected'];
    }
  }
}
