<?
/**
 * Lang
 */

/*
  <?=$olang->get('Clear')?>
  $olang->get('Clear')
*/

class lang
{
  public $sUserLang = '';
  public $arrLang = [];

  // Получение перевода
  function get( $sText = '' ){
    // load lang
    switch ($this->sUserLang) {
      case 'ru':
        $this->ru();
        break;
      case 'en':
        $this->en();
        break;
    }
    // return $this->sUserLang;
    if ( $this->arrLang[$this->sUserLang] && isset($this->arrLang[$this->sUserLang][$sText]) ) return $this->arrLang[$this->sUserLang][$sText];
    else return $sText;
  }

  function ru(){
    $this->arrLang['ru'] = [
      'For' => 'За',
      'Current' => 'Текущий',
      'Excluded' => 'Не учитывать',
      'Spent' => 'Потрачено',
      'Really' => 'Реально',
      'Received' => 'Получено',
      'Views' => 'Просмотры',
      'View' => 'Просмотр',
      'Icons' => 'Иконки',
      'Icon' => 'Иконка',
      'Href' => 'Ссылка',
      'Notifications' => 'Уведомления',
      'Notification' => 'Уведомление',
      'Notices' => 'Уведомления',
      'Notice' => 'Уведомление',
      'NoticeView' => 'Просмотр уведомления',
      'NoticesViews' => 'Просмотры уведомлений',

      'Currency' => 'Валюта',
      'Currencies' => 'Валюты',

      'HelloCards' => 'Добавьте свои карты, счета и наличные,<br/> чтобы отслеживать баланс и расходы',
      'HelloSubscriptions' => 'Добавьте ежемесячные платежи<br/> Чтобы отслеживать сколько и когда платить',
      'HelloWelcome' => 'Краткая информация<br/> для знакомства с сервисом',

      'ErrorLoginOrPassword' => 'Неправильный логин или пароль!',
      'SuccessfulLogin' => 'Успешный вход!',
      'SuccessfulExit' => 'Успешный выход!',
      'PasswordsDoNotMatch' => 'Пароли не совпадают',
      'ThisloginIsAlreadyTaken' => 'Такой логин уже используется.',
      'ThisEmailIsAlreadyTaken' => 'Такая почта уже используется.',
      'RegistrationCompletedSuccessfully' => 'Регистрация прошла успешно! (:',
      'SomethingWentWrongOhCallTheSuperProgrammers' => 'Что-то пошло не так, о, зовите супер программистов!',
      'ErrorSavingPassword' => 'Ошибка сохранения пароля',
      'PasswordRecovery' => 'Восстановление пароля',
      'AccountPasswordСhanged' => 'Пароль изменён',
      'NewPasswordSendToMail' => 'Новый пароль отправлен на email',
      'ThisEmailIsNotRegistered' => 'Такой email не зарегистрирован!',
      'YouAccountHasBeenDeleted' => 'Ваш аккаунт успешно удалён!',
      'Try' => 'Попробовать',
      'More' => 'Подробнее',
      'MoreInDocs' => 'Подробнее в документации',
      'LockText' => 'У вас нет доступа ): Нажмите сюда чтобы получить его',
      'ButtonSaveAndClose' => 'Сохранить и закрыть',
      'ButtonSaveAndCloseMin' => 'OK',
      'Copy' => 'Копировать',
      'StartIn' => 'начать в',
      'DownloadFrom' => 'скачать с',
      'Thread' => 'Лента',
      'LastDateAccess' => 'Доступ предоставлено до',
      'AccessDateStop' => 'Доступ предоставлен до',
      'AccessDateStart' => 'Доступ предоставлен с',
      'Accesslevel' => 'Уровень доступа',

      'Rewards' => 'Награды',
      'Reward' => 'Награда',
      'RewardsUsers' => 'Награды пользователей',
      'RewardsForStart' => 'Доступ за регистрацию',
      'RewardsForReferal' => 'Доступ за приглашение пользователя',
      'RewardUser' => 'Награда пользователя',
      'Access' => 'Доступ',
      'Accesses' => 'Доступы',
      'AccessesDenied' => 'Нет доступа',
      'Admin' => 'Администратор',
      'AdminPanel' => 'Админ панель',
      'Accesses' => 'Доступы',

      'HomePageTitle' => 'u0life',
      'HomePageDescription' => 'Оцифровка твоей жизни. Система управления деньгами, временем и задачами.',
      'Categories' => 'Категории',
      'CategoryLimitError' => 'Превышен лимит категорий: ',
      'Category' => 'Категория',
      'NoCategory' => 'Без категории',
      'Date' => 'Дата',
      'Spend' => 'Трата',
      'Balance' => 'Баланс',
      'BalanceOnCredit' => 'Учитывая кредитные карты',
      'Replenish' => 'Получение',
      'NoProject' => 'Не проект',
      'Commissions' => 'Комиссия',

      // Подписки
      'Subscription' => 'Подписка',
      'SubscriptionPaid' => 'Подписка',
      'SubscriptionSum' => 'Нужно оплатить',
      'SubscriptionSumClear' => 'Тут будет отображаться платежи по подпискам, сколько и когда.<br/>
      Добавьте свои ежемесячные платежи в <a href="/subscriptions/">подписки</a>, чтобы контролировать их (:',
      'SubscriptionSumPaid' => 'Уже оплачено',
      'SubscriptionSumNeedPaid' => 'Осталось оплатить',
      'SubscriptionDayPaid' => 'Даты платежей',
      'NoSubscription' => 'Не подписка',
      'Subscriptions' => 'Подписки',

      'NoTask' => 'Не задача',
      'Tasks' => 'Задачи',
      'Task' => 'Задача',
      'Welcome' => 'Добро пожаловать',
      'WebVersion' => 'Веб-версия',
      'TemplateNotFound' => 'Шаблон не найден',

      'Analytics' => 'Аналитика',
      'MoneyPerHour' => 'Денег за час',

      'Moneys' => 'Деньги',
      'Money' => 'Деньги',
      'Payment' => 'Платёж',
      'PaymentDay' => 'День платежа',
      'EveryMonth' => 'Ежемесячно',
      'Cards' => 'Карты',
      'CardDebit' => 'Дебетовая карта',
      'CardReloadBalanceBtn' => 'Пересчитать баланс',
      'CardUpdate' => 'Карта обновлена',
      'CardCredit' => 'Кредитная карта',
      'CardBill' => 'Счёт',
      'CardService' => 'Обслуживание карты',
      'CardServiceDate' => 'Дата платёжа за обслуживание',
      'CardFreeDaysLimit' => 'Количество безпроцентных дней',
      'CardDateCommissions' => 'Дата платежа коммиссий',
      'CardDateBillPercent' => 'Дата зачисления процентов',
      'CardMinPayment' => 'Минимальный платёж',
      'CardMinPaymentPercent' => 'Процент минимального платежа',
      'CardMinPaymentDate' => 'Дата минимального платежа',
      'Card' => 'Карта',
      'ToCard' => 'На карту',
      'FromCard' => 'С карты',
      'Price' => 'Цена',
      'Status' => 'Статус',
      'Cash' => 'Наличные',

      'NoStatus' => 'Без статуса',
      'Planned' => 'В планах',
      'InWork' => 'В работе',
      'Complited' => 'Выполено',

      'PricePlanned' => 'Планируемая стоимость',
      'MoneysCategories' => 'Категории для финансов',

      'Times' => 'Время',
      'Time' => 'Время',
      'TimesPlanned' => 'Планируемое время',
      'TimesReally' => 'Реальное время',
      'TimesSpent' => 'Потраченное время',
      'Effeciency' => 'Эффективность',

      'User' => 'Пользователь',
      'Users' => 'Пользователи',
      'UserEdit' => 'Редактирование пользователя',
      'UserPasswordEdit' => 'Редактирование пароля пользователя',
      'OldPassword' => 'Старый пароль',
      'NewPassword' => 'Новый пароль',
      'NewPasswordComfirm' => 'Подтверждение нового пароля',

      'Phone' => 'Телефон',
      'Email' => 'Почта',
      'DateRegistration' => 'Регистрация',
      'Registration' => 'Rегистрации',
      'Authorizations' => 'Авторизация',
      'ReferalLink' => 'Реферальная ссылка',
      'Login' => 'Прозвище',
      'Theme' => 'Оформление',
      'Description' => 'Описание',
      'Lang' => 'Язык',
      'Title' => 'Название',
      'Type' => 'Тип',
      'Active' => 'Активность',
      'ShowNoActive' => 'Показывать не активные',
      'Clear' => 'Отчистить',
      'Client' => 'Клиент',
      'Clients' => 'Клиенты',
      'Add' => 'Добавить',
      'Save' => 'Сохранить',
      'Edit' => 'Редактировать',
      'Exit' => 'Выход',
      'Other' => 'Прочее',
      'Config' => 'Конфигурации',
      'Info' => 'Информация',
      'Sort' => 'Сортировка',
      'Color' => 'Цвет',
      'Limit' => 'Лимит',
      'Percent' => 'Процент',
      'ChangesSaved' => 'Изменения сохранены',
      'DeleteSuccess' => 'Успешное удаление',
      'ChangePassword' => 'Поменять пароль',
      'PasswordRecovery' => 'Восстановление пароля',
      'Delete' => 'Удалить',
      'Transfer' => 'Перевод',
      'Dashboard' => 'Панель управления',

      'Day' => 'День',
      'ToDay' => 'Сегодня',
      'Week' => 'Неделя',
      'Month' => 'Месяц',
      'Year' => 'Год',
      'CurrentYear' => 'Текущий год',
      'CurrentMonth' => 'Текущий месяц',
      'CurrentWeek' => 'Текущая неделя',
      'CurrentDay' => 'Текущий день',
      'PrevYear' => 'Предыдущий год',
      'PrevMonth' => 'Предыдущий месяц',
      'PrevWeek' => 'Предыдущая неделя',
      'PrevDay' => 'Предыдущий день',
      'LastUpdate' => 'Обновление',
      'DateCreate' => 'Создан',

      'January' => 'Январь',
      'February' => 'Февраль',
      'March' => 'Март',
      'April' => 'Апрель',
      'May' => 'Май',
      'June' => 'Июнь',
      'July' => 'Июль',
      'August' => 'Август',
      'September' => 'Сентябрь',
      'October' => 'Октябрь',
      'November' => 'Ноябрь',
      'December' => 'Декабрь',

      'Sunday' => 'Воскресенье',
      'Monday' => 'Понедельник',
      'Tuesday' => 'Вторник',
      'Wednesday' => 'Среда',
      'Thursday' => 'Четверг',
      'Friday' => 'Пятница',
      'Saturday' => 'Суббота',

      'CategoryAnalyticsLineType1' => 'Только деньги',
      'CategoryAnalyticsLineType2' => 'Только время',
      'CategoryAnalyticsLineType3' => 'Деньги и время',

      'Mo' => 'Пн',
      'Tu' => 'Вт',
      'We' => 'Ср',
      'Th' => 'Чт',
      'Fr' => 'Пт',
      'Sa' => 'Сб',
      'Su' => 'Вс',

      'Transport' => 'Транспорт',
      'Sleeping' => 'Сон',
      'Smoking' => 'Курение',
      'Sleep' => 'Сон',
      'Working' => 'Работа',
      'Work' => 'Работа',
      'Eating' => 'Еда',
      'ThisService' => 'Этот сервис',

      'Costs' => 'Расходы',
      'Sum' => 'Сумма',
      'Wages' => 'Доходы',

      'WagesWork' => 'Заработано',

      'Home' => 'Главная',
      'Projects' => 'Проекты',
      'Project' => 'Проект',
      'Alcohol' => 'Алкоголь',
      'House' => 'Дом',
      'Health' => 'Здоровье',
      'Movies' => 'Фильмы',
      'Clothing' => 'Одежда',

      'Protect' => 'Защита',
      'ProtectType' => 'Тип защиты',
      'ProtectYes' => 'Защита установлена',
      'ProtectNo' => 'Защита не установлена',
      'ProtectKeyYes' => 'Защита по ключу',

      'Versions' => 'Версии',
      'Contacts' => 'Контакты',
      'Donate' => 'Донаты',
      'Docs' => 'Документация',
      'Menu' => 'Меню',
      'Filter' => 'Фильтровать',
      'MenuSub' => 'Доп.меню',
      'SignUp' => 'Регистрация',
      'SignIn' => 'Вход',
    ];
  }

  function en(){
    $this->arrLang['en'] = [
      'For' => 'For',
      'Current' => 'Current',
      'Excluded' => 'Excluded',
      'Spent' => 'Spent',
      'Really' => 'Really',
      'Received' => 'Received',
      'Views' => 'Views',
      'View' => 'View',
      'Icons' => 'Icons',
      'Icon' => 'Icon',
      'Href' => 'Href',
      'Notifications' => 'Notifications',
      'Notification' => 'Notification',
      'Notices' => 'Notices',
      'Notice' => 'Notice',
      'NoticeView' => 'Notice view',
      'NoticesViews' => 'Notices views',

      'Currency' => 'Currency',
      'Currencies' => 'Currencies',

      'HelloCards' => 'Add your cards, bills and cash,<br/> to track your balance and spending',
      'HelloSubscriptions' => 'Add monthly payments<br/> To track how much and when to pay',
      'HelloWelcome' => 'Brief information <br/> to get acquainted with the service',

      'ErrorLoginOrPassword' => 'Error entering login or password!',
      'SuccessfulLogin' => 'Successful login!',
      'SuccessfulExit' => 'Successful Exit!',
      'PasswordsDoNotMatch' => 'Passwords do not match.',
      'ThisloginIsAlreadyTaken' => 'This login is already taken.',
      'ThisEmailIsAlreadyTaken' => 'This email is already taken.',
      'RegistrationCompletedSuccessfully' => 'Registration completed successfully! (:',
      'SomethingWentWrongOhCallTheSuperProgrammers' => 'Something went wrong, oh, call the super programmers!',
      'ErrorSavingPassword' => 'Error saving password.',
      'PasswordRecovery' => 'Password recovery',
      'AccountPasswordСhanged' => 'Account password changed',
      'NewPasswordSendToMail' => 'New password send to mail',
      'ThisEmailIsNotRegistered' => 'This email is not registered.',
      'YouAccountHasBeenDeleted' => 'You account has been deleted!',
      'Try' => 'Try',
      'More' => 'More',
      'MoreInDocs' => 'More in docs',
      'LockText' => 'You do not have access ): get?',
      'ButtonSaveAndClose' => 'Save and close',
      'ButtonSaveAndCloseMin' => 'OK',
      'Copy' => 'Copy',
      'StartIn' => 'start in',
      'DownloadFrom' => 'download from',
      'Thread' => 'Thread',
      'LastDateAccess' => 'Last date access',
      'AccessDateStop' => 'Access granted until',
      'AccessDateStart' => 'Access granted from',
      'Accesslevel' => 'Access level',

      'Rewards' => 'Rewards',
      'Reward' => 'Reward',
      'RewardsUsers' => 'RewardsUsers',
      'RewardsForStart' => 'Rewards for registration',
      'RewardsForReferal' => 'Rewards for referal',
      'RewardUser' => 'RewardUser',
      'Access' => 'Access',
      'Accesses' => 'Accesses',
      'AccessesDenied' => 'Accesses denied',
      'Admin' => 'Admin',
      'AdminPanel' => 'Admin panel',
      'Accesses' => 'Accesses',

      'HomePageTitle' => 'u0life',
      'HomePageDescription' => 'Digitization you life. Time, money, tasks manager system!',
      'Categories' => 'Categories',
      'CategoryLimitError' => 'Category limit exceeded: ',
      'Category' => 'Category',
      'NoCategory' => 'No category',
      'Date' => 'Date',
      'Spend' => 'Spend',
      'Balance' => 'Balance',
      'BalanceOnCredit' => 'Including credit cards',
      'Replenish' => 'Replenish',
      'NoProject' => 'No project',
      'Commissions' => 'Commissions',

      // Подписки
      'Subscription' => 'Subscription',
      'SubscriptionPaid' => 'SubscriptionPaid',
      'SubscriptionSum' => 'Need to pay',
      'SubscriptionSumClear' => 'Subscription payments will be displayed here, how much and when.<br/>
               Add your monthly payments to <a href="/subscriptions/">subscriptions</a> to control them (:',
      'SubscriptionSumPaid' => 'Already paid',
      'SubscriptionSumNeedPaid' => 'It remains to pay',
      'SubscriptionDayPaid' => 'Payment dates',
      'NoSubscription' => 'No subscription',
      'Subscriptions' => 'Subscriptions',

      'NoTask' => 'No Task',
      'Tasks' => 'Tasks',
      'Task' => 'Task',
      'Welcome' => 'Welcome',
      'WebVersion' => 'WebVersion',
      'TemplateNotFound' => 'Template not found',

      'Analytics' => 'Analytics',
      'MoneyPerHour' => 'Money per hour',

      'Moneys' => 'Money',
      'Money' => 'Money',
      'Payment' => 'Payment',
      'PaymentDay' => 'Payment day',
      'EveryMonth' => 'Every month',
      'Cards' => 'Cards',
      'CardDebit' => 'Card debit',
      'CardReloadBalanceBtn' => 'Reload balance',
      'CardUpdate' => 'Card update',
      'CardCredit' => 'Card credit',
      'CardBill' => 'Card bill',
      'CardService' => 'Card service',
      'CardServiceDate' => 'Card service date',
      'CardFreeDaysLimit' => 'Card free days limit',
      'CardDateCommissions' => 'Card date commissions',
      'CardDateBillPercent' => 'Card date bill enrollment',
      'CardMinPayment' => 'Card min payment',
      'CardMinPaymentPercent' => 'Percent card min payment',
      'CardMinPaymentDate' => 'Date card min payment',
      'Card' => 'Card',
      'ToCard' => 'To card',
      'FromCard' => 'From card',
      'Price' => 'Price',
      'Status' => 'Status',
      'Cash' => 'Cash',

      'NoStatus' => 'No status',
      'Planned' => 'Planned',
      'InWork' => 'In work',
      'Complited' => 'Complited',

      'PricePlanned' => 'Price planned',
      'MoneysCategories' => 'Moneys categories',

      'Times' => 'Time',
      'Time' => 'Time',
      'TimesPlanned' => 'Time planned',
      'TimesReally' => 'Time really',
      'TimesSpent' => 'Spent time',
      'Effeciency' => 'Effeciency',

      'User' => 'User',
      'Users' => 'Users',
      'UserEdit' => 'User edit',
      'UserPasswordEdit' => 'User password edit',
      'OldPassword' => 'Old password',
      'NewPassword' => 'New password',
      'NewPasswordComfirm' => 'New password comfirm',

      'Phone' => 'Phone',
      'Email' => 'Email',
      'DateRegistration' => 'Date registration',
      'Registration' => 'Registration',
      'Authorizations' => 'Authorization',
      'ReferalLink' => 'Referal link',
      'Login' => 'Login',
      'Theme' => 'Theme',
      'Description' => 'Description',
      'Lang' => 'Lang',
      'Title' => 'Title',
      'Type' => 'Type',
      'Active' => 'Active',
      'ShowNoActive' => 'Show no active',
      'Clear' => 'Clear',
      'Client' => 'Client',
      'Clients' => 'Clients',
      'Add' => 'Add',
      'Save' => 'Save',
      'Edit' => 'Edit',
      'Exit' => 'Exit',
      'Other' => 'Other',
      'Config' => 'Config',
      'Info' => 'Info',
      'Sort' => 'Sort',
      'Color' => 'Color',
      'Limit' => 'Limit',
      'Percent' => 'Percent',
      'ChangesSaved' => 'Changes saved',
      'DeleteSuccess' => 'Delete success',
      'ChangePassword' => 'Change password',
      'PasswordRecovery' => 'Password recovery',
      'Delete' => 'Delete',
      'Transfer' => 'Transfer',
      'Dashboard' => 'Dashboard',

      'Day' => 'Day',
      'ToDay' => 'To day',
      'Week' => 'Week',
      'Month' => 'Month',
      'Year' => 'Year',
      'CurrentYear' => 'Current year',
      'CurrentMonth' => 'Current month',
      'CurrentWeek' => 'Current week',
      'CurrentDay' => 'Current day',
      'PrevYear' => 'Prev year',
      'PrevMonth' => 'Prev month',
      'PrevWeek' => 'Prev week',
      'PrevDay' => 'Prev day',
      'LastUpdate' => 'Update',
      'DateCreate' => 'Create',

      'January' => 'January',
      'February' => 'February',
      'March' => 'March',
      'April' => 'April',
      'May' => 'May',
      'June' => 'June',
      'July' => 'July',
      'August' => 'August',
      'September' => 'September',
      'October' => 'October',
      'November' => 'November',
      'December' => 'December',

      'Sunday' => 'Sunday',
      'Monday' => 'Monday',
      'Tuesday' => 'Tuesday',
      'Wednesday' => 'Wednesday',
      'Thursday' => 'Thursday',
      'Friday' => 'Friday',
      'Saturday' => 'Saturday',

      'CategoryAnalyticsLineType1' => 'Only money',
      'CategoryAnalyticsLineType2' => 'Only time',
      'CategoryAnalyticsLineType3' => 'Money and time',

      'Mo' => 'Mo',
      'Tu' => 'Tu',
      'We' => 'We',
      'Th' => 'Th',
      'Fr' => 'Fr',
      'Sa' => 'Sa',
      'Su' => 'Su',

      'Transport' => 'Transport',
      'Sleeping' => 'Sleeping',
      'Smoking' => 'Smoking',
      'Sleep' => 'Sleep',
      'Working' => 'Working',
      'Work' => 'Work',
      'Eating' => 'Eating',
      'ThisService' => 'This service',

      'Costs' => 'Costs',
      'Sum' => 'Sum',
      'Wages' => 'Wages',

      'WagesWork' => 'Wages for work',

      'Home' => 'Home',
      'Projects' => 'Projects',
      'Project' => 'Project',
      'Alcohol' => 'Alcohol',
      'House' => 'Alcohol',
      'Health' => 'Health',
      'Movies' => 'Movies',
      'Clothing' => 'Clothing',

      'Protect' => 'Protect',
      'ProtectType' => 'Protect type',
      'ProtectYes' => 'Protect yes',
      'ProtectNo' => 'Protect no',
      'ProtectKeyYes' => 'Protect key yes',

      'Versions' => 'Versions',
      'Contacts' => 'Contacts',
      'Donate' => 'Donate',
      'Docs' => 'Docs',
      'Menu' => 'Menu',
      'Filter' => 'Filter',
      'MenuSub' => 'Subs',
      'SignUp' => 'Sign up',
      'SignIn' => 'Sign in',
    ];
  }

  function __construct()
  {
    // Берём язык пользователя
    if ( $_SESSION['user'] && $_SESSION['user']['lang'] ) {
      $this->sUserLang = $_SESSION['lang'] = $_SESSION['user']['lang'];
    }
    // Другое
    else {
      // Указанные в сессии
      if ( $_SESSION['lang'] ) {
        $this->sUserLang = $_SESSION['lang'];
      }
      else {
        // Автоопределения от браузера
        if ( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ) {
          $arrlang = explode(';',$_SERVER['HTTP_ACCEPT_LANGUAGE']);
          $arrlangs = explode(',',$arrlang[0]);
          if ( count($arrlangs) ) {
            $slang = $arrlangs[1];
            if ( $slang ) $this->sUserLang = $_SESSION['lang'] = $slang;
            else $this->sUserLang = $_SESSION['lang'] = 'en';
          }
        }
      }
    }

    $this->arrLang = [];
  }
}
$oLang = new lang();
