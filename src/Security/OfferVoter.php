<?php

namespace App\Security;

use App\Entity\Offers;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class OfferVoter extends Voter
{

    const CREATE = 'create';
    const EDIT = 'edit';
    const ANONYMIZE = 'anonymize';

    private $security;
    private $requestStack;
    private $route = '';

    public function __construct(Security $security, RequestStack $requestStack)
    {
        $this->security = $security;
        $this->requestStack = $requestStack;
        $this->route = $this->requestStack->getCurrentRequest()->get('_route');
    }

    protected function supports(string $attribute, $subject): bool
    {
        if (!in_array($attribute, [self::CREATE, self::EDIT, self::ANONYMIZE])) {
            return false;
        }

        if (!$subject instanceof Offers) {
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

        switch ($attribute) {
            case self::CREATE:
                return $this->canCreate($user);
                break;
            case self::EDIT:
            case self::ANONYMIZE:
                return $this->canEditOrAnonymize($subject, $user);
                break;
            default:
                return false;
                break;
        }
    }

    private function canCreate($user): bool
    {
        switch ($this->route) {
            case 'new_offer':
                return $user->getAssociations()->count() > 0;
                break;
            default:
                return true; // everyone can create request
                break;
        }
    }

    private function canEditOrAnonymize($subject, $user): bool
    {
        /** @var Offers $offer */
        $offer = $subject;

        $isARequest = $offer->isARequest();

        if ((in_array($this->route, ['edit_offer', 'anonymize_offer']) && $isARequest) || in_array($this->route, ['edit_request', 'anonymize_request']) && !$isARequest) {
            return false;
        }

        if (!$isARequest) {

            if ($user === $offer->getAssociations()->getUsers()) {
                return true;
            }
        }

        return $user === $offer->getUsers();
    }
}
