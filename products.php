<?php
set_time_limit(0);

$host = 'localhost';
$dbname = 'db_easync';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$categories = [
    'canned-foods',
    'snacks',
    'instant-noodles',
    'sauces',
    'rice',
    'pasta',
    'chocolate-products',
    'biscuits',
    'sugar',
    'vegetable-oils',
    'juices',
    'beverages',
    'soft-drinks',
    'milk',
    'dairy',
    'bread',
    'frozen-foods'
];

$inserted = 0;
$maxPages = 50;

foreach ($categories as $cat) {
    echo "Fetching category: $cat\n";

    for ($page = 1; $page <= $maxPages; $page++) {
        $url = "https://world.openfoodfacts.org/category/$cat/$page.json";
        echo " - Page $page\n";

        $json = @file_get_contents($url); // Use @ to suppress warnings
        if (!$json) {
            echo "   Skipped (no data)\n";
            break;
        }

        $data = json_decode($json, true);
        if (!isset($data['products']) || empty($data['products'])) break;

        foreach ($data['products'] as $product) {
            if (empty($product['code']) || empty($product['product_name'])) continue;

            $barcode = $product['code'];
            $name = $product['product_name'];
            $brands = $product['brands'] ?? '';
            $categoriesText = $product['categories'] ?? '';
            $countries = $product['countries'] ?? '';
            $image = $product['image_url'] ?? '';

            // Keep only products available in the Philippines
            if (stripos($countries, 'Philippines') === false) continue;

            // Skip if already exists
            $stmt = $pdo->prepare("SELECT id FROM products WHERE barcode = ?");
            $stmt->execute([$barcode]);
            if ($stmt->fetch()) continue;

            $insert = $pdo->prepare("INSERT INTO products (barcode, product_name, brands, categories, countries, image_url)
                                     VALUES (?, ?, ?, ?, ?, ?)");

            try {
                $insert->execute([$barcode, $name, $brands, $categoriesText, $countries, $image]);
                $inserted++;
            } catch (PDOException $e) {
                continue; // Skip errors
            }
        }
    }
}

echo "\nImport complete. Inserted $inserted products.\n";
?>
