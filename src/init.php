<?php

if (! defined('LARAVEL_START')) {
    exit(0);
}

use App\Services\SchemaService;

if (request()->routeIs('client.product.index')) {
    pluginRepository()->addAction(
        hookName: 'head',
        callback: function () {
            $product = \View::getShared()['product'] ?? null;

            if ($product) {
                $productSchema = SchemaService::toJSON([
                    '@content' => 'https://schema.org',
                    '@type' => 'Product',
                    'name' => $product->title,
                    'description' => $product->content->summary,
                    'mpn' => $product->sku,
                    'sku' => $product->sku,
                    'image' => $product->images->map(fn ($image) => $image->url['medium']),
                    'category' => $product->categories->map(fn ($category) => $category->url),
                    'offer' => [
                        '@type' => 'Offer',
                        'availability' => $product->quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                        'price' => $product->final_price,
                        'priceCurrency' => 'IRR',
                    ],
                ]);

                $breadcrumbSchema = SchemaService::toJSON([
                    '@content' => 'https://schema.org',
                    '@type' => 'BreadcrumbList',
                    'itemListElement' => $product->categories->map(function ($category, $index) {
                        return [
                            '@type' => 'ListItem',
                            'position' => $index + 1,
                            'name' => $category->name,
                            'item' => $category->url,
                        ];
                    })
                ]);

                $html = '
<script type="application/ld+json">'.$productSchema.'</script>
<script type="application/ld+json">'.$breadcrumbSchema.'</script>';

                return $html;
            }
        }
    );
}
