# wordpress-rest-api-client

> A Wordpress REST API client for PHP

## Installation

This library can be installed with [Composer](https://getcomposer.org):

```text
composer require reactmore/wordpress-rest-api-client
```

## Usage

Example:

```php
require 'vendor/autoload.php';

$client = new Reactmore\WordpressClient\Wordpress(new Reactmore\WordpressClient\Request\GuzzleAdapter(new GuzzleHttp\Client()), 'http://domain.com');

// iF WP API DISABLE YOU NEED TO PASS AUTH
// $client->setCredentials(new Reactmore\WordpressClient\Auth\WpBasicAuth('user', 'password'));

// Argument 1 id default Null
// Argumend 2 Array default Null
$posts = $client->posts()->get(1, ['params' => 'Key']);

echo '<pre>';
print_r($posts);
echo '</pre>';
```

Response Success:

- status = status name
- code = status Code
- data = callback data from wordpress
- X-WP-TOTAL = count all post on current result
- X-WP-TOTAL-PAGE = count all page on current result

```Array
Array
(

    [status] => success
    [code] => 200
    [data] => Array(
        
        id =>
        slug => 
        xxxx
    )
    [X-WP-TOTAL] => 6909
    [X-WP-TOTAL-PAGE] => 70

)
```

Response Error:

- status = status name
- code = status Code
- error = error message

```Array
Array
(

    [status] => failed
    [code] => 400
    [error] => Array(
        
        message =>
       
    )
 

)
```

## Addon Custom Route
Insert On Functions Your Theme 
```php
function custom_api_get_all_posts()
{
    register_rest_route('custom/v1', '/all-posts', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'custom_api_get_all_posts_callback'
    ));
}

// custom Route API 
function custom_api_get_all_posts_callback(WP_REST_Request $request)
{
   
    $posts_data = array();
    
    $paged = $request->get_param('page');
    $paged = (isset($paged) || !(empty($paged))) ? $paged : 1;
    
    $author = $request->get_param('author');
    $author = (isset($author) || !(empty($author))) ? $author : '';
    
    $search = $request->get_param('search');
    $search = (isset($search) || !(empty($search))) ? $search : '';
    
    $after = $request->get_param('after');
    $after = (isset($after) || !(empty($after))) ? $after : '';
    
    $before = $request->get_param('before');
    $before = (isset($before) || !(empty($before))) ? $before : '';
    
    
    
   
    $query = new WP_Query(
        array(
            's' => $search,
            'author' => $author,
            'paged' => $paged,
            'post__not_in' => get_option('sticky_posts'),
            'posts_per_page' => 10,
            'post_type' => array('post'),
            'date_query'          => array(
                //set date ranges with strings!
                'after' => $after,
                'before' =>  $before,
               
            ),
            'orderby' => 'date',
            'order'   => 'DESC'
           
        )
    );

    // if no posts found return 
    if (empty($query->posts)) {
        return new WP_Error('no_posts', __('No post found'), array('status' => 404));
    }

    // set max number of pages and total num of posts
    $max_pages = $query->max_num_pages;
    $total = $query->found_posts;

    $posts = $query->posts;

    // prepare data for output
    $controller = new WP_REST_Posts_Controller('post');

    foreach ($posts as $post) {

        $response = $controller->prepare_item_for_response($post, $request);
        $post_data = $controller->prepare_response_for_collection($response);
        $post_thumbnail = (has_post_thumbnail($post_data['id'])) ? get_the_post_thumbnail_url($post_data['id']) : null;

        $data[] = (object) array(
            'id' => $post_data['id'],
            'date' => $post->post_date,
            'author' => array(
                'id' => $post->post_author,
                'name' => get_the_author_meta('user_nicename', $post->post_author),
             ),
            'type' => $post->post_type,
            'title' => $post->post_title,
            'url' => get_permalink($post_data['id']),
            'content' => $post_data['excerpt']['rendered'],
            'featured_img_src' => $post_thumbnail,
            'meta' => get_post_meta($post_data['id'], '', '')

        );
    }

    // set headers and return response      
    $response = new WP_REST_Response($data, 200);

    $response->header('X-WP-Total', $total);
    $response->header('X-WP-TotalPages', $max_pages);

    return $response;
}
```

Parameter :
- page = page your data
- author = filter by author id or name
- search = search post like search on API Post v2
- after and Before = Filter date Range better use Format d-f-y on query string or string on backend logic  

## Screenshoot

![Backend](https://raw.githubusercontent.com/reactmore/wordpress-rest-api-client/master/preview.jpg)
Implementations Paginations
