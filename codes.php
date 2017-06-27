<?php
/* example 1 */
function url_origin( $s, $use_forwarded_host = false ) {
    $ssl      = ( ! empty( $s['HTTPS'] ) && $s['HTTPS'] == 'on' );
    $sp       = strtolower( $s['SERVER_PROTOCOL'] );
    $protocol = substr( $sp, 0, strpos( $sp, '/' ) ) . ( ( $ssl ) ? 's' : '' );
    $port     = $s['SERVER_PORT'];
    $port     = ( ( ! $ssl && $port=='80' ) || ( $ssl && $port=='443' ) ) ? '' : ':'.$port;
    $host     = ( $use_forwarded_host && isset( $s['HTTP_X_FORWARDED_HOST'] ) ) ? $s['HTTP_X_FORWARDED_HOST'] : ( isset( $s['HTTP_HOST'] ) ? $s['HTTP_HOST'] : null );
    $host     = isset( $host ) ? $host : $s['SERVER_NAME'] . $port;
    return $protocol . '://' . $host;
}
/* example 2 */
function add_span_second_word($phrase, $start_word, $finish_word) {
  $phrase = preg_split("/\s+/", $phrase);
  $phrase[$start_word-1] = '<span>' . $phrase[$start_word-1];
  $phrase[$finish_word-1] = $phrase[$finish_word-1] . '</span>';
  $phrase = join(" ", $phrase);
  return $phrase;
}
?>
<?php
/* example 3 */
class Hometesear
{
  public function showall ($massive_posts, $class, $type, $count)
  {
    $message = '';
    $i=0;
    foreach ($massive_posts as $value) {
      $post_anonce = get_field('small_annonce', $value->ID);
      $post_anonce_sub = get_field('small_annonce_sub', $value->ID);
      $post_title = get_field('title_home_page', $value->ID);
      $post_img= get_field('image_home_page', $value->ID);
      if ($type!='posts'){
        $teasertype = get_field('big_teaser_home', $value->ID);
      } else {
        $teasertype = 'posts';
      }
      $permalink = get_permalink($value->ID);
      $post_categories = get_the_category( $value->ID );
      $my_anonc = get_field('short_title', $value->ID);
      $subclass = $class;
      if (isset($post_categories['0']->name))
      {
        $cat_name_forteas = $post_categories['0']->name;
      } else {
        $cat_name_forteas = '';
      }
      if (($teasertype == $type)&&($i!=$count))
      {
        $message .= '
        <a href="'.$permalink.'" class="'.$subclass.'">
          <div style="background-image: url('.$post_img.');" class="article-small__body">
            <div class="article-small__headline">'.$post_title.'</div>
            <div class="article-small__link">
              <svg class="icon icon-arrow">
                <use xlink:href="#icon-arrow"></use>
              </svg>
              <div class="article-small__link-inner"><span>'.$cat_name_forteas.'</span>'.$my_anonc.'</div>
            </div>
          </div>
        </a>';
        $i++;
      }
    }
    return $message;
  }
}

$args_stand = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'order'    => 'ASC',
        'posts_per_page' => -1
);
$args_page = array(
        'post_type' => 'page',
        'post_status' => 'publish',
        'order'    => 'ASC'
);
//  classes to teasers
//  'article-small article-small_medium';
//  'article-small article-small_large';
//  'article-small';
$post_stadard = query_posts($args_stand);
$page_stadard = new WP_Query($args_page);
// print_r($page_stadard);
 ?>
  <section class="grid">
    <div class="grid-sizer"></div>
    <?php
      $teaserObj = new Hometesear();
      $medium_posts = $teaserObj->showall($page_stadard->posts, 'article-small article-small_medium', 'medium', 1);
      $large_posts = $teaserObj->showall($page_stadard->posts, 'article-small article-small_large', 'large', 1);
      $standrat_posts = $teaserObj->showall($post_stadard, 'article-small', 'posts', -1);
      print_r($medium_posts);
      print_r($large_posts);
      print_r($standrat_posts);
    ?>
  </section>
