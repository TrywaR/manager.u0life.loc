<?
$oLang = new lang();

switch ($_REQUEST['form']) {
  case 'show': # Показ шаблона
    $sResultHtml = '';

    // Подключаем шаблон
    ob_start();
    if ( file_exists('core/app/templates/' . $_REQUEST['template'] . '.php') )
      include_once 'core/app/templates/' . $_REQUEST['template'] . '.php';
    else notification::error( $oLang->get('TemplateNotFound') );

    $sResultHtml = ob_get_contents();
    ob_end_clean();

    notification::success( $sResultHtml );
    break;

  case 'theme':
    $sResultHtml = '';

    ob_start();
    $bThemeAuto = false;
    if ( isset( $_SESSION['theme'] ) || isset( $_SESSION['user'] ) ) {
      $iTheme = $_SESSION['theme'] ? $_SESSION['theme'] : 0;
      if ( $_SESSION['user'] ) $iTheme = $_SESSION['user']['theme'] ? (int)$_SESSION['user']['theme'] : 0;

      switch ( $iTheme ) {
        case 0: # auto
          $bThemeAuto = true;
          break;
        case 1:
          ?>
          <script>
            document.head.insertAdjacentHTML("beforeend", `<link rel="stylesheet" href="/template/themes/Dark/theme.min.css?v=<?=$_SESSION['version']?>">`)
          </script>
          <?
          break;
        case 2:
          ?>
          <script>
            document.head.insertAdjacentHTML("beforeend", `<link rel="stylesheet" href="/template/themes/Light/theme.min.css?v=<?=$_SESSION['version']?>">`)
          </script>
          <?
          break;
        case 3:
          break;
      }
    }
    else { # auto
      $bThemeAuto = true;
    }

    if ( $bThemeAuto ) {
      ?>
      <script>
        var
          prefersDarkScheme = window.matchMedia("(prefers-color-scheme: dark)"),
          iThemeVal = prefersDarkScheme.matches ? 1 : 0,
          sThemePath = prefersDarkScheme.matches ? '/template/themes/Dark/theme.min.css?v=<?=$_SESSION['version']?>' : '/template/themes/Light/theme.min.css?v=<?=$_SESSION['version']?>'

       localStorage.setItem('theme', iThemeVal)

        document.head.insertAdjacentHTML("beforeend", `<link rel="stylesheet" href="` + sThemePath + `">`)
      </script>
      <?
    }

    $sResultHtml = ob_get_contents();
    ob_end_clean();

    notification::success( $sResultHtml );
    break;
}
