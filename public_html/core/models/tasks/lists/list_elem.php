<?
/**
 * Tasks lists elem
 */
class task_list_elem extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $title = '';
  public static $sort = '';
  public static $active = '';
  public static $list_id = '';
  public static $user_id = '';
  public static $date_create = '';
  public static $date_update = '';
  public static $status = '';

  function get_task_list_elem( $arrTaskListElem = [] ) {
    if ( ! $arrTaskListElem['id'] ) $arrTaskListElem = $this->get();

    return $arrTaskListElem;
  }

  function get_task_list_elems() {
    $arrTaskListElems = $this->get();
    if ( $arrTaskListElems['id'] ) $arrTaskListElems = $this->get_task_list_elem( $arrTaskListElems );
    else foreach ($arrTaskListElems as &$arrTaskListElem) $arrTaskListElem = $this->get_task_list_elem( $arrTaskListElem );
    return $arrTaskListElems;
  }


  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id_show'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры
    $arrFields['user_id'] = ['title'=>$oLang->get('User'),'type'=>'hidden','value'=>$_SESSION['user']['id']];

    $arrFields['list_id'] = ['title'=>$oLang->get('List'),'type'=>'hidden','value'=>$this->list_id];

    $arrFields['title'] = ['title'=>$oLang->get('Title'),'type'=>'text','value'=>$this->title];

    $arrFields['sort'] = ['title'=>$oLang->get('Sort'),'type'=>'number','value'=>$this->sort];
    $arrFields['status'] = ['title'=>$oLang->get('Status'),'type'=>'checkbox','value'=>$this->status];
    $arrFields['date_create'] = ['title'=>$oLang->get('DateCreate'),'type'=>'date_time','disabled'=>'disabled','value'=>$this->date_create];
    $arrFields['date_update'] = ['title'=>$oLang->get('DateUpdate'),'type'=>'date_time','disabled'=>'disabled','value'=>$this->date_update];

    $arrFields['active'] = ['title'=>$oLang->get('Active'),'type'=>'checkbox','value'=>$this->active];

    return $arrFields;
  }

  function __construct( $list_id = 0 )
  {
    $this->table = 'tasks_lists_elems';

    if ( $list_id ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $list_id . "'";
      $arrTaskListElem = db::query($mySql);

      $this->id = $arrTaskListElem['id'];
      $this->title = $arrTaskListElem['title'];
      $this->sort = $arrTaskListElem['sort'];
      $this->active = $arrTaskListElem['active'];
      $this->user_id = $arrTaskListElem['user_id'];
      $this->list_id = $arrTaskListElem['list_id'];
      $this->date_create = $arrTaskListElem['date_create'];
      $this->date_update = $arrTaskListElem['date_update'];
      $this->status = $arrTaskListElem['status'];
    }
    else {
      $this->date_create = date("Y-m-d H:i:s");
    }
  }
}
