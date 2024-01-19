<?php

declare(strict_types=1);

namespace KPhoen\RulerZBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Clear the cache.
 *
 * @author Kévin Gomez <contact@kevingomez.fr>
 */
class CacheClearCommand extends Command
{
    protected static $defaultName = 'rulerz:cache:clear';

    protected $filesystem;

    protected $parameterBag;

    public function __construct(Filesystem $filesystem, ParameterBagInterface  $parameterBag)
    {
        $this->filesystem = $filesystem;
        $this->parameterBag = $parameterBag;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this->setDescription("Clear RulerZ's cache");
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $cacheDir = $this->parameterBag->get('rulerz.cache_directory');

        if (!is_writable($cacheDir)) {
            throw new \RuntimeException(sprintf('Unable to write in the "%s" directory', $cacheDir));
        }

        if ($this->filesystem->exists($cacheDir)) {
            $this->filesystem->remove($cacheDir);
            $this->filesystem->mkdir($cacheDir);
        }

        return Command::SUCCESS;
    }
}
