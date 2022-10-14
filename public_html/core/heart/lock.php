<?
/**
 * Lock
 */
class lock
{
  public static $iUserRole = 0; # Уровень доступа пользователя
  public static $arrAccess = []; # Массив со что куда

  // Проверка доступа
  function check( $sAccess = '' ){
    if ( empty($this->arrAccess[$sAccess]) ) return true;
    if ( (int)$this->arrAccess[$sAccess] > (int)$this->iUserRole ) return false;
    else return true;
  }

  // Возврат html если надо
  function get( $sAccess = '' ) {
    if ( ! $this->check($sAccess) ) include 'core/templates/elems/lock.php';
    // return
  }

  // Возврат html если надо
  function get_attr( $sAccess = '', $sAttr = '' ) {
    if ( $this->check($sAccess) )
    return $sAttr;
  }

  // Возврат html если надо
  function get_class( $sAccess = '' ) {
    if ( ! $this->check($sAccess) )
    return 'disabled';
  }

  // Массив доступов
  function arrAccessInit(){
    $this->arrAccess = [
      'CategoryAnalyticYear' => 1,
      'CategoryLimit' => 1,
      'MoneysCostsAnalyticYear' => 1,
      'MoneysWagesAnalyticYear' => 1,
      'TimesCostsAnalyticYear' => 1,

      'RewardsAll' => 500,
      'RewardsShow' => 0,
      'RewardsForm' => 500,
      'RewardsSave' => 500,
      'RewardsDel' => 500,

      'NoticesAll' => 500,
      'NoticesShow' => 0,
      'NoticesForm' => 500,
      'NoticesSave' => 500,
      'NoticesDel' => 500,

      'UsersAll' => 500,
      'UsersShow' => 500,
      'UsersForm' => 500,
      'UsersSave' => 500,
      'UsersDel' => 500,

      'AccessAll' => 500,
      'AccessShow' => 500,
      'AccessForm' => 500,
      'AccessSave' => 500,
      'AccessDel' => 500,
    ];
  }

  function __construct(){
    $this->iUserRole = 0;

    if ( $_SESSION['user'] ) {
      if ( (int)$_SESSION['user']['role'] ) $this->iUserRole = $_SESSION['user']['role'];

      // Проверка оплаты
      $oAccess = new access();
      $arrUserAccessesLast = $oAccess->last_date_access();
      $this->iUserRole = (int)$this->iUserRole + (int)$arrUserAccessesLast['level'];
    }

    $this->arrAccessInit();
  }
}
