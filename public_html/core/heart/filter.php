<?
/**
 * Filter
 */
class filter
{
  public $query = '';
  public $from = '';
  public $limit = '';
  public $object = '';
  public $arrParams = '';
  public $arrParamsIngores = '';

  public function get_val( $sParamName = '' ){
    $sReturn = '';
    if ( $_REQUEST['filter'] )
      foreach ($_REQUEST['filter'] as $arrFilter)
        if ( $arrFilter['name'] == $sParamName ) $sReturn = $arrFilter['value'];
    return $sReturn;
  }

  public function get(){
    // Пагинация
    // if ( $_REQUEST['from'] ) $this->from = $_REQUEST['from'];
    // if ( $_REQUEST['limit'] ) $this->limit = $_REQUEST['limit'];

    // Фильтрация
    if ( $_REQUEST['filter'] ) {
      $arrFilters = $_REQUEST['filter'];
      foreach ($arrFilters as $arrFilter) {
        if ( $arrFilter['value'] ) {
          if ( isset($this->arrParamsIngores[$arrFilter['name']]) ) continue;

          $this->arrParams[$arrFilter['name']] = $arrFilter['value'];

          switch ($arrFilter['name']) {
            case 'date':
            $this->qeury .= ' AND `' . $arrFilter['name'] . '` = "' . $arrFilter['value'] . ' 00:00:00"';
            break;

            default:
            $this->qeury .= ' AND `' . $arrFilter['name'] . '` = "' . $arrFilter['value'] . '"';
            break;
          }
        }
      }
    }

    return $this->qeury;
  }

  function __construct( $oObject = '' )
  {
    $this->query = '';
    $this->from = '';
    $this->limit = '';
    $this->object = $oObject;
    $this->arrParams = [];
    $this->arrParamsIngores = [];
  }
}
