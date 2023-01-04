<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Versions')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <div class="container">
    <div class="row">
      <div class="col col-12 col-xl-4 mb-4">
        <div id="list-example" class="list-group" style="height: calc(75vh); overflow-y: auto;">
          <a class="list-group-item list-group-item-action active" href="#list-item-5_6_7">5.6.7 udpate</a>
          <a class="list-group-item list-group-item-action" href="#list-item-5_6_6">5.6.6 fix</a>
          <a class="list-group-item list-group-item-action" href="#list-item-5_6_5">5.6.5 fix</a>
          <a class="list-group-item list-group-item-action" href="/info/versions/old/">
            <?=$oLang->get('VersionsOld')?>
          </a>
        </div>
      </div>
      <div class="col col-12 col-xl-8">
        <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" class="scrollspy-example" tabindex="0" style="height: calc(75vh); overflow-y: auto;">
          <div class="pt-2 pb-2">
            <h2 id="list-item-5_6_7">5.6.7 update</h2>
            <ol>
              <li>
                Улучшены списки задач, сразу редактируются, применяются изменения по нажатию интер
              </li>
              <li>
                При создании тикетов из проекта, атоматически подставляется клиент
              </li>
              <li>
                У клиентов и проектов теперь можно задавать цвет, которые так же отображаются в задачах
              </li>
            </ol>

            <h2 id="list-item-5_6_6">5.6.6 fix</h2>
            <ol>
              <li>
                Исправленно: Ошибка при добавлении проектов  (5.6.5)
              </li>
              <li>
                Исправленно: Сообщение об удалении проектов
              </li>
              <li>
                Обновлены стили для светлой темы, фигово, но более менее
              </li>
              <li>
                При добавлении списков в задачах, сразу открываются на редактирование
              </li>
              <li>
                При добавлении задач в списки, сразу открывается редактирование
              </li>
            </ol>

            <h2 id="list-item-5_6_5">5.6.5 fix</h2>
            <ol>
              <li>
                Исправлена ошибка при создании тикетов
              </li>
              <li>
                Исправлена ошибка при Отображение тикетов и других элементов при создании
              </li>
              <li>
                Добавлены стили в меню и на дашборде для денег, задач и времени
              </li>
              <li>
                В подписках добавленна поддержка валют
              </li>
            </ol>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
