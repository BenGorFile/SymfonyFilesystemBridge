<?php

/*
 * This file is part of the BenGorFile package.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 * (c) Gorka Laucirica <gorka.lauzirika@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace BenGorFile\SymfonyFilesystemBridge\Infrastructure\Domain\Model;

use BenGorFile\File\Domain\Model\FileName;
use BenGorFile\File\Domain\Model\Filesystem;
use Symfony\Component\Filesystem\Filesystem as Symfony;

/**
 * Symfony implementation of filesystem domain class.
 *
 * @author Beñat Espiña <benatespina@gmail.com>
 * @author Gorka Laucirica <gorka.lauzirika@gmail.com>
 */
class SymfonyFilesystem implements Filesystem
{
    /**
     * The Symfony filesystem.
     *
     * @var Symfony
     */
    private $filesystem;

    /**
     * The path.
     *
     * @var string
     */
    private $path;

    /**
     * Constructor.
     *
     * @param string  $aPath       The path
     * @param Symfony $aFilesystem The Symfony filesystem
     */
    public function __construct($aPath, Symfony $aFilesystem)
    {
        $this->filesystem = $aFilesystem;
        $this->path = rtrim($aPath, '/') . '/';
    }

    /**
     * {@inheritdoc}
     */
    public function delete(FileName $aName)
    {
        $this->filesystem->remove($this->path . $aName->filename());
    }

    /**
     * {@inheritdoc}
     */
    public function has(FileName $aName)
    {
        return $this->filesystem->exists($this->path . $aName->filename());
    }

    /**
     * {@inheritdoc}
     */
    public function overwrite(FileName $aName, $aContent)
    {
        $this->filesystem->dumpFile($this->path . $aName->filename(), $aContent);
    }

    /**
     * {@inheritdoc}
     */
    public function read(FileName $aName)
    {
        return file_get_contents($this->path . $aName->filename());
    }

    /**
     * {@inheritdoc}
     */
    public function rename(FileName $anOldName, FileName $aNewName)
    {
        $this->filesystem->rename($this->path . $anOldName->filename(), $this->path . $aNewName->filename());
    }

    /**
     * {@inheritdoc}
     */
    public function write(FileName $aName, $aContent)
    {
        if ($this->has($aName)) {
            throw new \Exception(
                sprintf(
                    'Already exists a file with the same name that %s. ' .
                    'Maybe, you want to use "overwrite" method instead.',
                    $aName->filename()
                )
            );
        }
        $this->filesystem->dumpFile($this->path . $aName->filename(), $aContent);
    }
}
