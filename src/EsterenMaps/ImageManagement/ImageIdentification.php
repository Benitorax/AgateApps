<?php

declare(strict_types=1);

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EsterenMaps\ImageManagement;

/**
 * This class stores the attributes of an identification made by the MapsTilesManager.
 */
class ImageIdentification implements \ArrayAccess
{
    private const VALID_PROPERTIES = [
        'xmax',
        'ymax',
        'tiles_max',
        'wmax',
        'hmax',
        'wmax_global',
        'hmax_global',
    ];

    /**
     * @var array
     */
    private $properties = [];

    public function __construct(array $data = [])
    {
        $this->properties = \array_merge(\array_fill_keys(self::VALID_PROPERTIES, null), $data);
    }

    /**
     * @return int
     */
    public function getTilesX()
    {
        return $this->properties['xmax'];
    }

    /**
     * @return int
     */
    public function getTilesY()
    {
        return $this->properties['ymax'];
    }

    /**
     * @return int
     */
    public function getTiles()
    {
        return $this->properties['tiles_max'];
    }

    /**
     * @return float
     */
    public function getWidth()
    {
        return $this->properties['wmax'];
    }

    /**
     * @return float
     */
    public function getHeight()
    {
        return $this->properties['hmax'];
    }

    /**
     * @return float
     */
    public function getGlobalWidth()
    {
        return $this->properties['wmax_global'];
    }

    /**
     * @return float
     */
    public function getGlobalHeight()
    {
        return $this->properties['hmax_global'];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return \in_array($offset, self::VALID_PROPERTIES, true) && \array_key_exists($offset, $this->properties);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->properties[$offset] : null;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        if ($this->offsetExists($offset)) {
            $this->properties[$offset] = $value;
        } else {
            throw new \RuntimeException(\sprintf(
                'Undefined attribute %s in ImageIdentification',
                $offset
            ));
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        unset($this->properties[$offset]);

        return $this;
    }
}
