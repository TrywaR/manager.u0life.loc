<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Info')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <div class="dashboard_blocks">
    <!-- Уведомление -->
    <?
    $oNotice = new notice();
    $arrNotices = $oNotice->get_notices();
    $arrNoticesValids = [];

    foreach ($arrNotices as $arrNotice) {
      $oNoticeView = new notice_view();
      $oNoticeView->query .= ' AND `notice_id` = ' . $arrNotice['id'];
      $oNoticeView->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
      $arrNoticeViews = $oNoticeView->get_notices_views();
      if ( count($arrNoticeViews) >= $arrNotice['views'] ) continue;

      $arrNoticesValids[] = $arrNotice;
      break;
    }
    ?>
    <?php if ( count($arrNoticesValids) ): ?>
      <div class="_item __href __big block_notice __notice animate__animated ">
        <div class="_title">
          <?=$arrNoticesValids[0]['title']?>
        </div>
        <div class="_value">
          <div class="_sub">
            <?=$arrNoticesValids[0]['content']?>
          </div>
        </div>
        <div class="_icon">
          <?=$arrNoticesValids[0]['icon']?>
        </div>
        <div class="_icon_bg">
          <i class="fa-regular fa-bell"></i>
        </div>
        <div class="_close">
          <a href="javascript:;" class="content_loader_show" data-action="notices" data-form="add_view" data-id="<?=$arrNoticesValids[0]['id']?>" data-success_click="#notice_hide">
            <i class="fa-solid fa-xmark"></i>
          </a>
        </div>
        <div class="_href">
          <a href="javascript:;" class="content_loader_show" data-action="notices" data-form="add_view" data-id="<?=$arrNoticesValids[0]['id']?>" data-success_click="#notice_href">
          </a>
        </div>
        <div class="_hello"></div>
        <div class="_hover"></div>
        <a id="notice_hide" onclick="$(this).parents('.block_notice').hide(500);return false;"></a>
        <a id="notice_href" style="display:none;" href="<?=$arrNoticesValids[0]['href']?>"></a>
      </div>
    <?php endif; ?>

    <!-- День -->
    <div class="_item __href __big">
      <div class="_title">
        <?=$oLang->get('Day')?>
      </div>

      <div class="clock_revers">
        <div class="_date">
          <span class="_n">
            <?$dDateReally = new \DateTime();
            echo $oLang->get($dDateReally->format('F')) . ' ';
            echo $dDateReally->format('j')?>
          </span>
          <span class="_s">
            <?=$oLang->get($dDateReally->format('l'))?>
          </span>
        </div>
        <div class="_timer">
          <span class="_icon"><i class="fas fa-history"></i></span>
          <span class="_val" id="clock_revers"></span>
        </div>
        <div class="_progress progress">
          <div id="clock_revers_bar" class="_bar progress-bar" role="progressbar" aria-valuenow="<?=$iLeftHour?>" aria-valuemin="0" aria-valuemax="100"></div>
        </div>

        <script>
          function clockRevers( output, bar ) {
              var
                $out = $(output),
                $bar = $(bar),
                counter = new Date(),
                hrs = 23 - counter.getHours(),
                min = 59 - counter.getMinutes(),
                sec = 59 - counter.getSeconds(),
                midnight = '<span class="_h">'+String(hrs).padStart(2,'0')+'</span><i class="_p">:</i><span class="_m">'+String(min).padStart(2,'0')+'</span><i class="_p">:</i><span class="_s">'+String(sec).padStart(2,'0')+'</span>',
                iCurrentTimePercent = (hrs / 24 * 100),
                pctDayElapsed = (counter.getHours() * 3600 + counter.getMinutes() * 60 + counter.getSeconds())/86400
                pctDayElapsed = pctDayElapsed * 100
                pctDayElapsed = 100 - pctDayElapsed

              $out.html(midnight)
              $bar.attr({'style':'width: ' + pctDayElapsed + '%'})

              // recursion
              setTimeout(function(){ clockRevers(output, bar) }, 1000)
          }
          clockRevers('#clock_revers', '#clock_revers_bar')
        </script>
      </div>
      <div class="_href">
        <a href="/dashboard/days/"></a>
      </div>
      <div class="_hover"></div>
    </div>

    <!-- Месяц -->
    <div class="_item __big">
      <div class="_title">
        <?=$oLang->get('Month')?>
      </div>
      <div class="_value">
        <div class="_sub">
          <?=$oLang->get('Current')?>
        </div>

        <?
        // Месяц
        $iYear = (int)$_REQUEST['year'] ? $_REQUEST['year'] : date('Y');
        $iMonth = (int)$_REQUEST['month'] ? $_REQUEST['month'] : date('m');
        $dMonth = $iYear . '-' . sprintf("%02d", $iMonth);

        // Получаем категории
        $oCategory = new category();
        $oCategory->limit = 0;
        $oCategory->sortname = 'sort';
        $oCategory->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';
        $arrCategories = $oCategory->get();
        $arrCategoriesIds = [];
        foreach ($arrCategories as &$arrCategory) $arrCategoriesIds[$arrCategory['id']] = $arrCategory;
        // Подготавливаем категории
        foreach ($arrCategoriesIds as $key => $arrCategory) {
          $arrResults['categories'][$key]['title'] = $arrCategory['title'];
          $arrResults['categories'][$key]['value'] = '00:00:00';
          $arrResults['categories'][$key]['color'] = $arrCategory['color'];
        }

        // Время
        $arrMonthTimes = [];
        $oTime = new time();
        $oTime->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
        $oTime->query .= " AND `date` LIKE '" . $dMonth . "%'";
        $arrTimes = $oTime->get();
        foreach ($arrTimes as &$arrTime) {
          $dDateReally = new DateTime($arrTime['time_really']);
          $arrTimesSum[] = $arrMonthTimes[] = $arrTime['time'] = $dDateReally->format('H:i:s');
          if ( $arrResults['categories'][$arrTime['category_id']]['times']['value'] == '00:00:00' ) $arrResults['categories'][$arrTime['category_id']]['times']['value'] = $arrTime['time'];
          else $arrResults['categories'][$arrTime['category_id']]['times']['value'] =  $oTime->get_sum( [$arrResults['categories'][$arrTime['category_id']]['times']['value'], $arrTime['time']]);
        }
        $arrResults['times']['works'] = $arrResults['categories'][4]['times']['value'];
        $arrResults['times']['sum'] = $oTime->get_sum( $arrMonthTimes );

        // За месяц ушло
        $oMoney = new money();
        $oMoney->sortname = 'date';
        $oMoney->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
        $oMoney->query .= " AND `date` LIKE '" . $dMonth . "%' AND `type` = '1' AND `category` != 55";
        $oMoney->query .= " AND `to_card` = '0' ";

        $oMoney->show_currency = true;
        $oMoney->show_card = true;
        $oMoney->show_to_card = true;
        $arrMoneys = $oMoney->get_moneys();

        $iMonthSumm = 0;
        foreach ($arrMoneys as $arrMoney) $iMonthSumm = (int)$arrMoney['price'] + (int)$iMonthSumm;
        $arrResults['moneys']['costs'] = number_format($iMonthSumm, 2, '.', ' ');

        // За месяц пришло
        $oMoney = new money();
        $oMoney->sortname = 'date';
        $dCurrentDate = date('Y-m');
        $oMoney->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
        $oMoney->query .= " AND `date` LIKE '" . $dMonth . "%' AND `type` = '2' AND `category` != 55";

        $oMoney->show_currency = true;
        $oMoney->show_card = true;
        $oMoney->show_to_card = true;
        $arrMoneys = $oMoney->get_moneys();

        $iMonthSummSalary = 0;
        $iMonthSummSalaryWork = 0;
        foreach ($arrMoneys as $arrMoney) {
          $iMonthSummSalary = (int)$arrMoney['price'] + (int)$iMonthSummSalary;
          if ( (int)$arrMoney['category'] == 4 ) $iMonthSummSalaryWork = (int)$arrMoney['price'] + (int)$iMonthSummSalaryWork;
        }
        $arrResults['moneys']['wages'] = number_format($iMonthSummSalary, 2, '.', ' ');
        $arrResults['moneys']['wages_work'] = number_format($iMonthSummSalaryWork, 2, '.', ' ');

        // Balance
        $oCard = new card();
        $oCard->active = true;
        $oCard->query = ' AND ( `user_id` = ' . $_SESSION['user']['id'] . '  OR `user_id` = 0)';

        $oCard->show_currency = true;
        $arrCards = $oCard->get_cards();

        $iBalance = 0;
        foreach ( $arrCards as & $arrCard ) $iBalance = (int)$iBalance + (int)$arrCard['balance'];
        if ( (int)$iBalance > 0 ) $arrResults['balance'] = number_format($iBalance, 2, '.', ' ');
        else $arrResults['moneys']['balance'] = 0;

        // Money from hour
        $arrTime = explode(':',$arrResults['times']['works']);
        $iTimeSum = $arrTime[0] + $arrTime[1] / 60;
        $iMoneyForHour = $iMonthSummSalaryWork / $iTimeSum;
        if ( (int)$iMoneyForHour > 0 ) $arrResults['moneyforhour'] = number_format($iMoneyForHour, 2, '.', ' ');
        else $arrResults['moneyforhour'] = 0;
        ?>

        <div class="_group">
          <div class="_sub">
            <?=$oLang->get('Times')?>
          </div>
          <div class="_row">
            <small><?=$oLang->get('Work')?>:</small>
            <span><?=$arrResults['times']['works']?></span>
          </div>
          <div class="_row">
            <small><?=$oLang->get('Sum')?>:</small>
            <span><?=$arrResults['times']['sum']?></span>
          </div>
        </div>

        <div class="_group">
          <div class="_sub">
            <?=$oLang->get('Moneys')?>
          </div>
          <div class="_row">
            <small><?=$oLang->get('Costs')?>:</small>
            <span><?=$arrResults['moneys']['costs']?></span>
          </div>
          <div class="_row">
            <small><?=$oLang->get('Wages')?>:</small>
            <span><?=$arrResults['moneys']['wages']?></span>
          </div>
          <div class="_row">
            <small><?=$oLang->get('WagesWork')?>:</small>
            <span><?=$arrResults['moneys']['wages_work']?></span>
          </div>
        </div>

        <div class="_group">
          <div class="_sub">
            <?=$oLang->get('Other')?>
          </div>
          <div class="_row">
            <small><?=$oLang->get('MoneyPerHour')?>:</small>
            <span><?=$arrResults['moneyforhour']?></span>
          </div>
        </div>
      </div>
    </div>

    <!-- Баланс -->
    <div class="_item __href">
      <?
      $oCard = new card();
      $oCard->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
      $oCard->limit = 1;
      $arrCards = $oCard->get_cards();
      ?>
      <?php if (count($arrCards)): ?>
        <div class="_title">
          <?=$oLang->get('Balance')?>
        </div>
        <div class="_value">
          <div class="_sub">
            <?=$oLang->get('Current')?>
          </div>
          <div class="">
            <?$oCard = new card()?>
            <?=number_format($oCard->get_balance(), 2, '.', ' ')?>
          </div>
          <?php if (count($oCard->get_credit_cards())): ?>
            <div class="_sub">
              <?=$oLang->get('BalanceOnCredit')?>
            </div>
            <div class="">
              <?=number_format($oCard->get_balance_oncredit(), 2, '.', ' ')?>
            </div>
          <?php endif; ?>
        </div>
        <div class="_href">
          <a href="/moneys/data/cards/"></a>
        </div>
        <div class="_hover"></div>
      <?php else: ?>
        <div class="_title">
          <?=$oLang->get('Cards')?>
        </div>
        <div class="_value">
          <div class="_sub">
            <?=$oLang->get('HelloCards')?>
          </div>
        </div>
        <div class="_icon">
          <i class="fa-solid fa-circle-plus"></i>
        </div>
        <div class="_href">
          <a href="/moneys/data/cards/"></a>
        </div>
        <div class="_hello"></div>
        <div class="_hover"></div>
      <?php endif; ?>
    </div>

    <!-- Подписки -->
    <div class="_item __href">
      <?
      $oSubscription = new subscription();
      $oSubscription->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
      $oSubscription->linit = 1;
      $arrSubscriptions = $oSubscription->get_subscriptions();
      ?>
      <?php if (count($arrSubscriptions)): ?>
        <div class="_title">
          <?=$oLang->get('Subscriptions')?>
        </div>
        <div class="_value">
          <div class="_group">
            <div class="_sub">
              <?=$oLang->get($dDateReally->format('F'))?>
              <?=$oLang->get($dDateReally->format('Y'))?>
            </div>

            <?$arrSubscriptionsMonth = $oSubscription->get_month()?>
            <div class="_row">
              <small><?=$oLang->get('SubscriptionSum')?>:</small>
              <span><?=number_format($arrSubscriptionsMonth['subscriptions_sum'], 2, '.', ' ')?></span>
            </div>

            <div class="_row">
              <small><?=$oLang->get('SubscriptionSumPaid')?>:</small>
              <span><?=number_format($arrSubscriptionsMonth['subscriptions_sum_paid'], 2, '.', ' ')?></span>
            </div>

            <div class="_row">
              <small><?=$oLang->get('SubscriptionSumNeedPaid')?>:</small>
              <span><?=number_format($arrSubscriptionsMonth['subscriptions_sum_need'], 2, '.', ' ')?></span>
            </div>
          </div>
        </div>
        <div class="_href">
          <a href="/subscriptions/month/"></a>
        </div>
        <div class="_hover"></div>
      <?php else: ?>
        <div class="_title">
          <?=$oLang->get('Subscriptions')?>
        </div>
        <div class="_value">
          <div class="_sub">
            <?=$oLang->get('HelloSubscriptions')?>
          </div>
        </div>
        <div class="_icon">
          <i class="fa-solid fa-circle-plus"></i>
        </div>
        <div class="_href">
          <a href="/subscriptions/"></a>
        </div>
        <div class="_hello"></div>
        <div class="_hover"></div>
      <?php endif; ?>
    </div>

    <!-- Задачи -->
    <div class="_item __href">
      <div class="_icon">
        <i class="fa-solid fa-person-digging"></i>
      </div>
      <div class="_title">
        <?=$oLang->get('Tasks')?>
      </div>
      <div class="_href">
        <a href="/tasks/"></a>
      </div>
      <div class="_hover"></div>
    </div>

    <!-- Время -->
    <div class="_item __href">
      <div class="_icon">
        <i class="fa-solid fa-clock"></i>
      </div>
      <div class="_title">
        <?=$oLang->get('Times')?>
      </div>
      <div class="_href">
        <a href="/times/"></a>
      </div>
      <div class="_hover"></div>
    </div>

    <!-- Деньги -->
    <div class="_item __href">
      <div class="_icon">
        <i class="fa-solid fa-wallet"></i>
      </div>
      <div class="_title">
        <?=$oLang->get('Moneys')?>
      </div>
      <div class="_href">
        <a href="/moneys/"></a>
      </div>
      <div class="_hover"></div>
    </div>
  </div>
</div>
