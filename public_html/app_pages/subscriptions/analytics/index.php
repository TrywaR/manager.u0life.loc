<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Subscriptions')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <div class="subscription_analytics">
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
        if ( ! $(document).find('#subscription_analytics').find('.block_loading').length )
        $(document).find('#subscription_analytics').html('<div class="block_loading"><div class="_icon"><i class="fas fa-chart-area"></i></div></div>')

        // Получаем данные
        $.when(
          content_download( {
            'action': 'subscriptions',
            'form': 'analytics_month',
            'year': iYear,
            'month': iMonth,
          }, 'text', false )
        ).then( function( resultData ){
          if ( ! resultData ) return false
          // var oData = $.parseJSON( resultData )
          $(document).find('#subscription_analytics').html( resultData )
        })
      }
      
      month_show()
    </script>

    <div class="_analytics" id="subscription_analytics">
      <div class="block_loading">
        <div class="_icon">
          <i class="fas fa-chart-area"></i>
        </div>
      </div>
    </div>
  </div>
</div>
