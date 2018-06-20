<?php
/**
 * Created by PhpStorm.
 * User: fma
 * Date: 19/06/18
 * Time: 16:41
 */

namespace Maps_red\ManagerBundle\Maker;

use Symfony\Bundle\MakerBundle\ConsoleStyle;
use Symfony\Bundle\MakerBundle\DependencyBuilder;
use Symfony\Bundle\MakerBundle\FileManager;
use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Bundle\MakerBundle\InputConfiguration;
use Symfony\Bundle\MakerBundle\Validator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Bundle\MakerBundle\Maker\AbstractMaker;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Finder\SplFileInfo;

class MakeManager extends AbstractMaker
{
    /**
     * @var FileManager $fileManager
     */
    private $fileManager;

    public function __construct(FileManager $fileManager)
    {
        $this->fileManager = $fileManager;
    }

    public static function getCommandName(): string
    {
        return 'make:manager';
    }

    public function configureCommand(Command $command, InputConfiguration $inputConf)
    {
        $command
            ->setDescription('Creates a new manager class')
            ->addArgument('entity-class', InputArgument::OPTIONAL, "What entity do you want to use")
            ->setHelp("TODO");
    }

    public function interact(InputInterface $input, ConsoleStyle $io, Command $command)
    {
        if ($input->getArgument('entity-class')) {
            return;
        }

        $argument = $command->getDefinition()->getArgument('entity-class');
        $question = $this->createEntityClassQuestion($argument->getDescription());
        $value = $io->askQuestion($question);

        $input->setArgument('entity-class', $value);
    }

    public function generate(InputInterface $input, ConsoleStyle $io, Generator $generator)
    {
        $entityClass = $input->getArgument('entity-class');

        $managerClassNameDetails = $generator->createClassNameDetails($entityClass, 'Manager\\', 'Manager');

        $generator->generateClass(
            $managerClassNameDetails->getFullName(),
            __DIR__ . "/../Resources/skeleton/Manager.tpl.php",
            [
                'entity_full_class_name' => 'App\\Entity\\'.$entityClass,
                'entity_class_name' => $entityClass,
                'entity_full_repository_name' => 'App\\Repository\\'.$entityClass,
            ]
        );

        $generator->writeChanges();

        $this->writeSuccessMessage($io);

        $io->text('GOOD TO GO !');
    }

    /**
     * Configure any library dependencies that your maker requires.
     *
     * @param DependencyBuilder $dependencies
     */
    public function configureDependencies(DependencyBuilder $dependencies)
    {
    }

    /**
     * @param string $questionText
     * @return Question
     */
    private function createEntityClassQuestion(string $questionText): Question
    {
        $entityFinder = $this->fileManager->createFinder('src/Entity/')
            // remove if/when we allow entities in subdirectories
            ->depth('<1')
            ->name('*.php');

        $classes = [];
        /** @var SplFileInfo $item */
        foreach ($entityFinder as $item) {
            if (!$item->getRelativePathname()) {
                continue;
            }

            $classes[] = str_replace('/', '\\', str_replace('.php', '', $item->getRelativePathname()));
        }

        $question = new Question($questionText);
        $question->setValidator([Validator::class, 'notBlank']);
        $question->setAutocompleterValues($classes);

        return $question;
    }

}