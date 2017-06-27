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

  <?php
  /* example 4*/
  ini_set('error_reporting', E_ALL);
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);

  $config = (object) array(
  	/**
  	*
  	* Set site parameters
  	*
  	*/
      'host' => 'localhost',
      'dbuser' => 'root',
      'dbpass' => 'root',
      'dbname' => 'wpstock',
      'siteaddress' => 'http://wpstock:8888/', //for site and database, robots.txt HOST
      'themename' => 'test', //name theme project and theme dir!
      'admin_email' => 'websites@xpgraph.com', // admin email for notifications and dev
      'manager_email' => 'Manager@mail.here', //user (client) email for job
      'creator' => 'XPGraph', // author creator
      /* Unchanged parameters */
      'dbfile' => 'wpstock.sql',  //do not change!!!
      'path' => $_SERVER['DOCUMENT_ROOT'],  //do not change!!!
      'sitemap' => '/sitemap.xml',  //do not change!!!
      'reportfile' => 'report_install.txt', //do not change!!!
  		'AdminPass' => 'XPAdmin12345', // login panel /xp-panel, login name XPadmin
  		'ManagerPass' => 'XPManager12345' // login panel /xp-panel, login name Manager
  );

  class createSite
  {
  	protected $fileDb;
  	protected $connect;
  	protected $name;
  	protected $user;
  	protected $pass;
  	protected $host;
  	protected $flag = true;
  	protected $sqlQuery;
  	protected $sitemap;
  	protected $siteaddress;
  	protected $reportfile;
  	protected $themename;
  	protected $author;
  	protected $passadmin;
  	protected $passmanager;
  	//get config data after create
  	function __construct($StockConfig)
    {
  		$this->name = $StockConfig->dbname;
  		$this->host = $StockConfig->host;
  		$this->user = $StockConfig->dbuser;
  		$this->fileDb = $StockConfig->dbfile;
  		$this->pass = $StockConfig->dbpass;
  		$this->sitemap = $StockConfig->sitemap;
  		$this->siteaddress = $StockConfig->siteaddress;
  		$this->reportfile = $StockConfig->reportfile;
  		$this->themename = $StockConfig->themename;
  		$this->author = $StockConfig->creator;
  		$this->passadmin = $StockConfig->AdminPass;
  		$this->passmanager = $StockConfig->ManagerPass;
  	}

  	//connect db and check
  	public function dbConnect ()
    {
  		$this->connect = mysqli_connect($this->host, $this->user, $this->pass, $this->name);
  		if (!$this->connect) {
  			$message = "Error db connect MySQL. (function dbConnect)" . PHP_EOL;
  		} else {
  			$message = "DB Connected! (function dbConnect)";
  		}
  		return $message;
  	}

  	//check db empty
  	public function checkDb ()
    {
  		$name = $this->name;
  		$sql = "SHOW TABLES";
  		$result = $this->connect->query($sql);
  		if ($result->num_rows == 0) {
  		    $message = "db empty (function checkDb)";
  		} else {
  			$message = "db not empty (function checkDb)";
  			$this->flag = false;
  		}
  		return $message;
  	}

  	//get sql file and check file
  	public function getDbFile ()
    {
  		if (file_exists($this->fileDb)) {
  		    $message = 'File '.$this->fileDb.' exists (function getDbFile)';
  		    $this->sqlQuery = file_get_contents($this->fileDb);
  		} else {
  		    $message = 'File not  '.$this->fileDb.' exists (function getDbFile)';
  		    $this->sqlQuery = '';
  		}
  		return $message;
  	}

  	//import sql file on database
  	public function importDbFile ()
    {
  		if ($this->flag!==false) {
  			$sqlSource = $this->sqlQuery;
  			if (trim($sqlSource) == '')
  			{
  				$message = 'file exists but is empty (function importDbFile)';
  			} else {
  				if ($this->connect->multi_query($sqlSource)) {
  					$message = 'Sql Import done (function importDbFile)';
  				} else {
  					$message = "Error query (function importDbFile)" . $this->connect->error;
  				}
  				/* close connection */
  				$this->connect->close();
  			}
  		}
  		else {
  			$message = 'database not empty (function checkDb flag)';
  		}
  		return $message;
  	}

  	//create stock robots.txt with config params
  	public function createRobots ()
    {
  		if (file_exists("Robots.txt")) {
  			$message = 'robots.txt exists (function createRobots)';
  		} else {
  			$text = 'User-agent: *
  			Disallow: /
  Sitemap: '.$this->siteaddress.$this->sitemap.'
  Host: '.$this->siteaddress.'';
  			if ($fpRobo = fopen("Robots.txt", "w")) {
  				if (file_exists("Robots.txt")) {
  					fwrite($fpRobo, $text);
  					fclose($fpRobo);
  					$message = 'Robots.txt created (function createRobots)';
  				} else {
  					$message = 'Robots.txt not created (function createRobots)';
  				}
  			}
  		}
  		return $message;
  	}

  	public function updateThemeFile ()
  	{
  		$nametheme = $this->themename;
  		$pathtoreadm = 'wp-content/themes/' . $nametheme . '/README.txt';
  		$pathtocss = 'wp-content/themes/' . $nametheme . '/style.css';
  		$updated = '';
  		$olddir = 'wp-content/themes/XPproject';
  		$newdir = 'wp-content/themes/' . $nametheme;
  		if (file_exists(dirname(__FILE__) . $olddir)) {
  			if (rename($olddir, $newdir))	{
  				$updated .= $olddir . " - was updated to - " .$newdir. "\n";
  			} else {
  				$updated .= $olddir . " - has not been updated to - " .$newdir. "\n";
  			}
  		}
  		if (file_exists($pathtoreadm)) {
  			$fpreadme = fopen($pathtoreadm, "w");
  			$readmetxt = "== Changelog == \n release " . date('l jS \of F Y h:i:s A');
  			fwrite($fpreadme, $readmetxt);
  			fclose($fpreadme);
  			$updated .= $pathtoreadm . " - was updated \n";
  		} else {
  			$updated .= $pathtoreadm . " - has not been updated \n";
  		}
  		if (file_exists($pathtocss)) {
  			$fpcss = fopen($pathtocss, "w");
  			$csstxt = "/*
  Theme Name: ".$this->themename."
  Description: ".$this->themename." Theme
  Author: ".$this->author."
  Text Domain: ".$this->themename."
  Version: 1.0
  */";
  			fwrite($fpcss, $csstxt);
  			fclose($fpcss);
  			$updated .= $pathtocss . " - was updated \n";
  		} else {
  			$updated .= $pathtocss . " - has not been updated \n";
  		}
  		$message = "this files (function updateThemeFile): \n" . $updated . PHP_EOL;
  		return $message;
  	}

  	// run script and check file load script
  	public function setPass()
  	{
  		$admin_id = 1;
  		$manager_id = 2;
  		$message = '';
  		if (wp_set_password( $this->passadmin, $admin_id )) {
  			$message .= 'Admin pass set!'."\n";
  		}
  		if (wp_set_password( $this->passmanager, $manager_id )) {
  			$message .= 'Manager pass set!'."\n";
  		}
  		return $message;
  	}
  	public function runScript ()
    {
  		if (file_exists($this->reportfile)) {
  			$report = 'site was created (function runScript) check '.$this->reportfile.'';
  		} else {
  			$msg_connect = $this->dbConnect();
  			$msg_check = $this->checkDb();
  			$msg_getfile = $this->getDbFile();
  			$msg_import = $this->importDbFile();
  			$msg_robots = $this->createRobots();
  			$msg_file = $this->updateThemeFile();
  			$msg_pass = $this->setPass();
  			$report = $msg_connect . "\n" . $msg_check . "\n" . $msg_getfile . "\n" . $msg_pass . "\n" . $msg_import . "\n" . $msg_robots . "\n" . date('l jS \of F Y h:i:s A') . "\n" . $msg_file . "\n";
  			$fpInstal = fopen($this->reportfile, "w");
  			fwrite($fpInstal, $report);
  			fclose($fpInstal);
  		}
  		return $report;
  	}
  }
  $dbImport = new createSite($config);
  $report = $dbImport->runScript();
