<?php
  namespace MyApp;
  use Ratchet\MessageComponentInterface;
  use Ratchet\ConnectionInterface;

  class Chat implements MessageComponentInterface {

    protected $clients;

    public function __construct() {
      $this->clients = new \SplObjectStorage;
    }
      public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);

        echo "someone connected<br>";
        // ({$conn->resoureceId})
      }

      public function onMessage(ConnectionInterface $from, $msg) {

        foreach ($this->clients as $client) {
          if ($from !== $client) {
            $client->send($msg);
          }
        }
      }

      public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "they gone";
      }

      public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "error: {$e->getMessage()}<br>";
        $conn->close();
      }
  }

?>
