<?
/**
 * Client
 */
class client extends model
{
  public static $table = ''; # Таблица в bd
  public static $id = '';
  public static $title = '';
  public static $description = '';
  public static $sort = '';
  public static $active = '';
  public static $user_id = '';
  public static $phone = '';
  public static $email = '';
  public static $color = '';
  public static $telegram = '';
  public static $instagram = '';
  public static $site = '';

  function get_client( $arrClient = [] ) {
    if ( ! $arrClient['id'] ) $arrClient = $this->get();

    if ( (int)$arrClient['active'] ) $arrClient['active_show'] = 'true';
    else $arrClient['active_show'] = 'false';

    if ( $arrClient['site'] )
    if ( strripos($arrClient['site'], 'http') === false )
      $arrClient['site_host'] = $arrClient['site'];
    else
      $arrClient['site_host'] = parse_url($arrClient['site'], PHP_URL_HOST);

    return $arrClient;
  }

  function get_clients(){
    $arrClients = $this->get();
    if ( $arrClients['id'] ) $arrClients = $this->get_client( $arrClients );
    else foreach ($arrClients as &$arrClient) $arrClient = $this->get_client($arrClient);
    return $arrClients;
  }

  public function fields() # Поля для редактирования
  {
    $oLang = new lang();

    $arrFields = [];
    $arrFields['id'] = ['title'=>'ID','type'=>'number','disabled'=>'disabled','value'=>$this->id]; # Для отображения пользователю
    $arrFields['id'] = ['title'=>'ID','type'=>'hidden','disabled'=>'disabled','value'=>$this->id]; # Для передачи в параметры
    $arrFields['user_id'] = ['title'=>$oLang->get('User'),'type'=>'hidden','value'=>$_SESSION['user']['id']];

    $arrFields['title'] = ['title'=>$oLang->get('Title'),'type'=>'text','required'=>'required','value'=>$this->title];
    $arrFields['description'] = ['title'=>$oLang->get('Description'),'type'=>'textarea','value'=>$this->description];

    $arrFields['sort'] = ['title'=>$oLang->get('Sort'),'type'=>'number','value'=>$this->sort];

    $sColor = $this->color ? $this->color : sprintf( '#%02X%02X%02X', rand(0, 255), rand(0, 255), rand(0, 255) );
    $arrFields['color'] = ['title'=>$oLang->get('Color'),'type'=>'color','value'=>$sColor];

    $arrFields['phone'] = ['title'=>$oLang->get('Phone'),'icon'=>'<i class="fa-solid fa-phone"></i>','type'=>'text','value'=>$this->phone];
    $arrFields['email'] = ['title'=>$oLang->get('Email'),'icon'=>'<i class="fa-solid fa-envelope"></i>','type'=>'text','value'=>$this->email];
    $arrFields['telegram'] = ['title'=>$oLang->get('Telegram'),'icon'=>'<i class="fa-brands fa-telegram"></i>','type'=>'text','value'=>$this->telegram];
    $arrFields['instagram'] = ['title'=>$oLang->get('Instagram'),'icon'=>'<i class="fa-brands fa-instagram"></i>','type'=>'text','value'=>$this->instagram];
    $arrFields['site'] = ['title'=>$oLang->get('Site'),'icon'=>'<i class="fa-solid fa-globe"></i>','type'=>'text','value'=>$this->site];

    $arrFields['active'] = ['title'=>$oLang->get('Active'),'type'=>'checkbox','value'=>$this->active];

    return $arrFields;
  }

  function __construct( $client_id = 0 )
  {
    $this->table = 'clients';

    if ( $client_id ) {
      $mySql = "SELECT * FROM `" . $this->table . "`";
      $mySql .= " WHERE `id` = '" . $client_id . "'";
      $arrClient = db::query($mySql);

      $this->id = $arrClient['id'];
      $this->title = $arrClient['title'];
      $this->description = base64_decode($arrClient['description']);
      $this->sort = $arrClient['sort'];
      $this->active = $arrClient['active'];
      $this->user_id = $arrClient['user_id'];
      $this->phone = $arrClient['phone'];
      $this->email = $arrClient['email'];
      $this->telegram = $arrClient['telegram'];
      $this->instagram = $arrClient['instagram'];
      $this->site = $arrClient['site'];
      $this->color = $arrClient['color'];
    }
  }
}
