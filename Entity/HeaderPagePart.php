<?php

namespace Kunstmaan\PagePartBundle\Entity;

use Kunstmaan\PagePartBundle\Form\HeaderPagePartAdminType;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class that defines a header page part object to add to a page
 *
 * @ORM\Entity
 * @ORM\Table(name="kuma_header_page_parts")
 */
class HeaderPagePart extends AbstractPagePart
{

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $niv;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string", nullable=true)
     */
    protected $title;

    /**
     * Set niv
     *
     * @param int $niv
     */
    public function setNiv($niv)
    {
        $this->niv = $niv;
    }

    /**
     * Get niv
     *
     * @return int
     */
    public function getNiv()
    {
        return $this->niv;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return "HeaderPagePart " . $this->getTitle();
    }

    /**
     * @return string
     */
    public function getDefaultView()
    {
        return "KunstmaanPagePartBundle:HeaderPagePart:view.html.twig";
    }

    /**
     * @return HeaderPagePartAdminType
     */
    public function getDefaultAdminType()
    {
        return new HeaderPagePartAdminType();
    }
}
