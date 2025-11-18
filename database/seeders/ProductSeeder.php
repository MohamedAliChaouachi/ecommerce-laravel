<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::all();

        if ($categories->isEmpty()) {
            $categories = collect([Category::create([
                'name' => 'Général',
                'slug' => 'general',
            ])]);
        }

        // Électronique products
        $electronique = $categories->where('slug', 'electronique')->first();
        if ($electronique) {
            $electronicProducts = [
                [
                    'name' => 'Smartphone Samsung Galaxy S24',
                    'price' => 899.00,
                    'stock' => 25,
                    'description' => 'Smartphone haut de gamme avec écran AMOLED 6.2", 256GB de stockage et appareil photo 50MP.',
                    'image' => 'https://images.unsplash.com/photo-1610945415295-d9bbf067e59c?w=800&q=80',
                ],
                [
                    'name' => 'MacBook Pro 14" M3',
                    'price' => 2299.00,
                    'stock' => 8,
                    'description' => 'Ordinateur portable professionnel avec puce M3, 16GB RAM, SSD 512GB. Parfait pour le développement.',
                    'image' => 'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800&q=80',
                ],
                [
                    'name' => 'AirPods Pro 2ème génération',
                    'price' => 279.00,
                    'stock' => 45,
                    'description' => 'Écouteurs sans fil avec réduction de bruit active et audio spatial.',
                    'image' => 'https://images.unsplash.com/photo-1606841837239-c5a1a4a07af7?w=800&q=80',
                ],
                [
                    'name' => 'iPad Air 11" Wi-Fi',
                    'price' => 699.00,
                    'stock' => 15,
                    'description' => 'Tablette légère avec puce M2, écran Liquid Retina et support Apple Pencil.',
                    'image' => 'https://images.unsplash.com/photo-1544244015-0df4b3ffc6b0?w=800&q=80',
                ],
                [
                    'name' => 'Montre connectée Garmin Fenix 7',
                    'price' => 549.00,
                    'stock' => 12,
                    'description' => 'Montre GPS multisport avec cartes topographiques et autonomie de 18 jours.',
                    'image' => 'https://images.unsplash.com/photo-1579586337278-3befd40fd17a?w=800&q=80',
                ],
                [
                    'name' => 'Casque Sony WH-1000XM5',
                    'price' => 399.00,
                    'stock' => 20,
                    'description' => 'Casque sans fil premium avec réduction de bruit de pointe et 30h d\'autonomie.',
                    'image' => 'https://images.unsplash.com/photo-1546435770-a3e426bf472b?w=800&q=80',
                ],
                [
                    'name' => 'Clavier mécanique Logitech MX',
                    'price' => 159.00,
                    'stock' => 35,
                    'description' => 'Clavier mécanique rétroéclairé avec connexion multi-appareils et touches programmables.',
                    'image' => 'https://images.unsplash.com/photo-1587829741301-dc798b83add3?w=800&q=80',
                ],
                [
                    'name' => 'Souris Logitech MX Master 3S',
                    'price' => 109.00,
                    'stock' => 42,
                    'description' => 'Souris ergonomique professionnelle avec 8 boutons programmables et précision de 8000 DPI.',
                    'image' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=800&q=80',
                ],
            ];

            foreach ($electronicProducts as $data) {
                Product::create([
                    'category_id' => $electronique->id,
                    'name'        => $data['name'],
                    'slug'        => Str::slug($data['name']) . '-' . uniqid(),
                    'description' => $data['description'],
                    'price'       => $data['price'],
                    'stock'       => $data['stock'],
                    'image_path'  => $data['image'] ?? null,
                    'is_active'   => true,
                ]);
            }
        }

        // Vêtements products
        $vetements = $categories->where('slug', 'vetements')->first();
        if ($vetements) {
            $clothingProducts = [
                [
                    'name' => 'T-shirt Nike Dri-FIT Homme',
                    'price' => 34.99,
                    'stock' => 120,
                    'description' => 'T-shirt de sport respirant en tissu technique. Disponible en plusieurs couleurs.',
                    'image' => 'https://images.unsplash.com/photo-1521572163474-6864f9cf17ab?w=800&q=80',
                ],
                [
                    'name' => 'Jean Levi\'s 501 Original',
                    'price' => 89.00,
                    'stock' => 65,
                    'description' => 'Jean classique coupe droite, 100% coton. Un intemporel de la garde-robe.',
                    'image' => 'https://images.unsplash.com/photo-1542272604-787c3835535d?w=800&q=80',
                ],
                [
                    'name' => 'Veste en cuir Zara',
                    'price' => 149.00,
                    'stock' => 18,
                    'description' => 'Veste en simili cuir de qualité, style biker avec fermeture éclair asymétrique.',
                    'image' => 'https://images.unsplash.com/photo-1551028719-00167b16eac5?w=800&q=80',
                ],
                [
                    'name' => 'Robe d\'été H&M Fleurie',
                    'price' => 39.99,
                    'stock' => 55,
                    'description' => 'Robe légère à motifs floraux, parfaite pour l\'été. Coupe fluide et confortable.',
                    'image' => 'https://images.unsplash.com/photo-1595777457583-95e059d581b8?w=800&q=80',
                ],
                [
                    'name' => 'Pull en laine Uniqlo',
                    'price' => 59.00,
                    'stock' => 75,
                    'description' => 'Pull col rond en laine mérinos, doux et chaud. Entretien facile.',
                    'image' => 'https://images.unsplash.com/photo-1576566588028-4147f3842f27?w=800&q=80',
                ],
                [
                    'name' => 'Baskets Adidas Ultraboost',
                    'price' => 179.00,
                    'stock' => 40,
                    'description' => 'Chaussures de running avec technologie Boost pour un confort optimal.',
                    'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=800&q=80',
                ],
                [
                    'name' => 'Manteau d\'hiver The North Face',
                    'price' => 299.00,
                    'stock' => 22,
                    'description' => 'Parka isolante imperméable avec capuche amovible. Protection jusqu\'à -20°C.',
                    'image' => 'https://images.unsplash.com/photo-1539533018447-63fcce2678e3?w=800&q=80',
                ],
            ];

            foreach ($clothingProducts as $data) {
                Product::create([
                    'category_id' => $vetements->id,
                    'name'        => $data['name'],
                    'slug'        => Str::slug($data['name']) . '-' . uniqid(),
                    'description' => $data['description'],
                    'price'       => $data['price'],
                    'stock'       => $data['stock'],
                    'image_path'  => $data['image'] ?? null,
                    'is_active'   => true,
                ]);
            }
        }

        // Maison & Cuisine products
        $maison = $categories->where('slug', 'maison-cuisine')->first();
        if ($maison) {
            $homeProducts = [
                [
                    'name' => 'Cafetière Nespresso Vertuo',
                    'price' => 149.00,
                    'stock' => 30,
                    'description' => 'Machine à café avec système Vertuo, 5 tailles de tasses et reconnaissance automatique.',
                    'image' => 'https://images.unsplash.com/photo-1517668808822-9ebb02f2a0e6?w=800&q=80',
                ],
                [
                    'name' => 'Robot cuiseur Thermomix TM6',
                    'price' => 1399.00,
                    'stock' => 5,
                    'description' => 'Robot multifonction connecté : mixe, cuit, pèse et propose des recettes guidées.',
                    'image' => 'https://images.unsplash.com/photo-1585515320310-259814833e62?w=800&q=80',
                ],
                [
                    'name' => 'Aspirateur Dyson V15',
                    'price' => 699.00,
                    'stock' => 12,
                    'description' => 'Aspirateur sans fil puissant avec détection laser des poussières et 60 min d\'autonomie.',
                    'image' => 'https://images.unsplash.com/photo-1558317374-067fb5f30001?w=800&q=80',
                ],
                [
                    'name' => 'Set de couteaux Wüsthof 5 pièces',
                    'price' => 259.00,
                    'stock' => 25,
                    'description' => 'Couteaux de cuisine professionnels en acier forgé allemand avec support en bois.',
                    'image' => 'https://images.unsplash.com/photo-1593618998160-e34014e67546?w=800&q=80',
                ],
                [
                    'name' => 'Batterie de cuisine Tefal Ingenio',
                    'price' => 179.00,
                    'stock' => 18,
                    'description' => 'Set de 10 pièces avec poignée amovible, compatible tous feux dont induction.',
                    'image' => 'https://images.unsplash.com/photo-1584990347449-39b4aa82e5fb?w=800&q=80',
                ],
                [
                    'name' => 'Lampe de bureau LED Philips',
                    'price' => 89.00,
                    'stock' => 45,
                    'description' => 'Lampe ergonomique avec 4 modes d\'éclairage et port USB pour recharge.',
                    'image' => 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=800&q=80',
                ],
                [
                    'name' => 'Purificateur d\'air Xiaomi',
                    'price' => 199.00,
                    'stock' => 15,
                    'description' => 'Purificateur avec filtres HEPA, contrôle via app et capteurs de qualité d\'air.',
                    'image' => 'https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=800&q=80',
                ],
            ];

            foreach ($homeProducts as $data) {
                Product::create([
                    'category_id' => $maison->id,
                    'name'        => $data['name'],
                    'slug'        => Str::slug($data['name']) . '-' . uniqid(),
                    'description' => $data['description'],
                    'price'       => $data['price'],
                    'stock'       => $data['stock'],
                    'image_path'  => $data['image'] ?? null,
                    'is_active'   => true,
                ]);
            }
        }

        // Sport products
        $sport = $categories->where('slug', 'sport')->first();
        if ($sport) {
            $sportProducts = [
                [
                    'name' => 'Tapis de yoga premium 6mm',
                    'price' => 49.00,
                    'stock' => 60,
                    'description' => 'Tapis antidérapant en TPE écologique avec sac de transport. Parfait pour yoga et pilates.',
                    'image' => 'https://images.unsplash.com/photo-1601925260368-ae2f83cf8b7f?w=800&q=80',
                ],
                [
                    'name' => 'Vélo d\'appartement Peloton',
                    'price' => 1895.00,
                    'stock' => 3,
                    'description' => 'Vélo connecté avec écran HD 22" et accès aux cours en direct et à la demande.',
                    'image' => 'https://images.unsplash.com/photo-1556817411-31ae72fa3ea0?w=800&q=80',
                ],
                [
                    'name' => 'Haltères ajustables 2-24kg',
                    'price' => 349.00,
                    'stock' => 15,
                    'description' => 'Paire d\'haltères avec réglage rapide du poids, remplace 15 paires d\'haltères.',
                    'image' => 'https://images.unsplash.com/photo-1517836357463-d25dfeac3438?w=800&q=80',
                ],
                [
                    'name' => 'Montre GPS running Polar Vantage',
                    'price' => 449.00,
                    'stock' => 20,
                    'description' => 'Montre multisport avec cardiofréquencemètre au poignet et analyse de la récupération.',
                    'image' => 'https://images.unsplash.com/photo-1508685096489-7aacd43bd3b1?w=800&q=80',
                ],
                [
                    'name' => 'Sac de sport Nike Brasilia',
                    'price' => 35.00,
                    'stock' => 85,
                    'description' => 'Sac spacieux 60L avec compartiment chaussures et poche ventilée. Tissu déperlant.',
                    'image' => 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?w=800&q=80',
                ],
                [
                    'name' => 'Balle de tennis Wilson US Open',
                    'price' => 12.99,
                    'stock' => 150,
                    'description' => 'Tube de 4 balles, homologuées ITF. Idéal pour tous les types de terrains.',
                    'image' => 'https://images.unsplash.com/photo-1622279457486-62dcc4a431d6?w=800&q=80',
                ],
                [
                    'name' => 'Raquette de tennis Babolat Pure Drive',
                    'price' => 229.00,
                    'stock' => 12,
                    'description' => 'Raquette polyvalente utilisée par les pros, puissance et contrôle optimaux.',
                    'image' => 'https://images.unsplash.com/photo-1617883861744-87689545daa3?w=800&q=80',
                ],
                [
                    'name' => 'Gourde isotherme Hydro Flask 950ml',
                    'price' => 44.99,
                    'stock' => 95,
                    'description' => 'Bouteille en acier inoxydable, garde les boissons froides 24h ou chaudes 12h.',
                    'image' => 'https://images.unsplash.com/photo-1602143407151-7111542de6e8?w=800&q=80',
                ],
            ];

            foreach ($sportProducts as $data) {
                Product::create([
                    'category_id' => $sport->id,
                    'name'        => $data['name'],
                    'slug'        => Str::slug($data['name']) . '-' . uniqid(),
                    'description' => $data['description'],
                    'price'       => $data['price'],
                    'stock'       => $data['stock'],
                    'image_path'  => $data['image'] ?? null,
                    'is_active'   => true,
                ]);
            }
        }

        // Add some low stock products for testing stock alerts
        if ($electronique) {
            Product::create([
                'category_id' => $electronique->id,
                'name'        => 'Webcam Logitech C920 HD Pro',
                'slug'        => Str::slug('Webcam Logitech C920 HD Pro') . '-' . uniqid(),
                'description' => 'Webcam Full HD 1080p avec micro stéréo. Stock limité !',
                'price'       => 79.00,
                'stock'       => 3,
                'image_path'  => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=800&q=80',
                'is_active'   => true,
            ]);
        }

        // Add an out of stock product
        if ($vetements) {
            Product::create([
                'category_id' => $vetements->id,
                'name'        => 'Sneakers Jordan Air 1 Limited',
                'slug'        => Str::slug('Sneakers Jordan Air 1 Limited') . '-' . uniqid(),
                'description' => 'Édition limitée - Rupture de stock temporaire',
                'price'       => 189.00,
                'stock'       => 0,
                'image_path'  => 'https://images.unsplash.com/photo-1460353581641-37baddab0fa2?w=800&q=80',
                'is_active'   => true,
            ]);
        }
    }
}
