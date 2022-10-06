<?
$oProject = new project( $_REQUEST['project_id'] );
$arrProject = $oProject->get_project();
?>

<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$arrProject['title']?>
    </h1>
  </div>
</div>

<div class="main_content">
  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <button class="nav-link active" id="pills-info-tab" data-bs-toggle="pill" data-bs-target="#pills-info" type="button" role="tab" aria-controls="pills-info" aria-selected="true"><?=$oLang->get('Info')?></button>
    </li>
    <li class="nav-item" role="presentation">
      <button onclick="week_show()" class="nav-link" id="pills-week-tab" data-bs-toggle="pill" data-bs-target="#pills-week" type="button" role="tab" aria-controls="pills-week" aria-selected="true"><?=$oLang->get('Week')?></button>
    </li>
    <li class="nav-item" role="presentation">
      <button onclick="month_show()" class="nav-link" id="pills-month-tab" data-bs-toggle="pill" data-bs-target="#pills-month" type="button" role="tab" aria-controls="pills-month" aria-selected="true"><?=$oLang->get('Month')?></button>
    </li>
    <li class="nav-item" role="presentation">
      <button <?=$oLock->get_attr('MoneysWagesAnalyticYear', 'onclick="year_show()" id="pills-yaer-tab" data-bs-toggle="pill" data-bs-target="#pills-yaer" type="button" role="tab" aria-controls="pills-yaer" aria-selected="false"')?> class="nav-link elem_lock">
        <?=$oLang->get('Year')?>
        <?=$oLock->get('MoneysWagesAnalyticYear')?>
      </button>
    </li>
  </ul>

  <div class="tab-content" id="pills-tabContent">
    <!-- Info -->
    <div class="tab-pane fade show active" id="pills-info" role="tabpanel" aria-labelledby="pills-info-tab">
      <div class="project_info">
        <div class="_block d-flex flex-column justify-content-center px-4 pb-4">
          <?
            $iTimeReally = $oProject->get_times_really();
            $iTimePlanned = $oProject->get_times_planned();
            $iTimePercent = ( str_replace(':',',',$iTimeReally) / str_replace(':',',',$iTimePlanned) ) * 100;
            $iMoneyReally = $oProject->get_moneys_really();
            $iMoneyPlanned = $oProject->get_moneys_planned();
            $iMoneySpent = $oProject->get_moneys_spent();
          ?>
          <div class="_values">
            <div class="_title">
              <?=$oLang->get('Times')?>
            </div>

            <div class="_item">
              <small>
                <?=$oLang->get('Spent')?>:
              </small>
              <span>
                <?=$iTimeReally?>
              </span>
            </div>

            <div class="_item">
              <small>
                <?=$oLang->get('Planned')?>:
              </small>
              <span>
                <?=$iTimePlanned?>
              </span>
            </div>

            <div class="py-4">
              <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: <?=$iTimePercent?>%" aria-valuenow="<?=$iTimePercent?>" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>

          <div class="_values px-3">
            <div class="_title">
              <?=$oLang->get('Moneys')?>
            </div>

            <div class="_item">
              <small>
                <?=$oLang->get('Received')?>:
              </small>
              <span>
                <?=number_format($iMoneyReally, 2, '.', ' ')?>
              </span>
            </div>

            <div class="_item">
              <small>
                <?=$oLang->get('Planned')?>:
              </small>
              <span>
                <?=number_format($iMoneyPlanned, 2, '.', ' ')?>
              </span>
            </div>

            <div class="_item">
              <small>
                <?=$oLang->get('Spent')?>:
              </small>
              <span>
                <?=number_format($iMoneySpent, 2, '.', ' ')?>
              </span>
            </div>
          </div>

          <?/*
          <div class="_values px-3">
            <div class="moneyforhour_block">
              <div class="_result _active_">
                <div class="_icon">
                  <i class="fas fa-stopwatch"></i>
                </div>
                <div class="_value">
                  <?
                  $iTime = str_replace(":", ".", $iTimeReally);
                  echo round($iMoneyReally / $iTime);
                  ?>
                </div>
                <div class="_title">
                  <?=$oLang->get('MoneyPerHour')?>
                </div>
              </div>
            </div>
          </div>
          */?>
        </div>

        <div class="_block d-flex justify-content-center px-4">
          <div class="_values px-3">
            <div class="btn-group">
              <a href="/tasks/?project_id=<?=$oProject->id?>" class="btn btn-lg">
                <i class="fa-solid fa-person-digging"></i>
              </a>

              <a href="/times/?project_id=<?=$oProject->id?>" class="btn btn-lg">
                <i class="fas fa-clock"></i>
              </a>

              <a href="/moneys/?project_id=<?=$oProject->id?>" class="btn btn-lg">
                <i class="fas fa-wallet"></i>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Week -->
    <div class="tab-pane fade" id="pills-week" role="tabpanel" aria-labelledby="pills-week-tab">
      <!-- Фильтр -->
      <form class="content_filter week_filter pb-4 __no_ajax" action="">
        <div class="input-group mb-2">
          <span class="input-group-text">
            <i class="far fa-calendar-alt"></i>
          </span>

          <select name="week" class="form-select">
            <option value="" selected><?=$oLang->get('CurrentWeek')?></option>
            <option value="1"><?=$oLang->get('PrevWeek')?></option>
          </select>

          <button class="btn btn-dark" type="submit">
            Go
          </button>
        </div>
      </form>

      <div class="moneyforhour_block" id="moneyforhour_week">
        <div class="_result">
          <div class="_icon">
            <i class="fas fa-stopwatch"></i>
          </div>
          <div class="_value">

          </div>
          <div class="_title">
            <?=$oLang->get('MoneyPerHour')?>
          </div>
        </div>
      </div>

      <h2>
        <?=$oLang->get('Moneys')?>
        <span id="res_weeks_money_res" class="badge bg-primary">0</span>
      </h2>
      <div id="res_weeks_money" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <h2>
        <?=$oLang->get('Times')?>
        <span id="res_weeks_time_res" class="badge bg-primary">0</span>
      </h2>
      <div id="res_weeks_time" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <script>
        $(document).find('.week_filter').on ('submit', function(){
          var iWeek = $(this).find('[name="week"]').val()

          bWeekShow = false
          week_show( iWeek )

          return false
        })
        function week_show( iWeek ) {
          var bWeekShow = false
          if ( ! bWeekShow ) {
            $.when(
              content_download( {
                'action': 'projects_analytics',
                'form': 'analytics_week',
                'project_id': <?=$arrProject['id']?>,
                'week': iWeek,
              }, 'text', false )
            ).then( function( resultData ){
              if ( ! resultData ) return false
              var oData = $.parseJSON( resultData )

              if ( oData.success ) {
                if ( oData.success.chart_time ) $(document).find('#res_weeks_time').html( oData.success.chart_time )
                if ( oData.success.chart_money ) $(document).find('#res_weeks_money').html( oData.success.chart_money )

                if ( oData.success.money_sum ) animation_number_to($("#res_weeks_money_res"),0,oData.success.money_sum)
                if ( oData.success.time_sum ) animation_number_to($("#res_weeks_time_res"),0,oData.success.time_sum)

                if ( oData.success.moneyforhour ) {
                  animation_number_to($(document).find('#moneyforhour_week ._result ._value'),0,oData.success.moneyforhour)
                  $(document).find('#moneyforhour_week ._result').addClass('_active_')
                }
                else {
                  $(document).find('#moneyforhour_week ._result').removeClass('_active_')
                }

                bWeekShow = true
              }
            })
          }
        }
      </script>
    </div>

    <!-- Month -->
    <div class="tab-pane fade" id="pills-month" role="tabpanel" aria-labelledby="pills-month-tab">
      <!-- Фильтр -->
      <form class="content_filter month_filter pb-4 __no_ajax" action="">
        <div class="input-group mb-2">
          <span class="input-group-text">
            <i class="far fa-calendar-alt"></i>
          </span>

          <select name="year" class="form-select">
            <option value="" selected><?=$oLang->get('CurrentYear')?></option>
            <?for ($i=date('Y')-1; $i > date('Y') - 3; $i--) {?>
              <option value="<?=$i?>"><?=$i?></option>
            <?}?>
          </select>

          <select name="month" class="form-select">
            <option value="" selected><?=$oLang->get('CurrentMonth')?></option>
            <?for ($i=1; $i < 13; $i++) {?>
              <option value="<?=$i?>"><?=$oLang->get(date("F", strtotime(date('Y') . "-" . sprintf("%02d", $i))))?></option>
            <?}?>
          </select>

          <button class="btn btn-dark" type="submit">
            Go
          </button>
        </div>
      </form>

      <div class="moneyforhour_block" id="moneyforhour_month">
        <div class="_result">
          <div class="_icon">
            <i class="fas fa-stopwatch"></i>
          </div>
          <div class="_value">
          </div>
          <div class="_title">
            <?=$oLang->get('MoneyPerHour')?>
          </div>
        </div>
      </div>

      <h2>
        <?=$oLang->get('Money')?>
        <span id="res_month_money_res" class="badge bg-primary">0</span>
      </h2>
      <div id="res_month_money" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <h2>
        <?=$oLang->get('Time')?>
        <span id="res_month_time_res" class="badge bg-primary">0</span>
      </h2>
      <div id="res_month_time" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <script>
        $(document).find('.month_filter').on ('submit', function(){
          var
          iYear = $(this).find('[name="year"]').val(),
          iMonth = $(this).find('[name="month"]').val()

          bMonthShow = false
          month_show( iYear, iMonth )

          return false
        })
        function month_show( iYear, iMonth ) {
          var bMonthShow = false
          if ( ! bMonthShow ) {
            $.when(
              content_download( {
                'action': 'projects_analytics',
                'form': 'analytics_month',
                'project_id': <?=$arrProject['id']?>,
                'year': iYear,
                'month': iMonth,
              }, 'text', false )
            ).then( function( resultData ){
              if ( ! resultData ) return false
              var oData = $.parseJSON( resultData )

              if ( oData.success ) {
                if ( oData.success.chart_time ) $(document).find('#res_month_time').html( oData.success.chart_time )
                if ( oData.success.chart_money ) $(document).find('#res_month_money').html( oData.success.chart_money )

                if ( oData.success.money_sum ) animation_number_to($("#res_month_money_res"),0,oData.success.money_sum)
                if ( oData.success.time_sum ) animation_number_to($("#res_month_time_res"),0,oData.success.time_sum)

                if ( oData.success.moneyforhour ) {
                  animation_number_to($(document).find('#moneyforhour_month ._result ._value'),0,oData.success.moneyforhour)
                  $(document).find('#moneyforhour_month ._result').addClass('_active_')
                }
                else {
                  $(document).find('#moneyforhour_month ._result').removeClass('_active_')
                }

                bMonthShow = true
              }
            })
          }
        }
      </script>
    </div>

    <!-- Year -->
    <div class="tab-pane fade" id="pills-yaer" role="tabpanel" aria-labelledby="pills-yaer-tab">
      <!-- Фильтр -->
      <form class="content_filter year_filter pb-4 __no_ajax" action="">
        <div class="input-group mb-2">
          <span class="input-group-text">
            <i class="far fa-calendar-alt"></i>
          </span>

          <select name="year" class="form-select">
            <option value="" selected><?=$oLang->get('CurrentYear')?></option>
            <?for ($i=date('Y')-1; $i > date('Y') - 3; $i--) {?>
              <option value="<?=$i?>"><?=$i?></option>
            <?}?>
          </select>

          <button class="btn btn-dark" type="submit">
            Go
          </button>
        </div>
      </form>

      <div class="moneyforhour_block" id="moneyforhour_year">
        <div class="_result">
          <div class="_icon">
            <i class="fas fa-stopwatch"></i>
          </div>
          <div class="_value">
          </div>
          <div class="_title">
            <?=$oLang->get('MoneyPerHour')?>
          </div>
        </div>
      </div>

      <h2>
        <?=$oLang->get('Money')?>
        <span id="res_year_money_res" class="badge bg-primary">0</span>
      </h2>
      <div id="res_year_money" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <h2>
        <?=$oLang->get('Times')?>
        <span id="res_year_time_res" class="badge bg-primary">0</span>
      </h2>
      <div id="res_year_time" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <script>
        $(document).find('.year_filter').on ('submit', function(){
          var iYear = $(this).find('[name="year"]').val()

          bYearShow = false
          year_show( iYear )

          return false
        })
        function year_show( iYear ) {
          var bYearShow = false
          if ( ! bYearShow ) {
            $.when(
              content_download( {
                'action': 'projects_analytics',
                'form': 'analytics_year',
                'project_id': <?=$arrProject['id']?>,
                'year': iYear,
              }, 'text', false )
            ).then( function( resultData ){
              if ( ! resultData ) return false
              var oData = $.parseJSON( resultData )

              if ( oData.success ) {
                if ( oData.success.chart_time ) $(document).find('#res_year_time').html( oData.success.chart_time )
                if ( oData.success.chart_money ) $(document).find('#res_year_money').html( oData.success.chart_money )

                if ( oData.success.money_sum ) animation_number_to($("#res_year_money_res"),0,oData.success.money_sum)
                if ( oData.success.time_sum ) animation_number_to($("#res_year_time_res"),0,oData.success.time_sum)

                if ( oData.success.moneyforhour ) {
                  animation_number_to($(document).find('#moneyforhour_year ._result ._value'),0,oData.success.moneyforhour)
                  $(document).find('#moneyforhour_year ._result').addClass('_active_')
                }
                else {
                  $(document).find('#moneyforhour_year ._result').removeClass('_active_')
                }

                bYearShow = true
              }
            })
          }
        }
      </script>
    </div>
  </div>
</div>
