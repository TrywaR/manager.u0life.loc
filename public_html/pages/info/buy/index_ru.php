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
        <p>Основные функции приложения доступны бесплатно, другие вряд ли Вам понадобятся (:</p>
        <p>Тем не менее получить полный доступ к приложению можно осуществив</p>
      </div>
    </div>

    <h2 class="mb-3">
      Приглашение пользователя
    </h2>
    <div class="px-3 pb-4">
      <div>
        Для приглашения пользователя воспользуйтесь реферальной ссылкой на странице своего профиля, или в блоке ниже если вы авторизированны.
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
        После регистрации нового пользователя по вашей ссылке вы получите полный доступ на 30 дней
      </div>
    </div>

    <h2 class="mb-3">
      Донат
    </h2>
    <div class="px-3 pb-4">
      <div>
        Оплатить полный доступ на данный момент можно только донатом, указывая свой логин в сопроводительном сообщении или на почту info@u0life.com, любая сумма даёт доступ на 30 дней
      </div>
      <?include 'core/templates/elems/donates.php'?>
    </div>

    <div class="block_buytarifs">
      <h2 class="mb-1">
        Возможности полного доступа
      </h2>
      <ol>
        <li>
          Возможность участвовать в разработке приложения
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
          Возможность использовать до 50 категорий
        </li>
        
        <li>
          Мультивалютность
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
