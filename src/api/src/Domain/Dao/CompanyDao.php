<?php
/*
 * This file has been automatically generated by TDBM.
 * You can edit this file as it will not be overwritten.
 */

declare(strict_types=1);

namespace App\Domain\Dao;

use App\Domain\Dao\Generated\BaseCompanyDao;
use App\Domain\Model\Company;
use App\Domain\Model\Filter\CompaniesFilters;
use App\Domain\Throwable\Exists\CompanyWithNameExists;
use App\Domain\Throwable\Invalid\InvalidCompaniesFilters;
use App\Domain\Throwable\Invalid\InvalidCompany;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use TheCodingMachine\TDBM\ResultIterator;
use TheCodingMachine\TDBM\TDBMService;

/**
 * The CompanyDao class will maintain the persistence of Company class into the companies table.
 */
class CompanyDao extends BaseCompanyDao
{
    private ValidatorInterface $validator;

    public function __construct(TDBMService $tdbmService, ValidatorInterface $validator)
    {
        $this->validator = $validator;
        parent::__construct($tdbmService);
    }

    /**
     * @throws InvalidCompany
     */
    public function save(Company $company): void
    {
        $violations = $this->validator->validate($company);
        InvalidCompany::throwException($violations);

        parent::save($company);
    }

    /**
     * @throws CompanyWithNameExists
     */
    public function mustNotFindOneByName(string $name, ?string $id = null): void
    {
        $company = $this->findOneByName($name);

        if ($company === null) {
            return;
        }

        if ($id !== null && $company->getId() === $id) {
            return;
        }

        throw new CompanyWithNameExists($name);
    }

    /**
     * @return Company[]|ResultIterator
     *
     * @throws InvalidCompaniesFilters
     */
    public function search(CompaniesFilters $filters): ResultIterator
    {
        $violations = $this->validator->validate($filters);
        InvalidCompaniesFilters::throwException($violations);

        return $this->find(
            ['name LIKE :search OR website LIKE :search'],
            [
                'search' => '%' . $filters->getSearch() . '%',
            ],
            $filters->getSortBy() . ' ' . $filters->getSortOrder()
        );
    }
}
