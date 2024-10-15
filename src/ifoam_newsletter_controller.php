<?php

require_once __DIR__ . '/ifoam_newsletter_api.php';
require_once __DIR__ . '/ifoam_newsletter_response.php';

class IfoamNewsletterController {

  public static function process(): IfoamNewsletterResponse {
    $api = new IfoamNewsletterApi();

    [$isValid, $errorMessage, $params] = self::getPostedData($api->expectedFields);
    if ($isValid) {
      return $api->process($params);
    }
    else {
      $response = new IfoamNewsletterResponse();
      $response->status = 'error';
      $response->message = $errorMessage;

      return $response;
    }
  }

  private static function getPostedData(array $expectedFields): array {
    $params = [];

    $postedData = file_get_contents('php://input');

    if (empty($postedData)) {
      return [FALSE, 'POST contains no data', []];
    }

    $data = json_decode($postedData, TRUE);
    if (is_null($data)) {
      return [FALSE, 'Cannot decode POSTed data', []];
    }

    foreach ($expectedFields as $expectedField) {
      if (!isset($data[$expectedField])) {
        return [FALSE, "Missing field '$expectedField' in POSTed data", []];
      }

      // store a sanitized version of the param
      //$sanitizeFilter = ($expectedField == 'email') ? FILTER_SANITIZE_EMAIL : FILTER_SANITIZE_STRING;
      //$params[$expectedField] = filter_input(INPUT_POST, $expectedField, $sanitizeFilter);
      $params[$expectedField] = $data[$expectedField];
    }

    //if (!is_string(IFOAM_NEWSLETTER_API_KEY)) {
    //  return [FALSE, 'Cannot validate API KEY: not defined on server.', []];
    //}
echo "\n\n\n" . $params['api_key'] . "\n\n";
    var_dump($params);
    // check the api_key
    if ($params['api_key'] != IFOAM_NEWSLETTER_API_KEY) {
      return [FALSE, 'API KEY is not valid.', []];
    }

    // OK, valid and sanitized data!
    return [TRUE, '', $params];
  }
}
