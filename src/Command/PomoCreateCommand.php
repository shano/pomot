<?php
// src/Command/PomoCreateCommand.php
namespace Pomotodo\Command;

use Pomotodo\PomotodoQuery;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Class PomoCreateCommand
 *
 * @package Pomotodo\Command
 */
class PomoCreateCommand extends Command
{
    private $_queryManager;
    /**
     * PomoCreateCommand constructor.
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
        $this->setName('pomo:create')
            ->setDescription('Create a pomodoro with the supplied description.')
            ->addArgument(
                'description',
                InputArgument::REQUIRED,
                'Description of pomodoro'
            )->addOption(
                'notimer',
                'nt',
                null,
                InputOption::VALUE_NONE
            )
            ->setHelp(
                <<<EOF
The <info>%command.name%</info> will create a pomodoro with supplied description.
  <info>php app %command.full_name% 'My pomodoro description'</info>
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
        $no_timer = $input->getOption('notimer');
        if (!$no_timer) {
            $output->writeln(
                "Your pomodoro '{$description}' will run for 25 minutes."
            );
            $progress = new ProgressBar($output, 25);
            $progress->setFormat('%bar%');
            $progress->start();
            $i = 0;
            while ($i++ < 25) {
                sleep(60);
                $progress->advance();
            }
        }
        $pomo = $this->_queryManager->create(
            'pomos',
            [
                'description'=>$description,
                'started_at'=>date(DATE_ATOM, strtotime('-25 minutes')),
                'length'=>1500
            ]
        );
        $output->writeln("Your pomodoro '{$pomo['description']}' has completed.");
        if (!$no_timer) {
            $output->writeln("Please take a 5 minute break");
            $i = 0;
            while ($i++ < 5) {
                sleep(60);
                $progress->advance();
            }
        }
    }
}
