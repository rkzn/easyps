<?php
namespace AppBundle\EventListener;

use AppBundle\Entity\Wallet;
use AppBundle\Entity\WalletHistory;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WalletSubscriber implements ContainerAwareInterface, EventSubscriber
{
    /**
     * @var WalletHistory
     */
    protected $history;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @param LifecycleEventArgs $args
     */
    public function prePersist(LifecycleEventArgs $args)
    {
        $entity = $args->getObject();
        if ($entity instanceof Wallet) {
            $this->history = new WalletHistory();
            $this->history->setWallet($entity);
            $this->history->setRestBefore(0);
            $this->history->setRestAfter($entity->getRest());
        }
    }

    /**
     * @param PreUpdateEventArgs $args
     */
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getObject();

        if ($entity instanceof Wallet) {
            if ($args->hasChangedField('rest')) {
                $this->history = new WalletHistory();
                $this->history->setWallet($entity);
                $this->history->setRestBefore($args->getOldValue('rest'));
                $this->history->setRestAfter($entity->getRest());
            }
        }
    }

    /**
     * @param PostFlushEventArgs $args
     */
    public function postFlush(PostFlushEventArgs $args)
    {
        $entityManager = $args->getEntityManager();
        if (!is_null($this->history)) {
            $entityManager->persist($this->history);
            $this->history = null;
            $entityManager->flush();
        }
    }

    /**
     * Sets the Container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     *
     * @api
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Returns an array of events this subscriber wants to listen to.
     *
     * @return array
     */
    public function getSubscribedEvents()
    {
        return array(
            Events::prePersist,
            Events::preUpdate,
            Events::postFlush
        );
    }
}
