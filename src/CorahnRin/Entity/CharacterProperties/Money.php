<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity\CharacterProperties;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Embeddable
 */
class Money
{
    protected static $name = 'Daol';
    protected static $names = ['ember', 'azure', 'frost'];
    protected static $values = ['ember' => 0, 'azure' => 0, 'frost' => 0];

    /**
     * @var int
     *
     * @ORM\Column(name="ember", type="integer")
     */
    protected $ember;

    /**
     * @var int
     *
     * @ORM\Column(name="azure", type="integer")
     */
    protected $azure;

    /**
     * @var int
     *
     * @ORM\Column(name="frost", type="integer")
     */
    protected $frost;

    /**
     * @param int  $ember
     * @param int  $azure
     * @param int  $frost
     * @param bool $flatten
     */
    public function __construct($ember = 0, $azure = 0, $frost = 0, $flatten = false)
    {
        $this->ember = (int) $ember;
        $this->azure = (int) $azure;
        $this->frost = (int) $frost;

        if ($flatten) {
            $this->reallocate();
        }
    }

    /**
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getFrost()
    {
        return $this->frost;
    }

    /**
     * @param int $frost
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function addFrost($frost)
    {
        $this->frost += $frost;

        return $this;
    }

    /**
     * @param int $frost
     *
     * @codeCoverageIgnore
     */
    public function setFrost($frost)
    {
        $this->frost = $frost;
    }

    /**
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getAzure()
    {
        return $this->azure;
    }

    /**
     * @param int $azure
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function addAzure($azure)
    {
        $this->azure += $azure;

        return $this;
    }

    /**
     * @param int $azure
     *
     * @codeCoverageIgnore
     */
    public function setAzure($azure)
    {
        $this->azure = $azure;
    }

    /**
     * @return int
     *
     * @codeCoverageIgnore
     */
    public function getEmber()
    {
        return $this->ember;
    }

    /**
     * @param int $ember
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function addEmber($ember)
    {
        $this->ember += $ember;

        return $this;
    }

    /**
     * @param int $ember
     *
     * @return $this
     *
     * @codeCoverageIgnore
     */
    public function setEmber($ember)
    {
        $this->ember = $ember;

        return $this;
    }

    /**
     * Take all lower moneys to reallocate them to the higher money.
     *
     * @return Money
     */
    public function reallocate()
    {
        while ($this->ember > 10) {
            $this->azure++;
            $this->ember -= 10;
        }

        while ($this->azure > 10) {
            $this->frost++;
            $this->azure -= 10;
        }

        return $this;
    }

    /**
     * Take all high moneys and put them in the "ember" part.
     *
     * @return Money
     */
    public function flatten()
    {
        $this->ember =
            $this->ember +
            ($this->azure * 10) +
            ($this->frost * 100);

        $this->azure = 0;
        $this->frost = 0;

        return $this;
    }
}
