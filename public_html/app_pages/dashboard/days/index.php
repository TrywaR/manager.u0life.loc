<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Dashboard')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <div class="block_dashboard">
    <div class="_sections">
      <div class="_sections_title">
        <?=$oLang->get('Day')?>
      </div>

      <div class="_section _filter">
        <div class="block_date">
          <div class="_group input-group">
            <select name="year" class="_year form-select" id="dashboard_year">
              <?for ($i=date('Y'); $i > date('Y') - 3; $i--) {?>
                <option value="<?=$i?>"><?=$i?></option>
              <?}?>
            </select>

            <select name="month" class="_month form-select" id="dashboard_month">
              <?for ($i=1; $i < 13; $i++) {?>
                <? if ( sprintf("%02d", $i) === date('m')  ): ?>
                  <option selected="selected" value="<?=$i?>"><?=$oLang->get(date("F", strtotime(date('Y') . "-" . sprintf("%02d", $i))))?></option>
                <? else: ?>
                  <option value="<?=$i?>"><?=$oLang->get(date("F", strtotime(date('Y') . "-" . sprintf("%02d", $i))))?></option>
                <? endif; ?>
              <?}?>
            </select>
          </div>
        </div>
      </div>

      <div class="_section _day">
        <div class="_section_content _show_">
          <div class="block_liveliner _loading_" id="dashboard_days"></div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  $(dashboard_init)
</script>
