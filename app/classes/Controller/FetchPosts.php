<?php

namespace App\Controller;

use App\App;
use Core\Page\View;

class FetchPosts extends Base
{
    /**
     * Send a GET request without using PHP's curl functions.
     *
     * @param string $url The URL you are sending the GET request to.
     * @param array $postVars Associative array containing GET values.
     * @return string The output response.
     * @throws Exception If the request fails.
     */
    public function get($url, $postVars = [])
    {

        $url_with_parameters = "$url?sl_token={$postVars['sl_token']}&page={$postVars['page']}";

        // var_dump($url_with_parameters);
        // die();
 
        //Once again, we use file_get_contents to GET the URL in question.
        $contents = file_get_contents($url_with_parameters);
        
        //If $contents is FALSE, then the request has failed.
        if($contents === false){

            //If the request failed, throw an Exception containing
            //the error.
            $error = error_get_last();

            throw new Exception('POST request failed: ' . $error['message']);
        }

        //If everything went OK, return the response.
        return $contents;
    }

    public function fetch_all_posts($url, $how_many_pages)
    {

        $all_posts = [];

        for ($page = 1; $page <= $how_many_pages; $page++) {

            $get_request = self::get($url, [
                'sl_token' => $_COOKIE['slToken'],
                'page' => $page,
            ]);

            $get_request = json_decode($get_request, true);

            array_push($all_posts, ...array_values($get_request['data']['posts']));
        }

        return $all_posts;
    }

    public function filter_posts($user_id, $posts_array = [])
    {
        $filtered_posts_array = [];

        foreach($posts_array as $post) {
            if ($post['from_id'] === $user_id) {
                array_push($filtered_posts_array, $post);
            }
        }

        return $filtered_posts_array;
    }

    public function filter_posts_months($posts_array = []) 
    {
        $months = [];

        foreach($posts_array as $post) {
            $month = explode("-", $post['created_time']);

            if(!in_array($month[1], $months)) {
                $months[] = $month[1];
            }
        }

        return $months;
    }

    public function filter_posts_by_month($posts_array = [])
    {
        $months = self::filter_posts_months($posts_array);

        $posts_by_month = [];

        foreach($posts_array as $post) {
            $month = explode("-", $post['created_time']);

            if(in_array($month[1], $months)) {
                $posts_by_month[$month[1]][] = $post;
            }
        }

        return $posts_by_month;
    }

    public function avarage_char_length($filtered_posts_by_month_array = []) 
    {
        $months = sizeof($filtered_posts_by_month_array);
        $avarage_char_length = 0;

        foreach($filtered_posts_by_month_array as $month_posts_array) {
            $how_many_posts = sizeof($month_posts_array);

            $month_messages_length = 0;
            foreach($month_posts_array as $post) {
                $month_messages_length += strlen($post['message']);
            }

            $avarage_char_length_per_month = $month_messages_length / $how_many_posts;
            $avarage_char_length += $avarage_char_length_per_month;
        }

        return $avarage_char_length;
    }

    public function longest_post($filtered_posts_array = [])
    {
        $longest_message = [];
        $post_message_length = 0;

        foreach($filtered_posts_array as $post) {
            if($post_message_length <= $post['message']) {
                $longest_message = $post;
                $post_message_length = $post['message'];
            }
        }

        return $longest_message;
    }

    public function avarage_number_of_posts($filtered_posts_by_month_array = [], $filtered_posts_array = []) {
        $months = sizeof($filtered_posts_by_month_array);
        $posts = sizeof($filtered_posts_array);

        return $posts / $months;
    }

    public function __construct()
    {
        parent::__construct();

        if(isset($_COOKIE['tokenUser'])) {

            $all_posts = self::fetch_all_posts('https://api.supermetrics.com/assignment/posts', 10);
            $filtered_posts = self::filter_posts('user_1', $all_posts);
            $filtered_posts_by_month = self::filter_posts_by_month($filtered_posts);
            $avarage_char_length_of_months = self::avarage_char_length($filtered_posts);
            $longest_post_by_char = self::longest_post($filtered_posts);
            $avarage_number_of_posts = self::avarage_number_of_posts($filtered_posts_by_month, $filtered_posts);


            $this->page['title'] = 'Fetch Posts';

            $view = new View([
                'avarageCharLength' => $avarage_char_length_of_months,
                'longestPostByChar' => $longest_post_by_char['id'],
                'avarageNumberOfPosts' => $avarage_number_of_posts,
            ]);

            $this->page['content'] = $view->render(ROOT_DIR . '/app/views/fetchPosts.tpl.php');

        } else {
            header('Location: /register');

            exit();
        }
    }
}
