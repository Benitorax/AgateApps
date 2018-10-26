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
    public const SUBJECT_APPLICATION = 'contact.subject.application';
    public const SUBJECT_AFTER_SALES = 'contact.subject.after_sales';
    public const SUBJECT_CONVENTIONS = 'contact.subject.conventions';
    public const SUBJECT_ESTEREN_MAPS = 'contact.subject.esteren_maps';
    public const SUBJECT_OTHER = 'contact.subject.other';

    public const PRODUCT_RANGE_DRAGONS = 'contact.product_range.dragons';
    public const PRODUCT_RANGE_7TH_SEA = 'contact.product_range.7th_sea';
    public const PRODUCT_RANGE_REQUIEM = 'contact.product_range.requiem';
    public const PRODUCT_RANGE_ESTEREN = 'contact.product_range.esteren';

    public const SUBJECTS = [
        'contact.subject.specify' => '',
        self::SUBJECT_APPLICATION => self::SUBJECT_APPLICATION,
        self::SUBJECT_AFTER_SALES => self::SUBJECT_AFTER_SALES,
        self::SUBJECT_CONVENTIONS => self::SUBJECT_CONVENTIONS,
        self::SUBJECT_ESTEREN_MAPS => self::SUBJECT_ESTEREN_MAPS,
        self::SUBJECT_OTHER => self::SUBJECT_OTHER,
    ];

    public const PRODUCT_RANGES = [
        'contact.product_range.specify' => '',
        self::PRODUCT_RANGE_DRAGONS => self::PRODUCT_RANGE_DRAGONS,
        self::PRODUCT_RANGE_7TH_SEA => self::PRODUCT_RANGE_7TH_SEA,
        self::PRODUCT_RANGE_REQUIEM => self::PRODUCT_RANGE_REQUIEM,
        self::PRODUCT_RANGE_ESTEREN => self::PRODUCT_RANGE_ESTEREN,
    ];

    /** @var string */
    private $name = '';

    /** @var string */
    private $email = '';

    /** @var string */
    private $message = '';

    /** @var string */
    private $subject = '';

    /** @var string */
    private $productRange = '';

    /** @var string */
    private $title = '';

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

    public function getProductRange(): string
    {
        return $this->productRange;
    }

    public function setProductRange(?string $productRange): self
    {
        $this->productRange = (string) $productRange;

        return $this;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = (string) $title;

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
