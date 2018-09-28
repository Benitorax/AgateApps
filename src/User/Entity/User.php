<?php

/**
 * This file is part of the corahn_rin package.
 *
 * (c) Alexandre Rock Ancelet <alex@orbitale.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace User\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="User\Repository\UserRepository")
 * @ORM\Table(name="fos_user_user")
 * @UniqueEntity("emailCanonical", message="user.email.already_used")
 * @UniqueEntity("usernameCanonical", message="user.username.already_used")
 */
class User implements UserInterface, \Serializable
{
    public const ROLE_DEFAULT = 'ROLE_USER';
    public const ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';

    use TimestampableEntity;

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(name="username", type="string")
     */
    protected $username;

    /**
     * @var string
     *
     * @ORM\Column(name="username_canonical", type="string", unique=true)
     */
    protected $usernameCanonical;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string")
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="email_canonical", type="string", unique=true)
     */
    protected $emailCanonical;

    /**
     * Encrypted password. Must be persisted.
     *
     * @var string
     *
     * @ORM\Column(name="password", type="string")
     */
    protected $password;

    /**
     * Plain password. Used for model validation. Must not be persisted.
     *
     * @var string
     */
    protected $plainPassword;

    /**
     * @var string|null
     *
     * @ORM\Column(name="confirmation_token", type="string", unique=true, nullable=true)
     */
    protected $confirmationToken;

    /**
     * @var bool
     *
     * @ORM\Column(name="email_confirmed", type="boolean", options={"default" = "0"})
     */
    private $emailConfirmed = false;

    /**
     * @var array
     *
     * @ORM\Column(name="roles", type="array")
     */
    protected $roles;

    /**
     * @var string
     *
     * @ORM\Column(name="ulule_id", type="string", nullable=true)
     */
    private $ululeId;

    /**
     * @var string
     *
     * @ORM\Column(name="ulule_username", type="string", nullable=true)
     */
    private $ululeUsername;

    /**
     * @var string
     *
     * @ORM\Column(name="ulule_api_token", type="string", nullable=true)
     */
    private $ululeApiToken;

    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->roles = [static::ROLE_DEFAULT];
    }

    public function __toString()
    {
        return (string) $this->getUsername();
    }

    public function addRole($role): self
    {
        $role = \mb_strtoupper($role);

        if ($role === static::ROLE_DEFAULT) {
            return $this;
        }

        if (!\in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        $this->roles = \array_unique($this->roles);

        return $this;
    }

    public function getRoles($asObject = false): array
    {
        $roles = $this->roles;

        // we need to make sure to have at least one role
        $roles[] = static::ROLE_DEFAULT;

        $roles = \array_unique($roles);

        if (true === $asObject) {
            foreach ($roles as &$role) {
                $role = new Role($role);
            }
        }

        return $roles;
    }

    public function hasRole($role): bool
    {
        return \in_array(\mb_strtoupper($role), $this->getRoles(), true);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole(static::ROLE_SUPER_ADMIN);
    }

    public function removeRole($role): self
    {
        if (false !== $key = \array_search(\mb_strtoupper($role), $this->roles, true)) {
            unset($this->roles[$key]);
            $this->roles = \array_values($this->roles);
        }

        return $this;
    }

    public function setSuperAdmin($boolean): self
    {
        if (true === $boolean) {
            $this->addRole(static::ROLE_SUPER_ADMIN);
        } else {
            $this->removeRole(static::ROLE_SUPER_ADMIN);
        }

        return $this;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = [];

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getSalt(): void
    {
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername($username): self
    {
        $this->username = $username;

        return $this;
    }

    public function setUsernameCanonical($usernameCanonical): self
    {
        $this->usernameCanonical = $usernameCanonical;

        return $this;
    }

    public function getUsernameCanonical(): ?string
    {
        return $this->usernameCanonical;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmailCanonical($emailCanonical): self
    {
        $this->emailCanonical = $emailCanonical;

        return $this;
    }

    public function getEmailCanonical(): ?string
    {
        return $this->emailCanonical;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function setConfirmationToken(?string $confirmationToken): self
    {
        $this->confirmationToken = $confirmationToken;

        return $this;
    }

    public function isEmailConfirmed(): bool
    {
        return $this->emailConfirmed;
    }

    public function setEmailConfirmed(bool $emailConfirmed): self
    {
        $this->emailConfirmed = $emailConfirmed;

        return $this;
    }

    public function getConfirmationToken(): ?string
    {
        return $this->confirmationToken;
    }

    public function getUluleId(): ?string
    {
        return $this->ululeId;
    }

    public function setUluleId(?string $ululeId): self
    {
        $this->ululeId = $ululeId;

        return $this;
    }

    public function getUluleUsername(): ?string
    {
        return $this->ululeUsername;
    }

    public function setUluleUsername(?string $ululeUsername): self
    {
        $this->ululeUsername = $ululeUsername;

        return $this;
    }

    public function getUluleApiToken(): ?string
    {
        return $this->ululeApiToken;
    }

    public function setUluleApiToken(?string $ululeApiToken): self
    {
        $this->ululeApiToken = $ululeApiToken;

        return $this;
    }

    public function serialize()
    {
        return \serialize([
            $this->id,
            $this->username,
            $this->usernameCanonical,
            $this->email,
            $this->emailCanonical,
            $this->password,
            \implode(',', $this->getRoles()),
        ]);
    }

    public function unserialize($serialized)
    {
        $data = \unserialize($serialized, ['allowed_classes' => false]);

        $this->roles = \explode(',', $data[5]);

        [
            $this->id,
            $this->username,
            $this->usernameCanonical,
            $this->email,
            $this->emailCanonical,
            $this->password,
        ] = $data;
    }
}
