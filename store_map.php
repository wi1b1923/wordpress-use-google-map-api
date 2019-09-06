<?php
/*
Template Name: store_map
*/

get_header(); ?>
<style>
      #mymap {
        height: 80%;
        width:100%;
      }
</style>
<script src="https://maps.googleapis.com/maps/api/js?key='your key'&callback=initMap" async defer></script>


<?php
if(!empty($_GET['county'])){
    $choose_c = $_GET['county'];
}
else{
    $choose_c = '台北市'; //預設顯示台北市店家 
}
//從資料庫拉出前端選單選到的城市經緯度(預設台北市)
$city_latlng = $wpdb->get_row("SELECT city_lat,city_lng FROM city_latlng WHERE city='{$choose_c}'");


//取得資料status為1(仍營業中)店家的資料存入陣列
$list_item = $wpdb->get_results("SELECT store_name,address,city,store_tel,lng,lat FROM store_table WHERE status=1 AND city='{$choose_c}'");
if(!empty($list_item)){
    $i=0;
    foreach ($list_item as $showdp) {
        $cname[$i] = $showdp->store_name;
        $caddr[$i] = $showdp->address;
        $ccity[$i] = $showdp->city;
        $ctel[$i] = $showdp->store_tel;
        $store_lng[$i] = $showdp->lng;
        $store_lat[$i] = $showdp->lat;
        $i++;
    }
}

if(!empty($city_latlng)){
    $now_lat=$city_latlng->city_lat;
    $now_lng=$city_latlng->city_lng;
}
?>
<script>
//echo後端資料到前端給js，並使用google map api。可另外追加店家聯絡資訊顯示在maker上(本例未用到)
var map;


var markers = [];
var jscname = ["<?php echo join("\", \"", $cname); ?>"];
var jscaddr = ["<?php echo join("\", \"", $caddr); ?>"];
var jsccity = ["<?php echo join("\", \"", $ccity); ?>"];
var jsctel = ["<?php echo join("\", \"", $ctel); ?>"];
var jsstore_lng = ["<?php echo join("\", \"", $store_lng); ?>"];
var jsstore_lat = ["<?php echo join("\", \"", $store_lat); ?>"];
var jsNumber = '<?php print($i); ?>';

var jsnow_lat = '<?php print($now_lat); ?>';
var jsnow_lng = '<?php print($now_lng); ?>';
//初始化，地圖樣式透過 https://mapstyle.withgoogle.com/  製作
function initMap() {
    map = new google.maps.Map(document.getElementById('mymap'), {
        zoom: 11,
        center: {
        lat: parseFloat(jsnow_lat),
        lng: parseFloat(jsnow_lng)
         },
    styles:
[
    {
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#ebe3cd"
      }
    ]
  },
  {
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#523735"
      }
    ]
  },
  {
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#f5f1e6"
      }
    ]
  },
  {
    "featureType": "administrative",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#c9b2a6"
      }
    ]
  },
  {
    "featureType": "administrative.land_parcel",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#dcd2be"
      }
    ]
  },
  {
    "featureType": "administrative.land_parcel",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#ae9e90"
      }
    ]
  },
  {
    "featureType": "landscape.natural",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#dfd2ae"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#dfd2ae"
      }
    ]
  },
  {
    "featureType": "poi",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#93817c"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#a5b076"
      }
    ]
  },
  {
    "featureType": "poi.park",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#447530"
      }
    ]
  },
  {
    "featureType": "road",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#f5f1e6"
      }
    ]
  },
  {
    "featureType": "road.arterial",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#fdfcf8"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#f8c967"
      }
    ]
  },
  {
    "featureType": "road.highway",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#e9bc62"
      }
    ]
  },
  {
    "featureType": "road.highway.controlled_access",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#e98d58"
      }
    ]
  },
  {
    "featureType": "road.highway.controlled_access",
    "elementType": "geometry.stroke",
    "stylers": [
      {
        "color": "#db8555"
      }
    ]
  },
  {
    "featureType": "road.local",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#806b63"
      }
    ]
  },
  {
    "featureType": "transit.line",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#dfd2ae"
      }
    ]
  },
  {
    "featureType": "transit.line",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#8f7d77"
      }
    ]
  },
  {
    "featureType": "transit.line",
    "elementType": "labels.text.stroke",
    "stylers": [
      {
        "color": "#ebe3cd"
      }
    ]
  },
  {
    "featureType": "transit.station",
    "elementType": "geometry",
    "stylers": [
      {
        "color": "#dfd2ae"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "geometry.fill",
    "stylers": [
      {
        "color": "#b9d3c2"
      }
    ]
  },
  {
    "featureType": "water",
    "elementType": "labels.text.fill",
    "stylers": [
      {
        "color": "#92998d"
      }
    ]
  }
]
  });
