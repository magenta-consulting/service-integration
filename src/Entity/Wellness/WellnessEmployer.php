<?php

namespace App\Entity\Wellness;

use App\Entity\Wellness\WellnessOrganisation;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Criteria;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity)
 * @ORM\Table(name="mhs__employer")
 */
class WellnessEmployer {
    const PAYMENT_PRE = 'pre';
    const PAYMENT_POST = 'post';
 

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer",options={"unsigned":true})
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     */
    private $id;

    function __construct() {
        $this->paymentMode = self::PAYMENT_PRE;

        $this->orderEnabled            = true;
        $this->employeeBenefitsEnabled = true;
        $this->payChannelPartnerOnly   = false;
    }

    /**
     * @param $idNumber
     *
     * @return WellnessEmployee
     */
    public function findOneEmployeeByIdNumber($idNumber) {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("idNumber", $idNumber));

        return $this->employees->matching($criteria)->first();
    }

    public function getCommunityOffers() {
        $criteria = Criteria::create()->where(Criteria::expr()->eq("communityOffer", true));

        return $this->products->matching($criteria);
    }

    /**
     * @return ArrayCollection
     */
    public function getDuplicateEmployeeImport() {
        if(empty($this->duplicateEmployeeImport)) {
            $this->duplicateEmployeeImport = new ArrayCollection();
        }

        return $this->duplicateEmployeeImport;
    }

    /**
     * @var ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\Wellness\WellnessEmployee", mappedBy="employer", cascade={"all"}, orphanRemoval=true)
     */
    private $employees;

    /**
     * @param WellnessEmployee $employee
     *
     * @return WellnessEmployee|null
     */
    public function isEmployeeExistent(WellnessEmployee $employee) {
        $idNumber = $employee->getIdNumber();
        $memId    = $employee->getMemberId();
        if( ! empty($idNumber) || ! empty($memId)) {
            $criteria = Criteria::create();
            if( ! empty($memId)) {
                $criteria->andWhere(
                    Criteria::expr()->eq("memberId", $memId)
                );
            }
            if( ! empty($idNumber)) {
                $criteria->andWhere(
                    Criteria::expr()->eq("idNumber", $idNumber));
            };
            $employeeSearch = $this->employees->matching($criteria);
//            $employeeSearch = $this->employees->filter(
//            /** @var ChannelPartnerEmployee $entry */
//                function ($entry) use ($idNumber) {
//                    return $entry->getIdNumber() === $idNumber;
//                }
//            );
            if($employeeSearch->count() > 0) {
                $this->getDuplicateEmployeeImport()->add($employee);

                return $employeeSearch->first();
            }
        }

        return null;
    }

    /**
     * @param WellnessEmployee $employee
     */
    public function addEmployee($employee) {
        $this->employees->add($employee);
        $employee->setEmployer($this);
    }

    /**
     * @param WellnessEmployee $employee
     */
    public function removeEmployee($employee) {
        $this->employees->removeElement($employee);
        $employee->setEmployer(null);
    }

    /**
     * @var WellnessOrganisation $WellnessOrganisation
     * @ORM\OneToOne(targetEntity="App\Entity\Wellness\WellnessOrganisation", inversedBy="employer", cascade={"persist","merge"})
     * @ORM\JoinColumn(name="id_organisation", referencedColumnName="id")
     */
    private $organisation;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $club = false;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $cardPaymentEnabled = false;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":false})
     */
    private $payChannelPartnerOnly;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $employeeBenefitsEnabled;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", options={"default":true})
     */
    private $orderEnabled;

    /**
     * @var string
     * @ORM\Column(type="string", length=256,nullable=true)
     */
    private $prefix;

    /**
     * @var string
     * @ORM\Column(type="string",name="payment_mode",length=256,options={"default":"pre"})
     */
    private $paymentMode;

    /**
     * @var string
     * @ORM\Column(length=150, nullable=true)
     */
    protected $agencyLicenseNo;
