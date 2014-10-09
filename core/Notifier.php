<?php

require_once 'Fetcher.php';

class Notifier
{
    const NOTIFICATION_URL = 'https://smsapi.free-mobile.fr/sendmsg';
    const MESSAGE = '%d %s';

    /**
     * @var Fetcher
     */
    private $fetcher;

    /**
     * @var int
     */
    private $user;

    /**
     * @var string
     */
    private $password;

    /**
     * @var array
     */
    private $messages;

    /**
     * @param int $user
     * @param string $password
     */
    public function __construct($user, $password)
    {
        $this->user     = $user;
        $this->password = $password;
        $this->messages = array();
    }

    /**
     * @param string $link
     * @param int $code
     */
    public function createMessage($link, $code)
    {
        $this->messages[] = sprintf(self::MESSAGE, $code, $link);
    }

    /**
     * @throws Exception
     */
    public function notify()
    {
        if(count($this->messages) === 0) {
            return;
        }

        echo 'Sending notification'.PHP_EOL;

        if(!$this->user || !$this->password) {
            throw new Exception('User and password are required');
        }

        $total = count($this->messages);

        $code = $this->fetcher->getCode(sprintf(
            '%s?%s',
            self::NOTIFICATION_URL,
            http_build_query(array(
                'user'  => $this->user,
                'pass'  => $this->password,
                'msg'   => sprintf(
                    '%d error%s%s%s',
                    $total,
                    ($total > 1) ? 's' : '',
                    PHP_EOL,
                    implode(PHP_EOL, $this->messages)
                ),
            ))
        ));

        $this->logResult($code);
    }

    /**
     * @param int $code
     */
    private function logResult($code)
    {
        switch($code) {
            case 200:
                echo 'Notification sent'.PHP_EOL;
                break;

            case 400:
                echo 'Invalid notification request'.PHP_EOL;
                break;

            case 402:
                echo 'Too many notifications sent'.PHP_EOL;
                break;

            case 403:
                echo 'Invalid credentials or disabled service'.PHP_EOL;
                break;

            case 500:
                echo 'Internal server error'.PHP_EOL;
                break;

            default:
                echo 'Code '.$code.PHP_EOL;
                break;
        }
    }

    /**
     * @return Fetcher
     */
    public function getFetcher()
    {
        return $this->fetcher;
    }

    /**
     * @param Fetcher $fetcher
     * @return $this
     */
    public function setFetcher($fetcher)
    {
        $this->fetcher = $fetcher;

        return $this;
    }

    /**
     * @return int
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param int $user
     * @return $this
     */
    public function setUser($user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return array
     */
    public function getMessages()
    {
        return $this->messages;
    }

    /**
     * @param array $messages
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }
}
