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
use App\Services\ProductFormatter;


#[AsCommand(
    name: 'akeneo:product-format:data',
    description: 'Add a short description for your command',
)]
class AkeneoProductFormatDataCommand extends Command
{
    CONST PRODUCTFILE = './public/product/Portable Monitor.xlsx';
    public function __construct(
        private SpreadSheetReader $spreadSheetReader,
        private ProductFormatter $productFormatter
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

        $productData = $this->spreadSheetReader->loadProductData(self::PRODUCTFILE);
        $products = [];
        $iterations = 0;
        foreach($productData as $product) {
            $formattedArray = $this->productFormatter->formatProductData($product);
            if (0 == $iterations) {
                $products[] = array_keys($formattedArray);
                $products[] = array_values($formattedArray);
            } else {
                $products[] = array_values($formattedArray);
            }
            $iterations = $iterations + 1;
        }
        
        $this->spreadSheetReader->saveData($products);
        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
    
}
