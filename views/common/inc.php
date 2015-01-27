<?php
// 载入 Yii / 后台系统
$root = (dirname(dirname(__FILE__)));

define("ROOT_PATH", $root);

require_once(ROOT_PATH."/admin/yii/yii.php");

$config = ROOT_PATH.'/admin/protected/config/main.php';

defined('YII_DEBUG') or define('YII_DEBUG',true);
defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);

$app = Yii::createWebApplication($config);

$scriptUrl = Yii::app()->getRequest()->getScriptUrl();

$ret = Yii::app()->getRequest()->getBaseUrl();
Yii::app()->getRequest()->setBaseUrl("/admin");

global $language;
// 获取语言
$cookies = Yii::app()->request->cookies;
$lang = $cookies["lang"];
if ($lang) {
  Yii::app()->language = (string)$lang;
}
else {
    if (isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
      $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
      if ($lang == "zh") {
        Yii::app()->language = "zh_cn";
      }
      else {
        Yii::app()->language = "en_us";
      }
    }
    else {
      // 如果都没用判断出语言 则是英文.
      Yii::app()->language = "en_us";
    }
}

if (isset($_GET['lang']) && $_GET['lang'] == "fr") {
  Yii::app()->language = 'fr';
  setcookie('lang', $_GET['lang']);
}

// 测试代码
//Yii::app()->language = "zh_cn";
if (Yii::app()->language == "zh_cn") {
  $language = "cn";
}
else if (Yii::app()->language == "fr") {
  $language = "fr";
}
else {
  $language = "en";
}

// 计算出 Page title
$page_title = "Home";

$uri = preg_replace("/(\.php)|(^\/)/", "", $_SERVER["SCRIPT_NAME"]);
$uri = str_replace("-", " ", $uri);

$page_title = ucwords(Yii::t("page_title", $uri));
        
/**
 * 生成一个编辑我的区块
 */
function editme($key, $field = array("body"), $media = array(), $video = array()) {
  // 先禁用掉 Editme 功能
  return;
  $content = ContentAR::model()->loadByKey($key);
  ob_start();
  // 要修改的
  include(ROOT_PATH."/views/widget/editme.phtml");
  
  print ob_get_clean();
}

/**
 * 获取内容的字段
 */
function renderContentField($key, $fieldName) {
  $content = ContentAR::model()->loadByKey($key);
  
  if (isset($content->{$fieldName})) {
    return $content->{$fieldName};
  }
  
  $content->hasContentField($filedName);
  $content->reload();
  
  return $content->{$fieldName};
}


function loadContentList($type) {
  $class = ucfirst($type).'ContentAR';
  if (!class_exists($class)) {
    throw new Exception("Class with name ". $type . " is not exist");
  }
  else {
    $model = new $class();
    // 加一个Limit 10
    return $model->getList(10);
  }
}

function makeThumbnail($uri, $size = array()) {
  if (!$size) {
    return $uri;
  }
  return MediaAR::thumbnail($uri, $size);
}


function getSlideImageHtml( $uri , $percent = 1) {
  // $width = image_size($uri, "width");
  // $height = image_size($uri, "height");
  $per = array( 0 , 600, 1214 ,1800 );
  $tarW = $per[$percent];
  if( !$tarW ){
      $tarW = 600;
  }
  $tarH = 570;

  // if ($percent !== FALSE) {
  //   $tarW = $per[$percent];
  // }
  // else {
  //   $tarW = $per[ round( $width / $height ) ];
  // }
  // if( !$tarW ){
  //   $tarW = 600;
  // }
  // $tarW = min( $tarW , $width );
  // $tarH = min( 570 , $height );
  return  "<img data-width=\"$percent\" data-height=\"1\"  src=\"" . makeThumbnail($uri, array($tarW, $tarH)) . "\" width=\"100%\" />";
}
/**
 * 返回在某个系列下的某个类型的产品
 * @param string $type
 * @param CollectionContentAR $collection
 * @return 
 */
