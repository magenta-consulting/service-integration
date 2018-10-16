<?php

namespace App\Command;

use App\Entity\CBook\CBookOrganisation;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IntegrateCBook2WellnessCommand extends Command
{
    protected static $defaultName = 'magenta:integration:cbook2wellness';

    private $registry;

    public function __construct(RegistryInterface $registry, $name = null)
    {
        parent::__construct($name);
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $cbookOrgRepo = $this->registry->getRepository(CBookOrganisation::class);

        $cbookOrgs = $cbookOrgRepo->findAll();

        /** @var CBookOrganisation $cborg */
        foreach ($cbookOrgs as $cborg) {
            $io->note($cborg->getName());
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
