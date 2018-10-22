<?php

namespace App\Entity\CBook;

use Bean\Component\Organization\IoC\CBookMemberContainerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception\NotNullConstraintViolationException;
use Doctrine\ORM\Mapping as ORM;
use Magenta\Bundle\CBookModelBundle\Entity\Classification\Tag;
use Magenta\Bundle\CBookModelBundle\Entity\User\User;

/**
 * @ORM\Entity()
 * @ORM\Table(name="person__person")
 */
class CBookPerson extends CBookThing
{

    public function __construct()
    {
        $this->individualMembers = new ArrayCollection();
    }

    public function initiateUser($emailRequired = true)
    {
        if (empty($this->user)) {
            $this->user = new User();
        }
        $this->user->setEnabled(true);

        $this->user->addRole(User::ROLE_POWER_USER);
        if ($emailRequired) {
            if (empty($this->email)) {
                if (empty($this->user->getEmail())) {
//					throw new \InvalidArgumentException('person email is null');
                    $today = new \DateTime();
                    if (empty($this->name)) {
                        $this->name = 'random-' . $today->getTimestamp();
                    }
                    $this->email = str_replace(' ', '-', $this->name) . '_' . $today->format('dmY') . '@no-email.com';
                } else {
                    $this->email = $this->user->getEmail();
                }
            }
        }
        $username = '';
        if (!empty($this->givenName)) {
            $username .= Tag::slugify(trim($this->givenName));
            $username .= '-';
        }
        if (!empty($this->familyName)) {
            $username .= Tag::slugify(trim($this->familyName));
            $username .= '-';
        }
        $now = new \DateTime();
        $emailName = explode('@', $this->email)[0];
        $username .= $emailName;
        $username .= $now->format('-dmY');
        $this->user->setUsername($username);
        $this->user->setEmail($this->email);
        if (empty($this->user->getPlainPassword()) && empty($this->user->getPassword())) {
            $this->user->setPlainPassword($this->email);
        }
        $this->user->setPerson($this);

        return $this->user;
    }

    public function getCBookMemberOfCBookOrganisation(CBookOrganisation $org)
    {
        /** @var CBookMember $m */
        foreach ($this->individualMembers as $m) {
            if ($m->getOrganization() === $org) {
                return $m;
            }
        }

        return null;
    }

    /**
     * @var Collection
     * @ORM\OneToMany(targetEntity="App\Entity\CBook\CBookMember", mappedBy="person")
     */
    protected $individualMembers;

    public function addCBookMember(CBookMember $member)
    {
        $this->individualMembers->add($member);
        $member->setPerson($this);
    }

    public function removeCBookMember(CBookMember $member)
    {
        $this->individualMembers->removeElement($member);
        $member->setPerson(null);
    }

    /**
     * var User|null
     * ORM\OneToOne(targetEntity="Magenta\Bundle\CBookModelBundle\Entity\User\User", mappedBy="person")
     */
    protected $user;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime",nullable=true)
     */
    protected $birthDate;

    /**
     * @var string|null
     * @ORM\Column(type="string",nullable=true)
     */
    protected $idNumber;


    /**
     * @return null|string
     */
    public function getIdNumber(): ?string
    {
        return $this->idNumber;
    }

    /**
     * @param null|string $idNumber
     */
    public function setIdNumber(?string $idNumber): void
    {
        $this->idNumber = $idNumber;
    }

    /**
     * @return Collection
     */
    public function getCBookMembers(): Collection
    {
        return $this->individualMembers;
    }

    /**
     * @param Collection $individualMembers
     */
    public function setCBookMembers($individualMembers): void
    {
        $this->individualMembers = $individualMembers;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     */
    public function setBirthDate(?\DateTime $birthDate): void
    {
        $this->birthDate = $birthDate;
    }
}