function getProductInTypeWithCollection($type = "", $collection = NULL) {
  global $language;
  if ($type && $collection) {
    $sql = 'select * from content left join field on field_name="product_type" and field.cid = content.cid where field_content="'.$type.'" AND  type="product" and language="'.$language.'" and content.cid in (select cid from field where field_name="collection" and field_content="'.$collection.'")';
  }
  else if ($type && !$collection) {
    $sql = 'select * from content left join field on field_name="product_type" and field.cid = content.cid where field_content="'.$type.'" AND  type="product" and language="'.$language.'"';
  }
  else if ($collection && !$type) {
    $sql = 'select * from content left join field on field_name="collection" and field.cid = content.cid where field_content="'.$collection.'" AND  type="product" and language="'.$language.'"';
  }
  // 没有任何条件 就直接返回一个空数组
  else {
    return array();
  }
  
  $cids = array();
  $res = Yii::app()->db->createCommand($sql)->queryAll();
  foreach ($res as $item) {
    $cids[] = $item["cid"];
  }
 
  $query = new CDbCriteria();
  $query->addInCondition("cid", $cids);
  $query->order = "weight DESC , cdate DESC";
  return ProductContentAR::model()->findAll($query);
}

function getProductInType($type) {
  global $language;
  $sql = 'select * from content left join field on field_name="product_type" and field.cid = content.cid where field_content="'.$type.'" AND  type="product" and language="'.$language.'"';
  
  $cids = array();
  $res = Yii::app()->db->createCommand($sql)->queryAll();
  foreach ($res as $item) {
    $cids[] = $item["cid"];
  }
 
  $query = new CDbCriteria();
  $query->addInCondition("cid", $cids);
  $query->order = "weight DESC , cdate DESC";
  $products = ProductContentAR::model()->findAll($query);
  
  $ret = array();
  foreach ($products as $product) {
    $ret[$product->collection][] = $product;
  }
  
  $collections = CollectionContentAR::model()->getList();
  $retTmp = array();
  foreach ($collections as $collection) {
    if (isset($ret[$collection->cid])) {
      $retTmp[$collection->cid] = $ret[$collection->cid];
    }
  }
  
  return $retTmp;
}

/**
 * 加载Craft 相关联的产品
 * @param type $craft
 */
function loadCraftRelatedProducts($craft) {
  $product_ids = json_decode($craft->product, TRUE);
  if (!$product_ids || !is_array($product_ids)) {
    return array();
  }
  $query = new CDbCriteria();
  $query->limit = "5";
  $query->addInCondition("cid", $product_ids);
  
  $products = ProductContentAR::model()->findAll($query);
  return $products;
}

/**
 * 加载其他的Craft. 排除参数所指示的Craft
 * @param int $craft_id
 */
function loadOtherCraft($craft_id = 0) {
  global $language;
  $query = new CDbCriteria();
  $query->addNotInCondition("cid", array($craft_id));
  $query->addCondition("language=:language");
  $query->params[":language"] = $language;
  $query->order = "weight DESC, cdate DESC";
  
  return CraftContentAR::model()->findAll($query);
}

// 加载新闻
function loadFirstNews() {
  $news = NewsContentAR::model()->getList(1);
  if (count($news)) {
    return $news[0];
  }
  return FALSE;
}

function loadFirstEvent() {
  $events = EventContentAR::model()->getList(1);
  if (count($events)) {
    return $events[0];
  }
  return FALSE;
}

// 加载新闻列表，用年份来分组
function loadNewsWithYearGroup($teaser = FALSE) {
  $newsItems = NewsContentAR::model()->getList();
  $firstNews = loadFirstNews();
  $newsGroup = array();
  foreach ($newsItems as $newsItem) {
    if ($newsItem->cid == $firstNews->cid) {
      continue;
    }
    if ($teaser) {
      if (isset($newsGroup[date("Y", strtotime($newsItem->date))]) &&  count($newsGroup[date("Y", strtotime($newsItem->date))])< 3) {
        continue;
      }
    }
    $newsGroup[date("Y", strtotime($newsItem->date))][] = $newsItem;
  }
  
  return $newsGroup;
}

