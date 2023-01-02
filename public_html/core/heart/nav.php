<?
/**
 * Nav - Структура сайта
 */
class nav
{
  public $arrNav = []; # Структура
  public $arrNavs = []; # Обработанная структура
  public $arrNavsPath = []; # Текущий путь

  // Получить цепочку до текущего элемента
  public function get_path(){
    $arrUrls = explode('/',$_SERVER['REDIRECT_URL']);
    $sCurrentUrl = '/';

    // if ( isset( $this->arrNavs[ $sCurrentUrl ] ) ) {
    //   $arrNavItem = $this->arrNavs[ $sCurrentUrl ];
    //   unset($arrNavItem['subs']);
    //   $this->arrNavsPath[ $arrNavItem['url'] ] = $arrNavItem;
    // }

    if ( $_SERVER['REDIRECT_URL'] != '' )
      foreach ($arrUrls as $sUrl) {
        if ( ! $sUrl ) continue;

        $sCurrentUrl = $sCurrentUrl . $sUrl . '/';

        if ( isset( $this->arrNavs[ $sCurrentUrl ] ) ) {
          $arrNavItem = $this->arrNavs[ $sCurrentUrl ];
          unset($arrNavItem['subs']);
          $this->arrNavsPath[ $arrNavItem['url'] ] = $arrNavItem;
        }
      }
    else
      $this->arrNavsPath[ '/' ] = $this->arrNavs[ '/' ];
  }

  public function get_navs(){
    foreach ($this->arrNav as $arrNav) {
      $arrUnsetSubs = $arrNav;
      unset($arrUnsetSubs['subs']);
      $this->arrNavs[ $arrUnsetSubs['url'] ] = $arrUnsetSubs;
      $this->get_navs_subs( $arrNav );
    }
  }

  public function get_navs_subs( $arrNav = [] ) {
    if ( count($arrNav['subs']) )
      foreach ($arrNav['subs'] as $arrItem) {
        $arrUnsetSubs = $arrItem;
        unset($arrUnsetSubs['subs']);
        $this->arrNavs[ $arrUnsetSubs['url'] ] = $arrUnsetSubs;
        if ( count($arrItem['subs']) ) $this->get_navs_subs( $arrItem );
      }
  }

  // Получить nav
  public function get(){
    $arrResult = [];

    foreach ( $this->arrNav as $arrNav ) {
      if ( isset($arrNav['menu_hide']) && $arrNav['menu_hide'] ) continue;

      if ( isset($_REQUEST['app']) ) {
        if ( $_REQUEST['app'] == 'app' && isset($arrNav['onlysite']) ) continue;
        if ( $_REQUEST['app'] == 'site' && isset($arrNav['onlyapp']) ) continue;
      }

      $arrNav = $this->check_sub( $arrNav );

      if ( isset($arrNav['access']) ) {
        if ( $arrNav['access'] < 0 ) $arrResult[] = $arrNav;
        else if ( isset($_SESSION['user']) && $_SESSION['user']['role'] >= $arrNav['access'] ) $arrResult[] = $arrNav;
      }
      else {
        $arrResult[] = $arrNav;
      }
    }

    return $arrResult;
  }

  public function check_sub( $arrNav = [] ){
    if ( count($arrNav['subs']) ) {
      foreach ($arrNav['subs'] as $iIndex => $arrSub) {
        if ( isset($arrSub['menu_hide']) && $arrSub['menu_hide'] ) {
          if ( count($arrNav['subs']) ) unset($arrNav['subs'][$iIndex]);
          else unset($arrNav['subs']);
        }
        // else check_sub($arrSub);
      }
    }

    return $arrNav;
  }

