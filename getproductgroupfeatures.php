<?php

/**
 *      WHMCS Fetch Product Groups Custom API Action
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

$product_group_features_response = Capsule::table("tblproduct_group_features")
                                          ->where("product_group_id", (string)$gid)
                                          ->get("feature")
                                          ->toArray();

$product_group_features = array_column(array: $product_group_features_response, column_key: "feature");
$apiresults = array("result" => "error", "message" => ERROR_UNHANDLED_EXCEPTION);

if (!empty($product_group_features))
    $apiresults = array("result" => "success", "features" => $product_group_features);
else
    $apiresults = array("result" => "error", "message" => ERROR_GROUP_FEATURES_EMPTY);
