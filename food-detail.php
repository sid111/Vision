<?php
$page_title = "Food Detail - FoodFinder Karachi";
include 'config/database.php';
include 'includes/place-catalog.php';
include 'includes/food-catalog.php';
$conn = getConnection();

function ff_slug($text)
{
    return trim(strtolower(preg_replace('/[^a-z0-9]+/i', '-', $text)), '-');
}

$type = $_GET['type'] ?? 'restaurant';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$itemSlug = $_GET['item'] ?? '';

$catalog = [
    'restaurant' => [
        ['slug' => 'best-sellers', 'name' => 'Special BBQ Platter', 'category' => 'Best Sellers', 'price' => 'Rs. 2,850', 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1400', 'description' => 'Chicken, beef, mutton, seekh kebab and smoky boti with chutneys.', 'highlights' => ['Serves 2 to 3', 'Smoky BBQ spices', 'Best for family sharing']],
        ['slug' => 'bbq', 'name' => 'Special BBQ Platter', 'category' => 'BBQ', 'price' => 'Rs. 2,850', 'rating' => 4.9, 'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1400', 'description' => 'Charcoal grilled chicken, beef and mutton prepared with Karachi style masala and naan.', 'highlights' => ['Charcoal grilled', 'Signature sauce', 'Fresh salad side']],
        ['slug' => 'biryani', 'name' => 'Matka Biryani', 'category' => 'Biryani', 'price' => 'Rs. 1,250', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1631515242808-497c3fbd3972?w=1400', 'description' => 'Aromatic basmati rice layered with tender meat, fried onion, mint and dum flavor.', 'highlights' => ['Served in matka', 'Medium spice', 'Raita included']],
        ['slug' => 'karahi', 'name' => 'Chicken Karahi', 'category' => 'Karahi', 'price' => 'Rs. 1,850', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=1400', 'description' => 'Fresh chicken cooked in tomato, ginger, green chili and house karahi masala.', 'highlights' => ['Fresh karahi', 'Serves 2', 'Naan recommended']],
        ['slug' => 'fast-food', 'name' => 'Zinger Burger Meal', 'category' => 'Fast Food', 'price' => 'Rs. 950', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=1400', 'description' => 'Crispy chicken fillet burger with fries, dip and chilled drink.', 'highlights' => ['Crispy fillet', 'Fries included', 'Quick serve']],
        ['slug' => 'chinese', 'name' => 'Chicken Chow Mein', 'category' => 'Chinese', 'price' => 'Rs. 1,150', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1585032226651-759b368d7246?w=1400', 'description' => 'Stir fried noodles with chicken, vegetables and classic savory sauce.', 'highlights' => ['Wok tossed', 'Mild spice', 'Good for one']],
        ['slug' => 'desserts', 'name' => 'Molten Lava Cake', 'category' => 'Desserts', 'price' => 'Rs. 720', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?w=1400', 'description' => 'Warm chocolate cake with molten center and vanilla ice cream.', 'highlights' => ['Warm dessert', 'Ice cream side', 'Chocolate rich']],
        ['slug' => 'drinks', 'name' => 'Mint Margarita', 'category' => 'Drinks', 'price' => 'Rs. 420', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=1400', 'description' => 'Fresh mint, lemon and crushed ice blended into a refreshing cooler.', 'highlights' => ['Fresh mint', 'Chilled', 'Perfect with BBQ']]
    ],
    'cafe' => [
        ['slug' => 'signature-coffee', 'name' => 'Signature Cappuccino', 'category' => 'Signature Coffee', 'price' => 'Rs. 650', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=1400', 'description' => 'Rich espresso with steamed milk, silky foam and caramel notes.', 'highlights' => ['Double shot', 'Silky foam', 'House favorite']],
        ['slug' => 'latte', 'name' => 'Vanilla Latte', 'category' => 'Coffee', 'price' => 'Rs. 680', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1572442388796-11668a67e53d?w=1400', 'description' => 'Creamy latte with vanilla syrup and smooth milk.', 'highlights' => ['Creamy', 'Vanilla notes', 'Balanced']],
        ['slug' => 'cold-brew', 'name' => 'Cold Brew', 'category' => 'Cold Brew', 'price' => 'Rs. 720', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=1400', 'description' => 'Slow brewed coffee served chilled with a smooth low-acid finish.', 'highlights' => ['18 hour brew', 'Low acid', 'Served chilled']],
        ['slug' => 'mocha', 'name' => 'Mocha Frappe', 'category' => 'Coffee', 'price' => 'Rs. 760', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1485808191679-5f86510681a2?w=1400', 'description' => 'Coffee, cocoa and milk blended into a chilled frappe.', 'highlights' => ['Chocolate coffee', 'Chilled', 'Sweet finish']],
        ['slug' => 'croissant', 'name' => 'Butter Croissant', 'category' => 'Bakery', 'price' => 'Rs. 390', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1541592106381-b31e9677c0e5?w=1400', 'description' => 'Flaky, buttery croissant baked fresh every morning.', 'highlights' => ['Fresh baked', 'Buttery', 'Light snack']],
        ['slug' => 'breakfast', 'name' => 'English Breakfast', 'category' => 'Breakfast', 'price' => 'Rs. 1,450', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1533089860892-a7c6f0a88666?w=1400', 'description' => 'Eggs, toast, sausages, beans, grilled tomato and hash browns.', 'highlights' => ['All day breakfast', 'Filling meal', 'Tea pairing']],
        ['slug' => 'desserts', 'name' => 'Lotus Cheesecake', 'category' => 'Desserts', 'price' => 'Rs. 820', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=1400', 'description' => 'Creamy cheesecake with lotus crumb, caramel drizzle and smooth filling.', 'highlights' => ['Customer favorite', 'Creamy texture', 'Sweet finish']],
        ['slug' => 'sandwich', 'name' => 'Club Sandwich', 'category' => 'Snack', 'price' => 'Rs. 980', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1539252554453-80ab65ce3586?w=1400', 'description' => 'Toasted sandwich packed with chicken, cheese and fries.', 'highlights' => ['Toasted', 'Chicken filling', 'Served with fries']]
    ],
    'street' => [
        [
            'slug' => 'desserts',
            'name' => 'Zamzama Dessert Bars',
            'category' => 'Desserts',
            'price' => 'From Rs. 260',
            'rating' => 4.8,
            'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=1400',
            'description' => 'Dessert bars, ice cream corners, brownie carts and waffle counters.',
            'highlights' => ['Sweet section', 'Multiple carts', 'Late night favorite'],
            'vendors' => [
                ['name' => 'Sweet Corner', 'price' => 'Rs. 280+', 'rating' => 4.8, 'image' => 'https://images.unsplash.com/photo-1551024601-bec78aea704b?w=900', 'note' => 'Brownies, sundaes and dessert cups.'],
                ['name' => 'Waffle Point', 'price' => 'Rs. 360+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1562376552-0d160a2f53b0?w=900', 'note' => 'Fresh waffles, chocolate drizzle and fruit.'],
                ['name' => 'Ice Cream Cart', 'price' => 'Rs. 260+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1488900128323-21503983a07e?w=900', 'note' => 'Scoops, cones and seasonal sundaes.'],
                ['name' => 'Cake Stop', 'price' => 'Rs. 320+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1541781774459-bb2af2f05b55?w=900', 'note' => 'Slices, jars and celebration cakes.']
            ]
        ],
        [
            'slug' => 'chaat',
            'name' => 'Zamzama Chaat Carts',
            'category' => 'Chaat',
            'price' => 'From Rs. 350',
            'rating' => 4.7,
            'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=1400',
            'description' => 'Chana chaat, papri chaat, dahi baray and spicy street bowls.',
            'highlights' => ['Tangy section', 'Fresh yogurt', 'Crowd favorite'],
            'vendors' => [
                ['name' => 'Chana Chaat Cart', 'price' => 'Rs. 350+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=900', 'note' => 'Spicy chana and papri bowls.'],
                ['name' => 'Dahi Baray Stall', 'price' => 'Rs. 320+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1560717845-968823efbee1?w=900', 'note' => 'Cool yogurt, chutney and masala.'],
                ['name' => 'Papri Spot', 'price' => 'Rs. 380+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=900', 'note' => 'Crunchy papri and sweet chutney.'],
                ['name' => 'Fruit Chaat Stand', 'price' => 'Rs. 300+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1473442803954-5f4c0f3b1a02?w=900', 'note' => 'Seasonal fruit with chaat masala.']
            ]
        ],
        [
            'slug' => 'bbq',
            'name' => 'Zamzama BBQ Carts',
            'category' => 'BBQ',
            'price' => 'From Rs. 420',
            'rating' => 4.6,
            'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=1400',
            'description' => 'Seekh kebab carts, boti counters and charcoal barbecue stations.',
            'highlights' => ['Smoky section', 'Fresh naan', 'Charcoal flavor'],
            'vendors' => [
                ['name' => 'Seekh Kebab Cart', 'price' => 'Rs. 420+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1529193591184-b1d58069ecdd?w=900', 'note' => 'Juicy kebabs with chutney.'],
                ['name' => 'Boti Counter', 'price' => 'Rs. 480+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=900', 'note' => 'Chicken and beef boti plates.'],
                ['name' => 'BBQ Grill House', 'price' => 'Rs. 520+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1529239357791-2d4f8a7f4c90?w=900', 'note' => 'Mixed grill with naan.'],
                ['name' => 'Charcoal Stand', 'price' => 'Rs. 450+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1514516345957-556ca7cb83a1?w=900', 'note' => 'Freshly grilled and smoky.']
            ]
        ],
        [
            'slug' => 'rolls',
            'name' => 'Zamzama Roll Shops',
            'category' => 'Rolls',
            'price' => 'From Rs. 420',
            'rating' => 4.6,
            'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=1400',
            'description' => 'Paratha rolls, shawarma wraps and spicy chicken roll shops.',
            'highlights' => ['Quick bites', 'Paratha wrap', 'Late night grab'],
            'vendors' => [
                ['name' => 'Paratha Roll Shop', 'price' => 'Rs. 420+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=900', 'note' => 'Chicken and beef rolls.'],
                ['name' => 'Shawarma Stand', 'price' => 'Rs. 380+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=900', 'note' => 'Garlic sauce with pickles.'],
                ['name' => 'Kebab Wrap Cart', 'price' => 'Rs. 450+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=900', 'note' => 'Seekh kebab wrap with fries.'],
                ['name' => 'Double Roll Counter', 'price' => 'Rs. 480+', 'rating' => 4.7, 'image' => 'https://images.unsplash.com/photo-1551504734-5ee1c4a1479b?w=900', 'note' => 'Loaded roll with extra fillings.']
            ]
        ],
        [
            'slug' => 'kulfi',
            'name' => 'Kulfi Corner',
            'category' => 'Kulfi',
            'price' => 'From Rs. 260',
            'rating' => 4.5,
            'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=1400',
            'description' => 'Pista kulfi, mango kulfi and falooda glasses.',
            'highlights' => ['Cold dessert', 'Pistachio', 'After dinner'],
            'vendors' => [
                ['name' => 'Kulfi Corner', 'price' => 'Rs. 260+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1563805042-7684c019e1cb?w=900', 'note' => 'Pista, mango and malai kulfi.'],
                ['name' => 'Falooda Stall', 'price' => 'Rs. 320+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1565172362817-8c54f0a5f4a3?w=900', 'note' => 'Falooda with jelly and kulfi.'],
                ['name' => 'Sundae Cart', 'price' => 'Rs. 350+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1497034825429-c343d7c6a68f?w=900', 'note' => 'Chocolate and caramel sundaes.'],
                ['name' => 'Milk Shake Stand', 'price' => 'Rs. 300+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1517093157656-b9eccef91cb1?w=900', 'note' => 'Banana and chocolate shakes.']
            ]
        ],
        [
            'slug' => 'drinks',
            'name' => 'Fresh Juice Stall',
            'category' => 'Drinks',
            'price' => 'From Rs. 180',
            'rating' => 4.4,
            'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=1400',
            'description' => 'Mint lemonade, sugarcane juice and seasonal drinks.',
            'highlights' => ['Fresh juice', 'Cold drinks', 'Seasonal fruits'],
            'vendors' => [
                ['name' => 'Juice Stall', 'price' => 'Rs. 180+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1505253716362-afaea1d3d1af?w=900', 'note' => 'Fresh lime and mint.'],
                ['name' => 'Sugarcane Cart', 'price' => 'Rs. 220+', 'rating' => 4.3, 'image' => 'https://images.unsplash.com/photo-1464306076886-da185f6a0f82?w=900', 'note' => 'Ice-cold sugarcane juice.'],
                ['name' => 'Lassi Corner', 'price' => 'Rs. 250+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1517093157656-b9eccef91cb1?w=900', 'note' => 'Sweet and salty lassi.'],
                ['name' => 'Mocktail Stand', 'price' => 'Rs. 280+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1544145945-f90425340c7e?w=900', 'note' => 'Fruity street mocktails.']
            ]
        ],
        [
            'slug' => 'fries',
            'name' => 'Loaded Fries Cart',
            'category' => 'Snacks',
            'price' => 'From Rs. 300',
            'rating' => 4.5,
            'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=1400',
            'description' => 'Cheesy fries, masala fries and chicken loaded fries.',
            'highlights' => ['Loaded', 'Cheesy', 'Quick snack'],
            'vendors' => [
                ['name' => 'Masala Fries', 'price' => 'Rs. 300+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1573080496219-bb080dd4f877?w=900', 'note' => 'Spicy fries with dip.'],
                ['name' => 'Cheese Fries', 'price' => 'Rs. 350+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1579567761406-4684ee0c75b6?w=900', 'note' => 'Melted cheese and herbs.'],
                ['name' => 'Chicken Loaded Fries', 'price' => 'Rs. 420+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1512152272829-e3139592d56f?w=900', 'note' => 'Chicken topping with sauce.'],
                ['name' => 'Peri Peri Fries', 'price' => 'Rs. 320+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1630384060421-cb20d0e8d6b3?w=900', 'note' => 'Tangy peri peri seasoning.']
            ]
        ],
        [
            'slug' => 'shawarma',
            'name' => 'Shawarma Stand',
            'category' => 'Wraps',
            'price' => 'From Rs. 380',
            'rating' => 4.6,
            'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=1400',
            'description' => 'Chicken shawarma rolls with garlic sauce and pickles.',
            'highlights' => ['Wraps', 'Garlic sauce', 'Quick serve'],
            'vendors' => [
                ['name' => 'Chicken Shawarma', 'price' => 'Rs. 380+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=900', 'note' => 'Classic chicken shawarma.'],
                ['name' => 'Beef Shawarma', 'price' => 'Rs. 430+', 'rating' => 4.5, 'image' => 'https://images.unsplash.com/photo-1559847844-5315695dadae?w=900', 'note' => 'Beef wrap with garlic dip.'],
                ['name' => 'Zinger Shawarma', 'price' => 'Rs. 450+', 'rating' => 4.6, 'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=900', 'note' => 'Crispy chicken shawarma.'],
                ['name' => 'Mini Wrap Cart', 'price' => 'Rs. 300+', 'rating' => 4.4, 'image' => 'https://images.unsplash.com/photo-1604908176997-125f25cc6f3d?w=900', 'note' => 'Quick small wraps.']
            ]
        ]
    ]
];

if ($id <= 0 || !isset($catalog[$type])) {
    header('Location: index.php');
    exit();
}

$table = $type === 'restaurant' ? 'restaurants' : ($type === 'cafe' ? 'cafes' : 'food_streets');
$backPage = $type === 'restaurant' ? 'restaurant.php' : ($type === 'cafe' ? 'cafe.php' : 'food-street.php');
$listPage = $type === 'restaurant' ? 'restaurants.php' : ($type === 'cafe' ? 'cafes.php' : 'food-streets.php');

$stmt = $conn->prepare("SELECT * FROM $table WHERE id = ?");
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();
$place = $result->fetch_assoc();
if (!$place) {
    header('Location: ' . $listPage);
    exit();
}
$stmt->close();

if ($type === 'restaurant') {
    $placeCatalog = ff_get_restaurant_catalog($place['name']);
    $items = $placeCatalog['menu_items'];
    $placeMeta = $placeCatalog['summary'];
    $placeImage = $placeCatalog['hero_image'];
    $locationImage = $placeCatalog['location_image'] ?? $placeImage;
    $galleryImages = $placeCatalog['gallery_images'];
} elseif ($type === 'cafe') {
    $placeCatalog = ff_get_cafe_catalog($place['name']);
    $items = $placeCatalog['menu_items'];
    $placeMeta = $placeCatalog['summary'];
    $placeImage = $placeCatalog['hero_image'];
    $locationImage = $placeCatalog['location_image'] ?? $placeImage;
    $galleryImages = $placeCatalog['gallery_images'];
} elseif ($type === 'street') {
    $streetCatalog = ff_get_street_catalog($place['name'], $place['location']);
    $items = $streetCatalog['items'];
    $streetLabel = $streetCatalog['label'];
    $placeMeta = $streetCatalog['summary'];
    $placeImage = $streetCatalog['hero_image'];
    $locationImage = $streetCatalog['location_image'] ?? $placeImage;
    $galleryImages = $streetCatalog['gallery_images'];
}

$item = $items[0];
foreach ($items as $candidate) {
    $candidateCategory = $candidate['category'] ?? ($candidate['tag'] ?? '');
    if ($candidate['slug'] === $itemSlug || ff_slug($candidate['name']) === $itemSlug || ff_slug($candidateCategory) === $itemSlug) {
        $item = $candidate;
        break;
    }
}

$placeName = $place['name'];
$placeImage = !empty($placeImage) ? $placeImage : (!empty($place['image']) ? $place['image'] : $item['image']);
$placeLocation = $place['location'] ?? '';
$backUrl = $backPage . '?id=' . intval($id);
$page_title = $type === 'street' ? ($placeName . ' - ' . $streetLabel . ' - FoodFinder Karachi') : ($placeName . ' - FoodFinder Karachi');
$categoryLabel = $item['category'] ?? ($item['tag'] ?? ucwords(str_replace('-', ' ', $itemSlug)));
$highlights = $item['highlights'] ?? [];
$vendors = $item['vendors'] ?? [];
$collectionMode = $type === 'street' && !empty($vendors);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($page_title); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700;14..32,800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css?v=20260712">
    <style>
        body.food-detail-page {
            background: #080a0b;
            color: #f6f2e9;
            font-family: 'Inter', sans-serif;
        }

        .rd-muted {
            color: rgba(255, 255, 255, .68);
        }

        .fd-hero {
            min-height: 560px;
            display: flex;
            align-items: end;
            position: relative;
            background-size: cover;
            background-position: center;
        }

        .fd-hero::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(90deg, rgba(8, 10, 11, .98), rgba(8, 10, 11, .56), rgba(8, 10, 11, .88)), linear-gradient(180deg, transparent, #080a0b 96%);
        }

        .fd-hero .container {
            position: relative;
            z-index: 2;
        }

        .fd-chip {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            min-height: 34px;
            padding: 7px 12px;
            color: #fff;
            background: rgba(7, 8, 8, .72);
            border: 1px solid rgba(245, 176, 65, .36);
            border-radius: 8px;
            font-size: .86rem;
            font-weight: 800;
            text-decoration: none;
        }

        .fd-back {
            color: #F5B041;
        }

        .fd-title {
            color: #fff;
            font-size: clamp(2.5rem, 6vw, 5rem);
            font-weight: 800;
            line-height: 1.02;
            margin: .85rem 0 .75rem;
        }

        .fd-meta {
            display: flex;
            flex-wrap: wrap;
            gap: .8rem;
            color: rgba(255, 255, 255, .82);
        }

        .fd-panel {
            background: rgba(17, 18, 16, .92);
            border: 1px solid rgba(245, 176, 65, .22);
            border-radius: 8px;
            padding: 1.25rem;
            box-shadow: 0 22px 42px rgba(0, 0, 0, .26);
        }

        .fd-panel+.fd-panel {
            margin-top: 1rem;
        }

        .fd-price {
            color: #F5B041;
            font-size: 2rem;
            font-weight: 900;
        }

        .fd-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: .5rem;
            min-height: 44px;
            padding: 10px 22px;
            border-radius: 8px;
            color: #111;
            background: linear-gradient(95deg, #F5B041, #E67E22);
            font-weight: 800;
            text-decoration: none;
            border: 0;
        }

        .fd-outline {
            color: #F5B041;
            background: transparent;
            border: 1px solid rgba(245, 176, 65, .5);
        }

        .fd-highlight {
            display: grid;
            gap: .7rem;
            margin-top: 1rem;
        }

        .fd-highlight span {
            display: flex;
            align-items: center;
            gap: .55rem;
            color: rgba(255, 255, 255, .78);
        }

        .fd-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: .85rem;
        }

        .fd-related {
            display: block;
            min-height: 140px;
            border-radius: 8px;
            border: 1px solid rgba(245, 176, 65, .18);
            background-size: cover;
            background-position: center;
            overflow: hidden;
            color: #fff;
            text-decoration: none;
            position: relative;
        }

        .fd-related::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(180deg, transparent, rgba(0, 0, 0, .78));
        }

        .fd-related span {
            position: absolute;
            left: .8rem;
            right: .8rem;
            bottom: .8rem;
            font-weight: 800;
        }

        .fd-vendor-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: .85rem;
        }

        .fd-vendor {
            overflow: hidden;
            border-radius: 8px;
            border: 1px solid rgba(245, 176, 65, .22);
            background: rgba(255, 255, 255, .05);
        }

        .fd-vendor-image {
            min-height: 160px;
            background-size: cover;
            background-position: center;
        }

        .fd-vendor-body {
            padding: .9rem;
        }

        .fd-vendor-body h4 {
            color: #fff;
            font-size: 1rem;
            font-weight: 800;
            margin-bottom: .35rem;
        }

        .fd-vendor-body p {
            color: rgba(255, 255, 255, .68);
            font-size: .85rem;
            margin-bottom: .75rem;
        }

        @media (max-width: 991px) {

            .fd-grid,
            .fd-vendor-grid {
                grid-template-columns: 1fr;
            }

            .fd-hero {
                min-height: auto;
                padding: 4rem 0 2.5rem;
            }
        }
    </style>
</head>

<body class="food-detail-page">
    <?php include 'includes/nav.php'; ?>
    <div style="height:76px"></div>

    <section class="fd-hero"
        style="background-image: url('<?php echo htmlspecialchars($item['image'], ENT_QUOTES); ?>');">
        <div class="container py-5">
            <a class="fd-chip fd-back" href="<?php echo htmlspecialchars($backUrl); ?>"><i
                    class="fas fa-arrow-left"></i> Back to <?php echo htmlspecialchars($placeName); ?></a>
            <div class="mt-3"><span class="fd-chip"><i class="fas fa-star"
                        style="color:#F5B041"></i><?php echo number_format($item['rating'], 1); ?> item rating</span>
            </div>
            <h1 class="fd-title"><?php echo htmlspecialchars($item['name']); ?></h1>
            <div class="fd-meta">
                <span><?php echo htmlspecialchars($categoryLabel); ?></span>
                <span>&bull;</span>
                <span><?php echo htmlspecialchars($placeName); ?></span>
                <span>&bull;</span>
                <span><?php echo htmlspecialchars($placeLocation); ?></span>
            </div>
        </div>
    </section>

    <main class="container my-4">
        <div class="row g-4">
            <section class="col-lg-8">
                <div class="fd-panel">
                    <h2 class="h4 fw-bold mb-3">
                        <?php echo $collectionMode ? 'Carts, Bars and Shops' : 'Food Details'; ?></h2>
                    <p class="rd-muted mb-0"><?php echo htmlspecialchars($item['description']); ?></p>
                    <div class="fd-highlight">
                        <?php foreach ($highlights as $highlight): ?>
                            <span><i class="fas fa-check"
                                    style="color:#F5B041"></i><?php echo htmlspecialchars($highlight); ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <?php if ($collectionMode): ?>
                    <div class="fd-panel">
                        <h2 class="h4 fw-bold mb-3">All <?php echo htmlspecialchars($categoryLabel); ?> Places</h2>
                        <div class="fd-vendor-grid">
                            <?php foreach ($vendors as $vendor): ?>
                                <article class="fd-vendor">
                                    <div class="fd-vendor-image"
                                        style="background-image:url('<?php echo htmlspecialchars($vendor['image'], ENT_QUOTES); ?>')">
                                    </div>
                                    <div class="fd-vendor-body">
                                        <div class="d-flex justify-content-between gap-2 align-items-start">
                                            <h4><?php echo htmlspecialchars($vendor['name']); ?></h4>
                                            <span style="color:#F5B041;font-weight:800;"><i class="fas fa-star"></i>
                                                <?php echo number_format($vendor['rating'], 1); ?></span>
                                        </div>
                                        <p><?php echo htmlspecialchars($vendor['note']); ?></p>
                                        <div class="d-flex align-items-center justify-content-between">
                                            <span
                                                style="color:#F5B041;font-weight:900;"><?php echo htmlspecialchars($vendor['price']); ?></span>
                                            <span class="fd-chip">Open Cart</span>
                                        </div>
                                    </div>
                                </article>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="fd-panel">
                        <h2 class="h4 fw-bold mb-3">More From This Menu</h2>
                        <div class="fd-grid">
                            <?php foreach (array_slice($items, 0, 6) as $related): ?>
                                <?php if ($related['slug'] === $item['slug'])
                                    continue; ?>
                                <a class="fd-related"
                                    href="food-detail.php?type=<?php echo urlencode($type); ?>&id=<?php echo intval($id); ?>&item=<?php echo urlencode($related['slug']); ?>"
                                    style="background-image:url('<?php echo htmlspecialchars($related['image'], ENT_QUOTES); ?>')">
                                    <span><?php echo htmlspecialchars($related['name']); ?><br><small
                                            style="color:#F5B041"><?php echo htmlspecialchars($related['price']); ?> -
                                            <?php echo number_format($related['rating'], 1); ?></small></span>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </section>
            <aside class="col-lg-4">
                <div class="fd-panel position-sticky" style="top:100px;">
                    <h2 class="h4 fw-bold mb-3">Order Summary</h2>
                    <div class="fd-price mb-2"><?php echo htmlspecialchars($item['price']); ?></div>
                    <p class="mb-2"><i class="fas fa-star" style="color:#F5B041"></i>
                        <?php echo number_format($item['rating'], 1); ?> / 5.0</p>
                    <p class="rd-muted mb-3"><?php echo htmlspecialchars($placeMeta); ?></p>
                    <div class="mb-3" style="min-height:170px;border-radius:8px;overflow:hidden;border:1px solid rgba(245,176,65,.22);background-image:url('<?php echo htmlspecialchars($locationImage, ENT_QUOTES); ?>');background-size:cover;background-position:center;"></div>
                    <?php if ($collectionMode): ?>
                        <div class="fd-highlight mb-3">
                            <?php foreach (array_slice($vendors, 0, 4) as $vendor): ?>
                                <span><i class="fas fa-location-dot"
                                        style="color:#F5B041"></i><?php echo htmlspecialchars($vendor['name']); ?></span>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <a href="<?php echo htmlspecialchars($backUrl); ?>#menu" class="fd-btn w-100 mb-2">Back to Menu</a>
                    <a href="<?php echo htmlspecialchars($listPage); ?>" class="fd-btn fd-outline w-100">Explore More
                        Places</a>
                </div>
            </aside>
        </div>
    </main>

    <footer>
        <div class="container text-center">
            <p class="mb-1">FoodFinder Karachi</p><small>Discover, compare and enjoy the best food in Karachi.</small>
        </div>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
<?php $conn->close(); ?>





