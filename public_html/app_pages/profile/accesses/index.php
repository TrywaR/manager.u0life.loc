<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Accesses')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <?include 'core/templates/elems/content_manager_block.php'?>

  <ul
    id="accesses"
    class="block_accesses block_elems list-group block_content_loader"
    data-content_loader_table="accesses"
    data-content_loader_form="show"
    data-content_loader_limit="15"
    data-content_loader_scroll_nav="0"
    data-content_loader_template_selector=".block_template"
    data-content_loader_scroll_block="#accesses"
    data-content_loader_show_class="_show_"
  ></ul>
  <script>
    $(function(){
      $(document).find('#accesses').content_loader()
      // $(document).find('#content_filter').content_filter()
      // $(document).find('#content_manager_buttons').content_manager()
      // $(document).find('#footer_actions').content_actions( {'action':'accesses'} )
    })
  </script>

</div>

<div class="block_template">
    <div class="list-group-item _elem user" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
      <div class="row">
        <div class="col-12">
          <h3>
            {{data}}
          </h3>
        </div>

        <div class="col-12">
          <div class="pe-2">
            <small><?=$oLang->get('AccessDateStart')?></small>
            {{date_start}}
          </div>
          <div class="pe-2">
            <small><?=$oLang->get('AccessDateStop')?></small>
            {{date_stop}}
          </div>
          <div class="pe-2">
            <small><?=$oLang->get('Accesslevel')?></small>
            {{level}}
          </div>
        </div>
      </div>
    </div>
  </div>
