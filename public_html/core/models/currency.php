<?
/**
 * Currency
 */
class currency extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $currency_code = '';
  public static $currency_name = '';
  public static $icon = '';
  public static $val = '';
  public static $date_update = '';

  function get_val( $sCurrencuCode = '' ) {
    $sUserCurrency = $this->get_currency_user();
    $sUserCurrencyL = mb_strtolower($sUserCurrency);
    $sCurrencuCodeL = mb_strtolower($sCurrencuCode);
    $sCurrencyVal = 0;

    $curl = curl_init('https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/' . $sUserCurrencyL . '.json');
    $_config = [
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_SSL_VERIFYHOST => false,
    ];
    curl_setopt_array($curl, $_config);
    $resp = curl_exec($curl);
    curl_close($curl);
    $oResp = json_decode($resp);
    $sCurrencyVal = $oResp->{$sUserCurrencyL}->$sCurrencuCodeL;

    // $oCurrency = new currency();
    // $oCurrency->query .= ' AND `currency_code` = ' . $sUserCurrency;
    // $arrCurrency = $oCurrency->get_currency();
    //
    // // Проверяем корректность курса
    // $dDate = new DateTime('-7 days');
    // // Из базы
    // if ( strtotime($arrCurrency['date_update']) > strtotime($dDate->format('Y-m-d H:i:s')) ) {
    //   $arrVal = $arrCurrency['val'];
    //   $sCurrencyVal = $arrVal[ $sUserCurrencyL ][ mb_strtolower( $sCurrencuCode ) ];
    // }
    // // Обновляем курс
    // else {
    //   $curl = curl_init('https://cdn.jsdelivr.net/gh/fawazahmed0/currency-api@1/latest/currencies/' . $sUserCurrencyL . '.json');
    //   $resp = curl_exec($curl);
    //   curl_close($curl);
    //   $oResp = json_decode($resp);
    //
    //   $this->val = $oResp;
    //   $this->date_update = date("Y-m-d H:i:s");
    //   $this->save();
    // }

    // $sCurrencuCode
    return $sCurrencyVal;
  }

  function get_currency_user() {
    return $_SESSION['user']['currency'] ? $_SESSION['user']['currency'] : 'USD';
  }

  function get_currency( $arrCurrent = [] ) {
    if ( ! $arrCurrent['id'] ) $arrCurrent = $this->get();

    return $arrCurrent;
  }

  function get_currencies(){
    $arrCurrencies = $this->get();
    if ( $arrCurrencies['id'] ) $arrCurrencies = $this->get_currency( $arrCurrencies );
    else foreach ($arrCurrencies as &$arrCurrent) $arrCurrent = $this->get_currency($arrCurrent);
    return $arrCurrencies;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры

    $arrFields['currency_code'] = ['title'=>$oLang->get('Title'),'type'=>'text','required'=>'required','value'=>$this->currency_code];
    $arrFields['currency_name'] = ['title'=>$oLang->get('Currency'),'type'=>'textarea','value'=>$this->currency_name];
    $arrFields['icon'] = ['title'=>$oLang->get('Icon'),'type'=>'textarea','value'=>$this->icon];
    $arrFields['date_update'] = ['title'=>$oLang->get('DateUpdate'),'type'=>'date_time','disabled'=>'disabled','value'=>$this->date_update];

    return $arrFields;
  }

  function __construct( $iCurrencyId = 0 )
  {
    $this->table = 'currency';

    if ( $iCurrencyId ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $iCurrencyId . "'";
      $arrCurrent = db::query($mySql);

      $this->id = $arrCurrent['id'];
      $this->currency_code = $arrCurrent['currency_code'];
      $this->currency_name = $arrCurrent['currency_name'];
      $this->icon = $arrCurrent['icon'];
      $this->val = $arrCurrent['val'];
      $this->date_update = $arrCurrent['date_update'];
    }
  }
}
