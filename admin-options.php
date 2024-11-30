<?php
/*ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);*/



// Add admin options page
function custom_partner_list_options_page() {
    add_menu_page(
        'Custom Partner List Settings',
        'Partner List Settings',
        'manage_options',
        'custom-partner-list-settings',
        'custom_partner_list_options_callback'
    );
}
add_action('admin_menu', 'custom_partner_list_options_page');
// Initialize admin settings
function custom_partner_list_settings_init() {
    register_setting('custom_partner_list_options', 'custom_partner_list_options');

    add_settings_section(
        'custom_partner_list_options_section',
        'Shortcode Options',
        'custom_partner_list_options_section_callback',
        'custom-partner-list-settings'
    );

    add_settings_field(
        'selected_products',
        'Select Products',
        'selected_products_callback',
        'custom-partner-list-settings',
        'custom_partner_list_options_section'
    );

    add_settings_field(
        'selected_stakeholder',
        'Select Stakeholder Type',
        'selected_stakeholder_callback',
        'custom-partner-list-settings',
        'custom_partner_list_options_section'
    );
}
add_action('admin_init', 'custom_partner_list_settings_init');
function custom_partner_list_options_section_callback() {
//echo var_dump("<p>Sameen's test call back back function</p>");
}
// Callback for admin options page
function custom_partner_list_options_callback() {
    ?>
    <div class="wrap">
        <h2>Custom Partner List Settings</h2>
        <table style="width:100%">
            <tr>
                <td>
                    <form method="post" action="options.php">
                        <?php settings_fields('custom_partner_list_options'); ?>
                        <?php do_settings_sections('custom-partner-list-settings'); ?>
                        <input type="submit" class="button-primary" value="Generate Shortcode">
                    </form>
                </td>
                <td>
                    <div id="youtube-video">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/rymAEg9YdyE?si=PX8OMlM1QI5nA6rm" frameborder="0" allowfullscreen></iframe>
                        <br>
                        <p class="video-note">Get ready for some shortcode magic! Check out this awesome YouTube tutorial for a quick way to generate your own shortcode!</p>
                    </div>
                </td>
            </tr>
        </table>
       
		<table class="form-table" role="">
		<tbody>
		<tr><th scope="row">Your Shortcode </th>
			<td> 
				<div class="code-container">
					<pre id="code-block">
					<?php
						// // Display the generated shortcode
						// $options = get_option('custom_partner_list_options');
						// $shortcode_atts = array(
						// 	'product_id' => $options['selected_products'],
						// 	'stakeholder' => $options['selected_stakeholder']
						// );
						// // Display the generated shortcode
						// echo '[custom_partner_list product_id="' . implode(',', $shortcode_atts['product_id']) . '" stakeholder="' . implode(',', $shortcode_atts['stakeholder']) . '"]';
                        $options = get_option('custom_partner_list_options');

                        // Check if 'selected_products' and 'selected_stakeholder' are arrays
                        if (is_array($options['selected_products']) && is_array($options['selected_stakeholder'])) {
                            $shortcode_atts = array(
                                'product_id' => implode(',', $options['selected_products']),
                                'stakeholder' => implode(',', $options['selected_stakeholder'])
                            );
                        
                            // Display the generated shortcode
                            echo '[custom_partner_list product_id="' . $shortcode_atts['product_id'] . '" stakeholder="' . $shortcode_atts['stakeholder'] . '"]';
                        } else {
                            // Handle the case where the options are not arrays
                            echo 'Options are not properly set.';
                        }
					?>     
					</pre>
					<button id="copy-button">Copy Code</button>
				</div>
                
			<br>
			</td><td style="width:50%"> </td> </tr>
			</tbody>
		</table>
		
		
    </div>
	
<style>
.code-container {
	position: relative;
}

#code-block {
    background-color: #f7f7f7;
    padding: 20px;
    border: 1px solid #ddd;
    white-space: pre-wrap;
}

#copy-button {
    position: absolute;
    right: 0;
    top: 0;
}
.video-note{
	background: bisque!important;
    padding: 7px!important;
    width: 63%;
    border-radius: 10px;
}

</style>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const codeBlock = document.getElementById('code-block');
    const copyButton = document.getElementById('copy-button');

    copyButton.addEventListener('click', function () {
        const codeText = codeBlock.textContent.trim(); // Use trim() to remove extra white spaces
        const tempTextArea = document.createElement('textarea');
        tempTextArea.value = codeText;
        document.body.appendChild(tempTextArea);
        tempTextArea.select();
        document.execCommand('copy');
        document.body.removeChild(tempTextArea);

        copyButton.textContent = 'Copied!';
        setTimeout(function () {
            copyButton.textContent = 'Copy Code';
        }, 1500);
    });
});

</script>


    <?php
	
}


?>