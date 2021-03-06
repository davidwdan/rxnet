<?php
namespace Rxnet\Redis;


use Clue\Redis\Protocol\Parser\ResponseParser;
use Rx\Subject\Subject;
use Rxnet\Event\Event;
use Rxnet\NotifyObserverTrait;
use Underscore\Types\Arrays;

class RedisRequest extends Subject
{
    use NotifyObserverTrait;
    /**
     * @var ResponseParser
     */
    protected $parser;

    /**
     * RedisRequest constructor.
     */
    public function __construct()
    {
        $this->parser = new ResponseParser();
    }

    public function onNext($event) {
        $data = $this->parser->pushIncoming($event->data);

        $this->notifyNext(new Event("/redis/response", Arrays::first($data)->getValueNative()));
        $this->notifyCompleted();
    }
}