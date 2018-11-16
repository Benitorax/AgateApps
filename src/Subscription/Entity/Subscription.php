<?php

namespace Subscription\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Subscription\SubscriptionType;
use Symfony\Component\Validator\Constraints as Assert;
use Subscription\Constraint\UniqueSubscription;
use User\Entity\User;

/**
 * @ORM\Entity(repositoryClass="Subscription\Repository\SubscriptionRepository")
 * @ORM\Table(name="user_subscriptions")
 * @Gedmo\Loggable
 * @UniqueSubscription
 */
class Subscription
{
    /**
     * @var int|null
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var User|null
     *
     * @ORM\ManyToOne(targetEntity="User\Entity\User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)
     *
     * @Assert\NotBlank
     */
    private $user;

    /**
     * @var string|null
     *
     * @ORM\Column(name="type", type="string", nullable=false)
     *
     * @Assert\NotBlank
     * @Assert\Choice(Subscription\SubscriptionType::TYPES)
     */
    private $type;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="starts_at", type="date_immutable")
     *
     * @Assert\NotBlank
     * @Assert\DateTime
     * @Assert\GreaterThanOrEqual("today")
     */
    protected $startsAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(name="ends_at", type="date_immutable")
     *
     * @Assert\NotBlank
     * @Assert\DateTime
     * @Assert\GreaterThanOrEqual("tomorrow")
     * @Assert\GreaterThanOrEqual(propertyPath="startsAt")
     *
     * @Gedmo\Versioned
     */
    protected $endsAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="cancelled_manually", type="boolean", nullable=false, options={"default" = "0"})
     */
    protected $cancelledManually = false;

    public function __construct()
    {
        $this->startsAt = new \DateTimeImmutable();
        $this->endsAt = new \DateTimeImmutable('+1 month');
    }

    public static function create(User $user, string $type, \DateTimeImmutable $endsAt): self
    {
        $subscription = new self();

        $subscription->user = $user;
        $subscription->setType($type);
        $subscription->startsAt = new \DateTimeImmutable();
        $subscription->endsAt = $endsAt;

        return $subscription;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        if (!isset(SubscriptionType::TYPES[$type])) {
            throw new \InvalidArgumentException(\sprintf('Subscription type %s does not exist.', $type));
        }

        $this->type = $type;
    }

    public function getStartsAt(): ?\DateTimeInterface
    {
        return $this->startsAt;
    }

    public function setStartsAt(?\DateTimeInterface $startsAt): void
    {
        $this->startsAt = $startsAt;
    }

    public function getEndsAt(): ?\DateTimeInterface
    {
        return $this->endsAt;
    }

    public function setEndsAt(?\DateTimeInterface $endsAt): void
    {
        $this->endsAt = $endsAt;
    }
}