// 加载Event列表，用年份来分组
function loadEventsWithYearGroup($teaser = FALSE) {
  $newsItems = EventContentAR::model()->getList();
  $firstNews = loadFirstEvent();
  $newsGroup = array();
  foreach ($newsItems as $newsItem) {
//    if ($newsItem->cid == $firstNews->cid) {
//      continue;
//    }
    if ($teaser) {
      if (isset($newsGroup[date("Y", strtotime($newsItem->date))]) &&  count($newsGroup[date("Y", strtotime($newsItem->date))])< 3) {
        continue;
      }
    }
    $newsGroup[date("Y", strtotime($newsItem->date))][] = $newsItem;
  }
  
  return $newsGroup;
}

/**
 * 加载所有的Press. 如果参数Year 没有提供 则把所有的加载进来，否则只加载Year 所表示的Press
 * @param type $year
 * @param type $limit
 */
function loadPressWithYearGroup($yearGroup = FALSE, $limit = 15) {
  $presses = PressContentAR::model()->getList();
  $pressGroup = array();
  foreach ($presses as $press) {
    $year = date("Y", strtotime($press->publish_date));
    if ($yearGroup && $yearGroup == $year) {
      continue;
    }
    if (isset($pressGroup[$year]) && count($pressGroup[$year]) > $limit) 
      continue;
    $pressGroup[$year] = $press;
  }
  
  return $pressGroup;
}

// 加载Press 所有的年份
function loadPressYears() {
  $sql = "select distinct left(field_content, 4) as year from field left join content on content.cid = field.cid where field_name='publish_date' and content.cid != '' order by year DESC";
  $query = Yii::app()->db->createCommand($sql);
  
  $rows = $query->queryAll();
  return $rows;
}

// 从产品中加载对应的系列
function loadCollectionFromProduct($product) {
  return CollectionContentAR::model()->findByPk($product->collection);
}

// 加载相似的产品
// 同一个类型下／同一个分类下的所有产品
function loadSimilarProducts($product) {
   $collectionId = $product->collection;
   $product_type = $product->product_type;
   
   $query = new CDbCriteria();
   $query->addCondition("field_name=:field_name");
   $query->params[":field_name"] = "collection";
   $query->addCondition("field_content=:value");
   $query->params[":value"] = $collectionId;
   $res = FieldAR::model()->findAll($query);
   $cids1 = array();
   foreach ($res as $item) {
     $cids1[$item->cid] = $item->cid;
   }
   
   $query = new CDbCriteria();
   $query->addCondition("field_name=:field_name");
   $query->params[":field_name"] = "product_type";
   $query->addCondition("field_content=:value");
   $query->params[":value"] = $product_type;
   $res = FieldAR::model()->findAll($query);
   $cids2 = array();
   foreach ($res as $item) {
     $cids2[$item->cid] = $item->cid;
   }
   
   // 取交集
   global $language;
   $cids = array_intersect($cids1, $cids2);
   $query = new CDbCriteria();
   $query->addCondition("type=:type")
           ->addCondition("status=:status")
           ->addCondition("cid <> :cid")
           ->addInCondition("cid", $cids);
   //$query->params[":language"] = $language;
   $query->params[":type"] = "product";
   $query->params[":status"] = ContentAR::STATUS_ENABLE;
   $query->limit = "5";
   $query->params[":cid"] = $product->cid;
   $query->order = 'weight DESC, cdate DESC';
   $products = ProductContentAR::model()->findAll($query);
   return $products;
}

//加载Job 
function loadJob() {
  return JobContentAR::model()->getList();
}

