<?php
namespace AppBundle\Command;

use AppBundle\Entity\Transfer;
use Monolog\Handler\StreamHandler;
use Monolog\Processor\MemoryPeakUsageProcessor;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wrep\Daemonizable\Command\EndlessContainerAwareCommand;

class TransferDaemonCommand extends EndlessContainerAwareCommand
{
    /**
     * @var Logger
     */
    protected $logger;

    /**
     * @return Logger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param Logger $logger
     * @return self
     */
    public function setLogger($logger)
    {
        $this->logger = $logger;

        return $this;
    }

    protected function configure()
    {
        $this
            ->setName('app:easyps:transfer-daemon')
            ->setDescription('Transfer daemon');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $walletManager = $this->getContainer()->get('app_wallet');

        $this->initLogger($output);
        $this->getLogger()->debug('Start daemon', [$this->getName()]);

        $transfers = $this->findTransfers();
        $this->getLogger()->debug('Find transfers', ['rows_cnt' => count($transfers)]);

        /** @var Transfer $transfer */
        foreach ($transfers as $transfer) {
            try {
                $walletManager->executeTransfer($transfer);
            } catch (\Exception $e) {
                $this->getLogger()->error($e->getMessage());
            }
        }

        $this->getLogger()->debug('Done', [$this->getName()]);
    }

    protected function findTransfers()
    {
        $entityManger = $this->getContainer()->get('doctrine.orm.entity_manager');
        $repo = $entityManger->getRepository('AppBundle:Transfer');

        return $repo->findBy(['state' => Transfer::STATE_PENDING], null, 100);
    }

    protected function initLogger(OutputInterface $output)
    {
        if (!$this->getLogger()) {
            $logDir = $this->getContainer()->getParameter('kernel.logs_dir');
            $logger = new Logger('app:easyps:transferdaemon');
            $logger->pushHandler(new StreamHandler($logDir.'/transfer_daemon.log', Logger::DEBUG));

            if ($output->getVerbosity() > OutputInterface::VERBOSITY_NORMAL) {
                $logger->pushProcessor(new MemoryPeakUsageProcessor());
                $logger->pushHandler(new StreamHandler(STDOUT));
            }
            $this->setLogger($logger);
        }
    }
}
