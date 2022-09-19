<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Access')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <div class="block_buyinfo">
    <div class="block_text_alert">
      <div class="_icon">
        <i class="fa-solid fa-triangle-exclamation"></i>
      </div>
      <div class="">
        <p>The main functions of the application are available for free, you are unlikely to need others (:</p>
        <p>However, you can get full access to the application by</p>
      </div>
    </div>

    <h2 class="mb-3">
      User prompt
    </h2>
    <div class="px-3 pb-4">
      <div>
        To invite a user, use the referral link on your profile page, or in the block below if you are logged in.
      </div>

      <?php if ($_SESSION['user']): ?>
        <div class="card">
          <div class="card-body">
            <small><?=$oLang->get('ReferalLink')?>:</small>
            <input type="text" readonly class="form-control-plaintext" value="<?=config::$site_url?>/?referal=<?=$_SESSION['user']['id']?>">
          </div>
        </div>
      <?php endif; ?>

      <div>
        After registering a new user using your link, you will get full access for 30 days
      </div>
    </div>

    <h2 class="mb-3">
      Donat
    </h2>
    <div class="px-3 pb-4">
      <div>
        At the moment, you can pay for full access only by donating, indicating your login in the accompanying message or by mail info@u0life.com, any amount gives access for 30 days
      </div>
      <?include 'core/templates/elems/donates.php'?>
    </div>

    <div class="block_buytarifs">
      <h2 class="mb-1">
        Full access options
      </h2>
      <ol>
        <li>
          Opportunity to participate in the development of the application
        </li>

        <li>
          Access to financial statistics for the year
        </li>
        <li>
          Access to statistics by time per year
        </li>
        <li>
          Access to statistics by category for the year
        </li>

        <li>
          Ability to use up to 50 categories
        </li>
      </ol>
    </div>
  </div>
  <?/*
  <div class="block_buytarifs">
    <div class="_tarifs">
      <div class="_item _standart">
        <div class="_title">
          Light user
        </div>
      </div>
      <div class="_item _premium">
        <div class="_title">
          By povelitel
        </div>
        <ol>
          <li>
            Возможность учавствовать в разработке приложения
          </li>

          <li>
            Доступ к статистике по финансам за год
          </li>
          <li>
            Доступ к статистике по времени за год
          </li>
          <li>
            Доступ к статистике по категориям за год
          </li>

          <li>
            Возможность использовать до 50 активных категорий
          </li>

          <li>
            Улучшенная система шифрования данных (beta)
          </li>

          <li>

          </li>
        </ol>
      </div>
    </div>
  </div>
  */?>
</div>
