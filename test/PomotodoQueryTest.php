<?php
/*
 * This file is part of the Pomotodo {pomotodo-cli} Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */

namespace Pomotodo\Test;

use Pomotodo\Command\PomotodoCommand;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Pomotodo\PomotodoQuery;


/**
 * PomotodoCommandTest
 */
class PomotodoCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing Todo List functionality
     *
     * @return void
     */
    public function testListTodos()
    {
        $json = '[
  {
    "uuid": "ac753187-2f22-4b5c-b716-f1fcecfb4410",
    "created_at": "2016-08-06T06:48:52.000Z",
    "updated_at": "2016-08-06T06:51:12.000Z",
    "description": "Catch some little Monsters",
    "notice": null,
    "pin": false,
    "completed": false,
    "completed_at": null,
    "repeat_type": "none",
    "remind_time": null,
    "estimated_pomo_count": null,
    "costed_pomo_count": 0,
    "sub_todos": [
      "81921b2e-8b54-46cf-bb47-0d3c3c7e8302",
      "ff59811e-4c53-404f-a842-9590b632616f"
    ]
  }
]';
        $mock = new MockHandler(
            [
                new Response(200, ['Content-Type' => 'application/json'], $json),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $pomotodoQuery = new PomotodoQuery($client, 'TEST_KEY');
        self::assertEquals($pomotodoQuery->get('todos'), json_decode($json, true));
    }
    /**
     * Testing Pomo List functionality
     *
     * @return void
     */
    public function testListPomos()
    {
        $json = '[
  {
    "uuid": "deadcafe-dead-cafe-dead-cafedeadcafe",
    "created_at": "2016-08-06T10:00:00.000Z",
    "updated_at": "2016-08-06T10:00:00.000Z",
    "description": "Hello Pomotodo!",
    "started_at": "2016-08-06T10:00:00.000Z",
    "ended_at": "2016-08-06T10:00:00.000Z",
    "local_started_at": "2016-08-06T18:00:00.000Z",
    "local_ended_at": "2016-08-06T18:00:00.000Z",
    "length": 1500,
    "abandoned": false,
    "manual": false
  }
]';
        $mock = new MockHandler(
            [
                new Response(200, ['Content-Type' => 'application/json'], $json),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $pomotodoQuery = new PomotodoQuery($client, 'TEST_KEY');
        self::assertEquals($pomotodoQuery->get('pomos'), json_decode($json, true));
    }

    /**
     * Testing Todo Create functionality
     *
     * @return void
     */
    public function testCreateTodo()
    {
        $json = '{
  "uuid": "deadcafe-dead-cafe-dead-cafedeadcafe",
  "created_at": "2016-08-06T10:00:00.000Z",
  "updated_at": "2016-08-06T10:00:00.000Z",
  "description": "Hello Pomotodo!",
  "started_at": "2016-08-06T10:00:00.000Z",
  "ended_at": "2016-08-06T10:00:00.000Z",
  "local_started_at": "2016-08-06T18:00:00.000Z",
  "local_ended_at": "2016-08-06T18:00:00.000Z",
  "length": 1500,
  "abandoned": false,
  "manual": false
}';
        $mock = new MockHandler(
            [
                new Response(200, ['Content-Type' => 'application/json'], $json),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $pomotodoQuery = new PomotodoQuery($client, 'TEST_KEY');
        self::assertEquals(
            $pomotodoQuery->create('todos', ['description'=>'Test Description']),
            json_decode($json, true)
        );
    }

    /**
     * Testing Pomo Create functionality
     *
     * @return void
     */
    public function testCreatePomo()
    {
        $json = '{
  "uuid": "deadcafe-dead-cafe-dead-cafedeadcafe",
  "created_at": "2016-08-06T10:00:00.000Z",
  "updated_at": "2016-08-06T10:00:00.000Z",
  "description": "Hello Pomotodo!",
  "started_at": "2016-08-06T10:00:00.000Z",
  "ended_at": "2016-08-06T10:00:00.000Z",
  "local_started_at": "2016-08-06T18:00:00.000Z",
  "local_ended_at": "2016-08-06T18:00:00.000Z",
  "length": 1500,
  "abandoned": false,
  "manual": false
}';
        $mock = new MockHandler(
            [
                new Response(200, ['Content-Type' => 'application/json'], $json),
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $pomotodoQuery = new PomotodoQuery($client, 'TEST_KEY');
        self::assertEquals(
            $pomotodoQuery->create('pomos', ['description'=>'Test Description']),
            json_decode($json, true)
        );
    }
}
