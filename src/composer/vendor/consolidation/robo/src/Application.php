<?php
namespace Robo;

use Symfony\Component\Console\Application as SymfonyApplication;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;

class Application extends SymfonyApplication
{
    /**
     * @param string $name
     * @param string $version
     */
    public function __construct($name, $version)
    {
        parent::__construct($name, $version);

        $this->getDefinition()
            ->addOption(
                new InputOption('--simulate', null, InputOption::VALUE_NONE, 'Run in simulated mode (show what would have happened).')
            );
        $this->getDefinition()
            ->addOption(
                new InputOption('--progress-delay', null, InputOption::VALUE_REQUIRED, 'Number of seconds before progress bar is displayed in long-running task collections. Default: 2s.', Config::DEFAULT_PROGRESS_DELAY)
            );
    }

    /**
     * @param string $roboFile
     * @param string $roboClass
     */
    public function addInitRoboFileCommand($roboFile, $roboClass)
    {
        $createRoboFile = new Command('init');
        $createRoboFile->setDescription("Intitalizes basic RoboFile in current dir");
        $createRoboFile->setCode(function () use ($roboClass, $roboFile) {
            $output = Robo::output();
            $output->writeln("<comment>  ~~~ Welcome to Robo! ~~~~ </comment>");
            $output->writeln("<comment>  ". basename($roboFile) ." will be created in the current directory </comment>");
            file_put_contents(
                $roboFile,
                '<?php'
                . "\n/**"
                . "\n * This is project's console commands configuration for Robo task runner."
                . "\n *"
                . "\n * @see http://robo.li/"
                . "\n */"
                . "\nclass " . $roboClass . " extends \\Robo\\Tasks\n{\n    // define public methods as commands\n}"
            );
            $output->writeln("<comment>  Edit this file to add your commands! </comment>");
        });
        $this->add($createRoboFile);
    }
}
