<?
/**
 * Tasks lists
 */
class task_list extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $title = '';
  public static $sort = '';
  public static $active = '';
  public static $task_id = '';
  public static $user_id = '';
  public static $date_create = '';

  function get_task_list( $arrTaskList = [] ) {
    if ( ! $arrTaskList['id'] ) $arrTaskList = $this->get();

    return $arrTaskList;
  }

  function get_task_lists() {
    $arrTaskLists = $this->get();
    if ( $arrTaskLists['id'] ) $arrTaskLists = $this->get_task_list( $arrTaskLists );
    else foreach ($arrTaskLists as &$arrTaskList) $arrTaskList = $this->get_task_list( $arrTaskList );
    return $arrTaskLists;
  }


  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id_show'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры
    $arrFields['user_id'] = ['title'=>$oLang->get('User'),'type'=>'hidden','value'=>$_SESSION['user']['id']];

    $oTask = new task();
    $oTask->sortname = 'sort';
    $oTask->sortdir = 'ASC';
    $oTask->query .= ' AND `user_id` = ' . $_SESSION['user']['id'];
    $oTask->query .= ' AND `status` = 2';
    $oTask->active = true;
    $arrTasks = $oTask->get_tasks();

    $arrTasksFilter = [];
    $arrTasksFilter[] = array('id'=>0,'name'=>'...');
    $arrTaskCurrent = [];
    if ( (int)$this->task_id ) {
      $oTaskCurrent = new task( $this->task_id );
      $arrTaskCurrent = $oTaskCurrent->get_tasks();
      $arrTasksFilter[] = array('id'=>$arrTaskCurrent['id'],'name'=>$arrTaskCurrent['title']);
    }
    foreach ( $arrTasks as $arrTask ) {
      if ( $arrTaskCurrent['id'] && $arrTaskCurrent['id'] == $arrTask['id'] ) continue;
      $arrTasksFilter[] = array('id'=>$arrTask['id'],'name'=>$arrTask['title']);
    }
    $arrFields['task_id'] = ['title'=>$oLang->get('Task'),'type'=>'select','options'=>$arrTasksFilter,'search'=>true,'value'=>$this->task_id];

    $arrFields['title'] = ['title'=>$oLang->get('Title'),'type'=>'text','value'=>$this->title];

    $arrFields['sort'] = ['title'=>$oLang->get('Sort'),'type'=>'number','value'=>$this->sort];
    $arrFields['date_create'] = ['title'=>$oLang->get('DateCreate'),'type'=>'date_time','disabled'=>'disabled','value'=>$this->date_create];

    $arrFields['active'] = ['title'=>$oLang->get('Active'),'type'=>'checkbox','value'=>$this->active];

    return $arrFields;
  }

  function __construct( $task_id = 0 )
  {
    $this->table = 'tasks_lists';

    if ( $task_id ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $task_id . "'";
      $arrTaskList = db::query($mySql);

      $this->id = $arrTaskList['id'];
      $this->title = $arrTaskList['title'];
      $this->sort = $arrTaskList['sort'];
      $this->active = $arrTaskList['active'];
      $this->user_id = $arrTaskList['user_id'];
      $this->task_id = $arrTaskList['task_id'];
      $this->date_create = $arrTaskList['date_create'];
    }
    else {
      $this->date_create = date("Y-m-d H:i:s");
    }
  }
}
