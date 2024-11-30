<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/





function selected_products_callback() {
    $options = get_option('custom_partner_list_options');
    
    $selected_products = isset($options['selected_products']) ? $options['selected_products'] : array();
    $available_products = array(
        1 => 'ELLT',
        2 => 'ELLT Global',
		3 => 'OPSE',
		4 => 'BPSE',
		5 => 'OIFP',
		6 => 'OPMP',
       
    );
    ?>
    <fieldset>
        <?php foreach ($available_products as $product_id => $product_name) { ?>
            <label>
                <input type="checkbox" name="custom_partner_list_options[selected_products][]" value="<?php echo esc_attr($product_id); ?>" <?php checked(in_array($product_id, $selected_products)); ?>>
                <?php echo esc_html($product_name); ?>
            </label><br>
        <?php } ?>
    </fieldset>
    <?php
}

function selected_stakeholder_callback() {
    $options = get_option('custom_partner_list_options');
    $selected_stakeholder = isset($options['selected_stakeholder']) ? $options['selected_stakeholder'] : array();
    $available_stakeholders = array(
        1 => 'Partner',
        2 => 'Agent',
        3 => 'Test Centre',
        4 => 'Preparation Centre'
    );
    ?>
    <fieldset>
        <?php foreach ($available_stakeholders as $stakeholder_id => $stakeholder_name) { ?>
            <label>
                <input type="checkbox" name="custom_partner_list_options[selected_stakeholder][]" value="<?php echo esc_attr($stakeholder_id); ?>" <?php checked(in_array($stakeholder_id, $selected_stakeholder)); ?>>
                <?php echo esc_html($stakeholder_name); ?>
            </label><br>
        <?php } ?>
    </fieldset>
    <?php
}
// Define the shortcode to generate the partner list
function custom_partner_list_shortcode($atts) {
    $atts = shortcode_atts(array(
        'product_id' => '',
        'stakeholder' => '',
    ), $atts);

    $product_id = sanitize_text_field($atts['product_id']);
    $stakeholder_type = sanitize_text_field($atts['stakeholder']);
    ob_start();
    ?>
  
<html>
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>List of partners</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap" rel="stylesheet">
        <!--<link rel="stylesheet" href="styles.css">-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
       
        <link rel=“canonical” href=“https://oieg-corporate-internal-dev.oieg.net” />
    </head>
    <body class="plugin-body">
    <script>
    $(document).ready(function() {
        $("#partner-list").DataTable({
            "pageLength": 10,
                responsive: true
        });
		
         // Hide the "DataTables_info" element
    	$('.dataTables_info').hide();
		$($.fn.dataTable.tables(true)).DataTable()
   			.columns.adjust();
    });
    </script>

    <table id="partner-list" class="row-border hover dataTable" style="width: 100%">
        <thead>
            <tr >
                <th class="custom-background"></th>
                <th class="custom-background"  data-priority="1">Name</th>
                <th class="custom-background"  data-priority="2">Test</th>
                <th class="custom-background">Country</th>
                <th class="custom-background">City</th>
               <!-- <th>stakeholder</th>-->
            </tr>
        </thead>
        <tbody>
        <?php
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);
            //require_once('db-connnnnnnnnect.php');
            // Load WordPress core functions
			//require_once(ABSPATH . 'wp-config.php');
			//require_once(ABSPATH . 'wp-load.php');
			//require_once(ABSPATH . 'wp-includes/wp-db.php');
            require_once('functions.php');

			// Check if there was an error establishing the connection
			/*if ($con->last_error) {
				die("Database connection failed: " . $con->last_error);
			}*/

			
            //echo var_dump($atts['product']);
            global $wpdb;
            if (isset($atts['product']) && !empty($atts['product'])) {
                $product = explode(',', $atts['product']);
                $product = array_filter($product, function ($value) {
                    return is_numeric($value);
                });
            } else {
                // In case the product id is not given, get all product ids
                $sql = "SELECT `product_id` FROM {$wpdb->prefix}custom_partners_list_products";
                //echo var_dump("sameen's error msg".$result);
                $result = $wpdb->get_results($sql);

               /* $product = array();
                while ($row = $result->fetch_assoc()) {
                    array_push($product, $row['product_id']);
                }*/
				$product = array();
				foreach ($result as $row) {
					array_push($product, $row->product_id);
				}
				
            }
            $product_ids = $product;
            $product = implode(",", $product);
            //echo var_dump("sameen's product ids".$product);

            //$stakeholder_type_conditions = implode("', '", $atts['stakeholder_type']);
            

if (isset($atts['stakeholder']) && !empty($atts['stakeholder'])) {
    $stakeholder_type_conditions = explode(',', $atts['stakeholder']);
    $stakeholder_type_conditions = array_filter($stakeholder_type_conditions, 'is_numeric');
    
    foreach ($stakeholder_type_conditions as $stakeholder) {
		//echo var_dump("stakeholder". $stakeholder."<br>");
        // Map numeric values to actual stakeholder types
        $stakeholder_types = array(
            1 => 'Partner',
            2 => 'Agent',
            3 => 'Test Centre',
            4 => 'Preparation Centre'
        );
        
        if (array_key_exists($stakeholder, $stakeholder_types)) {
            $stakeholder_name = $stakeholder_types[$stakeholder];
     
            $sql = $wpdb->prepare(
                "SELECT * FROM {$wpdb->prefix}custom_partners_agents
                WHERE partner_id IN (
                    SELECT DISTINCT(wp_custom_partners_agents.partner_id) FROM {$wpdb->prefix}custom_partners_agents
                    INNER JOIN {$wpdb->prefix}custom_partners_products_relationship ON 
                    wp_custom_partners_agents.partner_id = {$wpdb->prefix}custom_partners_products_relationship.partner_id 
                    WHERE product_id IN ($product_id)
                )  
                AND stakeholder_type = %s
                ORDER BY wp_custom_partners_agents.name ASC",
                $stakeholder_name
            );
			//echo var_dump("database query".$sql."<br>");
			//echo var_dump("product id = ".$product_id.'<br>');
            
            
            $results = $wpdb->get_results($sql);
			//echo var_dump("results error".$results."<br>");
               foreach ($results as $row) {
    				$partner_url = !empty($row->url) && $row->url !== '#' ? '<a class="partner-url" href=' . esc_url($row->url) . ' target="_blank">' : '';
    				$logo_url = !empty($row->logo) && $row->logo !== ' ' ? '<img class="partner-logo" src="' . esc_url($row->logo) . '">' : '';
				   //$product_num = explode(',', $product_id);
				   //echo var_dump("sameens partner_id".$product_num);
				  // echo var_dump("sameens product_ids".$product);
   					$new_product = render_products(get_product_by_partner($wpdb, $row->partner_id, $product_ids));

                

                    echo '<tr class="border-bottom">';
                    echo '<td class="plugin-td">' . $logo_url . '</td>
                    <td class="plugin-td">' . $partner_url . esc_html($row->name) . '</a></td>
                    <td class="plugin-td">' . $new_product . '</td>
                    <td class="plugin-td">' . esc_html($row->country) . '</td>
                    <td class="plugin-td">' . esc_html($row->city) . '</td>';
					//<td>' . esc_html($row->stakeholder_type) . '</td>';
                    echo '</tr>';
                }
            
          
        }
    }
}
 
            ?>
        </tbody>
    </table>
		
	</body>
</html>

    <?php
    return ob_get_clean();
}
add_shortcode('custom_partner_list', 'custom_partner_list_shortcode');
?>