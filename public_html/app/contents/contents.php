<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'show': # Показ шаблона
    $sResultHtml = '';
    ob_start();

    // Определяем что открыть
    switch ($_REQUEST['path']) {
      default: # Запрашиваемая страница
        // Параметры
        $oNav = new nav();
        $arrNavCurrent = end($oNav->arrNavsPath);
        if ( $_SESSION['user'] ) {
          // НЕ первый вход
          if ( $_SESSION['user']['section'] ) $sIncludePathContent = $_REQUEST['path'] != '/' ? 'app_pages'.$_REQUEST['path'].'index' : 'app_pages/dashboard/index';
          // Первый вход
          else {
            $sIncludePathContent = $_REQUEST['path'] != '/' ? 'app_pages'.$_REQUEST['path'].'index' : 'app_pages/info/start/index';
            // Сохраняем что показали
            db::query("UPDATE `users` SET `section` = '1' WHERE `users`.`id` = " . $_SESSION['user']['id'] . ";");
          }
        }
        else {
          $sIncludePathContent = $_REQUEST['path'] != '/' ? 'app_pages'.$_REQUEST['path'].'index' : 'app_pages/authorizations/index';
        }

        // Проверки
        if ( $_SESSION['user'] )
        if ( ! $_SESSION['user']['id'] ) unset( $_SESSION['user'] );
        $bUser = isset($_SESSION['user']) ? 1 : 0;
        $iUserRole = isset($_SESSION['user']) ? $_SESSION['user']['role_val'] : 0;
        $iAccessLevel = isset($arrNavCurrent['access']) ? $arrNavCurrent['access'] : -1;
        $bFile = file_exists($sIncludePathContent.'.php') ? 1 : 0;

        // Существование файла
        if ( $bFile ) {
          // Доступ не всем
          if ( $iAccessLevel >= 0 ) {
            // Зарегистрирован ли пользователь
            if ( $bUser ) {
              // Недостаточно прав
              if ( $iUserRole > $iAccessLevel ) {
                http_response_code(403);
                $sIncludePathContent = 'app_pages/errors/403/role';
              }
            }
            // Нужно авторизироваться
            else {
              http_response_code(403);
              $sIncludePathContent = 'app_pages/errors/403/index';
            }
          }
        }
        // Нет файла
        else {
          $sIncludePathContent = 'app_pages/errors/404/index';
        }

        // Проверка наличия файла с языком
        if ( file_exists( $sIncludePathContent . '_' . $olang->sUserLang . '.php') ) include_once $sIncludePathContent . '_' . $olang->sUserLang . '.php';
        else include_once $sIncludePathContent . '.php';

        break;
    }

    $sResultHtml = ob_get_contents();
    ob_end_clean();

    notification::success( $sResultHtml );
    break;
}
