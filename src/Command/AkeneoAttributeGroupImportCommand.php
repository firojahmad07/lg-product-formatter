<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Services\SpreadSheetReader;
use App\Repository\AttributeGroupsRepository;

#[AsCommand(
    name: 'akeneo:attribute-group:import',
    description: 'Add a short description for your command',
)]
class AkeneoAttributeGroupImportCommand extends Command
{
    const attributeGroupFile = "./public/data/attribute_group.xlsx";

    public function __construct(
        private SpreadSheetReader $spreadSheetReader,
        private AttributeGroupsRepository $attributeGroupsRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        $rawData = $this->spreadSheetReader->loadNormalData(self::attributeGroupFile);

        foreach($rawData as $attributeGroup)
        {
            $this->attributeGroupsRepository->saveAttributeGroupData($attributeGroup);
        }


        // $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
