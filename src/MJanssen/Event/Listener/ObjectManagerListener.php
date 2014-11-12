<?php
namespace MJanssen\Event\Listener;

use RuntimeException;
use MJanssen\Service\ObjectManagerService;
use Symfony\Component\EventDispatcher\Event;


class ObjectManagerListener
{
    /**
     * @param Event $event
     */
    public function setObjectRepository(Event $event)
    {
        if(null === $event->getObjectName()) {
            throw new RuntimeException('Object name is not set');
        }

        if(null === $event->getObjectManager()) {
            throw new RuntimeException('Object manager is not set');
        }

        $objectManagerService = new ObjectManagerService(
            $event->getObjectManager()
        );

        $event->setObjectRepository(
            $objectManagerService->getObjectRepository(
                $event->getObjectName()
            )
        );
    }
} 