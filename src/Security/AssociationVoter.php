<?php

namespace App\Security;

use App\Entity\Associations;
use App\Entity\Users;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class AssociationVoter extends Voter
{

    const EDIT = 'edit';
    const ANONYMIZE = 'anonymize';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::EDIT, self::ANONYMIZE])) {
            return false;
        }

        if (!$subject instanceof Associations) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof Users) {
            return false;
        }
        if ($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        }

        return $user === $subject->getUsers() ? true : false;
    }
}