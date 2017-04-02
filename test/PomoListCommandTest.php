<?php
/*
 * This file is part of the Pomotodo {pomotodo-cli} Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */

namespace Pomotodo\Test;

use Pomotodo\Command\PomoListCommand;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Pomotodo\PomotodoQuery;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;


/**
 * PomoListCommandTest
 *
 */
class PomoListCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing Todo List functionality
     *
     * @return void
     */
    public function testList()
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
                new Response(200, ['Content-Type' => 'application/json'], $json)
            ]
        );
        $handler = HandlerStack::create($mock);
        $client = new Client(['handler' => $handler]);
        $pomotodoQuery = new PomotodoQuery($client, 'TEST_KEY');
        $application = new Application();
        $application->add(new PomoListCommand($pomotodoQuery));
        $command = $application->find('pomo:list');
        $tester = new CommandTester($command);
        $tester->execute(['command' => $command->getName()]);
        $result = '+-----------------+---------------------+---------------------+
| Date Created    | Description         | Estimate            |
+-----------------+---------------------+---------------------+
| Hello Pomotodo! | 2016-08-06 10:00:00 | 2016-08-06 10:00:00 |
+-----------------+---------------------+---------------------+
';
        self::assertEquals($result, $tester->getDisplay());
    }
}
