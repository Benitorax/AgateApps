<?php

namespace Esteren\PortalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Orbitale\Bundle\CmsBundle\Entity\Page as BasePage;

/**
 * @ORM\Entity(repositoryClass="Orbitale\Bundle\CmsBundle\Repository\PageRepository")
 * @ORM\Table(name="orbitale_cms_pages")
 */
class Page extends BasePage
{
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
}
