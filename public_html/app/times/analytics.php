<?
switch ($_REQUEST['form']) {
  case 'analytics_week': # Вывод статистики за неделю
    $arrResults = [];
    $arrCategories = [];
    $arrWeek = [];

    // Считаем неделю
    $arrWeek = [];
    if ( (int)$_REQUEST['week'] ) {
      $dDateStart = $dDateCurrent = date('Y-m-d', strtotime('last sunday -7 days'));
      $dDateStop = date('Y-m-d', strtotime('last sunday'));
    }
    else {
      $dDateStart = $dDateCurrent = date('Y-m-d', strtotime('monday this week'));
      $dDateStop = date('Y-m-d', strtotime('sunday this week'));
    }

    // Получаем категории
    $oCategory = new category();
    $oCategory->limit = 0;
    $oCategory->sortname = 'sort';
    $oCategory->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
    $oCategory->query .= ' AND `active` > 0';
    $oCategory->query .= ' AND `id` != 55';
    $arrCategories = $oCategory->get_categories();
    // Берём конфики костомных категорий пользователя
    $oCategoryConf = new category_config();
    $arrCategories = $oCategoryConf->update_categories($arrCategories);
    // Вычищаем не активные
    $arrCategories = $oCategoryConf->update_categories_active($arrCategories);
    $arrCategoriesIds = [];
    foreach ($arrCategories as &$arrCategory) {
      $arrCategoriesIds[$arrCategory['id']] = $arrCategory;
      $arrCategoriesIds[$arrCategory['id']]['sum'] = '00:00:00';
    }

    $arrResults['categories'] = $arrCategoriesIds;
    $arrResults['data'] = [];
    $arrTimesSum = [];

    // Обработка данных
    $iIndex = 1;
    while (strtotime($dDateCurrent) < strtotime($dDateStop)) {
      if ( $iIndex > 1 ) $dDateCurrent = date('Y-m-d', strtotime('+1 day', strtotime($dDateCurrent)));

      // Заполняем данные
      $arrResults['data'][$iIndex]['title'] = $dDateCurrent;

      // Собираем потраченное время
      $oTime = new time();
      $oTime->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
      $oTime->query .= " AND `date` = '" . $dDateCurrent . "'";
      $oTime->query .= ' AND `category_id` != 55';
      $arrData = $oTime->get();

      // Сумма
      $arrDaysTimes = [];

      // Подготавливаем категории
      foreach ($arrCategories as $key => $arrCategory) {
        $arrResults['data'][$iIndex]['categories'][$key]['title'] = $arrCategory['title'];
        $arrResults['data'][$iIndex]['categories'][$key]['value'] = '00:00:00';
        $arrResults['data'][$iIndex]['categories'][$key]['color'] = $arrCategory['color'];
      }

      // Записываем данные по категориям за неделю
      foreach ($arrData as & $arrDataItem) {
        $dDateReally = new DateTime($arrDataItem['time_really']);
        $arrTimesSum[] = $arrDaysTimes[] = $arrDataItem['time'] = $dDateReally->format('H:i:s');
        if ( $arrResults['data'][$iIndex]['categories'][$arrDataItem['category_id']]['value'] == '00:00:00' ) $arrResults['data'][$iIndex]['categories'][$arrDataItem['category_id']]['value'] = $arrDataItem['time'];
        else $arrResults['data'][$iIndex]['categories'][$arrDataItem['category_id']]['value'] =  $oTime->get_sum( [$arrResults['data'][$iIndex]['categories'][$arrDataItem['category_id']]['value'], $arrDataItem['time']]);

        // Суммы по категориям
        if ( isset($arrResults['categories'][$arrDataItem['category_id']]) )
          $arrResults['categories'][$arrDataItem['category_id']]['sum'] = $oTime->get_sum( [$arrResults['categories'][$arrDataItem['category_id']]['sum'], $arrDataItem['time']]);
      }

      $arrResults['data'][$iIndex]['sum'] = $oTime->get_sum( $arrDaysTimes );
      $arrResults['sum'] = floor((float)$arrResults['sum'] + (float)$oTime->get_sum( $arrDaysTimes ));
      $iIndex++;
    }

    // Заменяем значения дат на не роботские
    foreach ( $arrResults['data'] as & $arrMonth ) {
      $arrTime = explode(':',$arrMonth['sum']);
      $iTimeSum = $arrTime[0] . '.' . $arrTime[1];
      $arrMonth['sum'] = $iTimeSum;

      foreach ( $arrMonth['categories'] as & $arrCategory ) {
        $arrTime = explode(':',$arrCategory['value']);
        $iTimeSum = $arrTime[0] . '.' . $arrTime[1];
        $arrCategory['value'] = $iTimeSum;
      }
    }

    // Создаём график
    $oChart = new chart();
    $oChart->arrDataset = $arrResults['data'];
    $oChart->arrCategories = $arrResults['categories'];
    if ( $_REQUEST['chart_type'] ) $oChart->sChartType = $_REQUEST['chart_type'];
    if ( $_REQUEST['sChartScaleX'] ) $oChart->sChartScaleX = $_REQUEST['sChartScaleX'];
    if ( $_REQUEST['sChartScaleY'] ) $oChart->sChartScaleY = $_REQUEST['sChartScaleY'];
    if ( $_REQUEST['sChartScaleStackedX'] ) $oChart->sChartScaleStackedX = $_REQUEST['sChartScaleStackedX'];
    if ( $_REQUEST['sChartScaleStackedY'] ) $oChart->sChartScaleStackedY = $_REQUEST['sChartScaleStackedY'];

    $arrResults['chart'] = $oChart->show();
    $arrResults['chart_sum'] = $oChart->show_sum();

    // Сортируем суммы категорий
    usort($arrResults['categories'], function($a, $b){
      return -($a['sum'] - $b['sum']);
    });
    // округляем
    foreach ($arrResults['categories'] as &$arrCategory) $arrCategory['sum'] = round($arrCategory['sum']);

    notification::success($arrResults);
    break;

  case 'analytics_month': # Вывод статистики за месяц
    $arrResults = [];
    $arrCategories = [];

    $iYear = (int)$_REQUEST['year'] ? $_REQUEST['year'] : date('Y');
    $iMonth = (int)$_REQUEST['month'] ? $_REQUEST['month'] : date('m');
    $iMonthDaysSum = cal_days_in_month(CAL_GREGORIAN, $iMonth, $iYear);

    // Получаем категории
    $oCategory = new category();
    $oCategory->limit = 0;
    $oCategory->sortname = 'sort';
    $oCategory->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
    $oCategory->query .= ' AND `active` > 0';
    $oCategory->query .= ' AND `id` != 55';
    $arrCategories = $oCategory->get_categories();
    // Берём конфики костомных категорий пользователя
    $oCategoryConf = new category_config();
    $arrCategories = $oCategoryConf->update_categories($arrCategories);
    // Вычищаем не активные
    $arrCategories = $oCategoryConf->update_categories_active($arrCategories);
    $arrCategoriesIds = [];
    foreach ($arrCategories as &$arrCategory) {
      $arrCategoriesIds[$arrCategory['id']] = $arrCategory;
      $arrCategoriesIds[$arrCategory['id']]['sum'] = '00:00:00';
    }

    $arrResults['categories'] = $arrCategoriesIds;
    $arrResults['data'] = [];
    $arrTimesSum = [];

    // Суммы по месяцам
    for ($i=1; $i <= $iMonthDaysSum; $i++) {
      $oTime = new time();
      $oTime->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
      $oTime->query .= ' AND `category_id` != 55';
      $oTime->query .= " AND `date` LIKE '" . $iYear . '-' . sprintf("%02d", $iMonth) . '-' . sprintf("%02d", $i) . "%'";
      $arrTimes = $oTime->get();

      // Подготавливаем категории
      foreach ($arrCategories as $key => $arrCategory) {
        $arrResults['data'][$i]['categories'][$key]['title'] = $arrCategory['title'];
        $arrResults['data'][$i]['categories'][$key]['value'] = '00:00:00';
        $arrResults['data'][$i]['categories'][$key]['color'] = $arrCategory['color'];
      }

      // Сумма
      $arrDaysTimes = [];

      foreach ($arrTimes as &$arrTime) {
        $dDateReally = new DateTime($arrTime['time_really']);
        $arrTimesSum[] = $arrDaysTimes[] = $arrTime['time'] = $dDateReally->format('H:i:s');

        if ( $arrResults['data'][$i]['categories'][$arrTime['category_id']]['value'] == '00:00:00' ) $arrResults['data'][$i]['categories'][$arrTime['category_id']]['value'] = $arrTime['time'];
        else $arrResults['data'][$i]['categories'][$arrTime['category_id']]['value'] =  $oTime->get_sum( [$arrResults['data'][$i]['categories'][$arrTime['category_id']]['value'], $arrTime['time']]);

        // Суммы по категориям
        if ( isset($arrResults['categories'][$arrTime['category_id']]) )
          $arrResults['categories'][$arrTime['category_id']]['sum'] = $oTime->get_sum( [$arrResults['categories'][$arrTime['category_id']]['sum'], $arrTime['time']]);
      }

      $arrResults['data'][$i]['sum'] = $oTime->get_sum( $arrDaysTimes );
      $arrResults['data'][$i]['title'] = sprintf("%02d", $i);
      $arrResults['sum'] = floor((float)$arrResults['sum'] + (float)$oTime->get_sum( $arrDaysTimes ));
    }

    // Заменяем значения дат на не роботские
    foreach ( $arrResults['data'] as & $arrMonth ) {
      $arrTime = explode(':',$arrMonth['sum']);
      $iTimeSum = $arrTime[0] . '.' . $arrTime[1];
      $arrMonth['sum'] = $iTimeSum;

      foreach ( $arrMonth['categories'] as & $arrCategory ) {
        $arrTime = explode(':',$arrCategory['value']);
        $iTimeSum = $arrTime[0] . '.' . $arrTime[1];
        $arrCategory['value'] = $iTimeSum;
      }
    }

    // Создаём график
    $oChart = new chart();
    $oChart->arrDataset = $arrResults['data'];
    $oChart->arrCategories = $arrResults['categories'];
    if ( $_REQUEST['chart_type'] ) $oChart->sChartType = $_REQUEST['chart_type'];
    if ( $_REQUEST['sChartScaleX'] ) $oChart->sChartScaleX = $_REQUEST['sChartScaleX'];
    if ( $_REQUEST['sChartScaleY'] ) $oChart->sChartScaleY = $_REQUEST['sChartScaleY'];
    if ( $_REQUEST['sChartScaleStackedX'] ) $oChart->sChartScaleStackedX = $_REQUEST['sChartScaleStackedX'];
    if ( $_REQUEST['sChartScaleStackedY'] ) $oChart->sChartScaleStackedY = $_REQUEST['sChartScaleStackedY'];
    // $oChart->sChartScaleY;

    $arrResults['chart'] = $oChart->show();
    $arrResults['chart_sum'] = $oChart->show_sum();

    // Сортируем суммы категорий
    usort($arrResults['categories'], function($a, $b){
      return -($a['sum'] - $b['sum']);
    });
    // округляем
    foreach ($arrResults['categories'] as &$arrCategory) $arrCategory['sum'] = round($arrCategory['sum']);

    notification::success($arrResults);
    break;

  case 'analytics_year': # Вывод статистики за год
    $arrResults = [];
    $arrCategories = [];

    $iYear = (int)$_REQUEST['year'] ? $_REQUEST['year'] : date('Y');

    // Получаем категории
    $oCategory = new category();
    $oCategory->limit = 0;
    $oCategory->sortname = 'sort';
    $oCategory->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
    $oCategory->query .= ' AND `active` > 0';
    $oCategory->query .= ' AND `id` != 55';
    $arrCategories = $oCategory->get_categories();
    // Берём конфики костомных категорий пользователя
    $oCategoryConf = new category_config();
    $arrCategories = $oCategoryConf->update_categories($arrCategories);
    // Вычищаем не активные
    $arrCategories = $oCategoryConf->update_categories_active($arrCategories);
    $arrCategoriesIds = [];
    foreach ($arrCategories as &$arrCategory) {
      $arrCategoriesIds[$arrCategory['id']] = $arrCategory;
      $arrCategoriesIds[$arrCategory['id']]['sum'] = '00:00:00';
    }

    $arrResults['categories'] = $arrCategoriesIds;
    $arrResults['data'] = [];
    $arrTimesSum = [];

    // Суммы по месяцам
    for ($i=1; $i < 13; $i++) {
      $oTime = new time();
      $oTime->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
      $oTime->query .= " AND `date` LIKE '" . $iYear . '-' . sprintf("%02d", $i) . "%'";
      $oTime->query .= ' AND `category_id` != 55';
      $arrTimes = $oTime->get();

      // Подготавливаем категории
      foreach ($arrCategories as $key => $arrCategory) {
        $arrResults['data'][$i]['categories'][$key]['title'] = $arrCategory['title'];
        $arrResults['data'][$i]['categories'][$key]['value'] = '00:00:00';
        $arrResults['data'][$i]['categories'][$key]['color'] = $arrCategory['color'];
      }

      // Сумма
      $arrMonthTimes = [];

      foreach ($arrTimes as &$arrTime) {
        $dDateReally = new DateTime($arrTime['time_really']);
        $arrTimesSum[] = $arrMonthTimes[] = $arrTime['time'] = $dDateReally->format('H:i:s');
        if ( $arrResults['data'][$i]['categories'][$arrTime['category_id']]['value'] == '00:00:00' ) $arrResults['data'][$i]['categories'][$arrTime['category_id']]['value'] = $arrTime['time'];
        else $arrResults['data'][$i]['categories'][$arrTime['category_id']]['value'] =  $oTime->get_sum( [$arrResults['data'][$i]['categories'][$arrTime['category_id']]['value'], $arrTime['time']]);

        // Суммы по категориям
        if ( isset($arrResults['categories'][$arrTime['category_id']]) )
          $arrResults['categories'][$arrTime['category_id']]['sum'] = $oTime->get_sum( [$arrResults['categories'][$arrTime['category_id']]['sum'], $arrTime['time']]);
      }

      $arrResults['data'][$i]['sum'] = $oTime->get_sum( $arrMonthTimes );
      $arrResults['data'][$i]['title'] = date("F", strtotime($iYear . "-" . sprintf("%02d", $i)));
      $arrResults['sum'] = floor((float)$arrResults['sum'] + (float)$oTime->get_sum( $arrMonthTimes ));
    }

    // Заменяем значения дат на не роботские
    foreach ( $arrResults['data'] as & $arrMonth ) {
      $arrTime = explode(':',$arrMonth['sum']);
      $iTimeSum = $arrTime[0] . '.' . $arrTime[1];
      $arrMonth['sum'] = $iTimeSum;

      foreach ( $arrMonth['categories'] as & $arrCategory ) {
        $arrTime = explode(':',$arrCategory['value']);
        $iTimeSum = $arrTime[0] . '.' . $arrTime[1];
        $arrCategory['value'] = $iTimeSum;
      }
    }

    // Создаём график
    $oChart = new chart();
    $oChart->arrDataset = $arrResults['data'];
    $oChart->arrCategories = $arrResults['categories'];
    if ( $_REQUEST['chart_type'] ) $oChart->sChartType = $_REQUEST['chart_type'];
    if ( $_REQUEST['sChartScaleX'] ) $oChart->sChartScaleX = $_REQUEST['sChartScaleX'];
    if ( $_REQUEST['sChartScaleY'] ) $oChart->sChartScaleY = $_REQUEST['sChartScaleY'];
    if ( $_REQUEST['sChartScaleStackedX'] ) $oChart->sChartScaleStackedX = $_REQUEST['sChartScaleStackedX'];
    if ( $_REQUEST['sChartScaleStackedY'] ) $oChart->sChartScaleStackedY = $_REQUEST['sChartScaleStackedY'];

    $arrResults['chart'] = $oChart->show();
    $arrResults['chart_sum'] = $oChart->show_sum();

    // Сортируем суммы категорий
    usort($arrResults['categories'], function($a, $b){
      return -($a['sum'] - $b['sum']);
    });
    // округляем
    foreach ($arrResults['categories'] as &$arrCategory) $arrCategory['sum'] = round($arrCategory['sum']);

    notification::success($arrResults);
    break;
}
