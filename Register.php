<?php
class Register extends Query
{
    public function registryInfo()
    {
        $this->method = 'registryInfo';
        $this->params = array(
            'auth' => $this->auth,
            'id' => 'RU'
        );
        $this->sendQuery();
    }
}
?>