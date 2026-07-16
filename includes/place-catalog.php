<?php
if (!function_exists('ff_slug')) {
    function ff_slug($text)
    {
        return trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', (string) $text)), '-');
    }
}

if (!function_exists('ff_get_restaurant_catalog')) {
    function ff_get_restaurant_catalog($restaurantName)
    {
        $key = ff_slug($restaurantName);
        $catalogs = [
            'kolahi' => [
                'summary' => 'Kolahi is a sea-view Pakistani restaurant known for BBQ platters, handi, biryani and rich Karachi-style comfort food.',
                'hero_image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=900',
                    'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=900',
                    'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=900',
                    'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=900',
                    'https://images.unsplash.com/photo-1544025162-d76694265947?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'bbq', 'tag' => 'BBQ', 'name' => 'Special BBQ Platter', 'description' => 'Chicken, beef and mutton grilled with house spices.', 'price' => 'Rs. 2,850', 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1400'],
                    ['slug' => 'handi', 'tag' => 'Handi', 'name' => 'Chicken Handi', 'description' => 'Creamy boneless handi with rich gravy and naan.', 'price' => 'Rs. 1,650', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=1400'],
                    ['slug' => 'biryani', 'tag' => 'Biryani', 'name' => 'Matka Biryani', 'description' => 'Aromatic matka biryani with raita and spice.', 'price' => 'Rs. 1,250', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=1400'],
                    ['slug' => 'ribs', 'tag' => 'Ribs', 'name' => 'Mutton Ribs', 'description' => 'Tender mutton ribs glazed in BBQ sauce.', 'price' => 'Rs. 2,350', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1400'],
                    ['slug' => 'burger', 'tag' => 'Burger', 'name' => 'Zinger Burger Meal', 'description' => 'Crispy burger with fries and dip.', 'price' => 'Rs. 950', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1400'],
                    ['slug' => 'karahi', 'tag' => 'Karahi', 'name' => 'Chicken Karahi', 'description' => 'Fresh karahi with tomato, ginger and chili.', 'price' => 'Rs. 1,850', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Molten Lava Cake', 'description' => 'Warm chocolate dessert with vanilla ice cream.', 'price' => 'Rs. 720', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=1400'],
                    ['slug' => 'drinks', 'tag' => 'Drinks', 'name' => 'Mint Margarita', 'description' => 'Fresh mint and lemon cooler over ice.', 'price' => 'Rs. 420', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=1400']
                ]
            ],
            'bbq-tonight' => [
                'summary' => 'BBQ Tonight focuses on classic grill platters, karahi, curries and generous family-style servings.',
                'hero_image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=900',
                    'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=900',
                    'https://images.unsplash.com/photo-1544025162-d76694265947?w=900',
                    'https://images.unsplash.com/photo-1550966871-3ed3cdb5ed0c?w=900',
                    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'bbq', 'tag' => 'BBQ', 'name' => 'Signature BBQ Platter', 'description' => 'Mixed chicken, beef and mutton grill platter.', 'price' => 'Rs. 2,650', 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1400'],
                    ['slug' => 'malai-boti', 'tag' => 'Boti', 'name' => 'Malai Boti', 'description' => 'Creamy boneless boti with soft texture.', 'price' => 'Rs. 1,450', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1400'],
                    ['slug' => 'karahi', 'tag' => 'Karahi', 'name' => 'Chicken Karahi', 'description' => 'House masala karahi served hot.', 'price' => 'Rs. 1,750', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=1400'],
                    ['slug' => 'naan', 'tag' => 'Breads', 'name' => 'Fresh Naan Basket', 'description' => 'Roghni naan and tandoor breads.', 'price' => 'Rs. 220', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Gulab Jamun', 'description' => 'Warm syrup-soaked classic dessert.', 'price' => 'Rs. 380', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1400'],
                    ['slug' => 'drinks', 'tag' => 'Drinks', 'name' => 'Mint Lemonade', 'description' => 'Chilled mint lemonade with ice.', 'price' => 'Rs. 260', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?w=1400']
                ]
            ],
            'al-bustan' => [
                'summary' => 'Al Bustan is a Lebanese and Middle Eastern dining room with mezze, grills, wraps and fresh juices.',
                'hero_image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=900',
                    'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=900',
                    'https://images.unsplash.com/photo-1499028344343-cd173ffc68a9?w=900',
                    'https://images.unsplash.com/photo-1544025162-d76694265947?w=900',
                    'https://images.unsplash.com/photo-1534939561126-855b8675edd7?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'mezze', 'tag' => 'Mezze', 'name' => 'Hummus & Pita Platter', 'description' => 'Hummus, tahini and warm pita bread.', 'price' => 'Rs. 1,150', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=1400'],
                    ['slug' => 'falafel', 'tag' => 'Falafel', 'name' => 'Falafel Plate', 'description' => 'Crispy falafel with salad and dip.', 'price' => 'Rs. 980', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=1400'],
                    ['slug' => 'grill', 'tag' => 'Grill', 'name' => 'Chicken Shawarma Grill', 'description' => 'Grilled chicken with garlic sauce.', 'price' => 'Rs. 1,250', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=1400'],
                    ['slug' => 'wraps', 'tag' => 'Wraps', 'name' => 'Mixed Wrap Platter', 'description' => 'Chicken and beef wraps with fries.', 'price' => 'Rs. 1,380', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=1400'],
                    ['slug' => 'salads', 'tag' => 'Salads', 'name' => 'Fattoush Salad', 'description' => 'Fresh vegetables with toasted bread.', 'price' => 'Rs. 850', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Kunafa', 'description' => 'Sweet kunafa served warm.', 'price' => 'Rs. 720', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=1400']
                ]
            ],
            'saltanat' => [
                'summary' => 'Saltanat is a royal Mughlai dining room built around rich curries, handi, kebabs and traditional platters.',
                'hero_image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=900',
                    'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=900',
                    'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=900',
                    'https://images.unsplash.com/photo-1544025162-d76694265947?w=900',
                    'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'mughlai-platter', 'tag' => 'Platter', 'name' => 'Mughlai Royal Platter', 'description' => 'Slow cooked meat, kebabs and rice.', 'price' => 'Rs. 3,050', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=1400'],
                    ['slug' => 'mutton-karahi', 'tag' => 'Karahi', 'name' => 'Mutton Karahi', 'description' => 'Rich mutton karahi with house masala.', 'price' => 'Rs. 2,450', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=1400'],
                    ['slug' => 'biryani', 'tag' => 'Biryani', 'name' => 'Royal Biryani', 'description' => 'Aromatic rice with tender meat.', 'price' => 'Rs. 1,450', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=1400'],
                    ['slug' => 'kebabs', 'tag' => 'Kebabs', 'name' => 'Seekh Kebab Plate', 'description' => 'Grilled kebabs with chutney.', 'price' => 'Rs. 1,280', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1400'],
                    ['slug' => 'naan', 'tag' => 'Breads', 'name' => 'Tandoori Naan Basket', 'description' => 'Fresh breads from the tandoor.', 'price' => 'Rs. 260', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1571091718767-18b5b1457add?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Firni', 'description' => 'Classic Mughlai rice dessert.', 'price' => 'Rs. 540', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=1400']
                ]
            ],
            'ginsoy' => [
                'summary' => 'Ginsoy is a Chinese restaurant with wok dishes, noodles, fried rice and shareable starters.',
                'hero_image' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=900',
                    'https://images.unsplash.com/photo-1525755662778-989d0524087e?w=900',
                    'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=900',
                    'https://images.unsplash.com/photo-1547592180-85f173990554?w=900',
                    'https://images.unsplash.com/photo-1559847844-5315695dadae?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'noodles', 'tag' => 'Noodles', 'name' => 'Chicken Chow Mein', 'description' => 'Wok tossed noodles with chicken and vegetables.', 'price' => 'Rs. 1,150', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=1400'],
                    ['slug' => 'fried-rice', 'tag' => 'Rice', 'name' => 'Special Fried Rice', 'description' => 'Rice with egg, chicken and veggies.', 'price' => 'Rs. 1,050', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1512058564366-18510be2db19?w=1400'],
                    ['slug' => 'manchurian', 'tag' => 'Saucy', 'name' => 'Chicken Manchurian', 'description' => 'Sweet and spicy Chinese-style gravy.', 'price' => 'Rs. 1,280', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=1400'],
                    ['slug' => 'starter', 'tag' => 'Starter', 'name' => 'Spring Rolls', 'description' => 'Crispy rolls with dipping sauce.', 'price' => 'Rs. 680', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1498654896293-37aacf113fd9?w=1400'],
                    ['slug' => 'chilli', 'tag' => 'Dry', 'name' => 'Chilli Chicken', 'description' => 'Spicy dry chilli chicken stir fry.', 'price' => 'Rs. 1,220', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Honey Noodles', 'description' => 'Sweet fried noodles with ice cream.', 'price' => 'Rs. 750', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=1400']
                ]
            ]
        ];

        return $catalogs[$key] ?? [
            'summary' => 'A curated restaurant menu built around the place you opened.',
            'hero_image' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1800',
            'location_image' => 'https://images.unsplash.com/photo-1504674900247-0877df9cc836?w=1800',
            'gallery_images' => [
                'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=900',
                'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=900',
                'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=900',
                'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=900'
            ],
            'menu_items' => []
        ];
    }
}

if (!function_exists('ff_get_cafe_catalog')) {
    function ff_get_cafe_catalog($cafeName)
    {
        $key = ff_slug($cafeName);
        $catalogs = [
            'coffee-wagon' => [
                'summary' => 'Coffee Wagon is a cozy Clifton cafe with espresso drinks, brunch plates and bakery items.',
                'hero_image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900',
                    'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=900',
                    'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=900',
                    'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'cappuccino', 'tag' => 'Coffee', 'name' => 'Signature Cappuccino', 'description' => 'Rich espresso with silky foam.', 'price' => 'Rs. 650', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'],
                    ['slug' => 'latte', 'tag' => 'Coffee', 'name' => 'Vanilla Latte', 'description' => 'Creamy latte with vanilla syrup.', 'price' => 'Rs. 680', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=1400'],
                    ['slug' => 'cold-brew', 'tag' => 'Cold Brew', 'name' => 'Cold Brew', 'description' => 'Slow brewed and chilled coffee.', 'price' => 'Rs. 720', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1400'],
                    ['slug' => 'croissant', 'tag' => 'Bakery', 'name' => 'Butter Croissant', 'description' => 'Flaky butter croissant.', 'price' => 'Rs. 390', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1400'],
                    ['slug' => 'breakfast', 'tag' => 'Breakfast', 'name' => 'English Breakfast', 'description' => 'Eggs, toast and sausages.', 'price' => 'Rs. 1,450', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Lotus Cheesecake', 'description' => 'Creamy cheesecake with lotus crumb.', 'price' => 'Rs. 820', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=1400']
                ]
            ],
            'espresso' => [
                'summary' => 'Espresso in Bahadurabad is built around strong coffee, brunch plates and all-day quick bites.',
                'hero_image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=900',
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900',
                    'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=900',
                    'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'espresso-shot', 'tag' => 'Coffee', 'name' => 'Espresso Shot', 'description' => 'Bold double shot espresso.', 'price' => 'Rs. 420', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'],
                    ['slug' => 'americano', 'tag' => 'Coffee', 'name' => 'Americano', 'description' => 'Smooth black coffee with deep flavor.', 'price' => 'Rs. 450', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1400'],
                    ['slug' => 'latte', 'tag' => 'Coffee', 'name' => 'Caramel Latte', 'description' => 'Latte with caramel syrup.', 'price' => 'Rs. 680', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=1400'],
                    ['slug' => 'mocha', 'tag' => 'Coffee', 'name' => 'Mocha Frappe', 'description' => 'Coffee and cocoa blended chilled.', 'price' => 'Rs. 760', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=1400'],
                    ['slug' => 'sandwich', 'tag' => 'Snack', 'name' => 'Club Sandwich', 'description' => 'Toasted chicken club with fries.', 'price' => 'Rs. 980', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Lotus Cheesecake', 'description' => 'Creamy cheesecake slice.', 'price' => 'Rs. 820', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=1400']
                ]
            ],
            'cafe-aylanto' => [
                'summary' => 'Cafe Aylanto is an upscale Zamzama cafe with premium coffee, brunch, sandwiches and desserts.',
                'hero_image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900',
                    'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=900',
                    'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900',
                    'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'special-brew', 'tag' => 'Brew', 'name' => 'Special Brew', 'description' => 'House brew with balanced notes.', 'price' => 'Rs. 720', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'],
                    ['slug' => 'latte', 'tag' => 'Coffee', 'name' => 'Vanilla Latte', 'description' => 'Creamy latte with vanilla.', 'price' => 'Rs. 680', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=1400'],
                    ['slug' => 'croissant', 'tag' => 'Bakery', 'name' => 'Butter Croissant', 'description' => 'Fresh baked butter croissant.', 'price' => 'Rs. 390', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1400'],
                    ['slug' => 'club-sandwich', 'tag' => 'Snack', 'name' => 'Club Sandwich', 'description' => 'Toasted sandwich with fries.', 'price' => 'Rs. 980', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=1400'],
                    ['slug' => 'breakfast', 'tag' => 'Breakfast', 'name' => 'English Breakfast', 'description' => 'Eggs, toast and sides.', 'price' => 'Rs. 1,450', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Lotus Cheesecake', 'description' => 'House cheesecake with lotus crumb.', 'price' => 'Rs. 820', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=1400']
                ]
            ],
            'ginoxy' => [
                'summary' => 'Ginoxy is a Clifton cafe with trendy coffee blends, cakes, sandwiches and a work-friendly vibe.',
                'hero_image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=900',
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900',
                    'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=900',
                    'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'frappuccino', 'tag' => 'Coffee', 'name' => 'Frappuccino', 'description' => 'Chilled blended coffee.', 'price' => 'Rs. 700', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=1400'],
                    ['slug' => 'espresso', 'tag' => 'Coffee', 'name' => 'Espresso', 'description' => 'Bold coffee shot.', 'price' => 'Rs. 420', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'],
                    ['slug' => 'turkish-coffee', 'tag' => 'Coffee', 'name' => 'Turkish Coffee', 'description' => 'Rich and aromatic coffee.', 'price' => 'Rs. 580', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1400'],
                    ['slug' => 'cake', 'tag' => 'Desserts', 'name' => 'Chocolate Cake', 'description' => 'Soft chocolate cake slice.', 'price' => 'Rs. 620', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=1400'],
                    ['slug' => 'toast', 'tag' => 'Snack', 'name' => 'Avocado Toast', 'description' => 'Toasted bread with avocado and eggs.', 'price' => 'Rs. 890', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=1400'],
                    ['slug' => 'pasta', 'tag' => 'Brunch', 'name' => 'Creamy Pasta', 'description' => 'Light pasta bowl for brunch.', 'price' => 'Rs. 1,050', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1498837167922-ddd27525d352?w=1400']
                ]
            ],
            'evergreen-cafe' => [
                'summary' => 'Evergreen Cafe is a garden-themed brunch cafe built for slow breakfasts and coffee breaks.',
                'hero_image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=900',
                    'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=900',
                    'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900',
                    'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'breakfast', 'tag' => 'Breakfast', 'name' => 'English Breakfast', 'description' => 'Eggs, toast, beans and sausage.', 'price' => 'Rs. 1,450', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=1400'],
                    ['slug' => 'cold-coffee', 'tag' => 'Coffee', 'name' => 'Cold Coffee', 'description' => 'Chilled coffee with a smooth finish.', 'price' => 'Rs. 620', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1400'],
                    ['slug' => 'frappuccino', 'tag' => 'Coffee', 'name' => 'Frappuccino', 'description' => 'Blended coffee with cream.', 'price' => 'Rs. 720', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=1400'],
                    ['slug' => 'croissant', 'tag' => 'Bakery', 'name' => 'Butter Croissant', 'description' => 'Fresh baked butter croissant.', 'price' => 'Rs. 390', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1400'],
                    ['slug' => 'salad', 'tag' => 'Light', 'name' => 'Garden Salad', 'description' => 'Fresh greens and light dressing.', 'price' => 'Rs. 780', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1547592180-85f173990554?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Lotus Cheesecake', 'description' => 'Sweet house cheesecake slice.', 'price' => 'Rs. 820', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=1400']
                ]
            ],
            'big-tree-house-cafe' => [
                'summary' => 'Big Tree House Cafe in DHA is a relaxed coffee spot with espresso drinks, tea and brunch items.',
                'hero_image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900',
                    'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=900',
                    'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900',
                    'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'specialty-coffee', 'tag' => 'Coffee', 'name' => 'Specialty Coffee', 'description' => 'House espresso blend.', 'price' => 'Rs. 680', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'],
                    ['slug' => 'tea', 'tag' => 'Tea', 'name' => 'Signature Tea', 'description' => 'Freshly brewed tea selection.', 'price' => 'Rs. 320', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1517022812141-23620dba5c23?w=1400'],
                    ['slug' => 'espresso', 'tag' => 'Coffee', 'name' => 'Espresso', 'description' => 'Bold espresso shot.', 'price' => 'Rs. 420', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'],
                    ['slug' => 'croissant', 'tag' => 'Bakery', 'name' => 'Croissant', 'description' => 'Flaky butter croissant.', 'price' => 'Rs. 390', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1400'],
                    ['slug' => 'sandwich', 'tag' => 'Snack', 'name' => 'Club Sandwich', 'description' => 'Toasted sandwich with fries.', 'price' => 'Rs. 980', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Cheesecake', 'description' => 'Rich cheesecake slice.', 'price' => 'Rs. 820', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=1400']
                ]
            ],
            'cafe-flo' => [
                'summary' => 'Cafe Flo is a French-inspired Clifton cafe with pastries, espresso, brunch and desserts.',
                'hero_image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1559925393-8be0ec4767c8?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=900',
                    'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=900',
                    'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900',
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'french-press', 'tag' => 'Coffee', 'name' => 'French Press', 'description' => 'Smooth brew with a clean finish.', 'price' => 'Rs. 720', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'],
                    ['slug' => 'latte', 'tag' => 'Coffee', 'name' => 'Café Latte', 'description' => 'Creamy latte with silky milk.', 'price' => 'Rs. 680', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=1400'],
                    ['slug' => 'pastry', 'tag' => 'Bakery', 'name' => 'Butter Pastry', 'description' => 'Fresh pastry with a light filling.', 'price' => 'Rs. 450', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1400'],
                    ['slug' => 'omelette', 'tag' => 'Breakfast', 'name' => 'Cheese Omelette', 'description' => 'Soft omelette with cheese and toast.', 'price' => 'Rs. 880', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=1400'],
                    ['slug' => 'sandwich', 'tag' => 'Snack', 'name' => 'Chicken Sandwich', 'description' => 'Toasted sandwich with fries.', 'price' => 'Rs. 980', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Chocolate Tart', 'description' => 'Chocolate tart with a rich center.', 'price' => 'Rs. 760', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=1400']
                ]
            ],
            'cote-rotie' => [
                'summary' => 'Cote Rotie is a premium DHA cafe with single-origin coffees, brunch plates and pastries.',
                'hero_image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1800',
                'location_image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1800',
                'gallery_images' => [
                    'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=900',
                    'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900',
                    'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900',
                    'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=900'
                ],
                'menu_items' => [
                    ['slug' => 'single-origin', 'tag' => 'Coffee', 'name' => 'Single Origin Pour Over', 'description' => 'Carefully brewed single origin coffee.', 'price' => 'Rs. 860', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'],
                    ['slug' => 'espresso', 'tag' => 'Coffee', 'name' => 'Espresso', 'description' => 'Bold espresso with deep aroma.', 'price' => 'Rs. 450', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1400'],
                    ['slug' => 'cappuccino', 'tag' => 'Coffee', 'name' => 'Cappuccino', 'description' => 'Creamy cappuccino with foam.', 'price' => 'Rs. 650', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400'],
                    ['slug' => 'sandwich', 'tag' => 'Snack', 'name' => 'Chicken Panini', 'description' => 'Pressed sandwich with fries.', 'price' => 'Rs. 1,050', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=1400'],
                    ['slug' => 'croissant', 'tag' => 'Bakery', 'name' => 'Almond Croissant', 'description' => 'Buttery croissant with almond topping.', 'price' => 'Rs. 490', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1400'],
                    ['slug' => 'desserts', 'tag' => 'Desserts', 'name' => 'Chocolate Tart', 'description' => 'Premium tart with dark chocolate.', 'price' => 'Rs. 760', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=1400']
                ]
            ]
        ];

        return $catalogs[$key] ?? [
            'summary' => 'A curated cafe menu built around the place you opened.',
            'hero_image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1800',
            'location_image' => 'https://images.unsplash.com/photo-1501339847302-ac426a4a7cbb?w=1800',
            'gallery_images' => [
                'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=900',
                'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=900',
                'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=900',
                'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=900'
            ],
            'menu_items' => []
        ];
    }
}
