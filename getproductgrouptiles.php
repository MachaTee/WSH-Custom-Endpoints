<?php

/**
 *      WHMCS GetProductGroupTiles API Action for WSH.
 *
 *      WhiteSkyHosting-specific API action for retrieving product tiles to be displayed.
 *      When this action is called, the endpoint will respond with a list of all products that are:
 *          Not Hidden.
 *          Not within the filter group (@see $filter param).
 *      In a format where the slug of the product group's ID, name and slug are passed to the client.
 *  
 *      @param      int[]       $filter     Optional. Array of products to filter by GID.
 *      @return     mixed[]     Returns an array of product tiles
 * 
 *      @package    WHMCS
 *      @author     Macha 
**/
use WHMCS\Database\Capsule;

const ERROR_UNDEFINED_DEPENDENCY = 'Error while handling API request: API must be used with WHMCS';
const ERROR_UNHANDLED_EXCEPTION  = 'Error while executing API action: Unknown Exception';
const ERROR_PRODUCT_GROUPS_EMPTY = 'Error while retrieving product groups: No product groups found';
const ERROR_BAD_FORMAT_OF_FILTER = 'Error while processing filter: Badly formatted filter argument (filter must be an array of integers)';

const UNDER_CONSTRUCTION_TEXT = 'Under Construction';

// Ensure $error cannot be passed as an argument
unset($error);

// Non-WHMCS guard clause
if (!defined(constant_name: 'WHMCS'))
    exit(header(header: ERROR_UNDEFINED_DEPENDENCY));
    
// Check that $filter is of an appropriate syntax (array of integers)
if (isset($filter))
{
    // Fail if $filter is badly formatted
    if (!array_filter(array: $filter, callback: 'is_int'))
        $error = ERROR_BAD_FORMAT_OF_FILTER;

    else    // Otherwise query product groups where ID is not in filter and unhidden.
        $product_tiles = Capsule::table('tblproductgroups')
                                ->whereNotIn('id', $filter)
                                ->where('hidden', 0)
                                ->get(['id', 'name', 'slug'])
                                ->toArray();
}
else    // If $filter is NOT passed, query database without filtering.
    $product_tiles = Capsule::table('tblproductgroups')
                            ->where('hidden', 0)
                            ->get(['id', 'name', 'slug'])
                            ->toArray();

$under_construction_query = Capsule::table('tblproduct_group_features')
                                   ->whereIn('product_group_id', array_column(array: $product_tiles, column_key: 'id'))
                                   ->where('feature', UNDER_CONSTRUCTION_TEXT)
                                   ->get('product_group_id')
                                   ->toArray();

$under_construction = array_column(array: $under_construction_query, column_key: 'product_group_id');
$apiresults = ['result' => 'error', 'message' => ERROR_UNHANDLED_EXCEPTION];

if (empty($product_tiles))
    $error = ERROR_PRODUCT_GROUPS_EMPTY;

if (isset($error))
    $apiresults['message'] = $error;
else
    $apiresults = ['result' => 'success', 'tiles' => $product_tiles, 'under_construction' => $under_construction];