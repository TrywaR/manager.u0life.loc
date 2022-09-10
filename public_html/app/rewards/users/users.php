<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'show_all': # Показ
      $oRewardUser = $_REQUEST['id'] ? new reward_user( $_REQUEST['id'] ) : new reward_user();

      if ( $_REQUEST['from'] ) $oRewardUser->from = $_REQUEST['from'];
      if ( $_REQUEST['limit'] ) $oRewardUser->limit = $_REQUEST['limit'];

      $oRewardUser->query = ' AND `user_id` = ' . $_SESSION['user']['id'];

      $arrRewardsUsers = $oRewardUser->get_rewards();
      notification::send($arrRewardsUsers);
    break;
}
