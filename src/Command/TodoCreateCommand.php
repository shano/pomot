<?php
// src/Command/TodoCreateCommand.php
namespace Pomotodo\Command;

use Pomotodo\PomotodoQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TodoCreateCommand
 *
 * @package Pomotodo\Command
 */
class TodoCreateCommand extends Command
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
        $this->setName('todo:create')
            ->setDescription('Create a todo.')
            ->addArgument(
                'description',
                InputArgument::REQUIRED,
                'Description of todo'
            )
            ->setHelp(
                <<<EOF
The <info>%command.name%</info> will create a todo based on the supplied description.
  <info>php app %command.full_name% 'My task description'</info>
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
        $description = $input->getArgument('description');
        $todos = $this->_queryManager->create(
            'todos',
            ['description'=>$description]
        );

        $output->writeln("Your todo '{$todos['description']}' was created.");

    }
}
