<?/*
<?include 'core/templates/pages/logining.php';?>
*/?>
<div class="block_logining">
  <div class="btn-group">
    <a class="btn btn-primary" href="https://web.u0life.com" target="_blank">
      <div class="_icon">
        <i class="fa-solid fa-play"></i>
      </div>
      <div class="_text">
        <small><small><?=$oLang->get('StartIn')?></small></small>
        <span><?=$oLang->get('WebVersion')?></span>
      </div>
    </a>
    <a class="btn btn-primary disabled" disabled="disabled">
      <div class="_icon">
        <i class="fa-brands fa-google"></i>
      </div>
      <div class="_text">
        <small><?=$oLang->get('DownloadFrom')?></small>
        <span>GooglePlay</span>
      </div>
    </a>
    <a class="btn btn-primary disabled" disabled="disabled">
      <div class="_icon">
        <i class="fa-brands fa-apple"></i>
      </div>
      <div class="_text">
        <small><?=$oLang->get('DownloadFrom')?></small>
        <span>AppStore</span>
      </div>
    </a>
  </div>
</div>
