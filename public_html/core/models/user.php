<?
/**
 * User
 */
class user extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';

  public static $login = '';
  public static $email = '';
  public static $phone = '';
  public static $password = '';
  public static $date_registration = '';
  public static $theme = '';
  public static $lang = '';
  public static $role = '';
  public static $referal = '';
  public static $protect = '';
  public static $arrProtectTypes = '';
  public static $section = '';

  public function get_user( $arrUser = [] ) {
    if ( ! $arrUser['id'] ) $arrUser = $this->get();

    // Показ доступа
    if ( $this->show_access ) {
      $oAccess = new access();
      $oAccess->user_id = $arrUser['id'];
      $arrUser['access'] = $oAccess->last_date_access();
      $arrUser['role'] = (int)$arrUser['role'] + (int)$arrUser['access']['level'];
    }

    // Показ ролей
    if ( $this->show_role_val ) {
      $arrUser['role_val'] = '<i class="fas fa-user-circle"></i> User';
      if ( (int)$arrUser['role'] > 0 ) $arrUser['role_val'] = '<i class="fas fa-check"></i> Valid user';
      if ( (int)$arrUser['role'] >= 500 ) $arrUser['role_val'] = '<i class="fas fa-crown"></i> Admin';
    }

    // Показ наград
    if ( $this->show_rewards ) {
      $oRewardUser = new reward_user();
      $oRewardUser->query .= ' AND `user_id` = ' . $arrUser['id'];
      $arrRewardsUsers = $oRewardUser->get_rewards_users();

      if ( count($arrRewardsUsers) ) {
        $oLang = new lang();
        $arrUser['rewards'] = '<small class="pe-2 _reward_title">' . $oLang->get('Rewards') . ': </small>';
      }
      foreach ($arrRewardsUsers as $arrRewardsUser) {
        $oReward = new reward( $arrRewardsUser['reward_id'] );
        $arrReward = $oReward->get_reward();
        $arrUser['rewards'] .= '<div class="pe-2 _reward" title="' . $oLang->get($oReward->title) . '">' . $oReward->icon . '</div>';
      }
    }

    return $arrUser;
  }

  function get_users(){
    $arrUsers = $this->get();
    if ( $arrUsers['id'] ) $arrUsers = $this->get_user( $arrUsers );
    else foreach ($arrUsers as &$arrUser) $arrUser = $this->get_user($arrUser);
    return $arrUsers;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры

    $arrLangFilter = [
      array('id'=>'en','name'=>'English'),
      array('id'=>'ru','name'=>'Russian'),
    ];
    $arrFields['lang'] = ['title'=>$oLang->get('Lang'),'type'=>'select','options'=>$arrLangFilter,'value'=>$this->lang];

    $arrThemeFilter = [
      array('id'=>0,'name'=>'Auto'),
      array('id'=>1,'name'=>'Dark'),
      array('id'=>2,'name'=>'Light'),
    ];
    $arrFields['theme'] = ['title'=>$oLang->get('Theme'),'type'=>'select','options'=>$arrThemeFilter,'value'=>$this->theme];

    $arrFields['login'] = ['title'=>$oLang->get('Login'),'type'=>'text','value'=>$this->login];
    $arrFields['phone'] = ['title'=>$oLang->get('Phone'),'type'=>'text','value'=>$this->phone];
    $arrFields['email'] = ['title'=>$oLang->get('Email'),'type'=>'text','value'=>$this->email];
    $arrFields['section'] = ['title'=>$oLang->get('Section'),'type'=>'number','value'=>$this->section];
    $arrFields['date_registration'] = ['title'=>$oLang->get('DateRegistration'),'type'=>'date_time','disabled'=>'disabled','value'=>$this->date_registration];

    $oLock = new lock();
    if ( $oLock->check('UsersAll') || (int)$this->role > 100 ) {
      $arrFields['protect'] = ['title'=>$oLang->get('ProtectType'),'type'=>'select','options'=>$this->arrProtectTypes,'value'=>$this->protect];
    }

    if ( $oLock->check('UsersAll') && (int)$_SESSION['user']['role'] > (int)$this->role ) {
      $arrFields['role'] = ['title'=>$oLang->get('Role'),'type'=>'number','value'=>$this->role];
    }

    // $arrFields['price'] = ['title'=>$oLang->get('Price'),'type'=>'number','value'=>substr($this->price, 0, -2)];
    // $arrFields['date'] = ['title'=>$oLang->get('Date'),'type'=>'date','value'=>$this->date];

    return $arrFields;
  }

  function __construct( $iUserId = 0 )
  {
    $oLang = new lang();

    $this->table = 'users';

    if ( $iUserId ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $iUserId . "'";
      $arrUser = db::query($mySql);

      $this->id = $arrUser['id'];
      $this->login = $arrUser['login'];
      $this->email = $arrUser['email'];
      $this->phone = $arrUser['phone'];
      $this->password = $arrUser['password'];
      $this->date_registration = $arrUser['date_registration'];
      $this->theme = $arrUser['theme'];
      $this->lang = $arrUser['lang'];
      $this->role = $arrUser['role'];
      $this->referal = $arrUser['referal'];
      $this->protect = $arrUser['protect'];
      $this->section = $arrUser['section'];
    }
    else {
      $this->lang = 'en';
    }

    $this->arrProtectTypes = [
      array('id'=>0,'name'=>$oLang->get('ProtectNo')),
      array('id'=>1,'name'=>$oLang->get('ProtectYes')),
      array('id'=>2,'name'=>$oLang->get('ProtectKeyYes')),
    ];
    $this->arrProtectTypesIds = [];
    foreach ($this->arrProtectTypes as $arrProtectType) $this->arrProtectTypesIds[$arrProtectType['id']] = $arrProtectType;
  }
}
