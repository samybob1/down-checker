<?php

class Fetcher
{
    /**
     * @var resource
     */
    protected $ch;

    public function __construct()
    {
        $this->ch = curl_init();

        curl_setopt_array($this->ch, array(
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_HEADER          => true,
            CURLOPT_FOLLOWLOCATION  => true,
            CURLOPT_ENCODING        => '',
            CURLOPT_AUTOREFERER     => true,
            CURLOPT_CONNECTTIMEOUT  => 120,
            CURLOPT_TIMEOUT         => 120,
            CURLOPT_MAXREDIRS       => 10,
            CURLOPT_SSL_VERIFYPEER  => true,
            CURLOPT_SSL_VERIFYHOST  => 2,
            CURLOPT_CAINFO          => __DIR__.'/../ssl/free-mobile.crt',
        ));
    }

    /**
     * @param string $link
     * @return int
     */
    public function getCode($link)
    {
        curl_setopt($this->ch, CURLOPT_URL, $link);

        if(curl_exec($this->ch) === false) {
            echo curl_error($this->ch).PHP_EOL;
        }

        return (int) curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
    }

    public function __destruct()
    {
        curl_close($this->ch);
    }
}
