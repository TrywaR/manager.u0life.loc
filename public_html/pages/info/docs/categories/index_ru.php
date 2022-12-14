<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Categories')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <div class="block_docs">
    <div class="_doc">
      <div class="_content">
        <p>
          В зависимости от Ваших потребностей, помимо уже существующих категорий, Вы можете добавлять свои.
        </p>
        <p>
          Категории будут отображаться во временных и денежных графиках.
        </p>
        <p>
          <?$oCategory = new category();?>
          Лимит по категориям <strong><?=$oCategory->get_categories_limit()?></strong>, для увеличения используйте <a href="/info/buy/">полную версию</a>
        </p>
      </div>
      <div class="_link">
        <a href="https://web.u0life.com/categories/">
          <?=$oLang->get('Try')?>
        </a>
      </div>
    </div>
  </div>
</div>
