<?php
namespace Ababilithub\FlexPhp\Package\Dto\V1\Concrete\User;

use Ababilithub\{
    FlexPhp\Package\Dto\V1\Base\Dto as BaseDto,
    FlexPhp\Package\Model\V1\Concrete\User\Model as UserModel,
};
use InvalidArgumentException;

class Dto extends BaseDto
{
    public ?int $id = null;
    public string $username;
    public string $email;
    public string $password;
    public bool $isActive = true;
    public array $roles = [];
    public ?string $createdAt = null;
    public ?string $updatedAt = null;

    public static function fromEntity(object $user): static
    {
        if (!$user instanceof UserModel) {
            throw new InvalidArgumentException('Expected UserModel instance');
        }

        $dto = new static();
        $dto->id = $user->getId();
        $dto->username = $user->getUsername();
        $dto->email = $user->getEmail();
        $dto->isActive = $user->isActive();
        $dto->roles = $user->getRoles();
        $dto->createdAt = $user->getCreatedAt()?->format('Y-m-d H:i:s');
        $dto->updatedAt = $user->getUpdatedAt()?->format('Y-m-d H:i:s');
        
        return $dto;
    }

    public function toEntity(): object
    {
        $this->validate();
        
        $user = new UserModel();
        $user->setId($this->id);
        $user->setUsername($this->username);
        $user->setEmail($this->email);
        $user->setPassword($this->password);
        $user->setIsActive($this->isActive);
        $user->setRoles($this->roles);
        
        return $user;
    }

    public function validate(): void
    {
        $this->assertType('username', $this->username, 'string');
        $this->assertType('email', $this->email, 'string');
        $this->assertType('password', $this->password, 'string');
        $this->assertType('isActive', $this->isActive, 'boolean');
        $this->assertType('roles', $this->roles, 'array');
        $this->assertType('id', $this->id, 'integer', true);
        $this->assertType('createdAt', $this->createdAt, 'string', true);
        $this->assertType('updatedAt', $this->updatedAt, 'string', true);

        if (empty($this->username)) {
            throw new InvalidArgumentException('Username cannot be empty');
        }

        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }

        if (strlen($this->password) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters');
        }
    }
}