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

    <div class="_sections">
      <div class="_sections_title">
        <?=$oLang->get('Month')?>
      </div>

      <div class="_section _main">
        <div class="_section_content _show_ shower_content shower_content_dashboard_main dashboard_main" id="dashboard_main">
          <div class="_blocks">
            <div class="_block">
              <div class="_title">
                <?=$oLang->get('Moneys')?>
              </div>
              <div class="_elems">
                <div class="_elem">
                  <div class="_name"><?=$oLang->get('Costs')?></div>
                  <div class="_val" id="dashboard_main_money_costs">0</div>
                </div>
                <div class="_elem">
                  <div class="_name"><?=$oLang->get('Wages')?></div>
                  <div class="_val" id="dashboard_main_money_wages">0</div>
                </div>
              </div>
            </div>

            <div class="_block">
              <div class="_title">
                <?=$oLang->get('Times')?>
              </div>
              <div class="_elems">
                <div class="_elem">
                  <div class="_name"><?=$oLang->get('Working')?></div>
                  <div class="_val" id="dashboard_main_time_work">0</div>
                </div>
              </div>
            </div>

            <div class="_block">
              <div class="_title">
                <?=$oLang->get('MoneyPerHour')?>
              </div>
              <div class="_elems">
                <div class="_elem">
                  <div class="_val" id="dashboard_main_res">0</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="_section _subscriptions">
        <div href="" class="_section_title">
          <h2 class="sub_title"><?=$oLang->get('Subscriptions')?></h2>
          <button class="_button btn" id="dashboard_reload_subscriptions" style="display:none;">
            <i class="fa-solid fa-rotate-right"></i>
          </button>
          <span class="badge bg-primary" id="dashboard_subscriptions_sum"></span>
        </div>

        <div class="_section_content _show_">
          <div class="block_subscriptions_slider block_slider" id="dashboard_subscriptions"></div>
          <script>

          </script>
        </div>
      </div>
    </div>


    <?/*
    <div class="_seporator" style="text-align: center; padding: 1rem; opacity: .1; font-size: 5rem;">
      <i class="fa-brands fa-grav"></i>
    </div>

    <div class="_section _cards">
      <div class="_section_title">
        <h2 class="sub_title"><?=$oLang->get('Cards')?></h2>
        <button class="_button btn" id="dashboard_reload_cards" style="display:none;">
          <i class="fa-solid fa-rotate-right"></i>
        </button>
        <span class="badge bg-primary" id="dashboard_cards_balance"></span>
      </div>

      <div class="_section_content _show_">
        <div class="block_cards_slider block_slider" id="dashboard_cards"></div>
        <script>

        </script>
      </div>
    </div>

    <div class="_section _tasks">
      <div class="_section_title">
        <h2 class="sub_title"><?=$oLang->get('Tasks')?></h2>
        <button class="_button btn" id="dashboard_reload_tasks" style="display:none;">
          <i class="fa-solid fa-rotate-right"></i>
        </button>
      </div>
      <div class="_section_content _show_">
        <div class="block_tasks_slider block_slider" id="dashboard_tasks"></div>

        <script>

        </script>
      </div>
    </div>
    */?>
  </div>
</div>

<script>
  $(dashboard_init)
</script>
