<?php

namespace DP\Core\UserBundle\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\SecurityContext;
use Sylius\Bundle\ResourceBundle\Event\ResourceEvent;

class GroupAdminListener implements EventSubscriberInterface
{
    private $context;

    public static function getSubscribedEvents()
    {
        return array(
            'dedipanel.group.pre_create' => 'validateParentNotEmpty',
            'dedipanel.group.pre_update' => 'validateParentNotEmpty',
        );
    }

    public function __construct(SecurityContext $context)
    {
        $this->context = $context;
    }

    public function validateParentNotEmpty(ResourceEvent $event)
    {
        $group = $event->getSubject();

        if ($group->getParent() == null && !$this->context->isGranted('ROLE_SUPER_ADMIN')) {
            $event->stop('dedipanel.group.need_parent');
        }
    }
}