// 搜索关键字
function searchWithKeyword($keyword) {
   if ($keyword) {
     $keyword = trim($keyword);
     $types = array("'collection'", "'craft'");
     global $language;
     $intype = implode(",", $types);
     $sql = "SELECT * FROM content where type in (".$intype.")";
     $sql .= " AND language = '".$language."'";
     $keyword = addslashes($keyword);
     $sql .= " AND ( title COLLATE UTF8_GENERAL_CI like binary '%".strtolower($keyword)."%' OR body COLLATE UTF8_GENERAL_CI like binary '%".  strtolower($keyword)."%') ";
     
     $query = Yii::app()->db->createCommand($sql);
     $rows = $query->queryAll();
     
     $cids = array();
     foreach ($rows as $row) {
       $cids[$row["type"]][] = $row["cid"];
     }
     $rets = array();
     foreach ($cids as $type => $cidArray) {
       $class = ucfirst($type)."ContentAR";
         $ar = new $class();
         $query = new CDbCriteria();
         $query->addInCondition("cid", $cidArray);
         $rets += $ar->findAll($query);
      }
      
      // 然后搜索产品
     $sql = "SELECT * FROM content where type='product' AND language='".$language."'";
     $keyword = addslashes($keyword);
     $sql .= " AND ( title COLLATE UTF8_GENERAL_CI like binary '%".strtolower($keyword)."%' OR body COLLATE UTF8_GENERAL_CI like binary '%".  strtolower($keyword)."%') ";
     $query = Yii::app()->db->createCommand($sql);
     $rows = $query->queryAll();
     $cids = array();
     
     foreach ($rows as $row) {
       $cids[] = $row["cid"];
     }
     $query = new CDbCriteria();
     $query->addInCondition("cid", $cids);
     $products = ProductContentAR::model()->findAll($query);
     
     return array_merge($rets, $products);
   }
}

function getCity() {

    if(isset($_SERVER["HTTP_VIA"])){
        if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $userIp = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else {
            $userIp = $_SERVER["REMOTE_ADDR"];
        }
    }
    else {
        $userIp = $_SERVER["REMOTE_ADDR"];
    }

    //$userIp = (isset($_SERVER["HTTP_VIA"])) ? $_SERVER["HTTP_X_FORWARDED_FOR"] : $_SERVER["REMOTE_ADDR"];
  $city = Yii::app()->ip->toCity($userIp);
  if ($city == "上海市") {
    $city = "shanghai";
  }
  else if ($city == "北京市") {
    $city = "beijing";
  }
  else if ($city === FALSE && $userIp != "127.0.0.1"){
    $city = "paris";
  }
  // 默认是上海. 
  else {
    $city = "shanghai";
  }
  
  return strtolower($city);
}

// 生成一个URL
function url($uri, $params = array()) {
  if (( $pos = strpos($uri, ".php")) !== FALSE) {
    $simple_uri = substr($uri, 0, $pos);
  }
  else {
    $simple_uri = $uri;
  }
  if (isset($params["cid"])) {
    $content = ContentAR::model()->findByPk($params["cid"]);
    if ($content) {
      return '/'.$simple_uri."/".ContentAR::getContentUrl($content);
    }
  }
  if (isset($params["name"])) {
    return "/{$simple_uri}/name/{$params["name"]}";
  }
  if (isset($params["type"])) {
    return "/{$simple_uri}/{$params["type"]}";
  }
  return '/'.$simple_uri;
}


function is_weaving($craft_id = NULL) {
  //           中       英     , 法
  if (!$craft_id) {
    $craft_id = isset($_GET["cid"]) ? $_GET["cid"]: 0;
  }
  $ids = array("20322", "20315", "20779");
  if (isset($craft_id) && in_array($craft_id, $ids)) {
    return TRUE;
  }
}

function is_zitan($craft_id = NULL) {
  //           中       英     , 法
  if (!$craft_id) {
    $craft_id = isset($_GET["cid"]) ? $_GET["cid"]: 0;
  }
  $ids = array("20321", "20316", "20777");
  if (isset($craft_id) && in_array($craft_id, $ids)) {
    return TRUE;
  }
}

function is_cashmere($craft_id = NULL) {
  //           中       英     , 法
  if (!$craft_id) {
    $craft_id = isset($_GET["cid"]) ? $_GET["cid"]: 0;
  }
  $ids = array("20319", "20318", "20778");
  if (isset($craft_id) && in_array($craft_id, $ids)) {
    return TRUE;
  }
}

function is_eggshell($craft_id = NULL) {
  //           中       英     , 法
  if (!$craft_id) {
    $craft_id = isset($_GET["cid"]) ? $_GET["cid"]: 0;
  }
  
  $ids = array("20320", "20317", "20780");
  if (isset($craft_id) && in_array($craft_id, $ids)) {
    return TRUE;
  }
}

