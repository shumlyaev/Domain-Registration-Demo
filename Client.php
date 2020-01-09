<?php
class Client extends Query
{
    private $email;
    private $clientId;
    public function clientId($email)
    {
        $this->email = $email;
        $this->method = 'clientEnum';
        $this->params = array(
            'auth' => $this->auth,
            'query' => array(
                'filter' => array(
                    ['emails','=',$this->email]
                )
            )
        );
        $this->sendQuery();
        $answer = json_decode($this->getAnswer(), true);
        if ($answer['result']['clients'] == NULL)
        {
            return NULL;
        } else {
            $this->clientId = $answer['result']['clients'][0]['id'];
            return $this->clientId;
        }
    }
}
?>