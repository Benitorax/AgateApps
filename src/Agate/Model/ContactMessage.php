<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Agate\Model;

class ContactMessage
{
    /** @var string */
    private $name = '';

    /** @var string */
    private $email = '';

    /** @var string */
    private $message = '';

    /** @var string */
    private $subject = '';

    /** @var string */
    private $locale = 'fr';

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = \strip_tags((string) $name);

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = \strip_tags((string) $email);

        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage(?string $message): self
    {
        $this->message = \strip_tags((string) $message);

        return $this;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function setSubject(?string $subject): self
    {
        $this->subject = (string) $subject;

        return $this;
    }

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = (string) $locale;

        return $this;
    }
}
