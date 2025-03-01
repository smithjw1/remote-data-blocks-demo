<?php

declare(strict_types=1);

/**
 * Plugin Name: Airtable RDB Integration Example
 * Description: Creates an Airtable data source using Remote Data Blocks
 * Author: WPVIP
 * Author URI: https://remotedatablocks.com/
 * Text Domain: remote-data-blocks
 * Version: 1.0.0
 * Requires Plugins: remote-data-blocks
 */

namespace RemoteDataBlocks\Example\Airtable;

use RemoteDataBlocks\Store\DataSource\DataSourceConfigManager;
use RemoteDataBlocks\Integrations\Airtable\AirtableIntegration;
use function is_wp_error;
use function add_action;
use function error_log;

function register_airtable_data_source(): void
{
    $access_token = 'patatQ1Vsos9udlV4.bfcfe7e8cbd48c292131f2b13829abceb1920fc8f84c7b00efad9d07f4232259';
    $base_id = 'appHWR6MHe90igNWN'; // Airtable base ID
    $table_id = 'tblGtk5XwzXMG70Ci'; // Airtable table ID

    // Define the Airtable configuration
    $data_source_config = [
        'service' => 'airtable',
        'service_config' => [
            '__version' => 1,
            'access_token' => $access_token,
            'base' => [
                'id' => $base_id,
                'name' => 'Sample Data',
            ],
            'display_name' => 'Academy Award for Sound',
            'tables' => [
                [
                    'id' => $table_id,
                    'name' => 'Academy Award for Sound',
                    'output_query_mappings' => [
                        [
                            'key' => 'record_id',
                            'name' => 'ID',
                            'path' => '$.id',
                            'type' => 'id',
                        ],
                        [
                            'key' => 'year',
                            'name' => 'Year',
                            'path' => '$.fields.award_year',
                            'type' => 'number',
                        ],
                        [
                            'key' => 'name',
                            'name' => 'Name',
                            'path' => '$.fields.award_name',
                            'type' => 'string',
                        ],
                        [
                            'key' => 'recipient',
                            'name' => 'Recipient',
                            'path' => '$.fields.award_recipients',
                            'type' => 'string',
                        ],
                        [
                            'key' => 'film',
                            'name' => 'Film',
                            'path' => '$.fields.film_name',
                            'type' => 'string',
                        ],
                    ],
                ],
            ],
        ],
    ];

    // Create the data source
    $result = DataSourceConfigManager::create($data_source_config);

    if (is_wp_error($result)) {
        error_log(sprintf(
            'Failed to create Airtable data source: %s',
            $result->get_error_message()
        ));
        return;
    }

    // Log success
    error_log(sprintf(
        'Successfully created Airtable data source with UUID: %s',
        $result['uuid']
    ));

    // Register Airtable-specific blocks
    AirtableIntegration::register_blocks_for_airtable_data_source($result);
    AirtableIntegration::register_loop_blocks_for_airtable_data_source($result);
}

// Register the data source when WordPress initializes
add_action('init', __NAMESPACE__ . '\\register_airtable_data_source');
