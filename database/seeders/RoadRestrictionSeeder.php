<?php

namespace Database\Seeders;

use App\Models\RoadRestriction;
use Illuminate\Database\Seeder;

class RoadRestrictionSeeder extends Seeder
{
    public function run(): void
    {
        $restrictions = [
            [
                'restriction_type' => 'low_bridge',
                'address' => 'Laurel Street Bridge, Golden Square, VIC 3555',
                'latitude' => -36.7833,
                'longitude' => 144.2333,
                'description' => '3.6m',
                'severity' => 'critical',
                'verified' => true,
                'verification_count' => 5,
                'reporter_count' => 5,
                'status' => 'active',
            ],
            [
                'restriction_type' => 'low_bridge',
                'address' => 'Bridgewater Bridge, Midland Highway, Bridgewater, VIC 3516',
                'latitude' => -36.9056,
                'longitude' => 143.9833,
                'description' => '4.1m',
                'severity' => 'high',
                'verified' => true,
                'verification_count' => 3,
                'reporter_count' => 3,
                'status' => 'active',
            ],
            [
                'restriction_type' => 'low_bridge',
                'address' => 'Railway Overpass, Calder Highway, Eaglehawk, VIC 3556',
                'latitude' => -36.7167,
                'longitude' => 144.2500,
                'description' => '3.9m',
                'severity' => 'critical',
                'verified' => true,
                'verification_count' => 4,
                'reporter_count' => 4,
                'status' => 'active',
            ],
            [
                'restriction_type' => 'weight_limit',
                'address' => 'Bridge Street Bridge, Bendigo, VIC 3550',
                'latitude' => -36.7589,
                'longitude' => 144.2794,
                'description' => '15',
                'severity' => 'high',
                'verified' => true,
                'verification_count' => 3,
                'reporter_count' => 3,
                'status' => 'active',
            ],
            [
                'restriction_type' => 'low_bridge',
                'address' => 'Princes Highway Overpass, Dandenong, VIC 3175',
                'latitude' => -37.9870,
                'longitude' => 145.2147,
                'description' => '4.4m',
                'severity' => 'high',
                'verified' => true,
                'verification_count' => 6,
                'reporter_count' => 6,
                'status' => 'active',
            ],
            [
                'restriction_type' => 'low_bridge',
                'address' => 'Kings Way Overpass, South Melbourne, VIC 3205',
                'latitude' => -37.8310,
                'longitude' => 144.9590,
                'description' => '4.2m',
                'severity' => 'high',
                'verified' => true,
                'verification_count' => 4,
                'reporter_count' => 4,
                'status' => 'active',
            ],
            [
                'restriction_type' => 'low_bridge',
                'address' => 'West Gate Bridge Approach, Footscray, VIC 3011',
                'latitude' => -37.8230,
                'longitude' => 144.9150,
                'description' => '4.6m',
                'severity' => 'medium',
                'verified' => true,
                'verification_count' => 3,
                'reporter_count' => 3,
                'status' => 'active',
            ],
            [
                'restriction_type' => 'road_ban',
                'address' => 'Swanston Street, Melbourne CBD, VIC 3000',
                'latitude' => -37.8136,
                'longitude' => 144.9631,
                'description' => 'No heavy vehicles over 4.5t GVM',
                'severity' => 'high',
                'verified' => true,
                'verification_count' => 8,
                'reporter_count' => 8,
                'status' => 'active',
            ],
        ];

        foreach ($restrictions as $data) {
            RoadRestriction::updateOrCreate(
                [
                    'address' => $data['address'],
                ],
                array_merge($data, ['user_id' => null])
            );
        }
    }
}
