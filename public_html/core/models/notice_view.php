<?
/**
 * Notification
 */
class notice_view extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $user_id = '';
  public static $notice_id = '';
  public static $date = '';

  function get_notice_view( $arrNoticeView = [] ) {
    if ( ! $arrNoticeView['id'] ) $arrNoticeView = $this->get();

    return $arrNoticeView;
  }

  function get_notices_views(){
    $arrNoticeViews = $this->get();
    if ( $arrNoticeViews['id'] ) $arrNoticeViews = $this->get_notice_view( $arrNoticeViews );
    else foreach ($arrNoticeViews as &$arrNoticeView) $arrNoticeView = $this->get_notice_view($arrNoticeView);
    return $arrNoticeViews;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры

    $arrFields['user_id'] = ['title'=>$oLang->get('User'),'type'=>'text','required'=>'required','value'=>$this->user_id];
    $arrFields['notice_id'] = ['title'=>$oLang->get('Notification'),'type'=>'text','required'=>'required','value'=>$this->notice_id];
    $arrFields['date'] = ['title'=>$oLang->get('Date'),'type'=>'date_time','disabled'=>'disabled','value'=>$this->date];

    return $arrFields;
  }

  function __construct( $iNotificationId = 0 )
  {
    $this->table = 'notices_views';

    if ( $iNotificationId ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $iNotificationId . "'";
      $arrNoticeView = db::query($mySql);

      $this->id = $arrNoticeView['id'];
      $this->user_id = $arrNoticeView['user_id'];
      $this->notice_id = $arrNoticeView['notice_id'];
      $this->date = $arrNoticeView['date'];
    }
    else {
      $this->date = date("Y-m-d H:i:s");
    }
  }
}
