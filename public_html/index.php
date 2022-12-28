<?
// Обработка запросов к приложению
// header('Access-Control-Allow-Origin: *'); # Для подключения из вне
// header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
// header("Access-Control-Max-Age: 0");
// header("Content-Security-Policy: default-src *; connect-src *; script-src *; object-src *;");
// header("X-Content-Security-Policy: default-src *; connect-src *; script-src *; object-src *;");
// header("X-Webkit-CSP: default-src *; connect-src *; script-src 'unsafe-inline' 'unsafe-eval' *; object-src *;");

session_start();
$_SESSION['version'] = '5.6.4';

include_once 'core/core.php'; # Основные настройки

// Проверка сессии и пользователя. если запрос из приложения
// if ( $_REQUEST['action'] != 'sessions' ) {
  $oSession = new session();
  $oSession->install();
// }

$olang = new lang(); // Подтягиваем языки
$oLock = new lock(); // Подтягиваем уровни доступов

// Referal system
if ( $_REQUEST['referal'] ) {
  $_SESSION['referal'] = $_REQUEST['referal'];
  exit("<meta http-equiv='refresh' content='0; url= /'>");
}

// Если запросы на обработку данных
header('Access-Control-Allow-Origin: *'); # Для подключения из вне
if ( isset($_REQUEST['app']) ) {
  include_once 'app/app.php';
  die();
}

// Определяем что открыть
switch ($_SERVER['REQUEST_URI']) {
  default: # Запрашиваемая страница
    // Параметры
    $oNav = new nav();
    $arrNavCurrent = end($oNav->arrNavsPath);
    $sIncludePathContent = $_SERVER['REDIRECT_URL'] != '' ? 'pages'.$_SERVER['REDIRECT_URL'].'index' : 'pages/welcome/index';

    // Проверки
    if ( $_SESSION['user'] )
    if ( ! $_SESSION['user']['id'] ) unset( $_SESSION['user'] );
    $bUser = isset($_SESSION['user']) ? 1 : 0;
    $iUserRole = isset($_SESSION['user']) ? $_SESSION['user']['role_val'] : 0;
    $iAccessLevel = isset($arrNavCurrent['access']) ? $arrNavCurrent['access'] : -1;
    $bFile = file_exists($sIncludePathContent.'.php') ? 1 : 0;

    // Содержаине
    include_once 'core/templates/pages/head.php';

    include_once 'core/templates/pages/header.php';

    // Существование файла
    if ( $bFile ) {
      // Доступ не всем
      if ( $iAccessLevel >= 0 ) {
        // Зарегистрирован ли пользователь
        if ( $bUser ) {
          // Недостаточно прав
          if ( $iUserRole > $iAccessLevel ) {
            http_response_code(403);
            $sIncludePathContent = 'pages/errors/403/role';
          }
        }
        // Нужно авторизироваться
        else {
          http_response_code(403);
          $sIncludePathContent = 'pages/errors/403/index';
        }
      }
    }
    // Нет файла
    else {
      $sIncludePathContent = 'pages/errors/404/index';
    }

    // Проверка наличия файла с языком
    if ( file_exists( $sIncludePathContent . '_' . $olang->sUserLang . '.php') ) include_once $sIncludePathContent . '_' . $olang->sUserLang . '.php';
    else include_once $sIncludePathContent . '.php';

    include_once 'core/templates/pages/footer.php';
    break;
}
