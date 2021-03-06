<?php

namespace Kunstmaan\PagePartBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;
use Kunstmaan\PagePartBundle\Helper\RenderContext;
use Kunstmaan\PagePartBundle\Repository\PagePartRefRepository;
use Kunstmaan\PagePartBundle\Helper\PagePartInterface;
use Kunstmaan\PagePartBundle\Helper\HasPagePartsInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * PagePartTwigExtension
 */
class PagePartTwigExtension extends \Twig_Extension
{

    protected $em;
    protected $container;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @param ContainerInterface $container
     * @param EntityManager      $em
     */
    public function __construct(ContainerInterface $container, EntityManager $em)
    {
        $this->em = $em;
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $environment)
    {
        $this->environment = $environment;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array (
            'render_pageparts' => new \Twig_Function_Method($this, 'renderPageParts', array ('needs_context' => true, 'is_safe' => array ('html'))),
            'getpageparts'     => new \Twig_Function_Method($this, 'getPageParts'),
        );
    }

    /**
     * @param array                 $twigContext The twig context
     * @param HasPagePartsInterface $page        The page
     * @param string                $contextName The pagepart context
     * @param array                 $parameters  Some extra parameters
     *
     * @return string
     */
    public function renderPageParts(array $twigContext, HasPagePartsInterface $page, $contextName = "main", array $parameters = array ())
    {
        $template = $this->environment->loadTemplate("KunstmaanPagePartBundle:PagePartTwigExtension:widget.html.twig");
        /* @var $entityRepository PagePartRefRepository */
        $entityRepository = $this->em->getRepository('KunstmaanPagePartBundle:PagePartRef');
        $pageparts = $entityRepository->getPageParts($page, $contextName);

        $entityContext = new RenderContext();

        foreach ($pageparts as $pagePart) {
            if (method_exists($pagePart, "service")) {
                $pagePart->service($this->container, $entityContext);
            }
        }

        $twigContext = array_merge($twigContext, ["data" => $entityContext]);

        $newTwigContext = array_merge(
            $parameters, array (
                'pageparts' => $pageparts
            )
        );
        $newTwigContext = array_merge($newTwigContext, $twigContext);

        return $template->render($newTwigContext);
    }

    /**
     * @param HasPagePartsInterface $page    The page
     * @param string                $context The pagepart context
     *
     * @return PagePartInterface[]
     */
    public function getPageParts(HasPagePartsInterface $page, $context = "main")
    {
        /**@var $entityRepository PagePartRefRepository */
        $entityRepository = $this->em->getRepository('KunstmaanPagePartBundle:PagePartRef');
        $pageparts = $entityRepository->getPageParts($page, $context);

        return $pageparts;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'pageparts_twig_extension';
    }

}