function image_size($uri, $what = "width") {
  static $sizes = array();
  if (isset($sizes[$uri])) {
    $size = $sizes[$uri];
  }
  else {
    $size = MediaAR::model()->getImageSize($uri);
    $sizes[$uri] = $size;
  }
  return $what == "width" ?  $size[0]: $size[1];
}

function getLastCollection() {
  $list = CollectionContentAR::model()->getList(1);
  
  if ($list) {
    return $list[0];
  }
  
  return FALSE;
}

function getLastEvent() {
  $list = EventContentAR::model()->getList(1);
  
  if ($list) {
    return $list[0];
  }
  return FALSE;
}

function loadTypeMedias($type) {
  $path = "admin/upload/CROPS/". strtoupper($type);
  $dir = opendir($path);
  
  $files = array();
  while ($fPath = readdir($dir)) {
    if ($fPath[0] != '.') $files[] =  '/' .$path.'/'.$fPath;
  }
  
  return $files;
}

function nextProduct($product) {
  if (!$product) {
    return FALSE;
  }
  
  $productsGroupedByType = getProductInType($product->product_type);
  
  $products = $productsGroupedByType[$product->collection];
  
  foreach ($products as $index => $p) {
    if ($p->cid == $product->cid && $index < count($products) - 1) {
      return $products[$index + 1];
    }
  }
}

function prevProduct($product) {
  if (!$product) {
    return FALSE;
  }
  
  $productsGroupedByType = getProductInType($product->product_type);
  
  $products = $productsGroupedByType[$product->collection];
  
  foreach ($products as $index => $p) {
    if ($p->cid == $product->cid && $index > 0) {
      return $products[$index - 1];
    }
  }
}

function boutique_next_title($boutique ) {
  $locations = BoutiqueContentAR::getLocation();
  $keys = array_keys($locations);
  $boutiques = array();
  $i = 0;
  foreach ($locations as $key => $name) {
    $tmpBoutique = BoutiqueContentAR::model()->loadByAddressKey($key);
    if ($tmpBoutique->cid == $boutique->cid) {
      if (!empty($keys[$i + 1])) {
        $nkey = $keys[$i + 1];
        return BoutiqueContentAR::model()->loadByAddressKey($nkey)->title;
      }
    }
    $i += 1;
  }

  return Yii::t('strings', 'NEWS');
}

function boutique_next_link($boutique) {
  $locations = BoutiqueContentAR::getLocation();
  $keys = array_keys($locations);
  $boutiques = array();
  $i = 0;
  foreach ($locations as $key => $name) {
    $tmpBoutique = BoutiqueContentAR::model()->loadByAddressKey($key);
    if ($tmpBoutique->cid == $boutique->cid) {
      if (!empty($keys[$i + 1])) {
        $nkey = $keys[$i + 1];
        return url('boutique', array('cid' => BoutiqueContentAR::model()->loadByAddressKey($nkey)->title));
      }
    }
    $i += 1;
  }

  return '/news';
}

function boutique_pre_title($boutique) {
  $locations = BoutiqueContentAR::getLocation();
  $keys = array_keys($locations);
  $boutiques = array();
  $i = 0;
  foreach ($locations as $key => $name) {
    $tmpBoutique = BoutiqueContentAR::model()->loadByAddressKey($key);
    if ($tmpBoutique->cid == $boutique->cid) {
      if (!empty($keys[$i - 1])) {
        $nkey = $keys[$i - 1];
        return BoutiqueContentAR::model()->loadByAddressKey($nkey)->title;
      }
    }
    $i += 1;
  }
  return Yii::t('strings', 'CRAFTS');
}

function boutique_pre_link($boutique) {
  $locations = BoutiqueContentAR::getLocation();
  $keys = array_keys($locations);
  $boutiques = array();
  $i = 0;
  foreach ($locations as $key => $name) {
    $tmpBoutique = BoutiqueContentAR::model()->loadByAddressKey($key);
    if ($tmpBoutique->cid == $boutique->cid) {
      if (!empty($keys[$i - 1])) {
        $nkey = $keys[$i - 1];
        return url('boutique', array('cid' => BoutiqueContentAR::model()->loadByAddressKey($nkey)->title));
      }
    }
    $i += 1;
  }
  return '/craft/cashmere-felt';
}

