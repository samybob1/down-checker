<?php

require_once 'Fetcher.php';
require_once 'Notifier.php';

class Checker
{
    /**
     * @var Fetcher
     */
    private $fetcher;

    /**
     * @var Notifier
     */
    private $notifier;


    /**
     * @param Notifier $notifier
     * @param Fetcher $fetcher
     */
    public function __construct($notifier, $fetcher = null)
    {
        $this->notifier = $notifier;
        $this->fetcher  = $fetcher ?: new Fetcher();

        $this->notifier->setFetcher($this->fetcher);
    }

    /**
     * @param array $links
     */
    public function check($links = array())
    {
        echo date('d/m/Y H:i:s').PHP_EOL;

        foreach($links as $link) {
            $code = $this->fetcher->getCode($link);
            echo sprintf('%d %s%s', $code, $link, PHP_EOL);

            if($code !== 200) {
                $this->notifier->createMessage($link, $code);
            }
        }

        $this->notifier->notify();
    }

    /**
     * @return Notifier
     */
    public function getNotifier()
    {
        return $this->notifier;
    }

    /**
     * @param Notifier $notifier
     * @return $this
     */
    public function setNotifier($notifier)
    {
        $this->notifier = $notifier;

        return $this;
    }
}
