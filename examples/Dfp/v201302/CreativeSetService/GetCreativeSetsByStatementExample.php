<?php
/**
 * This code example gets all creative sets for a master creative. To create
 * a creative set, run CreateCreativeSetExample.php.
 *
 * Tags: CreativeSetService.getCreativeSetsByStatement
 *
 * PHP version 5
 *
 * Copyright 2013, Google Inc. All Rights Reserved.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * @package    GoogleApiAdsDfp
 * @subpackage v201302
 * @category   WebServices
 * @copyright  2013, Google Inc. All Rights Reserved.
 * @license    http://www.apache.org/licenses/LICENSE-2.0 Apache License,
 *             Version 2.0
 * @author     Paul Rashidi
 */
error_reporting(E_STRICT | E_ALL);

// You can set the include path to src directory or reference
// DfpUser.php directly via require_once.
// $path = '/path/to/dfp_api_php_lib/src';
$path = dirname(__FILE__) . '/../../../../src';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

require_once 'Google/Api/Ads/Dfp/Lib/DfpUser.php';
require_once dirname(__FILE__) . '/../../../Common/ExampleUtils.php';
require_once 'Google/Api/Ads/Common/Util/MapUtils.php';

try {
  // Get DfpUser from credentials in "../auth.ini"
  // relative to the DfpUser.php file's directory.
  $user = new DfpUser();

  // Log SOAP XML request and response.
  $user->LogDefaults();

  // Get the CreativeSetService.
  $creativeSetService = $user->GetService('CreativeSetService', 'v201302');

  $masterCreativeID = 'INSERT_MASTER_CREATIVE_ID_HERE';

  // Create bind variables.
  $vars = MapUtils::GetMapEntries(
      array('masterCreativeId' => new NumberValue($masterCreativeID)));

  // Create a statement object to only select creative sets that have the given
  // master creative.
  $filterStatement =
      new Statement("WHERE masterCreativeId = :masterCreativeId LIMIT 500",
          $vars);

  // Get creative sets by statement.
  $page = $creativeSetService->getCreativeSetsByStatement($filterStatement);

  // Display results.
  if (isset($page->results)) {
    $i = $page->startIndex;
    foreach ($page->results as $creativeSet) {
      printf ("A creative set with ID '%s', name '%s', master creative ID '%s' "
          . ", and companion creativeID(s) {%s} was found.\n",
          $creativeSet->id, $creativeSet->name, $creativeSet->masterCreativeId,
          join(',', $creativeSet->companionCreativeIds));
       $i++;
    }
  }

  print 'Number of results found: ' . $page->totalResultSetSize . "\n";
} catch (OAuth2Exception $e) {
  ExampleUtils::CheckForOAuth2Errors($e);
} catch (ValidationException $e) {
  ExampleUtils::CheckForOAuth2Errors($e);
} catch (Exception $e) {
  print $e->getMessage() . "\n";
}