function craft_next_title($craft) {
  $crafts = CraftContentAR::model()->getList();
  $i = 0;
  foreach ($crafts as $tmpcraft) {
    if ($tmpcraft->cid == $craft->cid) {
      if (!empty($crafts[$i + 1])) {
        $ncraft = $crafts[$i + 1];
        return $ncraft->title;
      }
    }
    $i += 1;
  }
  return Yii::t('strings', 'BOUTIQUES');
}

function craft_next_link($craft) {
  $crafts = CraftContentAR::model()->getList();
  $i = 0;
  foreach ($crafts as $tmpcraft) {
    if ($tmpcraft->cid == $craft->cid) {
      if (!empty($crafts[$i + 1])) {
        $ncraft = $crafts[$i + 1];
        return url('craft', array('cid' => $ncraft->cid));
      }
    }
    $i += 1;
  }
  return '/boutique/shanghai';
}

function craft_pre_title($craft) {
  $crafts = CraftContentAR::model()->getList();
  $i = 0;
  foreach ($crafts as $tmpcraft) {
    if ($tmpcraft->cid == $craft->cid) {
      if (!empty($crafts[$i - 1])) {
        $ncraft = $crafts[$i - 1];
        return $ncraft->title;
      }
    }
    $i += 1;
  }
  return Yii::t('strings', 'Collections');
}

function craft_pre_link($craft) {
  $crafts = CraftContentAR::model()->getList();
  $i = 0;
  foreach ($crafts as $tmpcraft) {
    if ($tmpcraft->cid == $craft->cid) {
      if (!empty($crafts[$i - 1])) {
        $ncraft = $crafts[$i - 1];
        return url('craft', array('cid' => $ncraft->cid));
      }
    }
    $i += 1;
  }
  return '/product-type/name/apparel';
}

function prev_product_type_title($type_name) {
  $type_name = ProductContentAR::getKeyWithTypeName($type_name);
  $types = ProductContentAR::getType();
  $keys = array_keys($types);
  $i = 0;
  foreach ($types as $key => $name) {
    if ($key == $type_name) {
      if (!empty($keys[$i - 1])) {
        $key = $keys[$i - 1];
        return Yii::t('strings', '「SHANG XIA」').' '.$types[$key];
      }
    }
    $i += 1;
  }
  return '';
}

function prev_product_type_link($type_name) {
  $type_name = ProductContentAR::getKeyWithTypeName($type_name);
  $types = ProductContentAR::getType();
  $keys = array_keys($types);
  $i = 0;
  foreach ($types as $key => $name) {
    if ($key == $type_name) {
      if (!empty($keys[$i - 1])) {
        $key = $keys[$i - 1];
        return url("product-type", array("name" => ProductContentAR::getTypeKeyName($key)));
      }
    }
    $i += 1;
  }
  return '';
}

function next_product_type_title($type_name) {
  $type_name = ProductContentAR::getKeyWithTypeName($type_name);
  $types = ProductContentAR::getType();
  $keys = array_keys($types);
  $i = 0;
  foreach ($types as $key => $name) {
    if ($key == $type_name) {
      if (!empty($keys[$i + 1])) {
        $key = $keys[$i + 1];
        return Yii::t('strings', '「SHANG XIA」').' '.$types[$key];
      }
    }
    $i += 1;
  }
  return Yii::t('strings', 'CRAFTS');
}

function next_product_type_link($type_name) {
  $type_name = ProductContentAR::getKeyWithTypeName($type_name);
  $types = ProductContentAR::getType();
  $keys = array_keys($types);
  $i = 0;
  foreach ($types as $key => $name) {
    if ($key == $type_name) {
      if (!empty($keys[$i + 1])) {
        $key = $keys[$i + 1];
        return url("product-type", array("name" => ProductContentAR::getTypeKeyName($key)));
      }
    }
    $i += 1;
  }

  return '/craft/cashmere-felt';
}



