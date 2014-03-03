<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),

            # Storage
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Vich\UploaderBundle\VichUploaderBundle(),
            new Oneup\UploaderBundle\OneupUploaderBundle(),

            # Layout
            new Mopa\Bundle\BootstrapBundle\MopaBootstrapBundle(),
            new Bmatzner\FontAwesomeBundle\BmatznerFontAwesomeBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\MarkdownBundle\KnpMarkdownBundle(),

            # Users
            new FOS\UserBundle\FOSUserBundle(),

            # Comments
            new FOS\RestBundle\FOSRestBundle(),
            new FOS\CommentBundle\FOSCommentBundle(),
            new JMS\SerializerBundle\JMSSerializerBundle(),

            # Authentication
            new HWI\Bundle\OAuthBundle\HWIOAuthBundle(),

            # System
            new Piwi\System\CoreBundle\PiwiSystemCoreBundle(),
            new Piwi\System\UserBundle\PiwiSystemUserBundle(),

            # S2p App
            new Piwi\S2p\DashboardBundle\PiwiS2pDashboardBundle(),
            new Piwi\S2p\UserBundle\PiwiS2pUserBundle(),
            new Piwi\S2p\EventBundle\PiwiS2pEventBundle(),
            new Piwi\S2p\CommentBundle\PiwiS2pCommentBundle(),
            new Piwi\S2p\PhotoBundle\PiwiS2pPhotoBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
