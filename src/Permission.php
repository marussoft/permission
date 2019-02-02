<?php

declare(strict_types=1);

namespace Marussia\Permission;

class Permission
{
    private $userGroups = [0];
    private $action = '-';
    private $permissions;
    private $rules = 0;

    public function __construct(Repository $repository)
    {
        $this->repository = $repository;
    }
    
    public function init(array $user_groups)
    {
        $this->permissions = $this->repository->getPerms();
        
        $this->userGroups = $user_groups;
    }
    
    // Возвращает список разрвешенных акшнов относящихся к текущему контроллеру
    public function getAllowedActions(string $controller)
    {
        $perms = $this->repository->getPermsByController($controller);
        
        $actions = [];
        
        foreach ($perms as $key => $perm) {
            
            $rules = array_intersect_key(json_decode($perm['rules'], true), $this->userGroups);
            
            if (!empty($rules)) {
                $actions[$perm['action']] = $perm;
            }
        }
        return $actions;
        
    }
    
    // Возвращает правило для экшна
    private function checkAllowed(string $action) : int
    {
        $this->action = $action;
    
        $this->permissions = $this->repository->getPermsByController($controller);
        
        foreach ($this->permissions as $key => $perm) {
            
            if ($perm['action'] !== $this->action) {
                continue;
            }
            
            $rules = array_intersect_key(json_decode($perm['rules'], true), $this->userGroups);
            
            if (empty($rules)) {
                return $this->rules;
            }
            
            asort($rules);

            $this->rules = array_shift($rules);
            
            break;
        }
        return $this->rules;
    }

}
 
