<?php
class Query
{
    protected $jsonrpc = '2.0';
    protected $method;
    protected $params;
    protected $id = 1;
    protected $url = 'https://vrdemo.virtreg.ru/vr-api';
    protected $post_data;
    protected $answer_json;
    protected $auth = array(
        'login' => 'demo', 
        'password' => 'demo'
    );

    protected function sendQuery()
    {
        $this->makeData();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/72.0.3626.121 Safari/537.36');
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($this->post_data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
            'Content-Type: application/json'
        ));
        $this->answer_json = curl_exec($ch);
        curl_close($ch);
    }
    private function makeData()
    {
        $this->post_data = array(
            'jsonrpc' => $this->jsonrpc,
            'method' => $this->method,
            'params' => $this->params,
            'id' => $this->id
        );
    }
    public function getAnswer()
    {
        return $this->answer_json;
    }
}
?>