////////////////////////////////////////////////////////////////////////
    /**
     *
     */

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return ArrayCollection
     */
    public function getPayments() {
        return $this->payments;
    }

    /**
     * @param ArrayCollection $payments
     */
    public function setPayments($payments) {
        $this->payments = $payments;
    }

    /**
     * @return ArrayCollection
     */
    public function getOrders() {
        return $this->orders;
    }

    /**
     * @param ArrayCollection $orders
     */
    public function setOrders($orders) {
        $this->orders = $orders;
    }

    /**
     * @return ArrayCollection
     */
    public function getSalesAgents() {
        return $this->salesAgents;
    }

    /**
     * @param ArrayCollection $salesAgents
     */
    public function setSalesAgents($salesAgents) {
        $this->salesAgents = $salesAgents;
    }

    /**
     * @return ArrayCollection
     */
    public function getEmployees() {
        return $this->employees;
    }

    /**
     * @param ArrayCollection $employees
     */
    public function setEmployees($employees) {
        $this->employees = $employees;
    }

    /**
     * @return WellnessOrganisation
     */
    public function getWellnessOrganisation() {
        return $this->organisation;
    }

    /**
     * @param WellnessOrganisation $WellnessOrganisation
     */
    public function setWellnessOrganisation($WellnessOrganisation) {
        $this->organisation = $WellnessOrganisation;
    }

    /**
     * @return bool
     */
    public function isEmployeeBenefitsEnabled() {
        return $this->employeeBenefitsEnabled;
    }

    /**
     * @param bool $employeeBenefitsEnabled
     */
    public function setEmployeeBenefitsEnabled($employeeBenefitsEnabled) {
        $this->employeeBenefitsEnabled = $employeeBenefitsEnabled;
    }

    /**
     * @return string
     */
    public function getPaymentMode() {
        return $this->paymentMode;
    }

    /**
     * @param string $paymentMode
     */
    public function setPaymentMode($paymentMode) {
        $this->paymentMode = $paymentMode;
    }

    /**
     * @return string
     */
    public function getAgencyLicenseNo() {
        return $this->agencyLicenseNo;
    }

    /**
     * @param string $agencyLicenseNo
     */
    public function setAgencyLicenseNo($agencyLicenseNo) {
        $this->agencyLicenseNo = $agencyLicenseNo;
    }

    /**
     * @return ArrayCollection
     */
    public function getChannelPartners() {
        return $this->channelPartners;
    }

    /**
     * @param ArrayCollection $channelPartners
     */
    public function setChannelPartners($channelPartners) {
        $this->channelPartners = $channelPartners;
    }

    /**
     * @return ArrayCollection
     */
    public function getProducts() {
        return $this->products;
    }

    /**
     * @param ArrayCollection $products
     */
    public function setProducts($products) {
        $this->products = $products;
    }

    /**
     * @return ArrayCollection
     */
    public function getVoucherProducts() {
        return $this->voucherProducts;
    }

    /**
     * @param ArrayCollection $voucherProducts
     */
    public function setVoucherProducts($voucherProducts) {
        $this->voucherProducts = $voucherProducts;
    }

    /**
     * @return ArrayCollection
     */
    public function getNonVoucherProducts() {
        return $this->nonVoucherProducts;
    }

    /**
     * @param ArrayCollection $nonVoucherProducts
     */
    public function setNonVoucherProducts($nonVoucherProducts) {
        $this->nonVoucherProducts = $nonVoucherProducts;
    }

    /**
     * @return bool
     */
    public function isOrderEnabled() {
        return $this->orderEnabled;
    }

    /**
     * @param bool $orderEnabled
     */
    public function setOrderEnabled($orderEnabled) {
        $this->orderEnabled = $orderEnabled;
    }

    /**
     * @return bool
     */
    public function isPayChannelPartnerOnly() {
        return $this->payChannelPartnerOnly;
    }

    /**
     * @param bool $payChannelPartnerOnly
     */
    public function setPayChannelPartnerOnly($payChannelPartnerOnly) {
        $this->payChannelPartnerOnly = $payChannelPartnerOnly;
    }

    /**
     * @return ArrayCollection
     */
    public function getBenefits() {
        return $this->benefits;
    }

    /**
     * @param ArrayCollection $benefits
     */
    public function setBenefits($benefits) {
        $this->benefits = $benefits;
    }

    /**
     * @return ArrayCollection
     */
    public function getTransactions() {
        return $this->transactions;
    }

    /**
     * @param ArrayCollection $transactions
     */
    public function setTransactions($transactions) {
        $this->transactions = $transactions;
    }

    /**
     * @return bool
     */
    public function isCardPaymentEnabled() {
        return $this->cardPaymentEnabled;
    }

    /**
     * @param bool $cardPaymentEnabled
     */
    public function setCardPaymentEnabled($cardPaymentEnabled) {
        $this->cardPaymentEnabled = $cardPaymentEnabled;
    }

    /**
     * @return bool
     */
    public function isClub() {
        return $this->club;
    }

    /**
     * @param bool $club
     */
    public function setClub($club) {
        $this->club = $club;
    }

    /**
     * @return string
     */
    public function getPrefix() {
        return $this->prefix;
    }

    /**
     * @param string $prefix
     */
    public function setPrefix($prefix) {
        $this->prefix = $prefix;
    }
}