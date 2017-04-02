<?php
// src/Command/PomoListCommand.php
namespace Pomotodo\Command;

use Pomotodo\PomotodoQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;

/**
 * Class PomoListCommand
 *
 * @package Pomotodo\Command
 */
class PomoListCommand extends Command
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
        $this->setName('pomo:list')
            ->setDescription('List your pomodoros.')
            ->setHelp(
                <<<EOF
The <info>%command.name%</info> will return a list of your pomodoros.
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
        $pomos = $this->_queryManager->get('pomos');
        $results = collect($pomos)->map(
            function ($pomo) {
                $pomo['started_at'] = date(
                    'Y-m-d H:i:s', strtotime($pomo['started_at'])
                );
                $pomo['ended_at'] = date(
                    'Y-m-d H:i:s', strtotime($pomo['ended_at'])
                );
                return collect($pomo)
                    ->only(['started_at', 'ended_at', 'description'])
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
