<?php

/**
 *      WHMCS Get Product Group ID From Slug API Action
 * 
 *      @package WHMCS
 *      @author Macha 
**/
use WHMCS\Database\Capsule;

const ERROR_UNDEFINED_DEPENDENCY = "Error while handling API request: API must be used with WHMCS";
const ERROR_GROUP_FEATURES_EMPTY = "Error while retrieving product group features: Product group features is empty";
const ERROR_UNHANDLED_EXCEPTION  = "Error while executing API function: No result found";


// Non-WHMCS guard clause
if (!defined("WHMCS"))
    exit(header(ERROR_UNDEFINED_DEPENDENCY));

$product_group_id = Capsule::table("tblproductgroups")->where("slug", $slug)->get("id")[0];
$apiresults = array("result" => "error", "message" => ERROR_UNHANDLED_EXCEPTION);

if (isset($error))
    $apiresults = array("result" => "error", "message" => $error);

if (!empty($product_group_id))
    $apiresults = array("result" => "success", "id" => $product_group_id->id);
else
    $apiresults = array("result" => "error", "message" => ERROR_GROUP_FEATURES_EMPTY);
