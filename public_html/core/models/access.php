<?
/**
 * Access
 */
class access extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $user_id = '';
  public static $level = '';
  public static $date_start = '';
  public static $date_stop = '';
  public static $data = '';
  public static $days = '';

  // Даём доступ
  function set_access() {
    // Подтягиваем параметры
    $oAccess = new access();
    if ( $this->id ) $oAccess->id = $this->id;
    $oAccess->user_id = $this->user_id;
    $oAccess->level = $this->level;
    $oAccess->days = $this->days;
    $oAccess->data = $this->data;

    // Берём последную актуальную дату
    $dLastDate = $oAccess->last_date_access();
    // Дата есть
    if ( $dLastDate ) $oAccess->date_start = $dLastDate;
    // Даты нет, берём текущую
    else $oAccess->date_start = date("Y-m-d");

    // Считаем количество дней для доступа
    $oAccess->date_stop = date("Y-m-d", strtotime($oAccess->date_start . '+ ' . $oAccess->days . ' days'));

    // Добавляем доступ
    if ( $oAccess->id ) {
      $oAccess->save();
      $arrAccess = $oAccess->get_access();
    }
    else {
      $iAccessId = $oAccess->add();
      $oAccess = new access( $iAccessId );
      $arrAccess = $oAccess->get_access();
    }

    // Возвращяем результат
    return $arrAccess;
  }

  // Определяем последний доступный день
  function last_date_access() {
    // Получаем доступы пользователя
    $oAccess = new access();
    $oAccess->query = ' AND `user_id` = ' . $this->user_id;
    $oAccess->query = ' AND `date_stop` != "0000-00-00"';
    $oAccess->sortname = 'date_stop';
    $oAccess->sortdir = 'DESC';
    $arrUserAccesses = $oAccess->get_accesses();

    // Проверяем актуальность по дате
    if ( count($arrUserAccesses) ) {
      $arrUserAccessesLast = end($arrUserAccesses);

      // Доступ актуальный
      if ( isset($arrUserAccessesLast['date_stop']) && strtotime($arrUserAccessesLast['date_stop']) > date("Y-m-d") ) return $arrUserAccessesLast['date_stop'];
      // Доступ не актуальный
      else return false;
    }
    // Нет доступа
    else return false;
  }

  function get_access( $arrAccess = [] ) {
    if ( ! $arrAccess['id'] ) $arrAccess = $this->get();

    if ( $this->show_user )
    if ( $arrAccess['user_id'] ) {
      $oUser = new user( $arrAccess['user_id'] );
      $arrAccess['user'] = $oUser->get_user();
    }

    return $arrAccess;
  }

  function get_accesses(){
    $arrAccesses = $this->get();
    if ( $arrAccesses['id'] ) $arrAccesses = $this->get_access( $arrAccesses );
    else foreach ($arrAccesses as &$arrAccess) $arrAccess = $this->get_access($arrAccess);
    return $arrAccesses;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры

    $arrFields['user_id'] = ['title'=>'Пользователь','type'=>'text','value'=>$this->user_id];
    $arrFields['level'] = ['title'=>'Уровень доступа','type'=>'number','value'=>$this->level];
    $arrFields['date_start'] = ['title'=>'Дата начала','type'=>'date','value'=>$this->date_start];
    $arrFields['date_stop'] = ['title'=>'Дана окончания','type'=>'date','value'=>$this->date_stop];
    $arrFields['data'] = ['title'=>'Данные','type'=>'text','value'=>$this->data];
    $arrFields['days'] = ['title'=>'Количество дней','type'=>'number','value'=>$this->days];

    return $arrFields;
  }

  function __construct( $iAccessId = 0 )
  {
    $this->table = 'accesses';

    if ( $iAccessId ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $iAccessId . "'";
      $arrAccess = db::query($mySql);

      $this->id = $arrAccess['id'];
      $this->user_id = $arrAccess['user_id'];
      $this->level = $arrAccess['level'];
      $this->date_start = $arrAccess['date_start'];
      $this->date_stop = $arrAccess['date_stop'];
      $this->data = $arrAccess['data'];
      $this->days = $arrAccess['days'];
    }
    else {
      $this->date_start = date("Y-m-d");
    }
  }
}
