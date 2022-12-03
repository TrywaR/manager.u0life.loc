<?
// Обработка запросов к приложению
header('Access-Control-Allow-Origin: *'); # Для подключения из вне
header("Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT");
header("Access-Control-Max-Age: 0");
header("Content-Security-Policy: default-src *; connect-src *; script-src *; object-src *;");
header("X-Content-Security-Policy: default-src *; connect-src *; script-src *; object-src *;");
header("X-Webkit-CSP: default-src *; connect-src *; script-src 'unsafe-inline' 'unsafe-eval' *; object-src *;");

// Проверка сессии и пользователя. если запрос из приложения
// if ( $_REQUEST['action'] != 'sessions' ) {
  $oSession = new session();
  $oSession->install();
// }

$olang = new lang(); // Подтягиваем языки
$oLock = new lock(); // Подтягиваем уровни доступов

// Если пользователь залогинен, выдаём инфу, которую запросили
if ( isset($_SESSION['user']) ) {
  switch ($_REQUEST['action']) {
    case 'authorizations': # Вход и регистрации
      include_once 'authorizations/authorizations.php';
      break;

    case 'clients': # Обработка клиентов
      include_once 'clients/clients.php';
      break;

    case 'clients_analytics': # Обработка клиентов
      include_once 'clients/analytics/analytics.php';
      break;

    case 'dashboards': # Главная страница
      include_once 'dashboards/dashboards.php';
      break;

    case 'navs': # Структура сайта
      include_once 'navs/navs.php';
      break;

    case 'analytics': # Общая статистика
      include_once 'analytics/analytics.php';
      break;

    case 'sessions': # Обработка сессий
      include_once 'sessions/sessions.php';
      break;

    case 'sessions_configs': # Обработка сессий
      include_once 'sessions/configs.php';
      break;

    case 'categories': # Категории
      include_once 'categories/categories.php';
      break;

    case 'categories_configs': # Категории
      include_once 'categories/categories_configs.php';
      break;

    case 'categories_analytics': # Категории
      include_once 'categories/analytics.php';
      break;

    case 'projects': # Обработка проектов
      include_once 'projects/projects.php';
      break;

    case 'projects_analytics': # Статистика
      include_once 'projects/analytics.php';
      break;

    case 'projects_analytics_money': # Статистика
      include_once 'projects/analytics_money.php';
      break;

    case 'projects_analytics_times': # Статистика
      include_once 'projects/analytics_times.php';
      break;

    case 'tasks': # Задачи по проектам
      include_once 'tasks/tasks.php';
      break;

    case 'tasks_templates': # Задачи по проектам (Шаблоны)
      include_once 'tasks/templates.php';
      break;

    case 'times': # Время
      include_once 'times/times.php';
      include_once 'times/analytics.php';
      break;

    case 'profiles': # Профиль
      include_once 'profiles/profiles.php';
      break;

    case 'users': # Пользователи
      include_once 'users/users.php';
      break;

    case 'moneys': # Обработка денежек
      include_once 'moneys/moneys.php';
      include_once 'moneys/analytics.php';
      break;

    case 'cards': # Карточки для денежек
      include_once 'cards/cards.php';
      break;

    case 'subscriptions': # Подписки
      include_once 'subscriptions/subscriptions.php';
      break;

    case 'charts': # Графики
      include_once 'charts/charts.php';
      break;

    case 'templates': # Шаблоны
      include_once 'templates/templates.php';
      break;

    case 'accesses': # Доступы
      include_once 'accesses/accesses.php';
      break;

    case 'rewards': # Награды
      include_once 'rewards/rewards.php';
      break;

    case 'rewards_users': # Награды пользователей
      include_once 'rewards/users/users.php';
      break;

    case 'contents': # Содержание
      include_once 'contents/contents.php';
      break;

    case 'notices': # Уведомления
      include_once 'notices/notices.php';
      break;

    case 'notices_views': # Просмотр Уведомления
      include_once 'notices/views/views.php';
      break;

    case 'currencies': # Валюты
      include_once 'currencies/currencies.php';
      break;
  }
}

// Если нет, ловим авторизацию
else {
  // Выводим содержание
  switch ($_REQUEST['action']) {
    case 'navs': # Структура сайта
      include_once 'navs/navs.php';
      break;

    case 'sessions':
      include_once 'sessions/sessions.php'; #Сессии
      break;

    case 'sessions_configs': # Обработка сессий
      include_once 'sessions/configs.php';
      break;

    case 'contents': # Содержание
      include_once 'contents/contents.php';
      break;

    case 'templates': # Шаблоны
      include_once 'templates/templates.php';
      break;

    default:
      // Авторизация
      include_once 'authorizations/authorizations.php'; # Авторизация
      break;
  }
}
