<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Costs')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
      <button onclick="week_show()" class="nav-link active" id="pills-week-tab" data-bs-toggle="pill" data-bs-target="#pills-week" type="button" role="tab" aria-controls="pills-wekk" aria-selected="true"><?=$oLang->get('Week')?></button>
    </li>
    <li class="nav-item" role="presentation">
      <button onclick="month_show()" class="nav-link" id="pills-month-tab" data-bs-toggle="pill" data-bs-target="#pills-month" type="button" role="tab" aria-controls="pills-month" aria-selected="true"><?=$oLang->get('Month')?></button>
    </li>
    <li class="nav-item" role="presentation">
      <button <?=$oLock->get_attr('MoneysCostsAnalyticYear', 'onclick="year_show()" id="pills-yaer-tab" data-bs-toggle="pill" data-bs-target="#pills-yaer" type="button" role="tab" aria-controls="pills-yaer" aria-selected="false"')?> class="nav-link elem_lock">
        <?=$oLang->get('Year')?>
        <?=$oLock->get('MoneysCostsAnalyticYear')?>
      </button>
    </li>
  </ul>

  <div class="tab-content" id="pills-tabContent">
    <!-- Week -->
    <div class="tab-pane fade show active" id="pills-week" role="tabpanel" aria-labelledby="pills-week-tab">
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

      <div id="res_weeks" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <h2>
        <?=$oLang->get('Sum')?>
        <span id="res_weeks_sum_res" class="badge bg-primary">0</span>
      </h2>

      <div id="res_weeks_sum" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <div id="res_weeks_categories" class="block_categories_links">
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

        var bWeekShow = false
        function week_show( iWeek ) {
          if ( ! bWeekShow ) {
            // Получаем данные
            $.when(
              content_download( {
                'action': 'moneys',
                'form': 'analytics_week',
                'chart_type': 'bar',
                'sChartScaleStackedX': true,
                'sChartScaleStackedY': true,
                'money_type': '1',
                'money_to_card': '0',
                'week': iWeek,
              }, 'text', false )
            ).then( function( resultData ){
              if ( ! resultData ) return false
              var oData = $.parseJSON( resultData )

              if ( oData.success ) {
                if ( oData.success.chart ) $(document).find('#res_weeks').html( oData.success.chart )
                if ( oData.success.chart_sum ) $(document).find('#res_weeks_sum').html( oData.success.chart_sum )
                if ( oData.success.sum ) animation_number_to($("#res_weeks_sum_res"),0,oData.success.sum)

                if ( oData.success.categories ) {
                  $(document).find('#res_weeks_categories').html('')

                  $.get('/templates/category_link.htm')
                  .fail(function(data){
                    status({'error': 'Шаблон не найден: /templates/category_link.htm'})
                  })
                  .done(function(data){
                    var
                      oTemplate = $('<div/>').html(data),
                      arrThisPath = location.pathname.split('/')

                    $.each(oData.success.categories, function( iIndex, oCategory ){
                      var oElemHtml = content_loader_elem_html( oCategory, oTemplate )
                      $(document).find('#res_weeks_categories').append( oElemHtml )
                    })
                  })
                }

                bWeekShow = true
              }
            })
          }
        }
        week_show()
      </script>
    </div>

    <!-- Month -->
    <div class="tab-pane fade show" id="pills-month" role="tabpanel" aria-labelledby="pills-month-tab">
      <!-- Фильтр -->
      <form class="content_filter month_filter pb-4 __no_ajax" action="">
        <div class="input-group mb-2">
          <span class="input-group-text">
            <i class="far fa-calendar-alt"></i>
          </span>

          <select name="year" class="form-select">
            <option value="" selected><?=$oLang->get('CurrentYear')?></option>
            <?for ($i=date('Y'); $i > date('Y') - 3; $i--) {?>
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

      <div id="res_month" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <h2>
        <?=$oLang->get('Sum')?>
        <span id="res_month_sum_res" class="badge bg-primary">0</span>
      </h2>

      <div id="res_month_sum" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <div id="res_month_categories" class="block_categories_links">
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

        var bMonthShow = false;
        function month_show( iYear, iMonth ) {
          if ( ! bMonthShow ) {
            // Получаем данные
            $.when(
              content_download( {
                'action': 'moneys',
                'form': 'analytics_month',
                'chart_type': 'bar',
                'sChartScaleStackedX': true,
                'sChartScaleStackedY': true,
                'money_type': '1',
                'money_to_card': '0',
                'year': iYear,
                'month': iMonth,
              }, 'text', false )
            ).then( function( resultData ){
              if ( ! resultData ) return false
              var oData = $.parseJSON( resultData )

              if ( oData.success ) {
                if ( oData.success.chart ) $(document).find('#res_month').html( oData.success.chart )
                if ( oData.success.chart_sum ) $(document).find('#res_month_sum').html( oData.success.chart_sum )
                if ( oData.success.sum ) animation_number_to($("#res_month_sum_res"),0,oData.success.sum)

                if ( oData.success.categories ) {
                  $(document).find('#res_month_categories').html('')

                  $.get('/templates/category_link.htm')
                  .fail(function(data){
                    status({'error': 'Шаблон не найден: /templates/category_link.htm'})
                  })
                  .done(function(data){
                    var
                      oTemplate = $('<div/>').html(data),
                      arrThisPath = location.pathname.split('/')

                    $.each(oData.success.categories, function( iIndex, oCategory ){
                      var oElemHtml = content_loader_elem_html( oCategory, oTemplate )
                      $(document).find('#res_month_categories').append( oElemHtml )
                    })
                  })
                }

                bMonthShow = true
              }
            })
          }
        }
      </script>
    </div>

    <!-- Year -->
    <div class="tab-pane fade show" id="pills-yaer" role="tabpanel" aria-labelledby="pills-yaer-tab">
      <!-- Фильтр -->
      <form class="content_filter year_filter pb-4 __no_ajax" action="">
        <div class="input-group mb-2">
          <span class="input-group-text">
            <i class="far fa-calendar-alt"></i>
          </span>

          <select name="year" class="form-select">
            <option value="" selected><?=$oLang->get('CurrentYear')?></option>
            <?for ($i=date('Y'); $i > date('Y') - 3; $i--) {?>
              <option value="<?=$i?>"><?=$i?></option>
            <?}?>
          </select>

          <button class="btn btn-dark" type="submit">
            Go
          </button>
        </div>
      </form>

      <div id="res_year" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <h2>
        <?=$oLang->get('Sum')?>
        <span id="res_year_sum_res" class="badge bg-primary">0</span>
      </h2>

      <div id="res_year_sum" class="block_chart">
        <div class="block_loading">
          <div class="_icon">
            <i class="fas fa-chart-area"></i>
          </div>
        </div>
      </div>

      <div id="res_year_categories" class="block_categories_links">
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

        var bYearShow = false;
        function year_show( iYear ) {
          if ( ! bYearShow ) {
            // Получаем данные
            $.when(
              content_download( {
                'action': 'moneys',
                'form': 'analytics_year',
                'chart_type': 'bar',
                'sChartScaleStackedX': true,
                'sChartScaleStackedY': true,
                'money_type': '1',
                'money_to_card': '0',
                'year': iYear,
              }, 'text', false )
            ).then( function( resultData ){
              if ( ! resultData ) return false
              var oData = $.parseJSON( resultData )

              // Отправляем данные а получаем график
              if ( oData.success ) {
                if ( oData.success.chart ) $(document).find('#res_year').html( oData.success.chart )
                if ( oData.success.chart_sum ) $(document).find('#res_year_sum').html( oData.success.chart_sum )
                if ( oData.success.sum ) animation_number_to($("#res_year_sum_res"),0,oData.success.sum)

                if ( oData.success.categories ) {
                  $(document).find('#res_year_categories').html('')

                  $.get('/templates/category_link.htm')
                  .fail(function(data){
                    status({'error': 'Шаблон не найден: /templates/category_link.htm'})
                  })
                  .done(function(data){
                    var
                      oTemplate = $('<div/>').html(data),
                      arrThisPath = location.pathname.split('/')

                    $.each(oData.success.categories, function( iIndex, oCategory ){
                      var oElemHtml = content_loader_elem_html( oCategory, oTemplate )
                      $(document).find('#res_year_categories').append( oElemHtml )
                    })
                  })
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
