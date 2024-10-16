<?php

require_once __DIR__ . '/ifoam_newsletter_response.php';

class IfoamNewsletterApi {
  public $expectedFields = [
    'first_name',
    'last_name',
    'email',
    'organization',
    'job_title',
    'receive_newsletter',
    'receive_press_releases',
    'api_key',
  ];

  public function process(array $params): IfoamNewsletterResponse {
    $response = new IfoamNewsletterResponse();

    try {
      $contact = $this->getContactByEmail($params['email']);
      if (!$contact) {
        $contact = $this->createContact($params);
      }

      $this->subscribeContactToNewsletter($contact, $params);

      $response->status = 'success';
      $response->message = '';
    }
    catch (Exception $e) {
      $response->status = 'error';
      $response->message = $e->getMessage();
    }

    return $response;
  }

  private function getContactByEmail(string $email): ?array {
    return \Civi\Api4\Contact::get(FALSE)
      ->addSelect('id')
      ->addJoin('Email AS email', 'INNER', ['id', '=', 'email.contact_id'], ['email.is_primary', '=', 1])
      ->addWhere('is_deleted', '=', FALSE)
      ->addWhere('is_deceased', '=', FALSE)
      ->addWhere('contact_type', '=', 'Individual')
      ->addWhere('email.email', '=', $email)
      ->execute()
      ->first();
  }

  private function createContact(array $params): array {
    $contact = \Civi\Api4\Contact::create(FALSE)
      ->addValue('contact_type', 'Individual')
      ->addValue('first_name', $params['first_name'])
      ->addValue('last_name', $params['last_name'])
      ->addValue('job_title', $params['job_title'])
      ->addValue('Newsletter_subscriptions.Organisation_name_as_submitted', $params['organization'])
      ->execute()
      ->first();

    \Civi\Api4\Email::create(FALSE)
      ->addValue('contact_id', $contact['id'])
      ->addValue('location_type_id', 2)
      ->addValue('email', $params['email'])
      ->execute();

    return $contact;
  }

  public function subscribeContactToNewsletter(array $contact, array $params) {
    if ($params['receive_newsletter'] == TRUE) {
      \Civi\Api4\Contact::update(FALSE)
        ->addValue('Newsletter_subscriptions.IFOAM_EU_newsletter', 1)
        ->addWhere('id', '=', $contact['id'])
        ->execute();
    }

    if ($params['receive_press_releases'] == TRUE) {
      \Civi\Api4\Contact::update(FALSE)
        ->addValue('Newsletter_subscriptions.IFOAM_EU_press_release', 1)
        ->addWhere('id', '=', $contact['id'])
        ->execute();
    }
  }
}