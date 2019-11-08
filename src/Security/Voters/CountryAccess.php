<?php

declare(strict_types=1);

namespace App\Security\Voters;

use App\Entity\Country;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;
use Symfony\Component\Security\Core\Security;
use LogicException;

class CountryAccess extends Voter
{
    public const VIEW = 'view';
    public const EDIT = 'edit';

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }
    
    protected function supports($attribute, $subject): bool
    {
        if (!in_array($attribute, [self::VIEW, self::EDIT], true)) {
            return false;
        }

        if (!$subject instanceof Country) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted('ROLE_SUPER_ADMIN')) {
            return true;
        }

        /** @var Country $country */
        $country = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($country, $user);
            case self::EDIT:
                return $this->canEdit($country, $user);
        }

        throw new LogicException('This code should not be reached!');
    }

    private function canView(Country $country, User $user): bool
    {
        return true;
    }

    private function canEdit(Country $country, User $user): bool
    {
        return in_array('ROLE_USER', $user->getRoles(), true);
    }

}
