<?
/**
 * Session
 */
class session extends model
{
  public static $table = 'sessions'; # Таблица в bd
  public static $id = 0;
  public static $user_id = 0;
  public static $data = '';
  public static $sSession = '';
  public static $date = '';
  public static $start = '';
  public static $ip = '';
  public static $push = '';
  // public static $active = 1;

  // Количество подключений
  function get_users_devices( $iUserId = 0 ) {
    if ( $this->user_id ) $iUserId = $this->user_id;
    if ( $iUserId ) {
      $date = new DateTime('-30 minute');
      $dateFixLastOnline = $date->format('Y-m-d H:i:s');
      $sQuery  = "SELECT * FROM `sessions`";
      $sQuery .= " WHERE `date` BETWEEN STR_TO_DATE('" .  $dateFixLastOnline . "', '%Y-%m-%d %H:%i:%s') ";
      $sQuery .= " AND STR_TO_DATE('" .  date('Y-m-d H:i:s') . "', '%Y-%m-%d %H:%i:%s')";
      $sQuery .= " AND `user_id` = " . $iUserId;
      // $sQuery .= " AND `active` > 0";
      $arrLastSessions = db::query_all($sQuery);
      return $arrLastSessions;
    }
    return $iUserId;
  }

  // Установка сессии (Проверка работоспособности сессии и подтягивание пользователя если есть)
  function install( $sSession = '' ) {
    $sSession = $sSession ? $sSession : $_REQUEST['session'];
    $sSession = $sSession ? $sSession : $_SESSION['session'];
    // Если есть информация о сессии
    if ( $sSession ) {
      $oSession = new session(0, $sSession);
      $arrSession = $oSession->get_session();
      if ( is_array($arrSession) && $arrSession['id'] ) {
        $_SESSION['session'] = $oSession->session;
        if ( $oSession->user_id ) {
          $oUser = new user( $oSession->user_id );
          $_SESSION['user'] = $oUser->get();
        }
        $oSession->update();
      }
    }
    // Если нет, создаём
    else {
      $oSession = new session();
      $_SESSION['session'] = $oSession->session;
      $oSession->add();
    }
  }

  // Обновление сессии
  function update() {
    // Обновляем дату
    $this->date = date("Y-m-d H:i:s");
    // Если ip поменялся, новая запись
    if ( $this->ip != $_SERVER['REMOTE_ADDR'] ) {
      $this->start = date("Y-m-d H:i:s");
      $this->id = 0;
      $this->ip = $_SERVER['REMOTE_ADDR'];
      $this->add();
    }
    // Если старый, обновляем дату
    else $this->save();
  }

  // Отключение сессий по хэшу
  function disabled_session( $sSession = '' ) {
    // Берём сессию
    if ( ! $sSession ) $sSession = $this->session;
    if ( $sSession ) {
      // Вытаскиваем все сессии по хэшу
      $mySqlDel = "DELETE FROM `" . $this->table . "`";
      $mySqlDel .= " WHERE `session` = '" . $sSession . "'";
      // Удаляем
      if ( db::query($mySqlDel) ) notification::error( 'Ошибка удаления!' );
    }
  }

  // Удаление всех сессий по хэшу
  function del_session( $sSession = '' ) {
    // Берём сессию
    if ( ! $sSession ) $sSession = $this->session;
    if ( $sSession ) {
      // Вытаскиваем все сессии по хэшу
      $mySqlDel = "DELETE FROM `" . $this->table . "`";
      $mySqlDel .= " WHERE `session` = '" . $sSession . "'";
      // Удаляем
      if ( db::query($mySqlDel) ) notification::error( 'Ошибка удаления!' );
    }
  }

  // Get session
  function get_session( $sSession = '' ) {
    $mySqlSalt = " ORDER BY DATE(`date`) DESC, TIME(`date`) DESC";
    if ( $sSession ) {
      $mySQL = "SELECT * FROM " . $this->table . " WHERE `session` = '" . $sSession . "'" . $mySqlSalt;
      $arrSession = db::query($mySQL);
      return $arrSession;
    }
    if ( $this->session ) {
      $mySQL = "SELECT * FROM " . $this->table . " WHERE `session` = '" . $this->session . "'" . $mySqlSalt;
      $arrSession = db::query($mySQL);
      return $arrSession;
    }
    return $this->get();
  }

  function __construct( $iSessionId = 0, $sSessionSession = '' )
  {
    $bNewSession = true;
    $this->table = 'sessions';
    $this->name = 'sessions';
    $mySqlSalt = " ORDER BY DATE(`date`) DESC, TIME(`date`) DESC";

    // Если передан id сессии
    if ( $iSessionId ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $iSessionId . "'" . $mySqlSalt;
      $arrSession = db::query($mySql);
    }

    // Если передан хэш сессии
    if ( ! $iSessionId && $sSessionSession ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `session` = '" . $sSessionSession . "'" . $mySqlSalt;
      $arrSession = db::query($mySql);
    }

    // Если вытащили сессию, подставляем значения
    if ( is_array($arrSession) && isset($arrSession['session']) ) {
      $this->id = $arrSession['id'];
      $this->user_id = $arrSession['user_id'];
      $this->data = base64_decode($arrSession['data']);
      $this->date = $arrSession['date'];
      $this->start = $arrSession['start'];
      $this->ip = $arrSession['ip'];
      $this->push = $arrSession['push'];
      $this->session = $arrSession['session'];
      $bNewSession = false;
    }
    // Если нет, создаём сессию основываясь на данных пользователя
    if ( $bNewSession ) {
      // Параметры
      $iUserId = $_SESSION['user'] ? $_SESSION['user']['id'] : 0;
      // Записываем параметры
      $this->name = 'session';
      $this->user_id = $iUserId;
      $this->date = date("Y-m-d H:i:s");
      $this->start = date("Y-m-d H:i:s");
      $this->data = $_SERVER['HTTP_USER_AGENT'];
      // $this->active = 1;
      $this->ip = $_SERVER['REMOTE_ADDR'];
      $this->push = $_REQUEST['push'] ? $_REQUEST['push'] : '';
      $this->session = hash( 'ripemd128', date(BydmGis) . rand(100, 999) );// Генерируем хэш
    }
  }
}
