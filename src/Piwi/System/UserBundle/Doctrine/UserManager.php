<?php

namespace Piwi\System\UserBundle\Doctrine;

class UserManager extends \FOS\UserBundle\Doctrine\UserManager
{
    /**
     * Get the users with the role ROLE_MEMBER
     *
     * @return array
     */
    public function getMembers()
    {
        return $this->getUsersByRole('ROLE_MEMBER');
    }

    /**
     * Get users by role
     *
     * @param $role
     * @return array
     */
    protected function getUsersByRole($role)
    {
        $mailUsers = [];
        /** @var $user \Fos\UserBundle\Model\User */
        foreach ($this->findUsers() as $user) {
            if ($user->hasRole($role)) {
                $mailUsers[] = $user;
            }
        }
        return $mailUsers;
    }
}