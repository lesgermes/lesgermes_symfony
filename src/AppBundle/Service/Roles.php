<?php

namespace AppBundle\Service;

use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchyInterface;

class Roles
{
    private $roleHierarchy;

    /**
     * Constructor
     *
     * @param RoleHierarchyInterface $roleHierarchy
     */
    public function __construct(RoleHierarchyInterface $roleHierarchy)
    {
        $this->roleHierarchy = $roleHierarchy;
    }

    public function getRoles($userRoles)
    {
        $roles = array();
        foreach ($userRoles as $userFole) {
            array_push($roles, $userFole);
        }
        array_walk_recursive($this->roleHierarchy, function($val) use (&$roles) {
            array_push($roles, $val);
        });

        return array_unique($roles);
    }

    /**
     * isGranted
     *
     * @param string $role
     * @param $user
     * @return bool
     */
    public function isGranted($role, $user) {

        $role = new Role($role);

        foreach($user->getRoles() as $userRole) {
            if (in_array($role, $this->roleHierarchy->getReachableRoles(array(new Role($userRole)))))
                return true;
        }

        return false;
    }
}