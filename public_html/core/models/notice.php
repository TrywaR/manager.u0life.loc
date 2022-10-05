<?
/**
 * Notification
 */
class notice extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $title = '';
  public static $content = '';
  public static $views = '';
  public static $href = '';
  public static $icon = '';

  function add_view()
  {
    $oNoticeView = new notice_view();
    $oNoticeView->arrAddFields['user_id'] = $_SESSION['user']['id'];
    $oNoticeView->arrAddFields['notice_id'] = $this->id;
    $oNoticeView->add();
  }

  function get_notice( $arrNotice = [] ) {
    if ( ! $arrNotice['id'] ) $arrNotice = $this->get();

    $oLang = new lang();

    $arrNotice['title'] = $oLang->get($arrNotice['title']);
    $arrNotice['content'] = $oLang->get($arrNotice['content']);

    return $arrNotice;
  }

  function get_notices(){
    $arrNotices = $this->get();
    if ( $arrNotices['id'] ) $arrNotices = $this->get_notice( $arrNotices );
    else foreach ($arrNotices as &$arrNotice) $arrNotice = $this->get_notice($arrNotice);
    return $arrNotices;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id_view'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры

    $arrFields['title'] = ['title'=>$oLang->get('Title'),'type'=>'text','required'=>'required','value'=>$this->title];
    $arrFields['content'] = ['title'=>$oLang->get('Description'),'type'=>'textarea','value'=>$this->content];

    $arrFields['views'] = ['title'=>$oLang->get('Views'),'type'=>'number','value'=>$this->views];
    $arrFields['href'] = ['title'=>$oLang->get('Href'),'type'=>'text','value'=>$this->href];
    $arrFields['icon'] = ['title'=>$oLang->get('Icon'),'type'=>'textarea','value'=>$this->icon];

    return $arrFields;
  }

  function __construct( $iNotificationId = 0 )
  {
    $this->table = 'notices';

    if ( $iNotificationId ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $iNotificationId . "'";
      $arrNotice = db::query($mySql);

      $this->id = $arrNotice['id'];
      $this->title = $arrNotice['title'];
      $this->content = base64_decode($arrNotice['content']);
      $this->views = $arrNotice['views'];
      $this->href = $arrNotice['href'];
      $this->icon = $arrNotice['icon'];
    }
  }
}
