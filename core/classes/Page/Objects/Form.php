<?php

namespace Core\Page\Objects;

use Core\Page\View;
use Exception;

class Form
{

    protected $form;
    protected $input;

    public function __construct(array $form)
    {
        $this->form = $form;
    }

    public function render($tpl_path = ROOT_DIR . '/core/views/form.tpl.php')
    {
        return (new View($this->form))->render($tpl_path);
    }

    public function get_safe_input()
    {
        $filtro_parametrai = [
            'action' => FILTER_SANITIZE_SPECIAL_CHARS
        ];
        foreach ($this->form['fields'] as $field_id => $field) {
            $filter_type = $field['filter'] ?? FILTER_SANITIZE_SPECIAL_CHARS;
            $filtro_parametrai[$field_id] = $filter_type;
        }
        return filter_input_array(INPUT_POST, $filtro_parametrai);
    }

    /**
     * Send a POST request without using PHP's curl functions.
     *
     * @param string $url The URL you are sending the POST request to.
     * @param array $postVars Associative array containing POST values.
     * @return string The output response.
     * @throws Exception If the request fails.
     */
    public function post($url, $postVars = array()){

        //Transform our POST array into a URL-encoded query string.
        $postStr = http_build_query($postVars);

        //Create an $options array that can be passed into stream_context_create.
        $options = array(
            'http' =>
                array(
                    'method'  => 'POST', //We are using the POST HTTP method.
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postStr //Our URL-encoded query string.
                )
        );

        //Pass our $options array into stream_context_create.
        //This will return a stream context resource.
        $streamContext  = stream_context_create($options);

        //Use PHP's file_get_contents function to carry out the request.
        //We pass the $streamContext variable in as a third parameter.
        $result = file_get_contents($url, false, $streamContext);

        //If $result is FALSE, then the request has failed.
        if($result === false){

            //If the request failed, throw an Exception containing
            //the error.
            $error = error_get_last();

            throw new Exception('POST request failed: ' . $error['message']);
        }

        //If everything went OK, return the response.
        return $result;
    }

    public function process()
    {
        if (!empty($_POST['email']) && !empty($_POST['full_name'])) {
            $this->input = $this->get_safe_input();

            $client_id = 'ju16a6m81mhid5ue1z3v2g0uh';

            $result = self::post('https://api.supermetrics.com/assignment/register', [
                'client_id' => $client_id,
                'email' => $this->input['email'],
                'name' => $this->input['full_name'],
            ]);

            $result = json_decode($result);
            
            if($result) {
                setcookie("tokenUser", $this->input['full_name'], time()+3600);
                setcookie("tokenEmail", $this->input['email'], time()+3600);
                setcookie("tokenClientId", $client_id, time()+3600);
                setcookie("slToken", $result->data->sl_token, time()+3600);
            }

            header('Location: /register');

            exit();
        }
    }

    public function getInput()
    {
        return $this->input;
    }

}
