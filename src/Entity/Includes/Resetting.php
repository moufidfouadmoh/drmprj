<?php

namespace App\Entity\Includes;

use App\Validator\Password;
use Symfony\Component\Validator\Constraints as Assert;

class Resetting
{
    /**
     * @Password(message="user.password.old")
     */
    protected $oldPassword;

    /**
     * @return mixed
     */
    public function getNewPassword()
    {
        return $this->newPassword;
    }

    /**
     * @param mixed $newPassword
     */
    public function setNewPassword($newPassword): void
    {
        $this->newPassword = $newPassword;
    }

    /**
     * @Assert\Length(
     *     min = 4,
     *     minMessage = "user.password.length.min"
     * )
     */
    protected $newPassword;

    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    public function setOldPassword(string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }
}
