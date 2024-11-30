<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//Render Products
function render_products(array $products)
{
    $html = '';
    $products_to_display = array_slice($products, 0, 2);
    $remaining_products = array_diff($products, $products_to_display);
    foreach ($products_to_display as $product) {
        $html = $html . css_class_by_product($product);
    }
    if (count($remaining_products) != 0) {
        $remaining_product_list = '';
        foreach ($remaining_products as $product) {
            $remaining_product_list =  $remaining_product_list . '<li class="remaining-product-list-item">' . $product . '</li>';
        }

        $html = $html . '<div onclick="showRemainingProds()" class="product-pill remaining-products">+' . count($remaining_products) . '</div>' . '<div class="hide-remaining-prods remaining-products-list" id="remaining-products-list"><div> Other products</div><ul>' .
            $remaining_product_list
            . '</ul><span class="close-btn" onclick="showRemainingProds()">x</span></div>';
    }
    return $html;
}

//Fetch list of products using partner id

function get_product_by_partner($con, INT $id, array $limit_products)
{
	
global $wpdb;
	$limit_products_str = implode(',', $limit_products);
	//echo var_dump($limit_products_str);
    $sql = $wpdb->prepare(
        "SELECT `product_name` FROM {$wpdb->prefix}custom_partners_list_products
        INNER JOIN {$wpdb->prefix}custom_partners_products_relationship ON 
        {$wpdb->prefix}custom_partners_list_products.product_id = {$wpdb->prefix}custom_partners_products_relationship.product_id
        WHERE partner_id = %d AND {$wpdb->prefix}custom_partners_list_products.product_id IN ($limit_products_str)",
        $id
    );
//echo var_dump($sql);
    $results = $wpdb->get_col($sql);
	//echo var_dump($wpdb->get_col($sql));
	//echo "SQL: $sql<br>";
    //echo "Results: ";
    //var_dump($results);

    return $results;
    
	
   /* $limit_products = implode(',', $limit_products);
    $sql = "SELECT `product_name` FROM `wp_custom_partners_list_products`
    INNER JOIN `wp_custom_partners_products_relationship` ON 
    `wp_custom_partners_list_products`.`product_id` = `wp_custom_partners_products_relationship`.`product_id`
    WHERE `partner_id` = $id AND `wp_custom_partners_list_products`.`product_id` IN ($limit_products)";

    $result = mysqli_query($con, $sql);
    return array_column($result->fetch_all(), 0);*/
}

//Assign css classes based on a product

function css_class_by_product($product_name)
{
    if ($product_name === 'ELLT') {
        $product = '<div class="product-pill product-ellt"> ELLT </div>';
    } else if ($product_name === 'ELLT Global') {
        $product = '<div class="product-pill product-ellt-global"> ELLT Global </div>';
    } else if ($product_name === 'OPSE') {
        $product = '<div class="product-pill product-spotlight"> OPSE </div>';
    } else if ($product_name === 'BPSE') {
        $product = '<div class="product-pill product-opse"> BPSE </div>';
    } else if ($product_name === 'OIFP') {
        $product = '<div class="product-pill product-oifp"> OIFP </div>';
    } else if ($product_name === 'OPMP') {
        $product = '<div class="product-pill product-opmp"> OPMP </div>';
    }

    return $product;
}


// Find partner id
function find_partner_id($partner_name)
{
    global $wpdb;

    $sql = $wpdb->prepare(
        "SELECT `partner_id` FROM {$wpdb->prefix}custom_partners_agents WHERE `name` = %s",
        $partner_name
    );

    $partner_id = $wpdb->get_var($sql);

    if ($partner_id === null) {
        return null;
    } else {
        return $partner_id;
    }
}

//Check if the partner and product pair exists
function check_partner_product_pair_exists($partner_id, $product_id = 0)
{
    global $wpdb;

    if ($product_id == 0) {
        $sql = $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}custom_partners_products_relationship WHERE `partner_id` = %d",
            $partner_id
        );
    } else {
        $sql = $wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->prefix}custom_partners_products_relationship WHERE `partner_id` = %d AND `product_id` = %d",
            $partner_id,
            $product_id
        );
    }

    $result = $wpdb->get_var($sql);

    if ($result == 0) {
        return false;
    } else {
        return true;
    }
}


function add_partner_product_relationship($con, $partner_id, $product_id)
{
    //check if the partner and product combination exists already
    if (!check_partner_product_pair_exists($con, $partner_id, $product_id)) {
        $sql = "INSERT INTO `wp_custom_partners_products_relationship` (partner_id, product_id)
    VALUES ($partner_id ,$product_id)";

        mysqli_query($con, $sql) or die(mysqli_error($con));
    }
}

// Add partners
function add_partner($con, $submission_id, $logo, $stakeholder_type, $country, $url, $city, $partner_name, $product_id)
{

    // add partner
    $sql = "INSERT INTO `wp_custom_partners_agents` (submission_id, name, logo, stakeholder_type, country, url, city)
        VALUES ('" . $submission_id . "', 
        '" . $partner_name . "',
        '" . $logo . "',
        '" . $stakeholder_type . "',
        '" . $country . "',
        '" . $url . "',
        '" . $city . "' ) ";
    mysqli_query($con, $sql) or die(mysqli_error($con));

    // get partner id
    $partner_id = find_partner_id($con, $partner_name);

    // add product and partner in the relationship table
    add_partner_product_relationship($con, $partner_id, $product_id);
}

// Delete partners
function delete_partner($con, INT $partner_id)
{
    $sql = "DELETE FROM `wp_custom_partners_products_relationship`
	WHERE `partner_id` = $partner_id";

    mysqli_query($con, $sql);

    //check if the partner has any products attached to it
    if (!check_partner_product_pair_exists($con, $partner_id)) {
        $sql = "DELETE FROM `wp_custom_partners_agents` WHERE `partner_id` = '$partner_id'";
        mysqli_query($con, $sql);
    }
}