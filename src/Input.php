<?php

// @TODO: namespaces - we should probably need them all over here and there if we extend the application
// @TODO: getters, setters in all classes.
class Input
{
    /** @var SplFileObject $file */
    private $file;

    public function __construct(string $pathToInputFile)
    {
        $this->setFile($pathToInputFile);
    }

    public function openTransactionFile()
    {
        $this->getFile()->rewind();
    }

    public function getNextTransactionLine(): ?InputItem
    {
        $this->getFile()->next();
        if ($this->getFile()->eof() === true) {

            return null;
        }

        // @TODO: Create the new item via factory or something similar.
        return new InputItem($this->getFile()->current());
    }

    private function setFile(string $pathToInputFile): void
    {
        if (!is_file($pathToInputFile)) {
            throw new \RuntimeException(sprintf('Input file is not found: %s', $pathToInputFile));
        }
        $this->file = new SplFileObject($pathToInputFile);
    }

    private function getFile(): SplFileObject
    {
        return $this->file;
    }
}
