<?
/**
 * Task
 */
class task_template extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $title = '';
  public static $description = '';
  public static $sort = '';
  public static $active = '';
  public static $client_id = '';
  public static $project_id = '';
  public static $user_id = '';
  public static $price_planned = '';
  public static $time_planned = '';
  public static $status = '';
  public static $arrStatus = [];
  public static $date_create = '';
  public static $date_update = '';

  function get_task_template( $arrTaskTemplate = [] ) {
    if ( ! $arrTaskTemplate['id'] ) $arrTaskTemplate = $this->get();

    if ( (int)$arrTaskTemplate['active'] ) $arrTaskTemplate['active_show'] = 'true';
    else $arrTaskTemplate['active_show'] = 'false';

    // Status
    if ( (int)$arrTaskTemplate['status'] ) {
      $arrTaskTemplate['status_show'] = 'true';
      foreach ($this->arrStatus as $arrStatus)
        if ( $arrStatus['id'] === (int)$arrTaskTemplate['status'] ) {
          $arrTaskTemplate['status_val'] = $arrStatus['name'];
          $arrTaskTemplate['status_color'] = $arrStatus['color'];
        }
    }

    // Client
    if ( (int)$arrTaskTemplate['client_id'] ) {
      $oClient = new client( $arrTaskTemplate['client_id'] );
      $arrTaskTemplate['client'] = (array)$oClient;
      $arrTaskTemplate['client_show'] = 'true';
    }

    // Project
    if ( (int)$arrTaskTemplate['project_id'] ) {
      $oProject = new project( $arrTaskTemplate['project_id'] );
      $arrTaskTemplate['project'] = (array)$oProject;
      $arrTaskTemplate['project_show'] = 'true';
    }

    // time
    if ( $arrTaskTemplate['time_planned'] != '00:00:00' ) {
      $arrTaskTemplate['time_planned'] = date('H:i', strtotime($arrTaskTemplate['time_planned']));
      // $arrTaskTemplate['time_really'] = '00:00';
      $arrTaskTemplate['time_show'] = 'true';

      // $oTime = new time();
      // $oTime->query .= ' AND `task_id` = ' . $arrTaskTemplate['id'];
      // $arrTimes = $oTime->get();
      //
      // if ( count($arrTimes) ) {
      //   $arrTimesResult = [];
      //   foreach ($arrTimes as $arrTime) $arrTimesResult[] = $arrTime['time_really'];
      //   $arrTaskTemplate['time_really'] = $oTime->get_sum( $arrTimesResult );
      // }
    }

    // money
    if ( (int)$arrTaskTemplate['price_planned'] ) {
      $arrTaskTemplate['price_planned'] = substr($arrTaskTemplate['price_planned'], 0, -5);
      $arrTaskTemplate['money_show'] = 'true';
      // $oMoney = new money();
      // $oMoney->query = ' AND `task_id` = ' . $arrTaskTemplate['id'];
      // $arrMoneys = $oMoney->get();
      // $iMoneySum = 0;
      // if ( count($arrMoneys) ) {
      //   foreach ($arrMoneys as $arrMoney) {
      //     switch ( (int)$arrMoney['type'] ) {
      //       case 1: # Траты
      //       $iMoneySum = $iMoneySum - $arrMoney['price'];
      //       break;
      //
      //       case 2: # Приход
      //       $iMoneySum = $iMoneySum + $arrMoney['price'];
      //       break;
      //     }
      //   }
      // }
      // $arrTaskTemplate['price_really'] = $iMoneySum;
    }

    // Description
    $arrTaskTemplate['description_prev'] = '';
    if ( $arrTaskTemplate['description'] != '' ) {
      $arrTaskTemplate['description_show'] = 'true';
      // Wiki mark
      // require_once("lib/Wiky.php-master/wiky.inc.php");
      // $oWiky = new wiky;
      // $arrTaskTemplate['description_prev'] = $oWiky->parse( htmlspecialchars( $arrTaskTemplate['description'] ) );

      require_once("lib/parsedown-master/Parsedown.php");
      $oParsedown = new Parsedown();
      $arrTaskTemplate['description_prev'] = $oParsedown->text( $arrTaskTemplate['description'] );
    }

    return $arrTaskTemplate;
  }

  function get_task_templates() {
    $arrTaskTemplates = $this->get();
    if ( $arrTaskTemplates['id'] ) $arrTaskTemplates = $this->get_task_template( $arrTaskTemplates );
    else foreach ($arrTaskTemplates as &$arrTaskTemplate) $arrTaskTemplate = $this->get_task_template( $arrTaskTemplate );
    return $arrTaskTemplates;
  }


  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id_show'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры
    $arrFields['user_id'] = ['title'=>$oLang->get('User'),'type'=>'hidden','value'=>$_SESSION['user']['id']];

    $arrFields['title'] = ['title'=>$oLang->get('Title'),'type'=>'text','value'=>$this->title];
    $arrFields['description'] = ['title'=>$oLang->get('Description'),'type'=>'textarea','value'=>$this->description];

    $oClient = new client();
    $oClient->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
    $arrClients = $oClient->get();
    $arrClientsFilter = [];
    $arrClientsFilter[] = array('id'=>0,'name'=>'...');
    foreach ($arrClients as $arrClient) $arrClientsFilter[] = array('id'=>$arrClient['id'],'name'=>$arrClient['title']);
    $arrFields['client_id'] = ['title'=>$oLang->get('Client'),'type'=>'select','options'=>$arrClientsFilter,'search'=>true,'value'=>$this->client_id];

    $oProject = new project();
    $oProject->query = ' AND `user_id` = ' . $_SESSION['user']['id'];
    $oProject->active = true;
    $arrTaskTemplates = $oProject->get_projects();
    $arrTaskTemplatesFilter = [];
    $arrTaskTemplatesFilter[] = array('id'=>0,'name'=>'...');
    $bProject = false;
    foreach ($arrTaskTemplates as $arrTaskTemplate) {
      $arrTaskTemplatesFilter[] = array('id'=>$arrTaskTemplate['id'],'name'=>$arrTaskTemplate['title'],'color'=>$arrTaskTemplate['color']);
      if ( $arrTaskTemplate['id'] == $this->project_id ) $bProject = true;
    }
    if ( ! $bProject ) {
      $oProject = new project( $this->project_id );
      $arrTaskTemplatesFilter[] = array('id'=>$oProject->id,'name'=>$oProject->title,'color'=>$oProject->color);
    }
    $arrFields['project_id'] = ['title'=>$oLang->get('Project'),'type'=>'select','options'=>$arrTaskTemplatesFilter,'search'=>true,'value'=>$this->project_id];

    $arrFields['sort'] = ['title'=>$oLang->get('Sort'),'type'=>'number','value'=>$this->sort];
    $arrFields['time_planned'] = ['title'=>$oLang->get('TimesPlanned'),'type'=>'time','section'=>2,'value'=>$this->time_planned];
    $arrFields['price_planned'] = ['title'=>$oLang->get('PricePlanned'),'type'=>'number','section'=>2,'value'=>substr($this->price_planned, 0, -2),'step'=>'0.01'];

    $arrFields['date_create'] = ['title'=>$oLang->get('DateCreate'),'type'=>'date_time','disabled'=>'disabled','value'=>$this->date_create];
    $arrFields['date_update'] = ['title'=>$oLang->get('LastUpdate'),'type'=>'date_time','disabled'=>'disabled','value'=>$this->date_update];

    $arrFields['status'] = ['title'=>$oLang->get('Status'),'type'=>'time','type'=>'select','options'=>$this->arrStatus,'value'=>$this->status];

    $arrFields['active'] = ['title'=>$oLang->get('Active'),'type'=>'checkbox','value'=>$this->active];

    return $arrFields;
  }

  function __construct( $task_id = 0 )
  {
    $this->table = 'tasks_templates';

    if ( $task_id ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $task_id . "'";
      $arrTaskTemplate = db::query($mySql);

      $this->id = $arrTaskTemplate['id'];
      $this->title = $arrTaskTemplate['title'];
      $this->description = base64_decode($arrTaskTemplate['description']);
      $this->sort = $arrTaskTemplate['sort'];
      $this->active = $arrTaskTemplate['active'];
      $this->client_id = $arrTaskTemplate['client_id'];
      $this->project_id = $arrTaskTemplate['project_id'];
      $this->user_id = $arrTaskTemplate['user_id'];
      $this->price_planned = $arrTaskTemplate['price_planned'];
      $this->time_planned = $arrTaskTemplate['time_planned'];
      $this->status = $arrTaskTemplate['status'];
      $this->date_create = $arrTaskTemplate['date_create'];
      $this->date_update = $arrTaskTemplate['date_update'];
    }
    else {
      $this->date_create = date("Y-m-d H:i:s");
    }

    $oLang = new lang();
    $this->arrStatus = [
      array('id'=>0,'color'=>'','name'=>$oLang->get('NoStatus')),
      array('id'=>1,'color'=>'#3a487d','name'=>$oLang->get('Planned')),
      array('id'=>2,'color'=>'#ce5f5f','name'=>$oLang->get('InWork')),
      array('id'=>3,'color'=>'#3a7d61','name'=>$oLang->get('Complited'))
    ];
  }
}