  function __construct()
  {
    $oLang = new lang();
    $this->arrNav = [
      '/' => array(
        'name' => $oLang->get('HomePageTitle'),
        'description' => $oLang->get('HomePageDescription'),
        'url' => '/',
        'icon' => '<i class="fa-solid fa-house"></i>',
        'access' => -1,
        'onlysite' => true,
      ),

      '/authorizations/' => array(
        'name' => $oLang->get('Authorizations'),
        'description' => $oLang->get('Authorizations'),
        'url' => '/authorizations/',
        'icon' => '<i class="fas fa-user-check"></i>',
        'menu_hide' => true,
        'access' => -1,
        'onlyapp' => true,
        'subs' => [
          '/authorizations/registration/' => array(
            'name' => $oLang->get('Registration'),
            'description' => $oLang->get('Registration'),
            'url' => '/authorizations/registration/',
            'icon' => '<i class="fas fa-user-plus"></i>',
            'menu_hide' => true,
            'access' => -1,
            'onlyapp' => true,
          ),
          '/authorizations/password_recovery/' => array(
            'name' => $oLang->get('PasswordRecovery'),
            'description' => $oLang->get('PasswordRecovery'),
            'url' => '/authorizations/password_recovery/',
            'icon' => '<i class="fa-solid fa-user-gear"></i>',
            'menu_hide' => true,
            'access' => -1,
            'onlyapp' => true,
          ),
        ]
      ),

      '/profile/' => array(
        'name' => $oLang->get('User'),
        'description' => $oLang->get('User'),
        'url' => '/profile/',
        'icon' => '<i class="fa-solid fa-user"></i>',
        'access' => 0,
        'menu_hide' => true,
        'onlyapp' => true,
      ),

      '/dashboard/' => array(
        'name' => $oLang->get('Dashboard'),
        'description' => $oLang->get('Dashboard'),
        'url' => '/dashboard/',
        'icon' => '<i class="fa-solid fa-grip"></i>',
        'access' => 0,
        'onlyapp' => true,
        'subs' => [
          '/dashboard/' => array(
            'name' => $oLang->get('Info'),
            'description' => $oLang->get('Info'),
            'url' => '/dashboard/',
            'icon' => '<i class="fa-solid fa-grip"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
          '/dashboard/days/' => array(
            'name' => $oLang->get('Days'),
            'description' => $oLang->get('Days'),
            'url' => '/dashboard/days/',
            'icon' => '<i class="fa-solid fa-calendar-day"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
        ]
      ),

      '/moneys/' => array(
        'name' => $oLang->get('Moneys'),
        'description' => $oLang->get('Moneys'),
        'url' => '/moneys/',
        'class' => '__moneys',
        'icon' => '<i class="fa-solid fa-wallet"></i>',
        'access' => 0,
        'onlyapp' => true,
        'subs' => [
          '/moneys/' => array(
            'name' => $oLang->get('Thread'),
            'description' => $oLang->get('Thread'),
            'url' => '/moneys/',
            'icon' => '<i class="fa-solid fa-bars"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
          '/moneys/analytics/costs/' => array(
            'name' => $oLang->get('Costs'),
            'description' => $oLang->get('Costs'),
            'url' => '/moneys/analytics/costs/',
            'icon' => '<i class="fa-solid fa-chart-area"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
          '/moneys/analytics/wages/' => array(
            'name' => $oLang->get('Wages'),
            'description' => $oLang->get('Wages'),
            'url' => '/moneys/analytics/wages/',
            'icon' => '<i class="fa-solid fa-chart-bar"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
          '/moneys/data/cards/' => array(
            'name' => $oLang->get('Cards'),
            'description' => $oLang->get('Cards'),
            'url' => '/moneys/data/cards/',
            'icon' => '<i class="fa-solid fa-credit-card"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
        ],
      ),

      '/times/' => array(
        'name' => $oLang->get('Times'),
        'description' => $oLang->get('Times'),
        'url' => '/times/',
        'class' => '__times',
        'icon' => '<i class="fa-solid fa-clock"></i>',
        'access' => 0,
        'onlyapp' => true,
        'subs' => [
          '/times/' => array(
            'name' => $oLang->get('Thread'),
            'description' => $oLang->get('Thread'),
            'url' => '/times/',
            'icon' => '<i class="fa-solid fa-bars"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
          '/times/analytics/costs/' => array(
            'name' => $oLang->get('Costs'),
            'description' => $oLang->get('Costs'),
            'url' => '/times/analytics/costs/',
            'icon' => '<i class="fa-solid fa-chart-bar"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
        ],
      ),

      '/tasks/' => array(
        'name' => $oLang->get('Tasks'),
        'description' => $oLang->get('Tasks'),
        'url' => '/tasks/',
        'class' => '__tasks',
        'icon' => '<i class="fa-solid fa-person-digging"></i>',
        'access' => 0,
        'onlyapp' => true,
        'subs' => [
          '/tasks/' => array(
            'name' => $oLang->get('Thread'),
            'description' => $oLang->get('Thread'),
            'url' => '/tasks/',
            'icon' => '<i class="fa-solid fa-bars"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
          '/tasks/templates/' => array(
            'name' => $oLang->get('Templates'),
            'description' => $oLang->get('Templates'),
            'url' => '/tasks/templates/',
            'icon' => '<i class="fa-solid fa-copy"></i>',
            'access' => 1,
            'onlyapp' => true,
          ),
        ],
      ),

      '/categories/' => array(
        'name' => $oLang->get('Categories'),
        'description' => $oLang->get('Categories'),
        'url' => '/categories/',
        'icon' => '<i class="fa-solid fa-list"></i>',
        'access' => 0,
        'onlyapp' => true,
        'subs' => [
          '/categories/analytics/' => array(
            'name' => $oLang->get('Category'),
            'description' => $oLang->get('Category'),
            'url' => '/categories/analytics/',
            'icon' => '<i class="fas fa-chart-area"></i>',
            'access' => 0,
            'menu_hide' => true,
            'onlyapp' => true,
          ),
        ],
      ),

      '/subscriptions/' => array(
        'name' => $oLang->get('Subscriptions'),
        'description' => $oLang->get('Subscriptions'),
        'url' => '/subscriptions/',
        'icon' => '<i class="fa-solid fa-calendar-check"></i>',
        'access' => 0,
        'onlyapp' => true,
        'subs' => [
          '/subscriptions/' => array(
            'name' => $oLang->get('Thread'),
            'description' => $oLang->get('Thread'),
            'url' => '/subscriptions/',
            'icon' => '<i class="fa-solid fa-bars"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
          '/subscriptions/month/' => array(
            'name' => $oLang->get('Month'),
            'description' => $oLang->get('Month'),
            'url' => '/subscriptions/month/',
            'icon' => '<i class="fa-solid fa-calendar-days"></i>',
            'access' => 0,
            'onlyapp' => true,
          ),
        ],
      ),

      '/clients/' => array(
        'name' => $oLang->get('Clients'),
        'description' => $oLang->get('Clients'),
        'url' => '/clients/',
        'icon' => '<i class="fa-solid fa-folder"></i>',
        'access' => 0,
        'onlyapp' => true,
      ),

      '/projects/' => array(
        'name' => $oLang->get('Projects'),
        'description' => $oLang->get('Projects'),
        'url' => '/projects/',
        'icon' => '<i class="fa-solid fa-folder-tree"></i>',
        'access' => 0,
        'onlyapp' => true,
      ),

      '/users/' => array(
        'name' => $oLang->get('Users'),
        'description' => $oLang->get('Users'),
        'url' => '/users/',
        'icon' => '<i class="fa-solid fa-users"></i>',
        'access' => 0,
        'onlyapp' => true,
        // 'menu_hide' => true,
        'subs' => [
          '/users/user/' => array(
            'name' => $oLang->get('User'),
            'description' => $oLang->get('User'),
            'url' => '/users/user/',
            'icon' => '<i class="fa-solid fa-user"></i>',
            'access' => 0,
            'menu_hide' => true,
          ),
        ]
      ),

      '/info/' => array(
        'name' => $oLang->get('Info'),
        'description' => $oLang->get('Info'),
        'url' => '/info/',
        'icon' => '<i class="fa-solid fa-circle-info"></i>',
        'menu_hide' => false,
        'subs' => [
          '/info/' => array(
            'name' => $oLang->get('Info'),
            'description' => $oLang->get('Info'),
            'url' => '/info/',
            'icon' => '<i class="fa-solid fa-circle-info"></i>',
            'access' => 0,
            'menu_hide' => false,
          ),
          '/info/versions/' => array(
            'name' => $oLang->get('Versions'),
            'description' => $oLang->get('Versions'),
            'url' => '/info/versions/',
            'icon' => '<i class="fas fa-code-branch"></i>',
            'menu_hide' => false,
          ),
          '/info/buy/' => array(
            'name' => $oLang->get('Donate'),
            'description' => $oLang->get('Donate'),
            'url' => '/info/buy/',
            'icon' => '<i class="fas fa-donate"></i>',
            'menu_hide' => false,
          ),
          '/info/contacts/' => array(
            'name' => $oLang->get('Contacts'),
            'description' => $oLang->get('Contacts'),
            'url' => '/info/contacts/',
            'icon' => '<i class="fas fa-info"></i>',
            'menu_hide' => false,
          ),
          '/info/analytics/' => array(
            'name' => $oLang->get('Analytics'),
            'description' => $oLang->get('Analytics'),
            'url' => '/info/analytics/',
            'icon' => '<i class="fas fa-chart-line"></i>',
            'menu_hide' => false,
          ),
          '/info/docs/' => array(
            'name' => $oLang->get('Docs'),
            'description' => $oLang->get('Docs'),
            'url' => '/info/docs/',
            'icon' => '<i class="fas fa-book-dead"></i>',
            'menu_hide' => false,
          ),
          '/info/android/' => array(
            'name' => $oLang->get('AppVersionForAndroid'),
            'description' => $oLang->get('AppVersionForAndroid'),
            'url' => '/info/android/',
            'icon' => '<i class="fa-brands fa-google"></i>',
            'menu_hide' => true,
          ),
          '/info/apple/' => array(
            'name' => $oLang->get('AppVersionForIphone'),
            'description' => $oLang->get('AppVersionForIphone'),
            'url' => '/info/apple/',
            'icon' => '<i class="fa-brands fa-apple"></i>',
            'menu_hide' => true,
          ),
        ],
      ),

      '/admin/' => array(
        'name' => $oLang->get('AdminPanel'),
        'description' => $oLang->get('AdminPanel'),
        'url' => '/admin/',
        'icon' => '<i class="fa-solid fa-screwdriver-wrench"></i>',
        'access' => 500,
        'onlyapp' => true,
        'subs' => [
          '/admin/rewards/' => array(
            'name' => $oLang->get('Rewards'),
            'description' => $oLang->get('Rewards'),
            'url' => '/admin/rewards/',
            'icon' => '<i class="fa-solid fa-award"></i>',
            'access' => 500,
            'onlyapp' => true,
          ),
          '/admin/users/' => array(
            'name' => $oLang->get('Users'),
            'description' => $oLang->get('Users'),
            'url' => '/admin/users/',
            'icon' => '<i class="fa-solid fa-users"></i>',
            'access' => 500,
            'onlyapp' => true,
          ),
          '/admin/accesses/' => array(
            'name' => $oLang->get('Accesses'),
            'description' => $oLang->get('Accesses'),
            'url' => '/admin/accesses/',
            'icon' => '<i class="fa-solid fa-user-clock"></i>',
            'access' => 500,
            'onlyapp' => true,
          ),
          '/admin/notices/' => array(
            'name' => $oLang->get('Notices'),
            'description' => $oLang->get('Notices'),
            'url' => '/admin/notices/',
            'icon' => '<i class="fa-regular fa-bell"></i>',
            'access' => 500,
            'onlyapp' => true,
          ),
          '/admin/notices/views/' => array(
            'name' => $oLang->get('NoticesViews'),
            'description' => $oLang->get('NoticesViews'),
            'url' => '/admin/notices/views/',
            'icon' => '<i class="fa-solid fa-bell"></i>',
            'access' => 500,
            'onlyapp' => true,
          ),
          '/admin/currencies/' => array(
            'name' => $oLang->get('Currencies'),
            'description' => $oLang->get('Currencies'),
            'url' => '/admin/currencies/',
            'icon' => '<i class="fa-solid fa-wallet"></i>',
            'access' => 500,
            'onlyapp' => true,
          ),
        ]
      ),
    ];

    $this->get_navs();
    $this->get_path();
  }
}
