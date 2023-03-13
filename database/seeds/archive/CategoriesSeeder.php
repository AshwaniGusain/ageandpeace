<?php

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            'Health and Wellness' => [
                'name'       => 'Health & Wellness',
                'slug'       => 'healthcare-and-wellness',
                'precedence' => 1,
                'children' => [
                    'Medical' => [
                        'name'       => 'Medical',
                        'slug'       => 'medical',
                        'precedence' => 1,
                        'children'   => [
                            'Geriatrician' => [
                                'name'       => 'Geriatrician',
                                'slug'       => 'geriatrician',
                                'precedence' => 1
                            ],
                            'Primary Care Physician (groups)' => [
                                'name'       => 'Primary Care Physician (groups)',
                                'slug'       => 'primary-care-physician-groups',
                                'precedence' => 2
                            ],
                            'Orthopedic' => [
                                'name'       => 'Orthopedic',
                                'slug'       => 'orthopedic',
                                'precedence' => 3
                            ],
                            'Oncologist' => [
                                'name'       => 'Oncologist',
                                'slug'       => 'oncologist',
                                'precedence' => 4
                            ],
                            'Ophthalmologist' => [
                                'name'       => 'Ophthalmologist',
                                'slug'       => 'ophthalmologist',
                                'precedence' => 5
                            ],
                            'Cardiologist' => [
                                'name'       => 'Cardiologist',
                                'slug'       => 'cardiologist',
                                'precedence' => 6
                            ],
                            'Audiologist' => [
                                'name'       => 'Audiologist',
                                'slug'       => 'audiologist',
                                'precedence' => 7
                            ],
                            'Endocrinologists' => [
                                'name'       => 'Endocrinologists',
                                'slug'       => 'endocrinologists',
                                'precedence' => 8
                            ],
                            'Geriatric Psychiatrist' => [
                                'name'       => 'Geriatric Psychiatrist',
                                'slug'       =>
                                    'geriatric-psychiatrist',
                                'precedence' => 9
                            ],
                            'Nephrologist' => [
                                'name'       => 'Nephrologist',
                                'slug'       => 'nephrologist',
                                'precedence' =>
                                    10
                            ],
                            'Rheumatologists' => [
                                'name'       => 'Rheumatologists',
                                'slug'       => 'rheumatologists',
                                'precedence' => 11
                            ],
                            'Urologist' => [
                                'name'       => 'Urologist',
                                'slug'       => 'urologist',
                                'precedence' => 12
                            ],
                            'Palliative Care' => [
                                'name' => 'Palliative Care',
                                'slug' => 'palliative-care',
                                'precedence'
                                       => 13
                            ],
                            'Drugs' => [
                                'name'       => 'Drugs',
                                'slug'       => 'drugs',
                                'precedence' => 14
                            ],
                            'Dental' => [
                                'name'       => 'Dental',
                                'slug'       => 'dental',
                                'precedence' => 15
                            ],
                            'Physical Therapist' => [
                                'name'       => 'Physical Therapist',
                                'slug'       => 'physical-therapist',
                                'precedence' => 16
                            ],
                            'Occupational Therapist' => [
                                'name'       => 'Occupational Therapist',
                                'slug'       => 'occupational-therapist',
                                'precedence' => 17
                            ]
                        ]
                    ],
                    'Mental / Dementia' => [
                        'name'       => 'Mental / Dementia',
                        'slug'       => 'mental-dementia',
                        'precedence' => 2,
                        'children'   => [
                            'LCSW' => [
                                'name'       => 'LCSW',
                                'slug'       => 'lcsw',
                                'precedence' => 1
                            ],
                            'Neurologist' => [
                                'name'       => 'Neurologist',
                                'slug'       => 'neurologist',
                                'precedence' => 2
                            ],
                            'PCP' => [
                                'name'       => 'PCP',
                                'slug'       => 'pcp',
                                'precedence' => 3
                            ],
                            'Pyschologist' => [
                                'name'       => 'Pyschologist',
                                'slug'       => 'pyschologist',
                                'precedence' => 4
                            ],
                            'Psychiatrist' => [
                                'name'       => 'Psychiatrist',
                                'slug'       => 'psychiatrist',
                                'precedence' => 5
                            ]
                        ]
                    ],
                    'Wellness' => [
                        'name'       => 'Wellness',
                        'slug'       => 'wellness',
                        'precedence' => 3,
                        'children'   => [
                            'Nutritionist' => [
                                'name'       => 'Nutritionist',
                                'slug'       => 'nutritionist',
                                'precedence' => 1
                            ],
                            'Personal Care - Spa Services' => [
                                'name'       => 'Personal Care - Spa Services',
                                'slug'       => 'personal-care-spa-services',
                                'precedence' => 2
                            ],
                            'Hair' => [
                                'name'       => 'Hair',
                                'slug'       => 'hair',
                                'precedence' => 3
                            ],
                            'Massage' => [
                                'name'       => 'Massage',
                                'slug'       => 'massage',
                                'precedence' => 4
                            ],
                            'Nails' => [
                                'name'       => 'Nails',
                                'slug'       => 'nails',
                                'precedence' => 5
                            ],
                            'Silver Sneakers' => [
                                'name'       => 'Silver Sneakers',
                                'slug'       => 'silver-sneakers',
                                'precedence' => 6
                            ],
                            'Clubs Focused On Seniors' => [
                                'name'       => 'Clubs Focused On Seniors',
                                'slug'       => 'clubs-focused-on-seniors',
                                'precedence' => 7
                            ],
                            'Aerobics' => [
                                'name'       => 'Aerobics',
                                'slug'       => 'aerobics',
                                'precedence' => 8
                            ],
                            'Yoga' => [
                                'name'       => 'Yoga',
                                'slug'       => 'yoga',
                                'precedence' => 9
                            ],
                            'Pilates' => [
                                'name'       => 'Pilates',
                                'slug'       => 'pilates',
                                'precedence' => 10
                            ],
                            'Sleep Services' => [
                                'name'       => 'Sleep Services',
                                'slug'       => 'sleep-services',
                                'precedence' => 11
                            ],
                            'Resistance Training' => [
                                'name'       => 'Resistance Training',
                                'slug'       => 'resistance-training',
                                'precedence' => 12
                            ]
                        ]
                    ],
                ],
            ],
            'Financial and Insurance' => [
                'name'       => 'Financial & Insurance',
                'slug'       => 'financial-and-insurance',
                'precedence' => 2,
                'children'   => [
                    'Financial' => [
                        'name'       => 'Financial',
                        'slug'       => 'financial',
                        'precedence' => 1,
                        'children' => [
                            'Financial Planner' => [
                                'name'       => 'Financial Planner',
                                'slug'       => 'financial-planner',
                                'precedence' => 1
                            ],
                            'Tax Specialist' => [
                                'name'       => 'Tax Specialist',
                                'slug'       => 'tax-specialist',
                                'precedence' => 2
                            ],
                            'Reverse Mortgage' => [
                                'name'       => 'Reverse Mortgage',
                                'slug'       => 'reverse-mortgage',
                                'precedence' => 3
                            ]
                        ]
                    ],
                    'Insurance' => [
                        'name'       => 'Insurance',
                        'slug'       => 'insurance',
                        'precedence' => 2,
                        'children'   => [
                            'Life' => [
                                'name'       => 'Life',
                                'slug'       => 'life',
                                'precedence' => 1
                            ],
                            'Long Term Care' => [
                                'name'       => 'Long Term Care',
                                'slug'       => 'long-term-care',
                                'precedence' => 2
                            ],
                            'Disability'     => [
                                'name'       => 'Disability',
                                'slug'       => 'disability',
                                'precedence' => 3
                            ]
                        ]
                    ],
                    'Benefit Programs' => [
                        'name'       => 'Benefit Programs',
                        'slug'       => 'benefit-programs',
                        'precedence' => 3,
                        'children'   => [
                            'Medicare' => [
                                'name'       => 'Medicare',
                                'slug'       => 'medicare',
                                'precedence' => 1
                            ],
                            'State Specific' => [
                                'name'       => 'State Specific',
                                'slug'       => 'state-specific',
                                'precedence' => 2
                            ],
                            'Medicaid' => [
                                'name'       => 'Medicaid',
                                'slug'       => 'medicaid',
                                'precedence' => 3
                            ],
                            'Veteran' => [
                                'name'       => 'Veteran',
                                'slug'       => 'veteran',
                                'precedence' => 4
                            ]
                        ]
                    ]
                ]
            ],
            'Legal and Legacy' => [
                'name'       => 'Legal & Legacy',
                'slug'       => 'legal-and-legacy',
                'precedence' => 3,
                'children'   => [
                    'Leaving a Legacy' => [
                        'name'       => 'Leaving a Legacy',
                        'slug'       => 'leaving-a-legacy',
                        'precedence' => 1,
                        'children' => [
                            'donations' => [
                                'name'       => 'donations',
                                'slug'       => 'donations',
                                'precedence' => 1
                            ],
                            'Social Media - Online Presence' => [
                                'name'       => 'Social Media - Online Presence',
                                'slug'       => 'social-media-online-presence',
                                'precedence' => 2
                            ],
                            'Memories' => [
                                'name'       => 'Memories',
                                'slug'       => 'memories',
                                'precedence' => 3
                            ],
                            'Geneology Services' => [
                                'name'       => 'Geneology Services',
                                'slug'       => 'geneology-services',
                                'precedence' => 4
                            ]
                        ]
                    ],
                    'End of Life' => [
                        'name'       => 'End of Life',
                        'slug'       => 'end-of-life',
                        'precedence' => 2,
                        'children'   => [
                            'Burial Insurance' => [
                                'name'       => 'Burial Insurance',
                                'slug'       => 'burial-insurance',
                                'precedence' => 1
                            ],
                            'Burial Sites' => [
                                'name'       => 'Burial Sites',
                                'slug'       => 'burial-sites',
                                'precedence' => 2
                            ],
                            'Funeral Home'     => [
                                'name'       => 'Funeral Home',
                                'slug'       => 'funeral-home',
                                'precedence' => 3
                            ],
                            'Photo / Video'     => [
                                'name'       => 'Photo / Video',
                                'slug'       => 'photo-video',
                                'precedence' => 4
                            ]
                        ]
                    ],
                    'Legal' => [
                        'name'       => 'Legal',
                        'slug'       => 'legal',
                        'precedence' => 3,
                        'children'   => [
                            'Eldercare Lawyer' => [
                                'name'       => 'Eldercare Lawyer',
                                'slug'       => 'eldercare-lawyer',
                                'precedence' => 1
                            ],
                            'Family Practice Attorney' => [
                                'name'       => 'Family Practice Attorney',
                                'slug'       => 'family-practice-attorney',
                                'precedence' => 2
                            ],
                            'Real Estate Attorney' => [
                                'name'       => 'Real Estate Attorney',
                                'slug'       => 'real-estate-attorney',
                                'precedence' => 3
                            ],
                        ]
                    ]
                ]
            ],
            'Home and Care' => [
                'name'       => 'Home & Care',
                'slug'       => 'home-and-care',
                'precedence' => 4,
                'children'   => [
                    'Aging In Place' => [
                        'name'       => 'Aging In Place',
                        'slug'       => 'aging-in-place',
                        'precedence' => 1,
                        'children' => [
                            'Home Safety Assessment' => [
                                'name'       => 'Home Safety Assessment',
                                'slug'       => 'home-safety-assessment',
                                'precedence' => 1
                            ],
                            'Home Construction Services' => [
                                'name'       => 'Home Construction Services',
                                'slug'       => 'home-construction-services',
                                'precedence' => 2
                            ],
                            'Animal Care' => [
                                'name'       => 'Animal Care',
                                'slug'       => 'animal-care',
                                'precedence' => 3
                            ],
                            'Grocery Delivery' => [
                                'name'       => 'Grocery Delivery',
                                'slug'       => 'grocery-delivery',
                                'precedence' => 4
                            ],
                            'Cleaning Services' => [
                                'name'       => 'Cleaning Services',
                                'slug'       => 'cleaning-services',
                                'precedence' => 5
                            ],
                            'Yard Services' => [
                                'name'       => 'Yard Services',
                                'slug'       => 'yard-services',
                                'precedence' => 6
                            ],
                            'Pet Boarding' => [
                                'name'       => 'Pet Boarding',
                                'slug'       => 'pet-boarding',
                                'precedence' => 7
                            ],
                            'Pet Grooming' => [
                                'name'       => 'Pet Grooming',
                                'slug'       => 'pet-grooming',
                                'precedence' => 8
                            ],
                            'Pet Sitting / Care' => [
                                'name'       => 'Pet Sitting / Care',
                                'slug'       => 'pet-sitting-care',
                                'precedence' => 9
                            ],
                            'In Home Care Services' => [
                                'name'       => 'In Home Care Services',
                                'slug'       => 'in-home-care-services',
                                'precedence' => 10
                            ]
                        ]
                    ],
                    'Living Transitions' => [
                        'name'       => 'Living Transitions',
                        'slug'       => 'living-transitions',
                        'precedence' => 2,
                        'children'   => [
                            'Realtor' => [
                                'name'       => 'Realtor',
                                'slug'       => 'realtor',
                                'precedence' => 1
                            ],
                            'Property Management' => [
                                'name'       => 'Property Management',
                                'slug'       => 'property-management',
                                'precedence' => 2
                            ],
                            'Retirement (55+) Communities'     => [
                                'name'       => 'Retirement (55+) Communities',
                                'slug'       => 'retirement-communities',
                                'precedence' => 3
                            ],
                            'Moving'     => [
                                'name'       => 'Moving',
                                'slug'       => 'moving',
                                'precedence' => 4
                            ],
                            'Storage Facilities'     => [
                                'name'       => 'Storage Facilities',
                                'slug'       => 'storage-facilities',
                                'precedence' => 5
                            ],
                            'Cataloging / Inventory'     => [
                                'name'       => 'Cataloging / Inventory',
                                'slug'       => 'cataloging-inventory',
                                'precedence' => 6
                            ],
                            'Estate Sales'     => [
                                'name'       => 'Estate Sales',
                                'slug'       => 'estate-sales',
                                'precedence' => 7
                            ]
                        ]
                    ],
                    'Care Communities' => [
                        'name'       => 'Care Communities',
                        'slug'       => 'care-communities',
                        'precedence' => 3,
                        'children'   => [
                            'In Home Care Services' => [
                                'name'       => 'In Home Care Services',
                                'slug'       => 'in-home-care-services',
                                'precedence' => 1
                            ],
                            'Independent Living' => [
                                'name'       => 'Independent Living',
                                'slug'       => 'independent-living',
                                'precedence' => 2
                            ],
                            'Assisted Living' => [
                                'name'       => 'Assisted Living',
                                'slug'       => 'assisted-living',
                                'precedence' => 3
                            ],
                            'Senior Care' => [
                                'name'       => 'Senior Care',
                                'slug'       => 'senior-care',
                                'precedence' => 4
                            ],
                            'Memory Care' => [
                                'name'       => 'Memory Care',
                                'slug'       => 'memory-care',
                                'precedence' => 5
                            ],
                            'Hospice Care' => [
                                'name'       => 'Hospice Care',
                                'slug'       => 'hospice-care',
                                'precedence' => 6
                            ],
                            'Day Center Care' => [
                                'name'       => 'Day Center Care',
                                'slug'       => 'day-center-care',
                                'precedence' => 7
                            ]
                        ]
                    ]
                ]
            ],
            'Social and Spiritual' => [
                'name'       => 'Social & Spiritual',
                'slug'       => 'social-and-spiritual',
                'precedence' => 5,
                'children'   => [
                    'Spiritual Journey' => [
                        'name'       => 'Spiritual Journey',
                        'slug'       => 'spiritual-journey',
                        'precedence' => 1,
                        'children' => [
                            'Places to Worship' => [
                                'name'       => 'Places to Worship',
                                'slug'       => 'places-to-worship',
                                'precedence' => 1
                            ],
                            'Spiritual Counseling' => [
                                'name'       => 'Spiritual Counseling',
                                'slug'       => 'spiritual-counseling',
                                'precedence' => 2
                            ],
                            'Life Coach' => [
                                'name'       => 'Life Coach',
                                'slug'       => 'life-coach',
                                'precedence' => 3
                            ],
                            'Chaplan Services' => [
                                'name'       => 'Chaplan Services',
                                'slug'       => 'chaplan-services',
                                'precedence' => 4
                            ]
                        ]
                    ],
                    'Out and About' => [
                        'name'       => 'Out and About',
                        'slug'       => 'out-and-about',
                        'precedence' => 2,
                        'children'   => [
                            'Thrive Program' => [
                                'name'       => 'Thrive Program',
                                'slug'       => 'thrive-program',
                                'precedence' => 1
                            ],
                            'Arts / Music' => [
                                'name'       => 'Arts / Music',
                                'slug'       => 'arts-music',
                                'precedence' => 2
                            ],
                            'Sports'     => [
                                'name'       => 'Sports',
                                'slug'       => 'sports',
                                'precedence' => 3
                            ],
                            'Vacation Services'     => [
                                'name'       => 'Vacation Services',
                                'slug'       => 'vacation-services',
                                'precedence' => 4
                            ],
                            'Education'     => [
                                'name'       => 'Education',
                                'slug'       => 'education',
                                'precedence' => 5
                            ]
                        ]
                    ],
                    'Support Groups' => [
                        'name'       => 'Support Groups',
                        'slug'       => 'support-groups',
                        'precedence' => 3,
                        'children'   => [
                            'Senior Centers' => [
                                'name'       => 'Senior Centers',
                                'slug'       => 'senior-centers',
                                'precedence' => 1
                            ],
                            'Spiritual Locations' => [
                                'name'       => 'Spiritual Locations',
                                'slug'       => 'spiritual-locations',
                                'precedence' => 2
                            ],
                            'Universities' => [
                                'name'       => 'Universities',
                                'slug'       => 'universities',
                                'precedence' => 3
                            ],
                            'Longevity Centers' => [
                                'name'       => 'Longevity Centers',
                                'slug'       => 'longevity-centers',
                                'precedence' => 4
                            ]
                        ]
                    ]
                ]
            ],
            'Care Giving' => [
                'name'       => 'Care Giving',
                'slug'       => 'care-giving',
                'precedence' => 2,
                'children'   => [
                    'Technology' => [
                        'name'       => 'Technology',
                        'slug'       => 'technology',
                        'precedence' => 1,
                        'children' => [
                            'Per Subcategory' => [
                                'name'       => 'Per Subcategory',
                                'slug'       => 'per-subcategory',
                                'precedence' => 1
                            ],
                        ]
                    ],
                    'Caregiver Support' => [
                        'name'       => 'Caregiver Support',
                        'slug'       => 'caregiver-support',
                        'precedence' => 2,
                        'children'   => [
                            'Financial' => [
                                'name'       => 'Financial',
                                'slug'       => 'financial',
                                'precedence' => 1
                            ],
                            'Legal' => [
                                'name'       => 'Legal',
                                'slug'       => 'legal',
                                'precedence' => 2
                            ],
                            'Psychological'     => [
                                'name'       => 'Psychological',
                                'slug'       => 'psychological',
                                'precedence' => 3
                            ],
                            'Wellness'     => [
                                'name'       => 'Wellness',
                                'slug'       => 'wellness',
                                'precedence' => 4
                            ]
                        ]
                    ],
                    'Education and Training' => [
                        'name'       => 'Education and Training',
                        'slug'       => 'education-and-training',
                        'precedence' => 3,
                        'children'   => [
                            'Training Groups' => [
                                'name'       => 'Training Groups',
                                'slug'       => 'training-groups',
                                'precedence' => 1
                            ],
                            'Govt Programs' => [
                                'name'       => 'Govt Programs',
                                'slug'       => 'govt-programs',
                                'precedence' => 2
                            ],
                            'Certifications' => [
                                'name'       => 'Certifications',
                                'slug'       => 'certifications',
                                'precedence' => 3
                            ]
                        ]
                    ]
                ]
            ],
        ];

        foreach ($data as $datum => $value){
            // Master Category
            $category = new Category([
                'name' => $value['name'],
                'slug' => $value['slug'],
                'precedence' => $value['precedence']
            ]);
            $category->save();

            // Subcategory
            foreach($value['children'] as $sub_category => $sub_value){
                $subcategory = new Category([
                    'name' => $sub_value['name'],
                    'slug' => $sub_value['slug'],
                    'precedence' => $sub_value['precedence']
                ]);
                $subcategory->save();

                // Lowest Level Category
                foreach($sub_value['children'] as $sub_sub_category => $sub_sub_value){
                    $sub_subcategory = new Category([
                        'name' => $sub_sub_value['name'],
                        'slug' => $sub_sub_value['slug'],
                        'precedence' => $sub_sub_value['precedence']
                    ]);

                    // Set relationship with subcategory
                    $subcategory->childrenCategories()->save($sub_subcategory);
                }

                // Set relationship with Master Category
                $category->childrenCategories()->save($subcategory);
            }

        }

    }
}
