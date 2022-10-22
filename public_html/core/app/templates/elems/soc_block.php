<?/*
<?include 'core/app/templates/elems/soc_block.php'?>
*/?>

<?
// load lang
switch ($oLang->sUserLang) {
  case 'ru':
    ?>
    <div class="_social block_social">
      <a class="_item" href="https://t.me/u0life_ru" target="_blank">
        <i class="fa-brands fa-telegram"></i>
      </a>
      <a class="_item" href="https://www.instagram.com/u0life_ru/" target="_blank">
        <i class="fa-brands fa-instagram"></i>
      </a>
      <a class="_item" href="https://www.tiktok.com/@u0life_ru" target="_blank">
        <i class="fa-brands fa-tiktok"></i>
      </a>
      <!-- <a class="_item" href="https://www.youtube.com/channel/UCCckiAXYcuFOJ4u2YFGtyuA" target="_blank">
        <i class="fa-brands fa-youtube"></i>
      </a> -->
      <a class="_item __donate" href="https://www.patreon.com/user?u=76441895" target="_blank">
        <i class="fa-brands fa-patreon"></i>
      </a>
      <a class="_item __donate" href="https://boosty.to/u0life" target="_blank">
        <i class="fa-solid fa-ruble-sign"></i>
      </a>
    </div>
    <?
    break;
  case 'en':
    ?>
    <div class="_social block_social">
      <a class="_item" href="https://t.me/u0life" target="_blank">
        <i class="fa-brands fa-telegram"></i>
      </a>
      <a class="_item" href="https://www.instagram.com/u0life/" target="_blank">
        <i class="fa-brands fa-instagram"></i>
      </a>
      <a class="_item" href="https://www.tiktok.com/@u0life" target="_blank">
        <i class="fa-brands fa-tiktok"></i>
      </a>
      <a class="_item" href="https://www.youtube.com/channel/UC3W0fM5ZGHsiT0wG9RRk0OQ" target="_blank">
        <i class="fa-brands fa-youtube"></i>
      </a>
      <a class="_item __donate" href="https://www.patreon.com/user?u=76441895" target="_blank">
        <i class="fa-brands fa-patreon"></i>
      </a>
      <a class="_item __donate" href="https://boosty.to/u0life" target="_blank">
        <i class="fa-solid fa-dollar-sign"></i>
      </a>
    </div>
    <?
    break;
}
?>
