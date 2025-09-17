<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $productIds;

    public function __construct(array $productIds = null)
    {
        $this->productIds = $productIds;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $query = Product::with(['variants', 'categories', 'brand']);

        if ($this->productIds) {
            $query->whereIn('id', $this->productIds);
        }

        return $query->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Product ID',
            'Product Name',
            'Product SKU',
            'Product Price',
            'Product Sale Price',
            'Product Stock Quantity',
            'Product Stock Status',
            'Product Type',
            'Brand',
            'Categories',
            'Variant SKU',
            'Variant Price',
            'Variant Sale Price',
            'Variant Stock Quantity',
            'Variant Stock Status',
            'Variant Attributes',
        ];
    }

    /**
     * @param mixed $product
     * @return array
     */
    public function map($product): array
    {
        $rows = [];

        // Add main product row
        $rows[] = [
            $product->id,
            $product->name,
            $product->sku,
            $product->price,
            $product->sale_price,
            $product->stock_quantity,
            $product->stock_status,
            $product->type,
            $product->brand->name ?? 'N/A',
            $product->categories->pluck('name')->implode(', '),
            '', // Variant SKU
            '', // Variant Price
            '', // Variant Sale Price
            '', // Variant Stock Quantity
            '', // Variant Stock Status
            '', // Variant Attributes
        ];

        // Add variant rows if available
        if ($product->type === 'variable' && $product->variants->isNotEmpty()) {
            foreach ($product->variants as $variant) {
                $attributes = $variant->attributeValues->map(function($attrValue) {
                    return $attrValue->attribute->name . ': ' . $attrValue->value;
                })->implode(', ');

                $rows[] = [
                    '', // Product ID
                    '', // Product Name
                    '', // Product SKU
                    '', // Product Price
                    '', // Product Sale Price
                    '', // Product Stock Quantity
                    '', // Product Stock Status
                    '', // Product Type
                    '', // Brand
                    '', // Categories
                    $variant->sku,
                    $variant->price,
                    $variant->sale_price,
                    $variant->stock_quantity,
                    $variant->stock_status,
                    $attributes,
                ];
            }
        }

        return $rows;
    }
}
