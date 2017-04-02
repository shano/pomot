<?php
// src/Command/TodoListCommand.php
namespace Pomotodo\Command;

use Pomotodo\PomotodoQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

/**
 * Class TodoListCommand
 *
 * @package Pomotodo\Command
 */
class TodoListCommand extends Command
{
    private $_queryManager;

    /**
     * PomoListCommand constructor.
     *
     * @param PomotodoQuery|null $queryManager Use to invoke commands
     */
    public function __construct(PomotodoQuery $queryManager = null)
    {
        $this->_queryManager = $queryManager;
        parent::__construct();
    }

    /**
     * Specify command specifics
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('todo:list')
            ->setDescription('List your todos.')
            ->setHelp(
                <<<EOF
The <info>%command.name%</info> will return a list of our todos.
  <info>php app %command.full_name%</info>
EOF
            );
    }

    /**
     * Handle command
     *
     * @param InputInterface  $input  Symfony Input Handler
     * @param OutputInterface $output Symfony Output Handler
     *
     * @return void
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $todos = $this->_queryManager->get('todos');
        $results = collect($todos)->map(
            function ($todo) {
                $todo['created_at'] = date(
                    'Y-m-d H:i:s', strtotime($todo['created_at'])
                );
                $todo['estimated_pomo_count']
                    = $todo['estimated_pomo_count'] ?? 'N/A';
                return collect($todo)
                    ->only(['created_at', 'description', 'estimated_pomo_count'])
                    ->all();
            }
        );

        $table = new Table($output);
        $table
            ->setHeaders(['Date Created', 'Description', 'Estimate'])
            ->setRows($results->toArray());
        $table->render();
    }
}
