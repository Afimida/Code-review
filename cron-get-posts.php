<?php
define('SOCIAL_POSTS_DIR', dirname(__FILE__));
define('FILE_JSON_SOCIAL', SOCIAL_POSTS_DIR . '/includes/data/social-api.json');

class Social
{
    public $array_my_post = [
        'facebook' => [],
        'twitter' => [],
        'instagram' => [],
        'tumblr' => [],
        'cultmedia' => [],
        'cultinvest' => []
    ];

    public function get_fb_posts($args)
    {
        $options = $args['postoptions'];
        $access_token = $args['connect']['access_token'];
        require_once SOCIAL_POSTS_DIR . '/includes/social-api-files/facebook/src/Facebook/autoload.php';
        $fb = new Facebook\Facebook([
            'app_id' => $args['connect']['app_id'],
            'app_secret' => $args['connect']['app_secretkey'],
            'default_graph_version' => $args['connect']['graph_version'],
        ]);
        $response = $fb->get('/me/posts?fields=message,name,story,permalink_url&limit=' . $options['count'] . '', $access_token);
        $response_name = $fb->get('/me', $access_token);
        $objectData = $response->getGraphEdge();
        $objectName = $response_name->getGraphNode();
        $j = 0;
        $new_array = array();
        foreach ($objectData->asArray() as $value) {
            $objName = $objectName->asArray();
            if (!empty($value)) {
                if (isset($value['story'])) {
                    $title = $value['story'];
                } elseif (isset($value['message'])) {
                    $title = substr($value['message'], 0, 70);
                } elseif (isset($value['name'])) {
                    $title = $value['name'];
                }

                if (isset($title)) {
                    $new_array[$j]['url_post'] = $value['permalink_url'];
                    $new_array[$j]['color'] = $options['color'];
                    $new_array[$j]['size'] = '';
                    $new_array[$j]['icon'] = $options['social'];
                    $new_array[$j]['title'] = $title;
                    $new_array[$j]['subtitle'] = $objName['name'];
                    $new_array[$j]['per_page'] = $options['per_page'];
                    $j++;
                }
            }
        }
        if (!empty($new_array)) {
            $this->prepare_posts($new_array, 'facebook');
        }
    }

    public function get_twitter_posts($args)
    {
        $options = $args['postoptions'];
        require_once SOCIAL_POSTS_DIR . '/includes/social-api-files/twitter/TwitterAPIExchange.php';
        $settings = array(
            'oauth_access_token' => $args['connect']['oauth_access_token'],
            'oauth_access_token_secret' => $args['connect']['oauth_access_token_secret'],
            'consumer_key' => $args['connect']['consumer_key'],
            'consumer_secret' => $args['connect']['consumer_secret'],
        );
        $requestMethod = 'GET';
        $url1 = $args['connect']['url'];

        $sc_name = $args['connect']['name_SC'];
        $getfield = '?screen_name=' . $sc_name . '&exclude_replies=true&include_rts=true&contributor_details=false&count=' . $options['count'];

        $twitter = new TwitterAPIExchange($settings);
        $tweets = $twitter->setGetfield($getfield)->buildOauth($url1, $requestMethod)->performRequest();

        $response = json_decode($tweets);
        $j = 0;
        $new_array = array();
        foreach ($response as $value) {
            if (!empty($value)) {
                $title = substr(htmlspecialchars($value->text), 0, 70);
                $new_array[$j]['url_post'] = 'https://twitter.com/' . $value->user->name . '/status/' . $value->id . '';
                $new_array[$j]['color'] = $options['color'];
                $new_array[$j]['size'] = '';
                $new_array[$j]['icon'] = $options['social'];
                $new_array[$j]['title'] = $title;
                $new_array[$j]['subtitle'] = $value->user->name;
                $new_array[$j]['per_page'] = $options['per_page'];
                $j++;
            }
        }
        if (!empty($new_array)) {
            $this->prepare_posts($new_array, 'twitter');
        }
    }

    public function get_instagram_posts($args)
    {
        $options = $args['postoptions'];
        $connection_c = curl_init(); // initializing
        curl_setopt($connection_c, CURLOPT_URL, $args['connect']['url']); // API URL to connect
        curl_setopt($connection_c, CURLOPT_RETURNTRANSFER, 1); // return the result, do not print
        curl_setopt($connection_c, CURLOPT_TIMEOUT, 20);
        $json_return = curl_exec($connection_c); // connect and get json data
        curl_close($connection_c); // close connection
        $data = (array)json_decode($json_return);
        $j = 0;
        $new_array = array();
        foreach ($data['data'] as $value_inst) {
            if (!empty($value_inst)) {
                $title = $value_inst->caption->text;
                $new_array[$j]['url_post'] = $value_inst->link;
                $new_array[$j]['color'] = $options['color'];
                $new_array[$j]['size'] = '';
                $new_array[$j]['icon'] = $options['social'];
                $new_array[$j]['title'] = $title;
                $new_array[$j]['subtitle'] = $value_inst->caption->from->username;
                $new_array[$j]['image'] = $value_inst->images->standard_resolution->url;
                $new_array[$j]['per_page'] = $options['per_page'];
                $j++;
            }
        }
        if (!empty($new_array)) {
            $this->prepare_posts($new_array, 'instagram');
        }
    }

