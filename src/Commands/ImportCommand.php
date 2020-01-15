<?php
declare(strict_types=1);

namespace App\Commands;

use App\Entity\Log;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ImportCommand extends Command
{
    private $linesPerBlock = 1000;

    protected static $defaultName = 'app:import';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this
            ->addOption(
                'filePath',
                null,
                InputOption::VALUE_REQUIRED,
                'Path to imported csv file',
                null
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $filePath = $input->getOption('filePath');
        if ($filePath === null) {
            throw new \InvalidArgumentException("filePath option not set");
        };

        if (!file_exists($filePath)) {
            throw new \InvalidArgumentException($filePath . " is not a valid existing file path");
        }

        $file = fopen($filePath, 'r');
        if ($file === null) {
            throw new \InvalidArgumentException("Error opening file " . $filePath);
        }

        set_time_limit (90);

        $qb = $this->entityManager->createQueryBuilder();

        $qb->select('MAX(l.rec_time) AS max_ts, MIN(l.rec_time) AS min_ts')
           ->from(Log::class, 'l');

        $query    = $qb->getQuery();
        $dbLimits = $query->execute();
        $minTS    = $dbLimits[0]['min_ts'];
        $maxTS    = $dbLimits[0]['max_ts'];

        $linesRead = 0;
        $date = new \DateTime();

        while (!feof($file)) {
            $block = [];
            while (count($block) < $this->linesPerBlock) {
                $s = fgets($file);
                if ($s === false) {
                    break;
                }
                $block[] = $s;
            }

            $linesRead += count($block);

            $firstLine = reset($block);
            $lastLine  = end($block);

            $minFileTS = $this->parseCSVString($firstLine)[0];
            $maxFileTS = $this->parseCSVString($lastLine)[0];

            if (!($minFileTS >= $minTS && $maxFileTS <= $maxTS))
            {
                foreach ($block as $line) {
                    $data = $this->parseCSVString($line);

                    $date->setTimestamp((int)$data[0]);

                    $log = (new Log())
                        ->setRecTime($date)
                        ->setTimeRFC3339($data[1])
                        ->setFilesize((int)$data[2])
                        ->setPath($data[3])
                        ->setUseragent($data[4])
                        ->setResponseHttpStatus((int)$data[5])
                        ->setRequestHttpMethod($data[6])
                        ->setContentType($data[7]);

                    if ($data[0] < $minTS || $data[0] > $maxTS) {
                        $this->entityManager->persist($log);
                    }
                }

                $this->entityManager->flush();
                $this->entityManager->clear();
            }

            $output->writeln('lines: ' . $linesRead . ' mem ' . round(memory_get_usage() / 1024000));
        }

        return 0;
    }

    private function parseCSVString($s)
    {
//            $recTime,
//            $timeRFC3339,
//            $filesize,
//            $path,
//            $useragent,
//            $responseHttpStatus,
//            $requestHttpMethod,
//            $contentType

        return str_getcsv($s, ' ', '"');
    }
}