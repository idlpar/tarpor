<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ProductAttribute;
use App\Models\ProductAttributeValue;
use Illuminate\Support\Str;

class ProductAttributeValueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = ProductAttribute::all();

        foreach ($attributes as $attribute) {
            switch ($attribute->name) {
                case 'Color':
                    $colors = [
                        ['value' => 'Red', 'color_code' => '#FF0000'],
                        ['value' => 'Blue', 'color_code' => '#0000FF'],
                        ['value' => 'Green', 'color_code' => '#008000'],
                        ['value' => 'Black', 'color_code' => '#000000'],
                        ['value' => 'White', 'color_code' => '#FFFFFF'],
                        ['value' => 'Gray', 'color_code' => '#808080'],
                        ['value' => 'Yellow', 'color_code' => '#FFFF00'],
                        ['value' => 'Pink', 'color_code' => '#FFC0CB'],
                        ['value' => 'Purple', 'color_code' => '#800080'],
                        ['value' => 'Orange', 'color_code' => '#FFA500'],
                    ];
                    foreach ($colors as $index => $color) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $color['value'],
                            'color_code' => $color['color_code'],
                            'position' => $index + 1,
                        ]);
                    }
                    break;
                case 'Size':
                    $sizes = ['XS', 'S', 'M', 'L', 'XL', 'XXL', '28', '30', '32', '34', '36', '38'];
                    foreach ($sizes as $index => $size) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $size,
                            'position' => $index + 1,
                        ]);
                    }
                    break;
                case 'Material':
                    $materials = ['Cotton', 'Polyester', 'Silk', 'Wool', 'Linen', 'Denim', 'Leather', 'Spandex', 'Rayon', 'Nylon'];
                    foreach ($materials as $index => $material) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $material,
                            'position' => $index + 1,
                        ]);
                    }
                    break;
                case 'Style':
                    $styles = ['Casual', 'Formal', 'Sporty', 'Bohemian', 'Vintage', 'Streetwear', 'Classic', 'Minimalist', 'Preppy', 'Gothic'];
                    foreach ($styles as $index => $style) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $style,
                            'position' => $index + 1,
                        ]);
                    }
                    break;
                case 'Neckline':
                    $necklines = ['Crew Neck', 'V-Neck', 'Scoop Neck', 'Boat Neck', 'Halter Neck', 'Square Neck', 'Sweetheart', 'Collared', 'Turtle Neck', 'Off-Shoulder'];
                    foreach ($necklines as $index => $neckline) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $neckline,
                            'position' => $index + 1,
                        ]);
                    }
                    break;
                case 'Sleeve Length':
                    $sleeveLengths = ['Sleeveless', 'Short Sleeve', 'Half Sleeve', 'Three-Quarter Sleeve', 'Long Sleeve', 'Cap Sleeve', 'Bell Sleeve', 'Puff Sleeve', 'Kimono Sleeve', 'Raglan Sleeve'];
                    foreach ($sleeveLengths as $index => $length) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $length,
                            'position' => $index + 1,
                        ]);
                    }
                    break;
                case 'Pattern':
                    $patterns = ['Solid', 'Striped', 'Floral', 'Plaid', 'Polka Dot', 'Geometric', 'Animal Print', 'Abstract', 'Checkered', 'Camo'];
                    foreach ($patterns as $index => $pattern) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $pattern,
                            'position' => $index + 1,
                        ]);
                    }
                    break;
                case 'Occasion':
                    $occasions = ['Casual', 'Formal', 'Party', 'Business', 'Sport', 'Beach', 'Wedding', 'Cocktail', 'Everyday', 'Travel'];
                    foreach ($occasions as $index => $occasion) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $occasion,
                            'position' => $index + 1,
                        ]);
                    }
                    break;
                case 'Fit':
                    $fits = ['Slim Fit', 'Regular Fit', 'Loose Fit', 'Relaxed Fit', 'Skinny Fit', 'Athletic Fit', 'Oversized', 'Bodycon', 'Straight Fit', 'Bootcut'];
                    foreach ($fits as $index => $fit) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $fit,
                            'position' => $index + 1,
                        ]);
                    }
                    break;
                case 'Closure Type':
                    $closures = ['Zipper', 'Button', 'Pullover', 'Drawstring', 'Hook & Eye', 'Snap Button', 'Velcro', 'Tie', 'Elastic', 'Buckle'];
                    foreach ($closures as $index => $closure) {
                        ProductAttributeValue::create([
                            'attribute_id' => $attribute->id,
                            'value' => $closure,
                            'position' => $index + 1,
                        ]);
                    }
                    break;
            }
        }
    }
}
