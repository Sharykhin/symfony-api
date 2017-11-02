<?php

namespace AppBundle\FilterRequest;

use AppBundle\Entity\User;
use InvalidArgumentException;

/**
 * Class AbstractRequest
 * @package AppBundle\FilterRequest
 */
abstract class AbstractRequest extends BaseRequest
{
    /**
     * @param array $parameters
     * @param string $action
     * @return array
     */
    public function filterRequest(array $parameters, string $action = null) : array
    {
        $filtered = [];

        $user = $this->getUser();
        if (!$user instanceof User) {
            $filtered = $this->intersect($parameters, $this->whiteList);
        } elseif ($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN', $user)) {
            $filtered = $this->intersect($parameters, array_merge($this->whiteList, $this->adminList, $this->superAdminList));
        } elseif ($this->authorizationChecker->isGranted('ROLE_ADMIN', $user)) {
            $filtered = $this->intersect($parameters, array_merge($this->whiteList, $this->adminList));
        } elseif ($this->authorizationChecker->isGranted('ROLE_USER', $user)) {
            $filtered = $this->intersect($parameters, $this->whiteList);
        }

        if (!is_null($action) && !$this->isSupport($action)) {
            throw new InvalidArgumentException();
        }

        if (!is_null($action)) {
            if (method_exists($this, $action)) {
                $filtered = call_user_func_array([$this, $action], [$filtered]);
            }
        }

        return $filtered;
    }

    /**
     * @param string $action
     * @return bool
     */
    abstract protected function isSupport(string $action) : bool;
}
