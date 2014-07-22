<?php
namespace Aoe\RefactorTool\Console\Command;

use Aoe\RefactorTool\Autoloading\File;
use Aoe\RefactorTool\Autoloading\Line;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;

/**
 * @package Aoe\RefactorTool\Console\Command
 */
class AutoloadingCommand extends Command
{
    /**
     * configures the command
     */
    protected function configure()
    {
        $this->setName('autoloading')
            ->setDescription('Remove require_once statements from files.');

        $this->addArgument(
            'path',
            InputArgument::REQUIRED,
            'The root path of your project directory'
        );

        $this->addOption(
            'restrict',
            'r',
            InputOption::VALUE_OPTIONAL,
            'If set, only the require statements matching against this pattern will be removed'
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln($input->getArgument('path'));

        $finder = new Finder();
        $finder->ignoreDotFiles(true);
        $finder->ignoreVCS(true);
        $finder->files()->name('*.php');
        $finder->files()->in($input->getArgument('path'));

        foreach ($finder as $result) {
            /** @var SplFileInfo $result */
            $output->writeln('<info>start processing file ' . $result->getRealPath() . '</info>');

            $file = new File($result);
            /** @var File $file */

            if (false === $file->isPhpFile()) {
                $output->writeln('<comment>skipped. no "php" file extension.</comment>');
                $output->writeln('<info>done.</info>');
                $output->writeln('');
                continue;
            }

            foreach ($file->getLinesBetweenOpeningPHPAndClassDefinition() as $line) {
                /** @var Line $line */
                if ($line->isRequire()) {
                    if ($input->hasOption('restrict') && false === $line->matches($input->getOption('restrict'))) {
                        $output->writeln(
                            '<comment>skipped require on line ' . $line->getNumber() .
                            ' because it does not match against restriction</comment>'
                        );
                        continue;
                    }
                    $output->writeln('<error>deleting require on line ' . $line->getNumber() . '</error>');
                    $file->deleteLine($line);
                }
            }

            if ($file->isModified()) {
                $file->write();
            }

            $output->writeln('<info>done.</info>');
            $output->writeln('');
        }
    }
}