    public function get_cultmedia_posts($args)
    {
        $options = $args['postoptions'];
        $connection_c = curl_init(); // initializing
        curl_setopt($connection_c, CURLOPT_URL, $args['connect']['url']); // API URL to connect
        curl_setopt($connection_c, CURLOPT_RETURNTRANSFER, 1); // return the result, do not print
        curl_setopt($connection_c, CURLOPT_TIMEOUT, 20);
        $json_return = curl_exec($connection_c); // connect and get json data
        curl_close($connection_c); // close connection
        $data = (array)json_decode($json_return);
        $j = 0;
        $new_array = array();
        foreach ($data as $value_inst) {
            if (!empty($value_inst)) {
                if (isset($value_inst->title)) {
                    $title = $value_inst->title;
                    $new_array[$j]['url_post'] = $value_inst->link;
                    $new_array[$j]['color'] = $options['color'];
                    $new_array[$j]['size'] = '';
                    $new_array[$j]['icon'] = $options['social'];
                    $new_array[$j]['title'] = $title;
                    $new_array[$j]['subtitle'] = 'Blog Culture-crossmedia';
                    $new_array[$j]['per_page'] = $options['per_page'];
                    $j++;
                }
            }
        }
        if (!empty($new_array)) {
            $this->prepare_posts($new_array, 'cultmedia');
        }
    }

    public function get_cultinvest_posts($args)
    {
        $options = $args['postoptions'];
        $connection_c = curl_init(); // initializing
        curl_setopt($connection_c, CURLOPT_URL, $args['connect']['url']); // API URL to connect
        curl_setopt($connection_c, CURLOPT_RETURNTRANSFER, 1); // return the result, do not print
        curl_setopt($connection_c, CURLOPT_TIMEOUT, 20);
        $json_return = curl_exec($connection_c); // connect and get json data
        curl_close($connection_c); // close connection
        $data = (array)json_decode($json_return);
        $j = 0;
        $new_array = array();
        foreach ($data as $value_inst) {
            if (!empty($value_inst)) {
                if (isset($value_inst->title)) {
                    $title = $value_inst->title;
                    $new_array[$j]['url_post'] = $value_inst->link;
                    $new_array[$j]['color'] = $options['color'];
                    $new_array[$j]['size'] = '';
                    $new_array[$j]['icon'] = $options['social'];
                    $new_array[$j]['title'] = $title;
                    $new_array[$j]['subtitle'] = 'Blog Culture-evenement';
                    $new_array[$j]['per_page'] = $options['per_page'];
                    $j++;
                }
            }
        }
        if (!empty($new_array)) {
            $this->prepare_posts($new_array, 'cultinvest');
        }
    }

    public function get_tumblr_posts($args)
    {
        $options = $args['postoptions'];
        require_once SOCIAL_POSTS_DIR . '/includes/social-api-files/tumblr/vendor/autoload.php';
        $client = new Tumblr\API\Client(
            $args['connect']['consumer_key'],
            $args['connect']['consumer_secret'],
            $args['connect']['oauth_access_token'],
            $args['connect']['oauth_access_token_secret']
        );
        // Make the request
        $j = 0;
        $allPosts = $client->getBlogPosts('mlab-moonda.tumblr.com', array('limit' => $options['count']));
        $new_array = array();
        foreach ($allPosts->posts as $value_inst) {
            $array = (array)$value_inst;
            if (!empty($array)) {
                $title = '';
                if (isset($array['title'])) {
                    $title = $array['title'];
                }
                $new_array[$j]['url_post'] = $array['short_url'];
                $new_array[$j]['color'] = $options['color'];
                $new_array[$j]['size'] = '';
                $new_array[$j]['icon'] = $options['social'];
                $new_array[$j]['title'] = $title;
                $new_array[$j]['subtitle'] = $array['blog_name'];
                $new_array[$j]['per_page'] = $options['per_page'];
                $j++;
            }
        }
        if (!empty($new_array)) {
            $this->prepare_posts($new_array, 'tumblr');
        }
    }

    /**
     * @param array $args
     * @param string $type
     */
    protected function prepare_posts($args, $type)
    {
        foreach ($args as $value) {
            array_push($this->array_my_post[$type], $value);
        }
    }
}

function asa_save_as_file($array)
{
    $file_name = FILE_JSON_SOCIAL;
    if (file_exists($file_name)) {
        $fp = fopen($file_name, 'w');
        fwrite($fp, json_encode($array));
        fclose($fp);
    }
}

function asa_show_on_theme()
{
    global $argsFbconect, $argsTwConect, $argsInsConect, $argsCultmConect, $argsCultiConect;

    require_once SOCIAL_POSTS_DIR . '/includes/settings/config.php';
    $showSoc = new Social();
    $showSoc->get_fb_posts($argsFbconect);
    $showSoc->get_twitter_posts($argsTwConect);
    $showSoc->get_instagram_posts($argsInsConect);
    // $showSoc->tumblMassive($argsTumblrConect);
    $showSoc->get_cultmedia_posts($argsCultmConect);
    $showSoc->get_cultinvest_posts($argsCultiConect);
    $array_post = $showSoc->array_my_post;
    asa_save_as_file($array_post);
    unset($showSoc);
}

asa_show_on_theme();
