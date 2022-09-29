<?
/**
 * subscription
 */
class subscription extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $title = '';
  public static $sort = '';
  public static $price = '';
  public static $time = '';
  public static $sum = '';
  public static $type = '';
  public static $arrTypes = '';
  public static $day = '';
  public static $category = '';
  public static $card = '';
  public static $active = '';
  public static $user_id = '';
  public static $description = '';
  public static $sDateQuery = '';

  public function get_subscription( $arrSubscription = [] ){
    if ( ! $arrSubscription['id'] ) $arrSubscription = $this->get();

    if ( (int)$arrSubscription['active'] ) $arrSubscription['active_show'] = 'true';
    else $arrSubscription['active_show'] = 'false';

    if ( (int)$arrSubscription['user_id'] ) $arrSubscription['edit_show'] = 'true';

    // Показ карты
    if ( $this->show_card )
    if ( (int)$arrSubscription['card'] ) {
      $oCard = new card( $arrSubscription['card'] );
      $arrSubscription['card_val'] = (array)$oCard;
      $arrSubscription['card_show'] = 'true';
    }

    // Показать карты
    if ( $this->show_category )
    if ( (int)$arrSubscription['category'] ) {
      $oCategory = new category( $arrSubscription['category'] );
      $arrSubscription['category_val'] = (array)$oCategory;
    }

    $arrSubscription['price'] = ceil(substr($arrSubscription['price'], 0, -2));
    $arrSubscription['sum'] = ceil(substr($arrSubscription['sum'], 0, -2));

    // Показать оплаты
    if ( $this->show_paid ) {
      $arrPaids = $this->get_subscription_month_paids( $arrSubscription['id'] );

      if ( count($arrPaids['data']) ) {
        $arrSubscription['paid_sum'] = ceil($arrPaids['sum']);
        $iSubscriptionNeed = ceil((int)$arrSubscription['price'] - (int)$arrPaids['sum']);
        if ( (int)$iSubscriptionNeed > 1 ) {
          $arrSubscription['paid_need'] = $iSubscriptionNeed;
          $arrSubscription['paid_need_show'] = 'true';
        }
        if ( ! (int)$arrSubscription['paid_need'] ) $arrSubscription['paid'] = true;
        $arrSubscription['paid_show'] = 'true';
      }
    }

    return $arrSubscription;
  }

  public function get_subscriptions(){
    $arrSubscriptions = $this->get();
    if ( $arrSubscriptions['id'] ) $arrSubscriptions = $this->get_subscription( $arrSubscriptions );
    else foreach ($arrSubscriptions as &$arrSubscription) $arrSubscription = $this->get_subscription($arrSubscription);
    return $arrSubscriptions;
  }

  public function get_month(){
    $oSubscription = new subscription();
    $oSubscription->show_paid = true;
    $oSubscription->active = true;
    $oSubscription->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
    $oSubscription->sDateQuery = $this->sDateQuery;
    // $oSubscription->show_query = true;

    $arrResults['subscriptions'] = $oSubscription->get_subscriptions();
    $arrResults['subscriptions_dates'] = [];
    $arrResults['subscriptions_sum'] = 0;
    $arrResults['subscriptions_sum_need'] = 0;
    $arrResults['subscriptions_sum_paid'] = 0;

    foreach ( $arrResults['subscriptions'] as & $arrSubscription ) {
      // Считаем сумму, которую нужно заплатить
      $arrResults['subscriptions_sum'] = (int)$arrResults['subscriptions_sum'] + (int)$arrSubscription['price'];

      // Сумма которая оплачена
      $arrResults['subscriptions_sum_paid'] = (int)$arrResults['subscriptions_sum_paid'] + (int)$arrSubscription['paid_sum'];

      // Сумма которая ещё нужна
      if ( (int)$arrSubscription['paid_sum'] ) {
        if ( $arrSubscription['paid_need'] )
          $arrResults['subscriptions_sum_need'] = (int)$arrResults['subscriptions_sum_need'] + (int)$arrSubscription['paid_need'];
      }
      else
        $arrResults['subscriptions_sum_need'] = (int)$arrResults['subscriptions_sum_need'] + (int)$arrSubscription['price'];

      // По датам
      switch ( (int)$arrSubscription['type'] ) {
        case 0:
          $arrResults['subscriptions_dates'][$arrSubscription['day']][] = $arrSubscription;
          break;
      }
    }

    // Сортировка
    ksort($arrResults['subscriptions_dates']);

    // Округление
    $arrResults['subscriptions_sum'] = floor($arrResults['subscriptions_sum']);
    $arrResults['subscriptions_sum_need'] = floor($arrResults['subscriptions_sum_need']);
    $arrResults['subscriptions_sum_paid'] = floor($arrResults['subscriptions_sum_paid']);

    return $arrResults;
  }

  // Оплаты за месяц по подписке
  public function get_subscription_month_paids( $iSubscriptionId = 0 ){
    if ( ! $iSubscriptionId ) $iSubscriptionId = $this->id;

    $arrResult = [];
    $arrResult['data'] = [];

    // За месяц
    $oMoney = new money();
    $dCurrentDate = $this->sDateQuery != '' ? $this->sDateQuery : date('Y-m');
    $oMoney->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
    $oMoney->query .= " AND `date` LIKE '" . $dCurrentDate . "%'";
    $oMoney->query .= " AND `type` = '1'";
    $oMoney->query .= " AND `subscription` = '" . $iSubscriptionId . "'";
    $arrMoneys = $oMoney->get_moneys();

    $iMonthSum = 0;
    if ( is_array($arrMoneys) ) {
      if ( $arrMoneys['id'] ) {
        $arrResult['data'][] = $arrMoney;
        $iMonthSum = (float)$arrMoney['price'] + (float)$iMonthSum;
      }
      else foreach ($arrMoneys as $arrMoney) {
        $arrResult['data'][] = $arrMoney;
        $iMonthSum = (float)$arrMoney['price'] + (float)$iMonthSum;
      }
    }
    $arrResult['sum'] = $iMonthSum;

    return $arrResult;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры
    $arrFields['user_id'] = ['title'=>$oLang->get('User'),'type'=>'hidden','value'=>$_SESSION['user']['id']];

    $arrFields['type'] = ['class'=>'switch','title'=>$oLang->get('Type'),'type'=>'select','options'=>$this->arrTypes,'value'=>$this->type];
    $arrFields['day'] = ['title'=>$oLang->get('PaymentDay'),'type'=>'number','value'=>$this->day];

    // $oCard = new card();
    // $oCard->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
    // $oCard->sortname = 'sort';
    // $oCard->sortdir = 'ASC';
    // $arrCards = $oCard->get();
    // $arrCardsFilter = [];
    // $arrCardsFilter[] = array('id'=>0,'name'=>'...');
    // foreach ($arrCards as $arrCard) $arrCardsFilter[] = array('id'=>$arrCard['id'],'name'=>$arrCard['title'],'color'=>$arrCard['color']);
    // $arrFields['card'] = ['class'=>'switch_values switch_type-0','title'=>$oLang->get('FromCard'),'type'=>'select','options'=>$arrCardsFilter,'value'=>$this->card];

    $oCard = new card();
    $oCard->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
    $oCard->active = 1;
    $oCard->sortname = 'sort';
    $oCard->sortdir = 'ASC';
    $arrCards = $oCard->get_cards();
    $arrCardsFilter = [];
    $arrCardsFilter[] = array('id'=>0,'name'=>'...');
    foreach ($arrCards as $arrCard) $arrCardsFilter[] = array('id'=>$arrCard['id'],'name'=>$arrCard['title'],'color'=>$arrCard['color']);
    $arrFields['card'] = ['title'=>$oLang->get('FromCard'),'type'=>'select','options'=>$arrCardsFilter,'value'=>$this->card];
    $arrFields['to_card'] = ['title'=>$oLang->get('ToCard'),'type'=>'select','options'=>$arrCardsFilter,'value'=>$this->to_card];

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
    foreach ($arrCategories as $arrCategory) $arrCategoriesFilter[] = array('id'=>$arrCategory['id'],'name'=>$arrCategory['title'],'color'=>$arrCategory['color']);
    $arrFields['category'] = ['title'=>$oLang->get('Category'),'type'=>'select','options'=>$arrCategoriesFilter,'search'=>true,'value'=>$this->category];

    $arrFields['title'] = ['title'=>$oLang->get('Title'),'type'=>'text','required'=>'required','value'=>$this->title];
    $arrFields['sort'] = ['title'=>$oLang->get('Sort'),'type'=>'number','value'=>$this->sort];

    // $arrFields['sum'] = ['title'=>$oLang->get('Sum'),'type'=>'number','value'=>$this->sum];
    $arrFields['price'] = ['title'=>$oLang->get('Payment'),'type'=>'number','value'=>substr($this->price, 0, -2),'step'=>'0.01'];
    $arrFields['sum'] = ['title'=>$oLang->get('Sum'),'type'=>'number','value'=>substr($this->sum, 0, -2),'step'=>'0.01'];

    $arrFields['description'] = ['title'=>$oLang->get('Description'),'type'=>'textarea','value'=>$this->description];

    // $sColor = $this->color ? $this->color : sprintf( '#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255) );
    // $arrFields['color'] = ['title'=>$oLang->get('Color'),'type'=>'color','value'=>$sColor];

    $arrFields['active'] = ['title'=>$oLang->get('Active'),'type'=>'checkbox','value'=>$this->active];

    return $arrFields;
  }

  function __construct( $iSubscriptionsId = 0 )
  {
    $oLang = new lang();
    $this->table = 'subscriptions';

    if ( $iSubscriptionsId ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $iSubscriptionsId . "'";
      $arrSubscription = db::query($mySql);

      $this->id = $arrSubscription['id'];
      $this->title = $arrSubscription['title'];
      $this->sort = $arrSubscription['sort'];
      $this->price = $arrSubscription['price'];
      $this->time = $arrSubscription['time'];
      $this->sum = $arrSubscription['sum'];
      $this->type = $arrSubscription['type'];
      $this->day = $arrSubscription['day'];
      $this->category = $arrSubscription['category'];
      $this->card = $arrSubscription['card'];
      $this->active = $arrSubscription['active'];
      $this->description = base64_decode($arrSubscription['description']);
      $this->user_id = $arrSubscription['user_id'];
    }

    $this->arrTypes = [
      array('id'=>0,'name'=>$oLang->get('EveryMonth')),
    ];
  }
}
