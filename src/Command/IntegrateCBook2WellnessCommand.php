<?php

namespace App\Command;

use App\Entity\CBook\CBookMember;
use App\Entity\CBook\CBookOrganisation;
use App\Entity\CBook\CBookPerson;
use App\Entity\Wellness\WellnessEmployee;
use App\Entity\Wellness\WellnessOrganisation;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class IntegrateCBook2WellnessCommand extends Command
{
    protected static $defaultName = 'magenta:integration:cbook2wellness';

    private $registry;

    public function __construct(RegistryInterface $registry, $name = null)
    {
        parent::__construct($name);
        $this->registry = $registry;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('arg1');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        if ($input->getOption('option1')) {
            // ...
        }

        $cbookManager = $this->registry->getEntityManager('cbook');
        $wellnessManager = $this->registry->getEntityManager('wellness');

        $cbookOrgRepo = $this->registry->getRepository(CBookOrganisation::class, 'cbook');
        $wellnessOrgRepo = $this->registry->getRepository(WellnessOrganisation::class, 'wellness');

        $cbookMemberRepo = $this->registry->getRepository(CBookMember::class, 'cbook');
        $cbookPersonRepo = $this->registry->getRepository(CBookPerson::class, 'cbook');

        $wellnessEmployeeRepo = $this->registry->getRepository(WellnessEmployee::class, 'wellness');

        $cbookOrgs = $cbookOrgRepo->findBy(['linkedToWellness' => true, 'enabled' => true]);

        $io->note('Going over each CBOOK organisation');
        $i = 0;
        /** @var CBookOrganisation $cborg */
        foreach ($cbookOrgs as $cborg) {
            $i++;
            $io->note($i . '. ' . $cborg->getName());
            $io->note(sprintf('... %s is%slinked to Wellness. %s', $cborg->getName(), $cborg->isLinkedToWellness() ? ' ' : ' NOT ', $cborg->isLinkedToWellness() ? 'Start synchronisation' : 'Skipping'));
            if ($cborg->isLinkedToWellness()) {
                if (empty($wellnessOrgId = $cborg->getWellnessId())) {
                    $io->note('... Link Wellness Org since it is currently null');
                    if (empty($cborg->getRegNo())) {
                        $io->error('cbook org does not have reg no! Skipping this org');
                        continue;
                        /** @var WellnessOrganisation $wellnessOrg */
                    } elseif (empty($wellnessOrg = $wellnessOrgRepo->findOneBy(['regNo' => $cborg->getRegNo(), 'typeEmployer' => true]))) {
                        $io->error('Cannot locate Wellness Organisation with Reg No. ' . $cborg->getRegNo() . '. Skipping this org');
                        continue;
                    };
                    $wellnessOrgId = $wellnessOrg->getId();
                    $io->note('... Found Wellness Org with the same Reg No (' . $wellnessOrg->getRegNo() . '), this org has the ID of: ' . $wellnessOrgId . ' name:' . $wellnessOrg->getName());
                    $io->note('... Associate CBookOrg (' . $cborg->getId() . ') with WellnessOrg (' . $wellnessOrgId . ')');
                    $cborg->setWellnessId($wellnessOrgId);
                    $wellnessOrg->setCbookId($cborg->getId());
                    $wellnessOrg->setLinkedToCBook(true);

                    $cbookManager->persist($cborg);
                    $wellnessManager->persist($wellnessOrg);
                } else {
                    $io->note('... Already linked to Wellness Org (' . $wellnessOrgId . ')');
                    /** @var WellnessOrganisation $wellnessOrg */
                    if (empty($wellnessOrg = $wellnessOrgRepo->find($wellnessOrgId))) {
                        $io->error('... Wellness Org not found (' . $wellnessOrgId . ')');
                    } else {
                        $io->note('... Found Wellness Org (' . $wellnessOrgId . ')');
                        $io->note('... Found Wellness Employer (' . $wellnessOrg->getEmployer()->getId() . ')');

                        if ($wellnessOrg->getSynchronisedAt() < $wellnessOrg->getUpdatedAt() || $cborg->getSynchronisedAt() < $cborg->getUpdatedAt()) {
                            $io->note('... ... Synchronise Org (' . $wellnessOrgId . ')');
                            if ($wellnessOrg->getUpdatedAt() < $cborg->getUpdatedAt()) {
                                $io->note('... ... ... Update Wellness (' . $wellnessOrgId . ') with CBook Info (' . $cborg->getId() . ')');
                                $wellnessOrg->setCode($cborg->getCode());
                                $wellnessOrg->setRegNo($cborg->getRegNo());
                                $wellnessOrg->setEnabled($cborg->isEnabled());
                                $now = new \DateTime();
                                $wellnessOrg->setUpdatedAt($now);
                                $wellnessOrg->setSynchronisedAt($now);
                                $cborg->setSynchronisedAt($now);
                                $cbookManager->persist($cborg);
                                $wellnessManager->persist(($wellnessOrg));
                            } else {
                                $io->note('... ... ... Update CBook (' . $cborg->getId() . ') with Wellness Info (' . $wellnessOrgId . ')');
                                $cborg->setCode($wellnessOrg->getCode());
                                $cborg->setRegNo($wellnessOrg->getRegNo());
                                $cborg->setEnabled($wellnessOrg->isEnabled());
                                $now = new \DateTime();
                                $cborg->setSynchronisedAt($now);
                                $wellnessOrg->setSynchronisedAt($now);
                                $cborg->setUpdatedAt($now);
                                $cbookManager->persist($cborg);
                                $wellnessManager->persist(($wellnessOrg));
                            }
                        }
                        $io->note('... ... Synchronise CBook Members (' . $cborg->getId() . ')');
                        /** @var CBookMember $member */
                        foreach ($cborg->getIndividualMembers() as $member) {
                            $io->note('... ... ... Working on person (' . $member->getPerson()->getId() . ') ' . $member->getPerson()->getName());
                            if (empty($wellnessId = $member->getWellnessId())) {
                                $io->note('... ... ... ... wellnessId is empty -> Associate wellnessId of CBookMember with WellnessMember');
                                $io->note('... ... ... ... ... Looking for Wellness Member with the given wellnessOrgId and memberIdNumber (' . $member->getPerson()->getIdNumber() . ')');
                                /**
                                 * @var WellnessEmployee $wemployee
                                 */
                                $wemployee = $wellnessEmployeeRepo->findOneBy([
                                    'employer' => $wellnessOrg->getEmployer()->getId(),
                                    'idNumber' => $member->getPerson()->getIdNumber()
                                ]);

                                if (empty($wemployee)) {
                                    $io->note('... ... ... ... ... Wellness Member for this emlpoyer (' . $wellnessOrgId . ' cannot be found. Try to create one');
                                    $now = new \DateTime();
                                    $newWEmployee = new WellnessEmployee();
                                    $newWEmployee->setEmployer($wellnessOrg->getEmployer());

                                    $newWEmployee->setEnabled(true);
                                    $newWEmployee->setUpdatedDate($now);
                                    $newWEmployee->initiateEmployeeCode();
                                    $newWEmployee->initiatePinCode();
                                    $newWEmployee->setIdNumber($member->getPerson()->getIdNumber());
                                    $newWEmployee->setSynchronisedAt($now);
                                    $newWEmployee->setName($member->getPerson()->getName());
                                    $newWEmployee->setFirstname($member->getPerson()->getGivenName());
                                    $newWEmployee->setLastname($member->getPerson()->getFamilyName());
                                    $newWEmployee->setDob($member->getPerson()->getBirthDate());

                                    $newWEmployee->setCbookId($member->getId());
                                    $newWEmployee->setCbookEmployeeCode($member->getCode());
                                    $newWEmployee->setCbookPin($member->getPin());

                                    $wellnessManager->persist($newWEmployee);
                                    $wellnessManager->flush($newWEmployee);

                                    $member->setWellnessPin($newWEmployee->getPinCode());
                                    $member->setWellnessEmployeeCode($newWEmployee->getEmployeeCode());
                                    $member->setWellnessId($newWEmployee->getId());
                                    $member->setSynchronisedAt($now);
                                    $member->setUpdatedAt($now);

                                    $cbookManager->persist($member);
                                    continue;
                                }

                                $io->note('... ... ... ... ...  Associate CBookMember (' . $member->getId() . ') with WellnessEmployee (' . $wemployee->getId() . ')');
                                $wemployee->setCbookId($member->getId());
                                $member->setWellnessId($wemployee->getId());

                                $wellnessManager->persist($wemployee);
                                $cbookManager->persist($member);
                            } else {
                                if (empty($wemployee = $wellnessEmployeeRepo->find($wellnessId))) {
                                    $io->note('... ... ... ... wellnessId cannot be found -> WellnessMember must have been deleted. So, we shall delete cbook member too');
                                    $cbookManager->remove($member);
                                    continue;
                                }

                                // let's fix old data first
                                if (empty($member->getWellnessPin()) || empty($member->getWellnessPin())) {
                                    $io->note('... ... ... ... Fix empty Wellness PIN/Code when wellnessId (' . $wemployee->getId() . ': ' . $wemployee->getPinCode() . '/' . $wemployee->getEmployeeCode() . ') is present.');

                                    $member->setWellnessPin($wemployee->getPinCode());
                                    $member->setWellnessEmployeeCode($wemployee->getEmployeeCode());
                                    $member->setEnabled($wemployee->isEnabled());
                                    $cbookManager->persist($member);
                                    $cbookManager->flush($member);
                                }
                                // end fixing

                                if (empty($member->getSynchronisedAt()) || $member->getSynchronisedAt() < $member->getUpdatedAt() || $wemployee->getSynchronisedAt() < $wemployee->getUpdatedDate()) {
                                    $io->note('... ... ... ... Synchronise Members (' . $wellnessOrgId . ')');
                                    if ($wemployee->getUpdatedDate() < $member->getUpdatedAt()) {
                                        $io->note('... ... ... Update Wellness (' . $wemployee->getId() . ': ' . $wemployee->getName() . ') with CBook Info (' . $member->getId() . ': ' . $member->getPerson()->getName() . ')');
                                        $wemployee->setCbookPin($member->getPin());
                                        $wemployee->setEmployeeCode($member->getCode());
                                        $wemployee->setEnabled($member->isEnabled());

                                        $now = new \DateTime();
                                        $wemployee->setSynchronisedAt($now);
                                        $member->setSynchronisedAt($now);
                                        $wemployee->setUpdatedDate($now);
                                        $wellnessManager->persist($wemployee);
                                    } else {
                                        $io->note('... ... ... Update CBook (' . $member->getId() . ': ' . $member->getPerson()->getName() . ') with Wellness (' . $wemployee->getId() . ': ' . $wemployee->getName() . ')');

                                        $member->setWellnessPin($wemployee->getPinCode());
                                        $member->setWellnessEmployeeCode($wemployee->getEmployeeCode());
                                        $member->setEnabled($wemployee->isEnabled());

                                        $now = new \DateTime();
                                        $member->setSynchronisedAt($now);
                                        $wemployee->setSynchronisedAt($now);
                                        $member->setUpdatedAt($now);
                                        $cbookManager->persist($member);
                                        $wellnessManager->persist($wemployee);
                                    }
                                }
                            }
                        }
                        $io->note('... ... Synchronise Wellness Members (' . $wellnessOrgId . ') and only query Wellness with cbookId == null');
                        $orphanWellness = $wellnessEmployeeRepo->findBy([
                            'employer' => $wellnessOrg->getEmployer()->getId(),
                            'cbookId' => null
                        ]);
                        if(empty($orphanWellness)){
                            $orphanWellness = $wellnessEmployeeRepo->findBy([
                                'employer' => $wellnessOrg->getEmployer()->getId(),
                                'cbookPin' => null
                            ]);
                        }
                        /** @var WellnessEmployee $ow */
                        foreach ($orphanWellness as $ow) {
                            $person = null;
                            $io->note('... ... ... Working on WellnessEmployee (' . $ow->getId() . ': ' . $ow->getName() . ': ' . $ow->getIdNumber() . ')');
                            if (empty($email = $ow->getEmailAddress())) {
                                $person = $cbookPersonRepo->findOneBy(['idNumber' => $ow->getIdNumber()]);
                            } else {
                                if (empty($person)) {
                                    $person = $cbookPersonRepo->findOneBy(['email' => $ow->getEmailAddress()]);
                                }
                            }

                            if (empty($person)) {
                                $io->note('... ... ... CBookPerson NOT found for WellnessEmployee (' . $ow->getId() . ': ' . $ow->getName() . '). Try to create one');
                                $person = new CBookPerson();
                                $person->setEnabled(true);
                                $person->setName($ow->getName());
                                $person->setFamilyName($ow->getLastname());
                                $person->setGivenName($ow->getFirstname());
                                $person->setIdNumber($ow->getIdNumber());
                                $person->setBirthDate($ow->getDob());
                                $cbookManager->persist($person);
                                $cbookManager->flush($person);
                            }

                            $io->note('... ... ... CBookPerson (' . $person->getId() . ': ' . $person->getName() . ') is ready for WellnessEmployee (' . $ow->getId() . ': ' . $ow->getName() . ')');
                            $member = $person->getCBookMemberOfCBookOrganisation($cborg);
                            if (!empty($member)) {
                                $io->note('... ... ... CBookMember (' . $member->getId() . ') found for CBookPerson (' . $person->getId() . ': ' . $person->getName() . ')');
                            } else {
                                $io->note('... ... ... CBookMember NOT found for CBookPerson (' . $person->getId() . ': ' . $person->getName() . '). Try to create one.');
                                $member = new CBookMember();
                                $member->setEnabled($ow->isEnabled());
                                $member->setPerson($person);
                                $member->setOrganization($cborg);
                                $cbookManager->persist($member);
                                $cbookManager->flush($member);
                            }

                            $io->note('... ... ... CBookMember - associate CBookMember with WellnessEmployee');
                            $member->setWellnessId($ow->getId());
                            $member->setWellnessEmployeeCode($ow->getEmployeeCode());
                            $member->setWellnessPin($ow->getPinCode());
                            $member->setEnabled($ow->isEnabled());

                            $ow->setCbookId($member->getId());
                            $ow->setCbookPin($member->getPin());
                            $ow->setCbookEmployeeCode($member->getCode());

                            $now = new \DateTime();
                            $member->setUpdatedAt($now);
                            $member->setSynchronisedAt($now);
                            $ow->setSynchronisedAt($now);
                            $ow->setUpdatedDate($now);
                            $wellnessManager->persist($ow);
                            $cbookManager->persist($member);
                        }

                        if (count($orphanWellness) === 0) {
                            // clean up CBook Members

                        }
                    }
                }
            } else {
                if (!empty($wellnessOrgId = $cborg->getWellnessId())) {
                    if ($cborg->getSynchronisedAt() < $cborg->getUpdatedAt()) {
                        if (!empty($wellnessOrg = $wellnessOrgRepo->find($wellnessOrgId))) {
                            if ($wellnessOrg->isLinkedToCBook()) {
                                $wellnessOrg->setLinkedToCBook(false);
                                $now = new \DateTime();
                                $wellnessOrg->setSynchronisedAt($now);
                                $cborg->setSynchronisedAt($now);
                                $wellnessManager->persist($wellnessOrg);
                                $cbookManager->persist($cborg);
                            }
                        };
                    }
                }
            }
        }

        $wellnessManager->flush();
        $cbookManager->flush();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');
    }
}
