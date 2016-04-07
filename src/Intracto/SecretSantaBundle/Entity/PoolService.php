<?php

namespace Intracto\SecretSantaBundle\Entity;

use Doctrine\ORM\EntityManager;
use JMS\DiExtraBundle\Annotation as DI;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * @DI\Service("intracto_secret_santa.pool_service")
 */
class PoolService
{
    /**
     * @DI\Inject("mailer")
     *
     * @var \Swift_Mailer
     */
    public $mailer;

    /**
     * @DI\Inject("doctrine.orm.entity_manager")
     *
     * @var EntityManager
     */
    public $em;

    /**
     * @DI\Inject("intracto_secret_santa.entry_shuffler")
     *
     * @var EntryShuffler $entryShuffler
     */
    public $entryShuffler;

    /**
     * @DI\Inject("templating")
     *
     * @var EngineInterface
     */
    public $templating;

    /**
     * @DI\Inject("%admin_email%")
     */
    public $adminEmail;

    /**
     * @DI\Inject("translator")
     *
     * @var TranslatorInterface;
     */
    public $translator;

    /**
     * @DI\Inject("router")
     * @var \Symfony\Bundle\FrameworkBundle\Routing\Router
     */
    public $routing;


}
