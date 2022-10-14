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

  function get_currentcy( $arrCurrent = [] ) {
    if ( ! $arrCurrent['id'] ) $arrCurrent = $this->get();

    return $arrCurrent;
  }

  function get_currencies(){
    $arrCurrencies = $this->get();
    if ( $arrCurrencies['id'] ) $arrCurrencies = $this->get_currentcy( $arrCurrencies );
    else foreach ($arrCurrencies as &$arrCurrent) $arrCurrent = $this->get_currentcy($arrCurrent);
    return $arrCurrencies;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры

    $arrFields['currency_code'] = ['title'=>$oLang->get('Title'),'type'=>'text','required'=>'required','value'=>$this->currency_code];
    $arrFields['currency_name'] = ['title'=>$oLang->get('currency_name'),'type'=>'textarea','value'=>$this->currency_name];

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
    }
  }
}
