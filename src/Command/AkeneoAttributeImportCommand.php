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
use App\Repository\AttributesRepository;

#[AsCommand(
    name: 'akeneo:attribute:import',
    description: 'Add a short description for your command',
)]
class AkeneoAttributeImportCommand extends Command
{
    const attributeFile = "./public/data/attributes.xlsx";

    public function __construct(

        private SpreadSheetReader $spreadSheetReader,
        private AttributesRepository $attributesRepository
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

        $rowData = $this->spreadSheetReader->loadNormalData(self::attributeFile);
        foreach($rowData as $attribute)
        {
            $this->attributesRepository->saveAttributeData($attribute);
        }

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
