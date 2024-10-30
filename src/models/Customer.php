<?php


namespace models;

use config\Database;

class Customer extends Model
{

    public function __construct(
        private int $customer_id,
        private ?string $cust_first_name = null,
        private ?string $cust_last_name = null,
        private ?string $cust_street_address = null,
        private ?string $cust_postal_code = null,
        private ?string $cust_city = null,
        private ?string $cust_state = null,
        private ?string $cust_country = null,
        private ?string $phone_numbers = null,
        private ?string $nls_language = null,
        private ?string $nls_territory = null,
        private ?float $credit_limit = null,
        private ?string $cust_email = null,
        private ?int $account_mgr_id = null,
        private ?string $cust_geo_location = null,
        private ?string $date_of_birth = null,
        private ?string $marital_status = null,
        private ?string $gender = null,
        private ?string $income_level = null
    ) {
    }


    protected static $table = 'customers';




    public function getCustomerId(): int
    {
        return $this->customer_id;
    }

    public function setCustomerId(int $customer_id): void
    {
        $this->customer_id = $customer_id;
    }

    public function getCustFirstName(): ?string
    {
        return $this->cust_first_name;
    }

    public function setCustFirstName(?string $cust_first_name): void
    {
        $this->cust_first_name = $cust_first_name;
    }

    public function getCustLastName(): ?string
    {
        return $this->cust_last_name;
    }

    public function setCustLastName(?string $cust_last_name): void
    {
        $this->cust_last_name = $cust_last_name;
    }

    public function getCustStreetAddress(): ?string
    {
        return $this->cust_street_address;
    }

    public function setCustStreetAddress(?string $cust_street_address): void
    {
        $this->cust_street_address = $cust_street_address;
    }

    public function getCustPostalCode(): ?string
    {
        return $this->cust_postal_code;
    }

    public function setCustPostalCode(?string $cust_postal_code): void
    {
        $this->cust_postal_code = $cust_postal_code;
    }

    public function getCustCity(): ?string
    {
        return $this->cust_city;
    }

    public function setCustCity(?string $cust_city): void
    {
        $this->cust_city = $cust_city;
    }

    public function getCustState(): ?string
    {
        return $this->cust_state;
    }

    public function setCustState(?string $cust_state): void
    {
        $this->cust_state = $cust_state;
    }

    public function getCustCountry(): ?string
    {
        return $this->cust_country;
    }

    public function setCustCountry(?string $cust_country): void
    {
        $this->cust_country = $cust_country;
    }

    public function getPhoneNumbers(): ?string
    {
        return $this->phone_numbers;
    }

    public function setPhoneNumbers(?string $phone_numbers): void
    {
        $this->phone_numbers = $phone_numbers;
    }

    public function getNlsLanguage(): ?string
    {
        return $this->nls_language;
    }

    public function setNlsLanguage(?string $nls_language): void
    {
        $this->nls_language = $nls_language;
    }

    public function getNlsTerritory(): ?string
    {
        return $this->nls_territory;
    }

    public function setNlsTerritory(?string $nls_territory): void
    {
        $this->nls_territory = $nls_territory;
    }

    public function getCreditLimit(): ?float
    {
        return $this->credit_limit;
    }

    public function setCreditLimit(?float $credit_limit): void
    {
        $this->credit_limit = $credit_limit;
    }

    public function getCustEmail(): ?string
    {
        return $this->cust_email;
    }

    public function setCustEmail(?string $cust_email): void
    {
        $this->cust_email = $cust_email;
    }

    public function getAccountMgrId(): ?int
    {
        return $this->account_mgr_id;
    }

    public function setAccountMgrId(?int $account_mgr_id): void
    {
        $this->account_mgr_id = $account_mgr_id;
    }

    public function getCustGeoLocation(): ?string
    {
        return $this->cust_geo_location;
    }

    public function setCustGeoLocation(?string $cust_geo_location): void
    {
        $this->cust_geo_location = $cust_geo_location;
    }

    public function getDateOfBirth(): ?string
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?string $date_of_birth): void
    {
        $this->date_of_birth = $date_of_birth;
    }

    public function getMaritalStatus(): ?string
    {
        return $this->marital_status;
    }

    public function setMaritalStatus(?string $marital_status): void
    {
        $this->marital_status = $marital_status;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): void
    {
        $this->gender = $gender;
    }

    public function getIncomeLevel(): ?string
    {
        return $this->income_level;
    }

    public function setIncomeLevel(?string $income_level): void
    {
        $this->income_level = $income_level;
    }

}