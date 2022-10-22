<?
/**
 * Card
 */
class card extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $title = '';
  public static $balance = '';
  public static $type = '';
  public static $arrTypes = '';
  public static $color = '';
  public static $limit = '';
  public static $sort = '';
  public static $active = '';
  public static $user_id = '';
  public static $date_update = ''; # Last update
  public static $service = '';
  public static $commission = '';
  public static $percent = '';
  public static $date_service = '';
  public static $date_commission = '';
  public static $free_days_limit = '';
  public static $min_payment = ''; # Минимальный платёж
  public static $min_payment_percent = ''; # Процент минимального платежа
  public static $min_payment_date = ''; # Дата минимального платежа
  public static $not_edit = 0; # Возможность редактировать
  public static $currency = ''; # Валюты

  // Вывод карты
  function get_card( $arrCard = [] ){
    if ( ! $arrCard['id'] ) $arrCard = $this->get();

    // Проверка последней оплаты, если не сходится обнавляем баланс
    $oMoney = new money();
    $oMoney->query .= ' AND `card` = ' . $arrCard['id'];
    $oMoney->query .= ' AND `user_id` = ' . $arrCard['user_id'];
    $oMoney->query .= ' ORDER BY `date` ASC LIMIT 1';
    $arrMoneys = $oMoney->get_money();
    $iLastMoney = strtotime($arrMoneys[0]['date']);
    $iLastUpdateCard = strtotime($arrCard['date_update']);
    // if ( $iLastMoney > $iLastUpdateCard ) $arrCard['balance'] = $this->balance_reload();

    // Обработка данных
    $arrCard['balance'] = round($arrCard['balance']);

    $arrCard['limit'] = round($arrCard['limit']);
    if ( ! (int)$arrCard['limit'] ) $arrCard['limit'] = '-';

    $arrCard['commission'] = round($arrCard['commission']);
    if ( ! (int)$arrCard['commission'] ) $arrCard['commission'] = '-';

    // Валюта
    $oLock = new lock();
    if ( $this->show_currency && $oLock->check('Currency') ) {
      $oCurrency = new currency();
      $arrCard['currency_user'] = $oCurrency->get_currency_user();
      $arrCard['currency_card'] = $arrCard['currency'];
      if ( $arrCard['currency'] != $arrCard['currency_user'] ) {
        $arrCard['currency_balance'] = round($arrCard['balance']);
        $arrCard['balance'] = round( $arrCard['balance'] / $oCurrency->get_val( $arrCard['currency'] ) );

        $arrCard['currency_card'] = $oCurrency->get_currency_user();
        $arrCard['currency_user'] = $arrCard['currency'];

        if ( (int)$arrCard['limit'] )
          $arrCard['limit'] = round( $arrCard['limit'] / $oCurrency->get_val( $arrCard['limit'] ) );
        if ( (int)$arrCard['commission'] )
          $arrCard['commission'] = round( $arrCard['commission'] / $oCurrency->get_val( $arrCard['commission'] ) );
      }
      else $arrCard['currency_user'] = '';
    }
    if ( ! (int)$arrCard['balance'] ) $arrCard['balance'] = '-';

    if ( (float)$arrCard['commission'] > 0 ) $arrCard['commission_show'] = 'true';
    if ( (int)$arrCard['user_id'] > 0 ) $arrCard['edit_show'] = 'true';
    if ( (int)$arrCard['not_edit'] > 0 ) {
      $arrCard['edit_show'] = 'false';
      $oLang = new lang();
      if ( $arrCard['title'] == 'Cash' ) $arrCard['title'] = $oLang->get( $arrCard['title'] );
    }

    if ( (int)$arrCard['active'] ) $arrCard['active_show'] = 'true';
    else $arrCard['active_show'] = 'false';

    return $arrCard;
  }

  // Вывод карт
  function get_cards(){
    $arrCards = $this->get();
    if ( $arrCards['id'] ) $arrCards = $this->get_card( $arrCards );
    else foreach ( $arrCards as &$arrCard ) $arrCard = $this->get_card( $arrCard );
    return $arrCards;
  }

  // Получение баланса
  function get_balance( $iCardId = 0 ) {
    $iBalance = 0;

    $oCards = new card();
    $oCards->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
    $arrCards = $oCards->get_cards();

    foreach ($arrCards as $arrCard) {
      $iBalance = (float)$iBalance + (float)$arrCard['balance'];
    }

    return $iBalance;
  }

  // Получение баланса с учётом кредиток
  function get_balance_oncredit( $iCardId = 0 ) {
    $iBalance = 0;

    $oCards = new card();
    $oCards->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
    $arrCards = $oCards->get_cards();

    foreach ($arrCards as $arrCard) {
      $iBalance = (float)$iBalance + (float)$arrCard['limit'];
      $iBalance = (float)$iBalance + (float)$arrCard['balance'];
    }

    return $iBalance;
  }

  // Кредитные карты
  function get_credit_cards( $iCardId = 0 ) {
    $arrCreditCards = [];

    $oCards = new card();
    $oCards->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
    $arrCards = $oCards->get_cards();

    foreach ($arrCards as $arrCard) {
      if ( (int)$arrCard['limit'] > 0 )
        $arrCreditCards[] = $arrCard;
    }

    return $arrCreditCards;
  }

  // Пополнение баланса карты
  function balance_add( $floatSum ){
    $this->balance = (float)$this->balance + (float)$floatSum;
    $this->date_update = date("Y-m-d H:i:s");
    $this->save();
    return $this->balance;
  }

  // Саписание баланса карты
  function balance_remove( $floatSum ){
    $this->balance = (float)$this->balance - (float)$floatSum;
    $this->date_update = date("Y-m-d H:i:s");
    $this->save();
    return $this->balance;
  }

  // Пересчёт баланса карты
  function balance_reload(){
    $this->balance = $this->limit;

    // Если кридитка, считаем комиссии
    if ( (float)$this->limit > 0 ) $this->commission = $this->commission_reload();

    // Анализируем затраты
    $oMoney = new money();
    $oMoney->query = ' AND `user_id` = ' . $_SESSION['user']['id'] . '';
    $oMoney->query .= ' AND `card` = ' . $this->id;
    $oMoney->query .= ' AND `type` = 1';
    $arrMoneys = $oMoney->get_moneys();
    foreach ($arrMoneys as $arrMoney) $this->balance = (float)$this->balance - (float)$arrMoney['price'];

    // Анализируем поступления
    $oMoney = new money();
    $oMoney->query = ' AND `user_id` = ' . $_SESSION['user']['id'] . '';
    $oMoney->query .= ' AND `to_card` = ' . $this->id;
    $oMoney->query .= ' AND `type` = 2';
    $arrMoneys = $oMoney->get_moneys();
    foreach ($arrMoneys as $arrMoney) $this->balance = (float)$this->balance + (float)$arrMoney['price'];

    // Анализируем поступления с других карт
    $oMoney = new money();
    $oMoney->query = ' AND `user_id` = ' . $_SESSION['user']['id'] . '';
    $oMoney->query .= ' AND `to_card` = ' . $this->id;
    $oMoney->query .= ' AND `type` = 1';
    $arrMoneys = $oMoney->get_moneys();
    foreach ($arrMoneys as $arrMoney) $this->balance = (float)$this->balance + (float)$arrMoney['price'];

    $this->date_update = date("Y-m-d H:i:s");
    unset($this->not_edit);
    $this->save();
    return $this->balance;
  }

  // Пополнение комиссии карты
  function commission_add( $floatSum ){
    $this->commission = (float)$this->commission + (float)$floatSum;
    $this->date_update = date("Y-m-d H:i:s");
    unset($this->not_edit);
    $this->save();
    return $this->commission;
  }

  // Саписание комиссии карты
  function commission_remove( $floatSum ){
    $this->commission = (float)$this->commission - (float)$floatSum;
    $this->date_update = date("Y-m-d H:i:s");
    unset($this->not_edit);
    $this->save();
    return $this->commission;
  }

  // Пересчёт комиссий
  function commission_reload(){
    $this->commission = 0;

    // Анализируем затраты
    $oMoneyCommission = new money();
    $oMoneyCommission->query .= ' AND `card` = ' . $this->id;
    $oMoneyCommission->query .= ' AND `category` = 2';
    $arrMoneysCommissions = $oMoneyCommission->get_money();
    foreach ($arrMoneysCommissions as $arrMoneyCommission) $this->commission = (float)$this->commission + (float)$arrMoneyCommission['price'];
    return $this->commission;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id_show'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры

    // Возможность редактировать
    // if ( (int)$this->not_edit )
    //   $arrFields['not_edit'] = ['title'=>'NotEdit','type'=>'hidden','value'=>$this->not_edit]; # Возможность редактировать

    $arrFields['user_id'] = ['title'=>$oLang->get('User'),'type'=>'hidden','value'=>$_SESSION['user']['id']];

    $arrFields['type'] = ['class'=>'switch','title'=>$oLang->get('Type'),'type'=>'select','options'=>$this->arrTypes,'value'=>$this->type];

    $arrFields['title'] = ['title'=>$oLang->get('Title'),'type'=>'text','required'=>'required','value'=>$this->title];

    $sColor = $this->color ? $this->color : sprintf( '#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255) );
    $arrFields['color'] = ['title'=>$oLang->get('Color'),'type'=>'color','value'=>$sColor];

    $iSort = $this->sort ? $this->sort : 100;
    $arrFields['sort'] = ['title'=>$oLang->get('Sort'),'type'=>'number','value'=>$iSort];

    $arrFields['limit'] = ['section'=>2,'class'=>'switch_values switch_type-1','title'=>$oLang->get('Limit'),'type'=>'number','value'=>substr($this->limit, 0, -2),'step'=>'0.01'];
    $arrFields['percent'] = ['section'=>2,'class'=>'switch_values switch_type-1 switch_type-2','title'=>$oLang->get('Percent'),'type'=>'number','value'=>substr($this->percent, 0, -2),'step'=>'0.01'];
    // $arrFields['price'] = ['title'=>$oLang->get('Price'),'type'=>'number','value'=>substr($this->price, 0, -2),'step'=>'0.01'];

    $arrFields['service'] = ['section'=>2,'class'=>'switch_values switch_type-1','title'=>$oLang->get('CardService'),'type'=>'number','value'=>substr($this->service, 0, -2),'step'=>'0.01'];
    $arrFields['date_service'] = ['section'=>2,'class'=>'switch_values switch_type-1','title'=>$oLang->get('CardServiceDate'),'type'=>'date','value'=>$this->date_service];

    $arrFields['free_days_limit'] = ['section'=>2,'class'=>'switch_values switch_type-1','title'=>$oLang->get('CardFreeDaysLimit'),'type'=>'number','value'=>$this->free_days_limit];
    $arrFields['date_commission'] = ['section'=>2,'class'=>'switch_values switch_type-1','title'=>$oLang->get('CardDateCommissions'),'type'=>'date','value'=>$this->date_commission];
    $arrFields['date_bill_percent'] = ['section'=>2,'class'=>'switch_values switch_type-2','title'=>$oLang->get('CardDateBillPercent'),'type'=>'date','value'=>$this->date_bill_percent];

    $arrFields['min_payment'] = ['section'=>2,'class'=>'switch_values switch_type-1','title'=>$oLang->get('CardMinPayment'),'type'=>'number','value'=>$this->min_payment];
    $arrFields['min_payment_percent'] = ['section'=>2,'class'=>'switch_values switch_type-1','title'=>$oLang->get('CardMinPaymentPercent'),'type'=>'number','value'=>$this->min_payment_percent];
    $arrFields['min_payment_date'] = ['section'=>2,'class'=>'switch_values switch_type-1','title'=>$oLang->get('CardMinPaymentDate'),'type'=>'number','value'=>$this->min_payment_date];

    $oCurrency = new currency();
    $arrCurrencies = $oCurrency->get_currencies();
    $arrCurrenciesFilter = [];
    $arrCurrenciesFilter[] = array('id'=>'','name'=>'...');
    foreach ( $arrCurrencies as $arrCurrency )
      $arrCurrenciesFilter[] = array('id'=>$arrCurrency['currency_code'],'name'=>$arrCurrency['currency_code'],'description'=>$arrCurrency['currency_name']);

    $arrFields['currency'] = ['section'=>2,'title'=>$oLang->get('Currency'),'type'=>'select','options'=>$arrCurrenciesFilter,'search'=>true,'value'=>$this->currency];

    // if ( ! (int)$this->not_edit )
    $arrFields['active'] = ['title'=>$oLang->get('Active'),'type'=>'checkbox','value'=>$this->active];

    return $arrFields;
  }

  function __construct( $card_id = 0 )
  {
    $oLang = new lang();
    $this->table = 'cards';

    if ( $card_id ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $card_id . "'";
      $arrCard = db::query($mySql);

      $this->id = $arrCard['id'];
      $this->title = $arrCard['title'];
      $this->balance = $arrCard['balance'];
      $this->type = $arrCard['type'];
      $this->color = $arrCard['color'];
      $this->limit = $arrCard['limit'];
      $this->sort = $arrCard['sort'];
      $this->active = $arrCard['active'];
      $this->user_id = $arrCard['user_id'];
      $this->date_update = $arrCard['date_update'];
      $this->service = $arrCard['service'];
      $this->commission = $arrCard['commission'];
      $this->percent = $arrCard['percent'];
      $this->date_service = $arrCard['date_service'];
      $this->date_comismsion = $arrCard['date_commission'];
      $this->free_days_limit = $arrCard['free_days_limit'];
      $this->min_payment = $arrCard['min_payment'];
      $this->min_payment_percent = $arrCard['min_payment_percent'];
      $this->min_payment_date = $arrCard['min_payment_date'];
      $this->not_edit = $arrCard['not_edit'];
      $this->currency = $arrCard['currency'];
    }

    $this->arrTypes = [
      array('id'=>0,'name'=>$oLang->get('Cash')),
      array('id'=>1,'name'=>$oLang->get('CardDebit')),
      array('id'=>2,'name'=>$oLang->get('CardCredit')),
      array('id'=>3,'name'=>$oLang->get('CardBill')),
    ];
  }
}
