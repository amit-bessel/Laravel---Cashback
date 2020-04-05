<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Password Reminder Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are the default lines which match reasons
    | that are given by the password broker for a password update attempt
    | has failed, such as for an invalid token or invalid new password.
    |
    */
    'register' => [
                    'form_title'    => 'Register',
                    'name'          => 'Name',
                    'email'         => 'Email Address',
                    'mobile_no'     => 'Phone Number',
                    'password'      => "Password",
                    'retype_password'      => "Confirm Password",
                    'please_note_all' => "Please note all",
                    'marked_fields_are_mandatory' => "marked fields are mandatory.",
                    'email_alerts'  => "Receive email alerts about new ads",
                    'submit'        => 'Next'
                ],

    'login' => [
                    'form_title'    => 'Login',
                    'email'         => 'Email Address',
                    'password'      => 'Password',
                    'submit'        => 'Login',
                    'register'      => 'Register',
                    'forgot_pass'   => 'Forgot Password?'
                ],

    'forgot_pasword' => [
                    'form_title'    => 'Forgot Password',
                    'email'         => 'Email Address',
                    'submit'        => 'Send'
                ],

    'reset_pasword' => [
                    'form_title'    => 'Reset Password',
                    'password'      => "Password",
                    'conform_pass'  => "Confirm Password",
                    'submit'        => 'Save'
                ],

    'change_password' => [
                    'form_title'    => 'Change Password',
                    'old_password'  => "Old Password",
                    'password'      => "Password",
                    'conform_pass'  => "Confirm Password",
                    'submit'        => 'Save'
                ],            

    'profile' => [
                    'form_title'    => 'Edit Profile',
                    'name'          => 'Name',
                    'email'         => 'Email Address',
                    'mobile_no'     => 'Phone Number',
                    'submit'        => 'Save',
                    'change_pass'   => 'Change Password'
                ],

    'home_page' => [
                    'header'        => [
                            'login'         => 'Login',
                            'register'      => 'Register',
                            'sell_watch'    => 'Sell Your Watch',
                            'about_us'      => 'About Us',
                            'news'          => 'News',
                            'contact_us'    => 'Contact Us',
                            'how_it_works'  => 'How It Works',
                            'my_watches'    => 'My Watches',
                            'my_favourites' => 'My Favourites',
                            'my_profile'    => 'My Profile',
                            'transactions'  => 'Transactions',
                            'logout'        => 'Logout'
                        ],

                    'content'        => [
                            'Your Local-City'               => 'Market your watch online',
                            'Market for Buying/Selling'     => 'Buyers in your city will contact you',
                            'Used Luxury Watches'           => 'Sell it face-to-face',
                            'recently'                      => 'recently',
                            'viewed'                        => 'viewed',
                            'added'                         => 'Added',
                            'Show'                          => 'Show',
                            'Sort by'                       => 'Sort by',
                            'priceHL'                       => 'Price High to Low',
                            'priceLH'                       => 'Price Low to High',
                            'recentlyAdded'                 => 'Recently Added',
                            'recentlyViewed'                => 'Recently Viewed',
                            'load_more'                     => 'Load More',
                            'details'                       => 'Details',
                            'sold'                          => 'Sold'
                        ],

                    'footer'        => [
                            'about_us'          => 'About Us',
                            'news'              => 'News',
                            'contact_us'        => 'Contact Us',
                            'connect'           => 'Connect',
                            'how_it_works'      => 'How It Works',
                            'terms_and_service' => 'Terms of service',
                            //'privacy_policy'    => 'Terms of Service',
                            //'legal_disclaimer'  => 'Legal Disclaimer'
                        ],

                    'filter'        => [
                            'city'              => 'City',
                            'gender'            => 'Gender',
                            'brand'             => 'Brand',
                            'box_and_papers'    => 'Box and Papers',
                            'case_material'     => 'Case Material',
                            'more'              => 'More',
                            'more_options'      =>  [
                                                        'style'         => 'Style',
                                                        'condition'     => 'Condition',
                                                        'case_size'     => 'Case Size',
                                                        'bracelet'      => 'Bracelet',
                                                        'dial_color'    => 'Dial Color',
                                                        'age'           => 'Age'
                                                    ]
                        ]
                    
                ],

    'product_details_page' => [
                    'home'          => 'Home',
                    'specification' => 'Specifications',
                    'brand'         => 'Brand',
                    'model'         => 'Model',
                    'model_no'      => 'Model Number',
                    'gender'        => 'Gender',
                    'box_and_papers'=> 'Box and Papers',
                    'case_material' => 'Case Material',
                    'style'         => 'Style',
                    'condition'     => 'Condition',
                    'case_size'     => 'Case Size',
                    'bracelet'      => 'Bracelet',
                    'dial_color'    => 'Dial Color',
                    'age'           => 'Age',
                    'description'   => 'Description',
                    'contact_seller'=> 'Contact Seller',
                    'show_your_friends' => 'Show Your Friends',
                    'similar' => 'Similar',
                    'watches' => 'Watches',
                    'ask_question' => 'Ask A Question',
                    'report' => 'Report',
                ],

        'sellwatch' => [
                        'page_title' => 'POST WATCH',
                        'sub_title' => 'PLEASE ENTER THE DETAILS AS GIVEN BELOW',
                        'city_id'    => 'City',
                        'contact_number'    => 'Contact Number',
                        'brand_id'     => 'Brand',
                        'currency_id'         => 'Currency',
                        'sell_price'     => 'Sell Price',
                        'gender'     => 'Gender',
                        'box_papers'    => 'Box and Papers',
                        'case_material'    => 'Case Material',
                        'style'     => 'Style',
                        'condition'         => 'Condition',
                        'case_size'     => 'Case Size',
                        'bracelet'     => 'Bracelet',
                        'dial_color'     => 'Dial Color',
                        'age'     => 'Age',
                        'model_name'     => 'Model Name',
                        'model_number'         => 'Model Number',
                        'comments'     => 'Description',
                        'mandatory'     => 'PLEASE NOTE ALL * MARKED FIELDS ARE MANDATORY.',
                        'button'     => 'Next'
                       ],
                       
            'askquestion' => [
                        'page_title' => 'Ask A Question',
                        'subject' => 'SUBJECT',
                        'mes'    => 'QUESTION',
                        'button'    => 'SEND',
                      ], 
            'report' => [
                        'name'    => 'Name',
                        'email'    => 'Email',
                        'phone'    => 'Phone',
                        'mes'    => 'Message',
                        'button'    => 'SEND',
                      ], 

            'contact_us' => [
                    'form_title'    => 'Contact Us',
                    'name'          => 'Name',
                    'email'         => 'Email Address',
                    'subject'     => 'Subject',
                    'message'      => "Message",
                    'submit'        => 'Send'
                ],
];
