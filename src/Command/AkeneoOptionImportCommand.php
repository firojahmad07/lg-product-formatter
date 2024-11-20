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
use App\Repository\AttributeOptionsRepository;

#[AsCommand(
    name: 'akeneo:option:import',
    description: 'Add a short description for your command',
)]
class AkeneoOptionImportCommand extends Command
{
    const attributeFile = "./public/data/attribute_option.xlsx";

    public function __construct(
        private SpreadSheetReader $spreadSheetReader,
        private AttributeOptionsRepository $attributeOptionsRepository
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
        $rowData = $this->spreadSheetReader->loadNormalData(self::attributeFile);
        foreach($rowData as $attributeOption) {
            $this->attributeOptionsRepository->saveAttributeOptionData($attributeOption);
        }
        $io->success('Attribute Option imported success.');

        return Command::SUCCESS;
    }
}
