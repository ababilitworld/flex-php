<?php
namespace Ababilithub\FlexPhp\Package\Model\V1\Concrete\User;

use Ababilithub\{
    FlexPhp\Package\Model\V1\Base\Model as BaseModel
};

class Model extends BaseModel
{
    protected string $username;
    protected string $email;
    protected string $password;
    protected bool $isActive = true;
    protected array $roles = [];

    public function validate(): void
    {
        if (empty($this->username)) {
            throw new \InvalidArgumentException('Username cannot be empty');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Invalid email format');
        }

        if (strlen($this->password) < 8) {
            throw new \InvalidArgumentException('Password must be at least 8 characters');
        }
    }

    public function toArray(): array
    {
        return array_merge(parent::toArray(), [
            'username' => $this->username,
            'email' => $this->email,
            'is_active' => $this->isActive,
            'roles' => $this->roles
        ]);
    }

    public function getUsername():string
    {
        return $this->username;
    }

    public function getEmail():string
    {
        return $this->email;
    }

    public function isActive():bool
    {
        return $this->isActive;
    }

    public function getRoles():array
    {
        return $this->roles;
    }

    public function setUsername(string $username):void
    {
        $this->username = $username;
    }

    public function setEmail(string $email):void
    {
        $this->email = $email;
    }

    public function setPassword(string $password):void
    {
        $this->password = $password;
    }

    public function setIsActive(bool $isActive): void   
    {
        $this->isActive = $isActive;
    }

    public function setRoles(array $roles):void
    {
        $this->roles = $roles;
    }

    // Model-specific methods
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new \DateTimeImmutable();
    }
}