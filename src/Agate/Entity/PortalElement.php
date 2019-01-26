<?php

/*
 * This file is part of the Agate Apps package.
 *
 * (c) Alexandre Rock Ancelet <pierstoval@gmail.com> and Studio Agate.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Agate\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity as AssertUniqueEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="portal_elements",
 *     uniqueConstraints={
 *         @ORM\UniqueConstraint(name="portal_and_locale", columns={"portal", "locale"})
 *     }
 * )
 * @AssertUniqueEntity(fields={"portal", "locale"})
 */
class PortalElement
{
    use TimestampableEntity;

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="portal", type="string")
     *
     * @Assert\NotBlank
     */
    private $portal;

    /**
     * @var string
     *
     * @ORM\Column(name="locale", type="string")
     *
     * @Assert\NotBlank
     */
    private $locale;

    /**
     * @var string
     *
     * @ORM\Column(name="image_url", type="string")
     */
    private $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string")
     *
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="subtitle", type="string")
     */
    private $subtitle = '';

    /**
     * @var string
     *
     * @ORM\Column(name="button_text", type="string")
     */
    private $buttonText = '';

    /**
     * @var string
     *
     * @ORM\Column(name="button_link", type="string")
     */
    private $buttonLink = '';

    /**
     * @var UploadedFile|null
     *
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/png"},
     *     minWidth=1000,
     *     minRatio="1.3",
     *     allowPortrait=false,
     *     allowSquare=false,
     *     detectCorrupted=true
     * )
     */
    private $image;

    public function getId(): int
    {
        return (int) $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = (int) $id;

        return $this;
    }

    public function getPortal(): string
    {
        return (string) $this->portal;
    }

    public function setPortal(?string $portal): self
    {
        $this->portal = (string) $portal;

        return $this;
    }

    public function getLocale(): string
    {
        return (string) $this->locale;
    }

    public function setLocale(?string $locale): self
    {
        $this->locale = (string) $locale;

        return $this;
    }

    public function getImageUrl(): string
    {
        return (string) $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): self
    {
        $this->imageUrl = (string) $imageUrl;

        return $this;
    }

    public function getTitle(): string
    {
        return (string) $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = (string) $title;

        return $this;
    }

    public function getSubtitle(): string
    {
        return (string) $this->subtitle;
    }

    public function setSubtitle(?string $subtitle): self
    {
        $this->subtitle = (string) $subtitle;

        return $this;
    }

    public function getButtonText(): string
    {
        return (string) $this->buttonText;
    }

    public function setButtonText(?string $buttonText): self
    {
        $this->buttonText = (string) $buttonText;

        return $this;
    }

    public function getButtonLink(): string
    {
        return (string) $this->buttonLink;
    }

    public function setButtonLink(?string $buttonLink): self
    {
        $this->buttonLink = (string) $buttonLink;

        return $this;
    }

    public function getImage(): ?UploadedFile
    {
        return $this->image;
    }

    public function setImage(?UploadedFile $image): self
    {
        $this->image = $image;

        return $this;
    }
}
