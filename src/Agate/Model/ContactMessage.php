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

namespace Agate\Model;

class ContactMessage
{
    public const SUBJECT_APPLICATION = 'contact.subject.application';
    public const SUBJECT_COLLABORATION = 'contact.subject.collaboration';
    public const SUBJECT_AUTHORIZATION = 'contact.subject.authorization';
    public const SUBJECT_EVENTS = 'contact.subject.events';

    public const SUBJECT_AFTER_SALES_CROWDFUNDING = 'contact.subject.after_sales_crowdfunding';
    public const SUBJECT_AFTER_SALES_PDF = 'contact.subject.after_sales_pdf';

    public const SUBJECT_7TH_SEA = 'contact.subject.7th_sea';
    public const SUBJECT_DRAGONS = 'contact.subject.dragons';
    public const SUBJECT_ESTEREN = 'contact.subject.esteren';
    public const SUBJECT_ESTEREN_MAPS = 'contact.subject.esteren_maps';
    public const SUBJECT_REQUIEM = 'contact.subject.requiem';

    public const SUBJECT_OTHER = 'contact.subject.other';

    public const SUBJECTS = [
        'contact.subject.specify' => '',
        self::SUBJECT_APPLICATION => self::SUBJECT_APPLICATION,
        self::SUBJECT_COLLABORATION => self::SUBJECT_COLLABORATION,
        self::SUBJECT_AUTHORIZATION => self::SUBJECT_AUTHORIZATION,
        self::SUBJECT_EVENTS => self::SUBJECT_EVENTS,
        self::SUBJECT_AFTER_SALES_CROWDFUNDING => self::SUBJECT_AFTER_SALES_CROWDFUNDING,
        self::SUBJECT_AFTER_SALES_PDF => self::SUBJECT_AFTER_SALES_PDF,
        self::SUBJECT_7TH_SEA => self::SUBJECT_7TH_SEA,
        self::SUBJECT_DRAGONS => self::SUBJECT_DRAGONS,
        self::SUBJECT_ESTEREN => self::SUBJECT_ESTEREN,
        self::SUBJECT_ESTEREN_MAPS => self::SUBJECT_ESTEREN_MAPS,
        self::SUBJECT_REQUIEM => self::SUBJECT_REQUIEM,
        self::SUBJECT_OTHER => self::SUBJECT_OTHER,
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
