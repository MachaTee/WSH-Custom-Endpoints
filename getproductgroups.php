<?php

/**
 *      WHMCS Get Product Groups API Action
 * 
 *      @package WHMCS
 *      @author Macha 
**/
use WHMCS\Database\Capsule;

const ERROR_UNDEFINED_DEPENDENCY = "Error while handling API request: API must be used with WHMCS";
const ERROR_PRODUCT_GROUPS_EMPTY = "Error while retrieving product groups: No product groups found";
const ERROR_UNHANDLED_EXCEPTION  = "Error while executing API function: Unknown Exception";


// Non-WHMCS guard clause
if (!defined("WHMCS"))
    exit(header(ERROR_UNDEFINED_DEPENDENCY));
    

$product_group_ids_capsule = Capsule::table("tblproductgroups")->get("id");
$product_group_ids = array();

// Normalise data such that IDs exist in a sequential array
foreach ($product_group_ids_capsule as $product_group_id) {
    $product_group_ids[] = $product_group_id->id;
}

$apiresults = array("result" => "error", "message" => ERROR_UNHANDLED_EXCEPTION);

if (empty($product_group_ids))
    $apiresults = array("result" => "error", "message" => ERROR_PRODUCT_GROUPS_EMPTY);

    
$apiresults = array("result" => "success", "gids" => $product_group_ids);