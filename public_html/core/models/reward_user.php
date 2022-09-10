<?
/**
 * Reward user
 */
class reward_user extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $user_id = '';
  public static $reward_id = '';
  public static $date = '';
  public static $access_id = '';

  function get_reward_user( $arrRewardUser = [] ) {
    if ( ! $arrRewardUser['id'] ) $arrRewardUser = $this->get();

    return $arrRewardUser;
  }

  function get_rewards_users() {
    $arrRewardsUsers = $this->get();
    if ( $arrRewardsUsers['id'] ) $arrRewardsUsers = $this->get_reward_user( $arrRewardsUsers );
    else foreach ($arrRewardsUsers as &$arrRewardUser) $arrRewardUser = $this->get_reward_user($arrRewardUser);
    return $arrRewardsUsers;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры

    $arrFields['user_id'] = ['title'=>'Пользователь','type'=>'number','value'=>$this->user_id];
    $arrFields['reward_id'] = ['title'=>'Награда','type'=>'number','value'=>$this->reward_id];
    $arrFields['date'] = ['title'=>'Дата награждения','type'=>'date_time','value'=>$this->date];
    $arrFields['access_id'] = ['title'=>'ID полученного доступа','type'=>'number','value'=>$this->access_id];

    return $arrFields;
  }

  function __construct( $iRewardUserId = 0 )
  {
    $this->table = 'rewards_users';

    if ( $iRewardUserId ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $iRewardUserId . "'";
      $arrRewardUser = db::query($mySql);

      $this->id = $arrRewardUser['id'];
      $this->user_id = $arrRewardUser['user_id'];
      $this->reward_id = $arrRewardUser['reward_id'];
      $this->date = $arrRewardUser['date'];
      $this->access_id = $arrRewardUser['access_id'];
    }
    else {
      $this->date = date("Y-m-d H:i:s");
    }
  }
}
