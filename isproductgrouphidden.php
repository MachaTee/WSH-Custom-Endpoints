<?php

/**
 *      WHMCS Get Product Group Hidden Status by GID
 * 
 *      @package WHMCS
 *      @author Macha 
**/
use WHMCS\Database\Capsule;

const ERROR_UNDEFINED_DEPENDENCY = "Error while handling API request: API must be used with WHMCS";
const ERROR_PRODUCT_GROUPS_EMPTY = "Error while retrieving product groups: No product groups found";
const ERROR_UNHANDLED_EXCEPTION  = "Error while executing API action: Unknown Exception";


// Non-WHMCS guard clause
if (!defined("WHMCS"))
    exit(header(ERROR_UNDEFINED_DEPENDENCY));
    

$product_group_is_hidden = Capsule::table("tblproductgroups")->where("id", (string)$gid)->get("hidden")[0]->hidden;

$apiresults = array("result" => "error", "message" => ERROR_UNHANDLED_EXCEPTION);

if (empty($product_group_is_hidden))
    $apiresults = array("result" => "error", "message" => ERROR_PRODUCT_GROUPS_EMPTY);

$apiresults = array("result" => "success", "hidden" => $product_group_is_hidden);