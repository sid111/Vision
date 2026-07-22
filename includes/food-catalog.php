<?php
if (!function_exists('ff_slug')) {
    function ff_slug($text)
    {
        return trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', (string) $text)), '-');
    }
}

if (!function_exists('ff_get_street_catalog')) {
    function ff_get_street_catalog($streetName, $streetLocation, $image = '')
    {
        $streetName = trim((string) $streetName);
        $streetLocation = trim((string) $streetLocation);
        $key = ff_slug($streetName . ' ' . $streetLocation);

        $catalogs = [
            'burns-road' => [
                'label' => 'Burns Road Food Street',
                'hero_image' => 'assets/images/buns%20rode.png',
                'location_image' => 'assets/images/buns%20rode.png',
                'gallery_images' => [
                    'assets/images/buns%20rode.png',
                    'assets/images/buns%20rode.png',
                    'assets/images/buns%20rode.png',
                    'assets/images/buns%20rode.png',
                    'assets/images/buns%20rode.png'
                ],
                'summary' => 'Burns Road is Karachi’s classic late-night food lane, known for nihari, biryani, rabri, tea, and family tables that stay busy well after dark.',
                'items' => [
                    [
                        'slug' => 'nihari',
                        'tag' => 'Nihari',
                        'name' => 'Burns Road Nihari House',
                        'description' => 'Slow-cooked beef nihari served with soft naan, ginger, lemon and a heavy gravy finish.',
                        'price' => 'From Rs. 520',
                        'rating' => 4.9,
                        'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1400',
                        'highlights' => ['Rich gravy', 'Bone marrow', 'Late-night favorite'],
                        'vendors' => [
                            ['name' => 'Nihari Bowl', 'price' => 'Rs. 520+', 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=900', 'note' => 'Deep, slow-cooked beef nihari with naan.'],
                            ['name' => 'Paya Point', 'price' => 'Rs. 480+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=900', 'note' => 'Paya served hot with herbs and chili.'],
                            ['name' => 'Naan Basket', 'price' => 'Rs. 140+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=900', 'note' => 'Fresh naan and roghni naan from the tandoor.']
                        ]
                    ],
                    [
                        'slug' => 'biryani',
                        'tag' => 'Biryani',
                        'name' => 'Burns Road Biryani Cart',
                        'description' => 'Aromatic Karachi biryani layered with spice, potatoes and raita on the side.',
                        'price' => 'From Rs. 320',
                        'rating' => 4.8,
                        'image' => 'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=1400',
                        'highlights' => ['Dum rice', 'Potato option', 'Raita included'],
                        'vendors' => [
                            ['name' => 'Biryani Plate', 'price' => 'Rs. 320+', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=900', 'note' => 'Dum style biryani with bold Karachi masala.'],
                            ['name' => 'Chicken Biryani Corner', 'price' => 'Rs. 350+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=900', 'note' => 'Chicken pieces with fragrant rice.'],
                            ['name' => 'Raita Stop', 'price' => 'Rs. 60+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1563379091339-03246963d198?w=900', 'note' => 'Cool raita, salad and mirch mix.']
                        ]
                    ],
                    [
                        'slug' => 'desserts',
                        'tag' => 'Desserts',
                        'name' => 'Burns Road Rabri Lane',
                        'description' => 'Rabri, kheer, gulab jamun and falooda glasses for a sweet finish.',
                        'price' => 'From Rs. 180',
                        'rating' => 4.7,
                        'image' => 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?w=1400',
                        'highlights' => ['Sweet end', 'Falooda cups', 'Cold rabri'],
                        'vendors' => [
                            ['name' => 'Rabri Bowl', 'price' => 'Rs. 180+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?w=900', 'note' => 'Creamy rabri topped with nuts.'],
                            ['name' => 'Falooda Cup', 'price' => 'Rs. 220+', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=900', 'note' => 'Falooda with jelly and kulfi.'],
                            ['name' => 'Kheer Spot', 'price' => 'Rs. 200+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=900', 'note' => 'Rice kheer served chilled or warm.']
                        ]
                    ],
                    [
                        'slug' => 'tea',
                        'tag' => 'Tea',
                        'name' => 'Burns Road Chai Point',
                        'description' => 'Doodh patti, qehwa and bun maska for the all-night crowd.',
                        'price' => 'From Rs. 90',
                        'rating' => 4.6,
                        'image' => 'https://images.unsplash.com/photo-1517022812141-23620dba5c23?w=1400',
                        'highlights' => ['Milk tea', 'Bun maska', 'All night'],
                        'vendors' => [
                            ['name' => 'Doodh Patti', 'price' => 'Rs. 90+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1517022812141-23620dba5c23?w=900', 'note' => 'Strong chai brewed the Burns Road way.'],
                            ['name' => 'Bun Maska', 'price' => 'Rs. 120+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=900', 'note' => 'Buttery bun with warm tea.'],
                            ['name' => 'Qehwa Stall', 'price' => 'Rs. 100+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=900', 'note' => 'Light green tea with herbs.']
                        ]
                    ]
                ]
            ],
            'do-darya' => [
                'label' => 'Do Darya Food Front',
                'hero_image' => 'assets/images/dodariya.png',
                'location_image' => 'assets/images/dodariya.png',
                'gallery_images' => [
                    'assets/images/dodariya.png',
                    'assets/images/dodariya.png',
                    'assets/images/dodariya.png',
                    'assets/images/dodariya.png',
                    'assets/images/dodariya.png'
                ],
                'summary' => 'Do Darya is the sea-view dining strip where seafood, grilled platters, continental dishes and sunset mocktails carry the evening.',
                'items' => [
                    [
                        'slug' => 'seafood',
                        'tag' => 'Seafood',
                        'name' => 'Do Darya Seafood Grill',
                        'description' => 'Fresh fish, prawns and grilled platters with seaside masala and lemon butter.',
                        'price' => 'From Rs. 1,250',
                        'rating' => 4.8,
                        'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1400',
                        'highlights' => ['Fresh catch', 'Butter glaze', 'Sea view'],
                        'vendors' => [
                            ['name' => 'Fish Grill', 'price' => 'Rs. 1,250+', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=900', 'note' => 'Grilled fish with lemon butter.'],
                            ['name' => 'Prawn Plate', 'price' => 'Rs. 1,450+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=900', 'note' => 'Jumbo prawns in seaside masala.'],
                            ['name' => 'Seafood Platter', 'price' => 'Rs. 1,850+', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1499028344343-cd173ffc68a9?w=900', 'note' => 'Mixed grill with fries and salad.']
                        ]
                    ],
                    [
                        'slug' => 'bbq',
                        'tag' => 'BBQ',
                        'name' => 'Do Darya BBQ Deck',
                        'description' => 'Charcoal kebabs, boti, malai tikka and naan served along the water.',
                        'price' => 'From Rs. 720',
                        'rating' => 4.7,
                        'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1400',
                        'highlights' => ['Smoky grill', 'Family tables', 'Naan hot'],
                        'vendors' => [
                            ['name' => 'Seekh Kebab Counter', 'price' => 'Rs. 720+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=900', 'note' => 'Soft kebabs with chutney.'],
                            ['name' => 'Malai Tikka Corner', 'price' => 'Rs. 850+', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=900', 'note' => 'Creamy tikka finished on coals.'],
                            ['name' => 'Boti Plate', 'price' => 'Rs. 980+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1514516345957-556ca7cb83a1?w=900', 'note' => 'Tender boti with fresh naan.']
                        ]
                    ],
                    [
                        'slug' => 'continental',
                        'tag' => 'Continental',
                        'name' => 'Sunset Continental Corner',
                        'description' => 'Creamy pasta, steaks and baked dishes for a longer dinner by the sea.',
                        'price' => 'From Rs. 1,100',
                        'rating' => 4.6,
                        'image' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1400',
                        'highlights' => ['Cream sauces', 'Dinner plates', 'Sea breeze'],
                        'vendors' => [
                            ['name' => 'Cream Pasta Bar', 'price' => 'Rs. 1,100+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=900', 'note' => 'Pasta with rich white sauce.'],
                            ['name' => 'Steak Plate', 'price' => 'Rs. 1,650+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=900', 'note' => 'Grilled steak with sides.'],
                            ['name' => 'Bake House', 'price' => 'Rs. 1,250+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1534939561126-855b8675edd7?w=900', 'note' => 'Baked casseroles and lasagna.']
                        ]
                    ],
                    [
                        'slug' => 'drinks',
                        'tag' => 'Drinks',
                        'name' => 'Ocean Breeze Drinks Stand',
                        'description' => 'Cool mocktails, fresh juices and lemonade for the waterfront crowd.',
                        'price' => 'From Rs. 220',
                        'rating' => 4.5,
                        'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=1400',
                        'highlights' => ['Chilled', 'Fresh fruit', 'Sunset favorite'],
                        'vendors' => [
                            ['name' => 'Mint Lemonade', 'price' => 'Rs. 220+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=900', 'note' => 'Mint, lemon and crushed ice.'],
                            ['name' => 'Mocktail Bar', 'price' => 'Rs. 280+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=900', 'note' => 'Fruit mocktails with sea-side garnish.'],
                            ['name' => 'Fresh Juice Cart', 'price' => 'Rs. 260+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?w=900', 'note' => 'Seasonal juice and chilled lassi.']
                        ]
                    ]
                ]
            ],
            'boat-basin' => [
                'label' => 'Boat Basin Food Street',
                'hero_image' => 'assets/images/boatbasan.png',
                'location_image' => 'assets/images/boatbasan.png',
                'gallery_images' => [
                    'assets/images/boatbasan.png',
                    'assets/images/boatbasan.png',
                    'assets/images/boatbasan.png',
                    'assets/images/boatbasan.png',
                    'assets/images/boatbasan.png'
                ],
                'summary' => 'Boat Basin is a busy all-evening food hub with burgers, rolls, fried snacks, shakes and big family platters.',
                'items' => [
                    [
                        'slug' => 'burgers',
                        'tag' => 'Burgers',
                        'name' => 'Boat Basin Burger Hub',
                        'description' => 'Loaded burgers, crispy chicken fillets and stacked sauces that stay popular after sunset.',
                        'price' => 'From Rs. 580',
                        'rating' => 4.7,
                        'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1400',
                        'highlights' => ['Crispy fillet', 'Loaded sauces', 'Quick bites'],
                        'vendors' => [
                            ['name' => 'Zinger Burger', 'price' => 'Rs. 580+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=900', 'note' => 'Crispy fillet burger with fries.'],
                            ['name' => 'Double Stack Burger', 'price' => 'Rs. 780+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1553979459-d2229ba7433b?w=900', 'note' => 'Double patty with cheese.'],
                            ['name' => 'Chicken Smash', 'price' => 'Rs. 650+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1561758033-d89a9ad46330?w=900', 'note' => 'Smash burger with spicy mayo.']
                        ]
                    ],
                    [
                        'slug' => 'rolls',
                        'tag' => 'Rolls',
                        'name' => 'Boat Basin Roll Point',
                        'description' => 'Paratha rolls, shawarma wraps and spicy chicken rolls for a quick night stop.',
                        'price' => 'From Rs. 320',
                        'rating' => 4.6,
                        'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=1400',
                        'highlights' => ['Paratha wrap', 'Garlic dip', 'Fast serve'],
                        'vendors' => [
                            ['name' => 'Chicken Roll', 'price' => 'Rs. 320+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=900', 'note' => 'Chicken roll with spicy chutney.'],
                            ['name' => 'Shawarma Wrap', 'price' => 'Rs. 380+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=900', 'note' => 'Garlic shawarma with pickles.'],
                            ['name' => 'Double Filling Roll', 'price' => 'Rs. 460+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?w=900', 'note' => 'Loaded roll with double filling.']
                        ]
                    ],
                    [
                        'slug' => 'fries',
                        'tag' => 'Fries',
                        'name' => 'Boat Basin Fries Cart',
                        'description' => 'Masala fries, cheese fries and peri peri fries for the snack crowd.',
                        'price' => 'From Rs. 260',
                        'rating' => 4.5,
                        'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=1400',
                        'highlights' => ['Loaded', 'Cheesy', 'Snack box'],
                        'vendors' => [
                            ['name' => 'Masala Fries', 'price' => 'Rs. 260+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=900', 'note' => 'Spicy fries with dip.'],
                            ['name' => 'Cheese Fries', 'price' => 'Rs. 320+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1579567761406-4684ee0c75b6?w=900', 'note' => 'Melted cheese and herbs.'],
                            ['name' => 'Peri Peri Fries', 'price' => 'Rs. 300+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1630384060421-cb20d0e8d6b3?w=900', 'note' => 'Tangy seasoning with dip.']
                        ]
                    ],
                    [
                        'slug' => 'shakes',
                        'tag' => 'Shakes',
                        'name' => 'Boat Basin Shake Spot',
                        'description' => 'Thick shakes, cold coffee and milkshakes that run through the night.',
                        'price' => 'From Rs. 220',
                        'rating' => 4.5,
                        'image' => 'https://images.unsplash.com/photo-1517093157656-b9eccef91cb1?w=1400',
                        'highlights' => ['Cold drinks', 'Creamy texture', 'Late night'],
                        'vendors' => [
                            ['name' => 'Chocolate Shake', 'price' => 'Rs. 220+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1517093157656-b9eccef91cb1?w=900', 'note' => 'Creamy chocolate milkshake.'],
                            ['name' => 'Oreo Shake', 'price' => 'Rs. 260+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=900', 'note' => 'Thick cookies and cream shake.'],
                            ['name' => 'Cold Coffee', 'price' => 'Rs. 200+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=900', 'note' => 'Chilled coffee with foam.']
                        ]
                    ]
                ]
            ],
            'hussainabad' => [
                'label' => 'Hussainabad Food Street',
                'hero_image' => 'assets/images/hussainabad.png',
                'location_image' => 'assets/images/hussainabad.png',
                'gallery_images' => [
                    'assets/images/hussainabad.png',
                    'assets/images/hussainabad.png',
                    'assets/images/hussainabad.png',
                    'assets/images/hussainabad.png',
                    'assets/images/hussainabad.png'
                ],
                'summary' => 'Hussainabad comes alive at night with chana chaat, BBQ, rolls, kulfi and plenty of quick stalls for the rush hours.',
                'items' => [
                    [
                        'slug' => 'chaat',
                        'tag' => 'Chaat',
                        'name' => 'Hussainabad Chana Chaat Cart',
                        'description' => 'Tangy chana chaat, papri chaat, dahi baray and spicy street bowls.',
                        'price' => 'From Rs. 220',
                        'rating' => 4.8,
                        'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=1400',
                        'highlights' => ['Tangy section', 'Fresh yogurt', 'Crowd favorite'],
                        'vendors' => [
                            ['name' => 'Chana Chaat Cart', 'price' => 'Rs. 220+', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=900', 'note' => 'Spicy chana with papri and chutney.'],
                            ['name' => 'Papri Chaat Stall', 'price' => 'Rs. 260+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=900', 'note' => 'Crunchy papri and sweet tamarind.'],
                            ['name' => 'Dahi Baray Counter', 'price' => 'Rs. 240+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1560717845-968823efbee1?w=900', 'note' => 'Cool yogurt, chutney and masala.']
                        ]
                    ],
                    [
                        'slug' => 'bbq',
                        'tag' => 'BBQ',
                        'name' => 'Hussainabad BBQ Corner',
                        'description' => 'Seekh kebab, boti, malai tikka and smoky plates from the charcoal grill.',
                        'price' => 'From Rs. 420',
                        'rating' => 4.7,
                        'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1400',
                        'highlights' => ['Charcoal fire', 'Naan hot', 'Late night grill'],
                        'vendors' => [
                            ['name' => 'Seekh Kebab Cart', 'price' => 'Rs. 420+', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=900', 'note' => 'Juicy kebabs with chutney.'],
                            ['name' => 'Malai Boti Stand', 'price' => 'Rs. 520+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=900', 'note' => 'Creamy boti served hot.'],
                            ['name' => 'Charcoal Grill', 'price' => 'Rs. 600+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1514516345957-556ca7cb83a1?w=900', 'note' => 'Mixed grill with fresh naan.']
                        ]
                    ],
                    [
                        'slug' => 'rolls',
                        'tag' => 'Rolls',
                        'name' => 'Hussainabad Roll Shops',
                        'description' => 'Chicken rolls, beef wraps and paratha rolls with spicy mayo and pickles.',
                        'price' => 'From Rs. 320',
                        'rating' => 4.6,
                        'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=1400',
                        'highlights' => ['Quick bites', 'Paratha wrap', 'Late night grab'],
                        'vendors' => [
                            ['name' => 'Paratha Roll Shop', 'price' => 'Rs. 320+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=900', 'note' => 'Chicken and beef rolls.'],
                            ['name' => 'Shawarma Stand', 'price' => 'Rs. 380+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=900', 'note' => 'Garlic sauce with pickles.'],
                            ['name' => 'Loaded Wrap Cart', 'price' => 'Rs. 420+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=900', 'note' => 'Seekh kebab wrap with fries.']
                        ]
                    ],
                    [
                        'slug' => 'desserts',
                        'tag' => 'Desserts',
                        'name' => 'Hussainabad Kulfi Lane',
                        'description' => 'Kulfi, falooda, brownies and sweet cups for a cold ending after the grill.',
                        'price' => 'From Rs. 180',
                        'rating' => 4.6,
                        'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=1400',
                        'highlights' => ['Cold dessert', 'Pistachio', 'After dinner'],
                        'vendors' => [
                            ['name' => 'Kulfi Corner', 'price' => 'Rs. 180+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=900', 'note' => 'Pista, mango and malai kulfi.'],
                            ['name' => 'Falooda Stall', 'price' => 'Rs. 250+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1565172362817-8c54f0a5f4a3?w=900', 'note' => 'Falooda with jelly and kulfi.'],
                            ['name' => 'Sweet Cup Cart', 'price' => 'Rs. 220+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=900', 'note' => 'Dessert cups and brownie jars.']
                        ]
                    ]
                ]
            ],
            'bahadurabad' => [
                'label' => 'Bahadurabad Food Street',
                'hero_image' => 'assets/images/bahadurabad.png',
                'location_image' => 'assets/images/bahadurabad.png',
                'gallery_images' => [
                    'assets/images/bahadurabad.png',
                    'assets/images/bahadurabad.png',
                    'assets/images/bahadurabad.png',
                    'assets/images/bahadurabad.png',
                    'assets/images/bahadurabad.png'
                ],
                'summary' => 'Bahadurabad is a dependable food stop for bun kabab, burgers, biryani, sweets and family-friendly evening snacks.',
                'items' => [
                    [
                        'slug' => 'bun-kabab',
                        'tag' => 'Bun Kabab',
                        'name' => 'Bahadurabad Bun Kabab Point',
                        'description' => 'Classic bun kabab with chutney, egg, and a Karachi-style spicy bite.',
                        'price' => 'From Rs. 150',
                        'rating' => 4.7,
                        'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1400',
                        'highlights' => ['Street classic', 'Egg option', 'Quick serve'],
                        'vendors' => [
                            ['name' => 'Bun Kabab', 'price' => 'Rs. 150+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=900', 'note' => 'Soft bun with spicy kabab.'],
                            ['name' => 'Anda Shami', 'price' => 'Rs. 170+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1553979459-d2229ba7433b?w=900', 'note' => 'Egg-topped shami burger.'],
                            ['name' => 'Aloo Tikki Bun', 'price' => 'Rs. 130+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1561758033-d89a9ad46330?w=900', 'note' => 'Budget-friendly vegetarian bite.']
                        ]
                    ],
                    [
                        'slug' => 'biryani',
                        'tag' => 'Biryani',
                        'name' => 'Bahadurabad Biryani Counter',
                        'description' => 'Spicy chicken biryani, beef biryani and raita cups for a proper Karachi lunch.',
                        'price' => 'From Rs. 300',
                        'rating' => 4.6,
                        'image' => 'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=1400',
                        'highlights' => ['Dum rice', 'Raita cup', 'Lunch favorite'],
                        'vendors' => [
                            ['name' => 'Chicken Biryani', 'price' => 'Rs. 300+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=900', 'note' => 'Classic Karachi chicken biryani.'],
                            ['name' => 'Beef Biryani', 'price' => 'Rs. 360+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=900', 'note' => 'Beef pieces with biryani masala.'],
                            ['name' => 'Raita Station', 'price' => 'Rs. 50+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1563379091339-03246963d198?w=900', 'note' => 'Plain and mint raita cups.']
                        ]
                    ],
                    [
                        'slug' => 'sweets',
                        'tag' => 'Sweets',
                        'name' => 'Bahadurabad Sweet Shop Lane',
                        'description' => 'Falooda, rabri, gulab jamun and ice cream for the after-dinner crowd.',
                        'price' => 'From Rs. 180',
                        'rating' => 4.7,
                        'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=1400',
                        'highlights' => ['Sweet finish', 'Falooda cups', 'Chilled plates'],
                        'vendors' => [
                            ['name' => 'Falooda House', 'price' => 'Rs. 180+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=900', 'note' => 'Falooda with jelly and kulfi.'],
                            ['name' => 'Rabri Shop', 'price' => 'Rs. 220+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?w=900', 'note' => 'Thick rabri and sweet cream.'],
                            ['name' => 'Gulab Jamun Cart', 'price' => 'Rs. 160+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=900', 'note' => 'Warm syrup-soaked gulab jamun.']
                        ]
                    ],
                    [
                        'slug' => 'fries',
                        'tag' => 'Fries',
                        'name' => 'Bahadurabad Fries Stand',
                        'description' => 'Masala fries, cheesy fries and spice-loaded boxes for quick street cravings.',
                        'price' => 'From Rs. 260',
                        'rating' => 4.5,
                        'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=1400',
                        'highlights' => ['Loaded fries', 'Cheese dip', 'Snack box'],
                        'vendors' => [
                            ['name' => 'Masala Fries', 'price' => 'Rs. 260+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=900', 'note' => 'Crunchy fries with spice dust.'],
                            ['name' => 'Cheese Box', 'price' => 'Rs. 320+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1579567761406-4684ee0c75b6?w=900', 'note' => 'Melted cheese and herbs.'],
                            ['name' => 'Chicken Loaded Fries', 'price' => 'Rs. 420+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1512152272829-e3139592d56f?w=900', 'note' => 'Chicken topping with sauce.']
                        ]
                    ]
                ]
            ],
            'zamzama' => [
                'label' => 'Zamzama Food Street',
                'hero_image' => 'assets/images/zamzama.png',
                'location_image' => 'assets/images/zamzama.png',
                'gallery_images' => [
                    'assets/images/zamzama.png',
                    'assets/images/zamzama.png',
                    'assets/images/zamzama.png',
                    'assets/images/zamzama.png',
                    'assets/images/zamzama.png'
                ],
                'summary' => 'Zamzama mixes upscale dessert bars, coffee counters, small gourmet bites and late-night snack carts in a polished dining strip.',
                'items' => [
                    [
                        'slug' => 'desserts',
                        'tag' => 'Desserts',
                        'name' => 'Zamzama Dessert Bar',
                        'description' => 'Dessert bars, ice cream cups, brownie jars and waffle counters for after-dinner cravings.',
                        'price' => 'From Rs. 260',
                        'rating' => 4.8,
                        'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=1400',
                        'highlights' => ['Sweet section', 'Multiple carts', 'Late night favorite'],
                        'vendors' => [
                            ['name' => 'Sweet Corner', 'price' => 'Rs. 280+', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=900', 'note' => 'Brownies, sundaes and dessert cups.'],
                            ['name' => 'Waffle Point', 'price' => 'Rs. 360+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1562376552-0d160a2f53b0?w=900', 'note' => 'Fresh waffles with chocolate drizzle.'],
                            ['name' => 'Ice Cream Cart', 'price' => 'Rs. 260+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1488900128323-21503983a07e?w=900', 'note' => 'Scoops, cones and sundaes.']
                        ]
                    ],
                    [
                        'slug' => 'coffee',
                        'tag' => 'Coffee',
                        'name' => 'Zamzama Coffee Corner',
                        'description' => 'Espresso drinks, cold brew, mochas and quick café stops for the after-work crowd.',
                        'price' => 'From Rs. 360',
                        'rating' => 4.7,
                        'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400',
                        'highlights' => ['Specialty coffee', 'Cold brew', 'Work friendly'],
                        'vendors' => [
                            ['name' => 'Cappuccino Bar', 'price' => 'Rs. 360+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900', 'note' => 'Rich espresso with silky foam.'],
                            ['name' => 'Cold Brew Stand', 'price' => 'Rs. 420+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=900', 'note' => 'Smooth cold brew and iced lattes.'],
                            ['name' => 'Mocha Cart', 'price' => 'Rs. 400+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=900', 'note' => 'Mocha and frappe styles.']
                        ]
                    ],
                    [
                        'slug' => 'rolls',
                        'tag' => 'Rolls',
                        'name' => 'Zamzama Roll House',
                        'description' => 'Paratha rolls, shawarma wraps and grilled wraps with a more premium street feel.',
                        'price' => 'From Rs. 420',
                        'rating' => 4.6,
                        'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=1400',
                        'highlights' => ['Quick bites', 'Paratha wrap', 'Late night grab'],
                        'vendors' => [
                            ['name' => 'Paratha Roll Shop', 'price' => 'Rs. 420+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=900', 'note' => 'Chicken and beef rolls.'],
                            ['name' => 'Shawarma Stand', 'price' => 'Rs. 380+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=900', 'note' => 'Garlic sauce with pickles.'],
                            ['name' => 'Kebab Wrap Cart', 'price' => 'Rs. 450+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=900', 'note' => 'Seekh kebab wrap with fries.']
                        ]
                    ],
                    [
                        'slug' => 'gourmet-bites',
                        'tag' => 'Bites',
                        'name' => 'Zamzama Gourmet Bites',
                        'description' => 'Small plates, sliders, pasta bowls and shareable snack carts with a more modern menu.',
                        'price' => 'From Rs. 560',
                        'rating' => 4.6,
                        'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=1400',
                        'highlights' => ['Premium snacks', 'Shareable plates', 'Evening hangout'],
                        'vendors' => [
                            ['name' => 'Mini Slider Cart', 'price' => 'Rs. 560+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1561758033-d89a9ad46330?w=900', 'note' => 'Mini burgers with house sauce.'],
                            ['name' => 'Pasta Cup', 'price' => 'Rs. 680+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=900', 'note' => 'Creamy pasta in a compact plate.'],
                            ['name' => 'Snack Box', 'price' => 'Rs. 520+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1482049016688-2d3e1b311543?w=900', 'note' => 'Fries, bites and dip.']
                        ]
                    ]
                ]
            ]
        ];

        if (isset($catalogs[$key])) {
            return $catalogs[$key];
        }

        foreach ($catalogs as $needle => $catalog) {
            if ($needle !== '' && str_contains($key, $needle)) {
                return $catalog;
            }
        }

        $fallbackLabel = $streetName !== '' ? $streetName . ' Food Street' : 'Food Street';
        $fallbackImage = 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1800';

        return [
            'label' => $fallbackLabel,
            'hero_image' => $fallbackImage,
            'location_image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=1800',
            'gallery_images' => [
                $fallbackImage,
                'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=900',
                'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=900',
                'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=900',
                'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=900'
            ],
            'summary' => trim($streetName . ' ' . $streetLocation) !== '' ? trim($streetName . ' ' . $streetLocation) . ' offers a local food street experience with carts, snacks and family-friendly evening bites.' : 'This food street offers a local food street experience with carts, snacks and family-friendly evening bites.',
            'items' => [
                [
                    'slug' => 'street-food',
                    'tag' => 'Street Food',
                    'name' => $fallbackLabel,
                    'description' => 'A mix of carts, snacks and quick bites that belong to this street.',
                    'price' => 'From Rs. 200',
                    'rating' => 4.5,
                    'image' => $fallbackImage,
                    'highlights' => ['Local favorites', 'Fresh carts', 'Night crowd'],
                    'vendors' => [
                        ['name' => 'Snack Cart', 'price' => 'Rs. 200+', 'rating' => 4.5, 'image' => $fallbackImage, 'note' => 'A compact local snack cart.']
                    ]
                ]
            ]
        ];
    }
}
