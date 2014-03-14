<?php

namespace Piwi\System\CoreBundle\Twig;

use Symfony\Component\HttpFoundation\RequestStack;

class DatetimeExtension extends \Twig_Extension
{
    /**
     * @var \Symfony\Component\HttpFoundation\RequestStack
     */
    private $requestStack;

    /**
     * @param RequestStack $requestStack
     */
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('datetime', array($this, 'datetime'))
        );
    }

    public function datetime($d, $format = "%B %e")
    {
        if ($d instanceof \DateTime) {
            $d = $d->getTimestamp();
        }
        setlocale(LC_ALL, $this->requestStack->getMasterRequest()->getLocale());
        return strftime($format, $d);
    }

    public function getName()
    {
        return 'datetime_extension';
    }
}