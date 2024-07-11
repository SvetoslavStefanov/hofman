<?php

namespace App\Services;

use HubSpot\Client\Files\ApiException;
use HubSpot\Factory;
use HubSpot\Client\Crm\Contacts\Api\BasicApi as ContactsApi;
use HubSpot\Client\Crm\Companies\Api\BasicApi as CompaniesApi;
use HubSpot\Client\Crm\Deals\Api\BasicApi as DealsApi;
use HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInput;
use HubSpot\Client\Crm\Companies\Model\SimplePublicObjectInput as CompanyInput;
use HubSpot\Client\Crm\Deals\Model\SimplePublicObjectInput as DealInput;
use HubSpot\Client\Crm\Associations\Model\PublicAssociation;
use HubSpot\Client\Crm\Search\Model\Filter;
use HubSpot\Client\Crm\Search\Model\FilterGroup;
use HubSpot\Client\Crm\Search\Model\PublicObjectSearchRequest;


class HubSpotService {
  protected $hubSpot;

  public function __construct() {
    $this->hubSpot = Factory::createWithAccessToken(env('HUBSPOT_API_KEY'));
  }

  /**
   * @throws \Exception
   */
  public function createContact(array $data): string {
    $contactId = $this->findContactByEmail($data['email'] ?? '');

    if ($contactId) {
      return $contactId;
    }

    try {
      $response = $this->hubSpot->crm()->contacts()->basicApi()->create([
        'properties' => $data
      ]);
      return $response->getId();
    } catch (ApiException $e) {
      throw new \Exception('Failed to create contact: ' . $e->getMessage());
    }
  }

  public function findContactByEmail($email): ?string {
    try {
      $response = $this->hubSpot->crm()->contacts()->searchApi()->doSearch([
        'filterGroups' => [
          [
            'filters' => [
              [
                'propertyName' => 'email',
                'operator' => 'EQ',
                'value' => $email
              ]
            ]
          ]
        ],
        'properties' => ['email'],
        'limit' => 1
      ]);

      if (count($response->getResults()) > 0) {
        return $response->getResults()[0]->getId();
      }

      return null;
    } catch (\Exception $e) {
      throw new \Exception('Failed to find contact by email: ' . $e->getMessage());
    }
  }

  /**
   * @throws \Exception
   */
  public function createDeal(array $data): string {
    try {
      $response = $this->hubSpot->crm()->deals()->basicApi()->create([
        'properties' => $data
      ]);
      return $response->getId();
    } catch (ApiException $e) {
      throw new \Exception('Failed to create deal: ' . $e->getMessage());
    }
  }
}
