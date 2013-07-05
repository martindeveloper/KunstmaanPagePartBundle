<?php

namespace Kunstmaan\PagePartBundle\Twig\Extension;

use Doctrine\ORM\EntityManager;
use Kunstmaan\PagePartBundle\Entity\AbstractPagePart;
use Kunstmaan\PagePartBundle\Repository\PagePartRefRepository;
use Kunstmaan\PagePartBundle\Helper\PagePartInterface;
use Kunstmaan\PagePartBundle\Helper\HasPagePartsInterface;

/**
 * PagePartTwigExtension
 */
class PagePartTwigExtension extends \Twig_Extension
{

    protected $em;

    /**
     * @var \Twig_Environment
     */
    protected $environment;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
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
        return array(
            'render_pageparts'  => new \Twig_Function_Method($this, 'renderPageParts', array('needs_context' => true, 'is_safe' => array('html'))),
            'render_pagepart'  => new \Twig_Function_Method($this, 'renderPagePart', array('needs_context' => true, 'is_safe' => array('html'))),
            'getpageparts'  => new \Twig_Function_Method($this, 'getPageParts'),
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
    public function renderPageParts(array $twigContext, HasPagePartsInterface $page, $contextName = "main", array $parameters = array())
    {
        $template = $this->environment->loadTemplate("KunstmaanPagePartBundle:PagePartTwigExtension:widget.html.twig");
        /* @var $entityRepository PagePartRefRepository */
        $entityRepository = $this->em->getRepository('KunstmaanPagePartBundle:PagePartRef');
        $pageparts = $entityRepository->getPageParts($page, $contextName);
        $newTwigContext = array_merge($parameters, array(
            'pageparts' => $pageparts
        ));
        $newTwigContext = array_merge($newTwigContext, $twigContext);

        return $template->render($newTwigContext);
    }

    /**
     * @param array                 $twigContext The twig context
     * @param AbstractPagePart      $pagePart        The pagepart
     * @param array                 $parameters  Some extra parameters
     *
     * @return string
     */
    public function renderPagePart(array $twigContext, AbstractPagePart $pagePart, array $parameters = array())
    {
        $template = $this->environment->loadTemplate($pagePart->getDefaultView());
        $newTwigContext = array_merge($parameters, array(
            'resource' => $pagePart
        ));
        $newTwigContext = array_merge($twigContext, $newTwigContext);

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
