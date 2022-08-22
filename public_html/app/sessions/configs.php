<?
switch ($_REQUEST['form']) {
  case 'theme': # Переключение темы в сессии
    $arrResult = [];
    $arrResult['theme'] = $_SESSION['theme'] = $_REQUEST['theme'];
    if ( isset($_SESSION['user']) ) {
      $_SESSION['user']['theme'] = $arrResult['theme'];
      db::query("UPDATE `users` SET `theme` = '" . $arrResult['theme'] . "' WHERE `id` = '" . $_SESSION['user']['id'] . "'");
    }
    notification::success($arrResult);
    break;

  case 'lang': # Переключение языка в сессии
    $arrResult = [];
    $arrResult['lang'] = $_SESSION['lang'] = $_REQUEST['lang'];
    if ( isset($_SESSION['user']) ) {
      $_SESSION['user']['lang'] = $arrResult['lang'];
      db::query("UPDATE `users` SET `lang` = '" . $arrResult['lang'] . "' WHERE `id` = '" . $_SESSION['user']['id'] . "'");
    }
    notification::success($arrResult);
    break;
}
