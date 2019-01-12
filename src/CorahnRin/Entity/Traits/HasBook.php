<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CorahnRin\Entity\Traits;

use CorahnRin\Entity\Book;
use Doctrine\ORM\Mapping as ORM;

trait HasBook
{
    /**
     * @var Book
     * @ORM\ManyToOne(targetEntity="CorahnRin\Entity\Book")
     * @ORM\JoinColumn(name="book_id", nullable=true)
     */
    protected $book;

    public function setBook(Book $book = null): self
    {
        $this->book = $book;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->book;
    }
}
