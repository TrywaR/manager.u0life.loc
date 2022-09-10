<?
/**
 * Reward
 */
class reward extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $title = '';
  public static $level = '';
  public static $icon = '';
  public static $days = '';
  public static $description = '';
  public static $condition = '';
  public static $condition_val = '';
  public static $max_count = '';

  function set_reward( $iUserId = 0 ) {
    if ( ! $arrReward['id'] ) $arrReward = $this->get();
    if ( ! $iUserId ) $iUserId = $this->user_id;
    if ( ! $iUserId ) $iUserId = $_SESSION['user']['id'];

    // Смотрим получал пользователь и сколько
    if ( (int)$arrReward['max_count'] ) {
      $oRewardUser = new reward_user();
      $oRewardUser->query = ' AND `user_id` = ' . $iUserId;
      $oRewardUser->query .= ' AND `reward_id` = ' . $arrReward['max_count'];
      $arrRewardsUser = $oRewardUser->get_rewards_users();
      if ( count($arrRewardsUser) >= (int)$arrReward['max_count'] ) return false;
    }

    // Награждаем
    $oRewardUser = new reward_user();
    $oRewardUser->user_id = $iUserId;
    $oRewardUser->reward_id = $arrReward['id'];

    // Добавляем время
    if ( (int)$arrReward['days'] ) {
      $oAccess = new access();
      $oAccess->user_id = $iUserId;
      $oAccess->level = $arrReward['level'];
      $oAccess->days = $arrReward['days'];
      $oAccess->data = 'REWARD: ' . $arrReward['title'];
      $arrAccess = $oAccess->set_access();

      $oRewardUser->access_id = $arrAccess['id'];
    }

    // Сохраняем награждение
    $iRewardUserId = $oRewardUser->add();
    $oRewardUser = new reward_user( $iRewardUserId );
    $arrRewardUser = $oRewardUser->get_reward_user();

    return $arrRewardUser;
  }

  function get_reward( $arrReward = [] ) {
    if ( ! $arrReward['id'] ) $arrReward = $this->get();

    return $arrReward;
  }

  function get_rewards(){
    $arrRewards = $this->get();
    if ( $arrRewards['id'] ) $arrRewards = $this->get_reward( $arrRewards );
    else foreach ($arrRewards as &$arrReward) $arrReward = $this->get_reward($arrReward);
    return $arrRewards;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры

    $arrFields['title'] = ['title'=>'Пользователь','type'=>'text','value'=>$this->title];
    $arrFields['level'] = ['title'=>'Уровень доступа','type'=>'number','value'=>$this->level];
    $arrFields['icon'] = ['title'=>'Иконка','type'=>'textarea','value'=>$this->icon];
    $arrFields['days'] = ['title'=>'Количество дней','type'=>'number','value'=>$this->days];
    $arrFields['description'] = ['title'=>'Описание','type'=>'text','value'=>$this->description];

    $arrConditions = [];
    $arrConditions[] = array('id'=>0,'name'=>'...');
    $arrConditions[] = array('id'=>1,'name'=>'Регистрация');
    $arrConditions[] = array('id'=>2,'name'=>'Регистрация по реферальной ссылке');

    $arrFields['condition'] = ['class'=>'switch','title'=>'Условие','type'=>'select','options'=>$arrConditions,'search'=>true,'value'=>$this->condition];
    $arrFields['condition_val'] = ['class'=>'switch_values switch_condition-2','title'=>'Значение для условия','type'=>'text','value'=>$this->condition_val];

    $arrFields['max_count'] = ['title'=>'Максимальное количество на одного','type'=>'number','value'=>$this->max_count];

    return $arrFields;
  }

  function __construct( $iRewardId = 0 )
  {
    $this->table = 'rewards';

    if ( $iRewardId ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $iRewardId . "'";
      $arrReward = db::query($mySql);

      $this->id = $arrReward['id'];
      $this->title = $arrReward['title'];
      $this->level = $arrReward['level'];
      $this->days = $arrReward['days'];
      $this->icon = base64_decode($arrReward['icon']);
      $this->description = base64_decode($arrReward['description']);
      $this->condition = $arrReward['condition'];
      $this->condition_val = $arrReward['condition_val'];
      $this->max_count = $arrReward['max_count'];
    }
  }
}
