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
        <div class="col-12 col-md-6">
          <div class="pe-2">
            <small>user_id</small>
            <small>#{{user_id}}</small> {{user.login}}
          </div>
          <div class="pe-2">
            <small>date_start</small>
            {{date_start}}
          </div>
          <div class="pe-2">
            <small>date_stop</small>
            {{date_stop}}
          </div>
          <div class="pe-2">
            <small>level</small>
            {{level}}
          </div>
          <div class="pe-2">
            <small>days</small>
            {{days}}
          </div>
          <div class="">
            <small>data</small>
            {{data}}
          </div>
        </div>
        <div class="col-12 col-md-6 d-flex align-items-start justify-content-end">
          <span class="btn-group">
            <a href="#" class="btn btn-dark content_manager_switch switch_icons">
              <div class="">
                <i class="far fa-square"></i>
              </div>
              <div class="">
                <i class="fas fa-square"></i>
              </div>
            </a>

            <a data-action="accesses" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem=".time" data-form="form" href="javascript:;" class="btn btn-dark content_loader_show" title="Time edit">
              <i class="fas fa-pen-square"></i>
            </a>
          </span>
        </div>
      </div>
    </div>
  </div>
