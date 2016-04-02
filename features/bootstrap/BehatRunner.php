<?php

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\PhpExecutableFinder;
use Symfony\Component\Process\Process;

final class BehatRunner
{
    /**
     * @var string
     */
    private $workingDir;

    /**
     * @var string
     */
    private $phpBin;

    /**
     * @var Process
     */
    private $process;

    /**
     * @var Filesystem
     */
    private $filesystem;

    /**
     * @param string $workingDir
     */
    public function __construct($workingDir)
    {
        $this->workingDir = $workingDir;
        $this->phpBin = $this->findPhpBinary();
        $this->process = new Process(null);
        $this->filesystem = new Filesystem();

        $this->filesystem->mkdir($this->workingDir, 0777);
    }
    
    public function removeWorkspace()
    {
        $this->filesystem->remove($this->workingDir);
    }

    /**
     * @param string $filePath
     * @param string $content
     */
    public function addFile($filePath, $content)
    {
        $this->filesystem->dumpFile($this->workingDir.'/'.$filePath, $content);
    }

    /**
     * @param string $path
     */
    public function addDirectory($path)
    {
        $this->filesystem->mirror($path, $this->workingDir);
    }

    public function run()
    {
        $this->process->setWorkingDirectory($this->workingDir);
        $this->process->setCommandLine(
            sprintf(
                '%s %s %s',
                $this->phpBin,
                escapeshellarg(BEHAT_BIN_PATH),
                strtr('--format-settings=\'{"timer": false}\'', ['\'' => '"', '"' => '\"'])
            )
        );
        $this->process->start();
        $this->process->wait();
    }

    /**
     * @return int|null
     */
    public function getExitCode()
    {
        return $this->process->getExitCode();
    }

    /**
     * @return string
     */
    public function getFullOutput()
    {
        return $this->process->getErrorOutput().$this->process->getOutput();
    }

    /**
     * @return string
     *
     * @throws \RuntimeException
     */
    private function findPhpBinary()
    {
        $phpFinder = new PhpExecutableFinder();

        if (false === $php = $phpFinder->find()) {
            throw new \RuntimeException('Unable to find the PHP executable.');
        }

        return $php;
    }
}
