<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Accesses')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <ul
    id="accesses"
    class="block_accesses block_elems list-group block_content_loader"
    data-content_loader_table="accesses"
    data-content_loader_form="show_all"
    data-content_loader_limit="15"
    data-content_loader_scroll_nav="0"
    data-content_loader_template_selector=".block_template"
  ></ul>
  <script>
    $(function(){
      $(document).find('#accesses').content_loader()
    })
  </script>
</div>

<div class="block_template">
  <div class="list-group-item _elem access" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
    <div class="_date_start">
      {{date_start}}
    </div>
    <div class="_date_stop">
      {{date_stop}}
    </div>
  </div>
</div>