//呼叫addMarker加入標記點
    for (var k = 0; k < jsNumber; k++) {
    addMarker(k);
    
  } 
}


function addMarker(e) {
    markers[e] = new google.maps.Marker({
        position: {
            lat: parseFloat(jsstore_lat[e]),
            lng: parseFloat(jsstore_lng[e])
        },
        map: map,
        title: jscname[e]
    });
      markers[e].addListener('click', function() {
          map.setZoom(20);
          map.setCenter(markers[e].getPosition());
      });
}


function get_store_label(LatLng){
    map.setCenter(markers[LatLng].getPosition());
    map.setZoom(20);
}
</script>

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">

            <?php while ( have_posts() ) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

                    <div class="entry-main">

                        <?php do_action('vantage_entry_main_top') ?>

                        <div class="entry-content">
                            <?php the_content( __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'vantage' ) ); ?>
                            <h3 class="uppercase ChText">門市地圖</h3>
                            
                            <div style="height:400px;">
                                <div id="mymap"></div>
                            </div>
                            <div class="container">
                                <div class="row">
                                    <div class="col-md-4 col-xs-4">
                                        <form>
                                            <div class="row">
                                                <p style="font-weight:bold;padding-left:20px;">請選擇您欲前往之門市所在縣市</p>
                                            </div>
                                            <div class="row">
                                                <select name="county" class="col-md-4" style="background: #f5f5f5;font-weight: 700;color: #777;">
                                                    <option value="">縣市</option>
                                                    <option value="基隆市">基隆市</option>
                                                    <option value="台北市">台北市</option>
                                                    <option value="新北市">新北市</option>
                                                    <option value="宜蘭縣">宜蘭縣</option>
                                                    <option value="新竹市">新竹市</option>
                                                    <option value="新竹縣">新竹縣</option>
                                                    <option value="桃園市">桃園市</option>
                                                    <option value="苗栗縣">苗栗縣</option>
                                                    <option value="台中市">台中市</option>
                                                    <option value="彰化縣">彰化縣</option>
                                                    <option value="南投縣">南投縣</option>
                                                    <option value="嘉義市">嘉義市</option>
                                                    <option value="嘉義縣">嘉義縣</option>
                                                    <option value="雲林縣">雲林縣</option>
                                                    <option value="台南市">台南市</option>
                                                    <option value="高雄市">高雄市</option>
                                                    <option value="屏東縣">屏東縣</option>
                                                    <option value="台東縣">台東縣</option>
                                                    <option value="花蓮縣">花蓮縣</option>
                                                    <option value="金門縣">金門縣</option>
                                                    <option value="連江縣">連江縣</option>
                                                    <option value="澎湖縣">澎湖縣</option>
                                                </select>
                                                <div class="col-md-4"><button type="submit" style="background: #000;border: 1px solid #eee;color: #fff;width: 100%;letter-spacing: 1px;">查詢</button></div>
                                            </div>
                                        </form>
                                    </div>

                                    <div class="col-md-8" style="padding-bottom:50px;">
                                        <div id="store_list">
                                            <h6 style="letter-spacing: 0.5rem;text-align:center;">點擊<img src="your maker img url">&nbsp;可快速找到門市位置哦！</h6>
                                        <?php for($j=0;$j<$i;$j++){ ?><!--迴圈打印出各店列表-->
                                            <div class="row"><hr size="8px" align="center" width="100%"></div><!--分隔線-->
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h4><?php echo $cname[$j]?></h4>
                                                    <p><?php echo $ctel[$j]?></p>
                                                    <p><a href="https://www.google.com.tw/maps/place/<?php echo $ccity[$j].$caddr[$j];?>" target="_blank"><?php echo $ccity[$j].$caddr[$j];?></a></p>
                                                </div>
                                                <div class="col-md-1"><a href="#" onclick="get_store_label(<?php echo $j;?>)"><img src="your maker img url" title="點擊前往門市標記點"></a></div>
                                                <div class="col-md-4"><!--店照片--></div>
                                            </div>
                                        <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php wp_link_pages( array( 'before' => '<div class="page-links">' . __( 'Pages:', 'vantage' ), 'after' => '</div>' ) ); ?>
                        </div><!-- .entry-content -->

                        <?php do_action('vantage_entry_main_bottom') ?>

                    </div>

                </article><!-- #post-<?php the_ID(); ?> -->

                <?php if ( comments_open() || '0' != get_comments_number() ) : ?>
                    <?php comments_template( '', true ); ?>
                <?php endif; ?>

            <?php endwhile; // end of the loop. ?>

        </div><!-- #content .site-content -->
    </div><!-- #primary .content-area -->

<?php get_footer(); ?>