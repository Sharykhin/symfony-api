<?php

namespace AppBundle\Security\Voter;

use AppBundle\Contract\Entity\IAdvancedUser;
use AppBundle\Contract\Entity\IId;
use AppBundle\Contract\Entity\IUser;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use InvalidArgumentException;

/**
 * Class UserVoter
 * @package AppBundle\Security\Voter
 */
class UserVoter implements VoterInterface
{
    const READ = 'read';
    const UPDATE = 'update';

    /**
     * @param $attribute
     * @return bool
     */
    public function supportsAttribute($attribute) : bool
    {
        return in_array($attribute, [self::READ, self::UPDATE]);
    }

    /**
     * @param TokenInterface $token
     * @param mixed $subject
     * @param array $attributes
     * @return int
     */
    public function vote(TokenInterface $token, $subject, array $attributes)
    {
        // check if the voter is used correct, only allow one attribute
        // this isn't a requirement, it's just one easy way for you to
        // design your voter
        if (1 !== count($attributes)) {
            throw new InvalidArgumentException('Only one attribute is allowed for VIEW or EDIT');
        }

        // set the attribute to check against
        $attribute = $attributes[0];

        // check if the given attribute is covered by this voter
        if (!$this->supportsAttribute($attribute)) {
            return VoterInterface::ACCESS_ABSTAIN;
        }

        // get current logged in user
        $user = $token->getUser();

        // make sure there is a user object (i.e. that the user is logged in)
        if (!$user instanceof IAdvancedUser) {
            return VoterInterface::ACCESS_DENIED;
        }

        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles())) {
            return true;
        }

        switch($attribute) {
            case self::READ:
                return $this->read($user, $subject);
            case self::UPDATE:
                return $this->update($user, $subject);
        }

        return VoterInterface::ACCESS_DENIED;
    }

    /**
     * @param IAdvancedUser $user
     * @param IId $subject
     * @return int
     */
    private function read(IAdvancedUser $user, IId $subject) : int
    {
        return $subject->getId() === $user->getId() ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
    }

    /**
     * @param IAdvancedUser $user
     * @param IAdvancedUser $subject
     * @return int
     */
    private function update(IAdvancedUser $user, IAdvancedUser $subject) : int
    {
        if (in_array('ROLE_ADMIN', $user->getRoles()) && !in_array('ROLE_ADMIN', $subject->getRoles())) {
            return true;
        }

        return $subject->getId() === $user->getId() ? VoterInterface::ACCESS_GRANTED : VoterInterface::ACCESS_DENIED;
    }
}
