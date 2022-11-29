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
          Depending on your needs, in addition to existing categories, you can add your own.
        </p>
        <p>
          Categories will be displayed in Time and Money charts.
        </p>
        <p>
          <?$oCategory = new category();?>
          Category limit <strong><?=$oCategory->get_categories_limit()?></strong>, use the <a href="/info/buy/">full version</a> to enlarge</a>
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
