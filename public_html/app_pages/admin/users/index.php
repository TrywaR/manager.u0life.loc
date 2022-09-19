<div class="main_jumbotron">
  <div class="_block_title">
    <h1 class="sub_title _value">
      <?=$oLang->get('Users')?>
    </h1>
  </div>
</div>

<div class="main_content">
  <?include 'core/templates/elems/content_manager_block.php'?>

  <ul
    id="users"
    class="block_users block_elems list-group block_content_loader"
    data-content_loader_table="users"
    data-content_loader_form="show"
    data-content_loader_limit="15"
    data-content_loader_scroll_nav="0"
    data-content_loader_template_selector=".block_template"
    data-content_loader_scroll_block="#users"
    data-content_loader_show_class="_show_"
  ></ul>
  <script>
    $(function(){
      $(document).find('#users').content_loader()
      // $(document).find('#content_filter').content_filter()
      // $(document).find('#content_manager_buttons').content_manager()
      // $(document).find('#footer_actions').content_actions( {'action':'users'} )
    })
  </script>

</div>

<div class="block_template">
    <div class="list-group-item _elem user" data-content_manager_item_id="{{id}}"  data-id="{{id}}">
      <div class="row">
        <div class="col-12 col-md-6 d-flex">
          <div class="pe-2">
            {{login}}
          </div>
          <div class="pe-2">
            <small>{{role_val}}</small>
            <small>({{role}})</small>
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

            <a data-action="users" data-animate_class="animate__flipInY" data-id="{{id}}" data-elem=".time" data-form="form" href="javascript:;" class="btn btn-dark content_loader_show" title="Time edit">
              <i class="fas fa-pen-square"></i>
            </a>
          </span>
        </div>

        <div class="col-12 d-flex align-items-center">
          <small class="pe-2">
            <?=$oLang->get('LastDateAccess')?>
          </small>
          <small class="pe-2">
            <i class="fa-solid fa-clock"></i>
          </small>
          {{access.date_stop}}
        </div>

        <div class="col-12 d-flex align-items-center">
          {{rewards}}
        </div>
      </div>
    </div>
  </div>
