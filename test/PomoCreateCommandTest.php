<?php
/*
 * This file is part of the Pomotodo {pomotodo-cli} Package.
 * For the full copyright and license information, 
 * please view the LICENSE file that was distributed 
 * with this source code.
 */

namespace Pomotodo\Test;

use Pomotodo\Command\PomoCreateCommand;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Pomotodo\PomotodoQuery;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;


/**
 * PomoCreateCommandTest
 */
class PomoCreateCommandTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Testing Todo List functionality
     *
     * @return void
     */
    public function testCreate()
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

        $application = new Application();
        $application->add(new PomoCreateCommand($pomotodoQuery));
        $command = $application->find('pomo:create');
        $tester = new CommandTester($command);
        $tester->execute(
            [
                'command' => $command->getName(),
                'description'=>'Test Description',
                '--notimer' => true
                ]
        );
        $result = "Your pomodoro 'Hello Pomotodo!' has completed.\n";
        self::assertEquals($result, $tester->getDisplay());
    }
}
