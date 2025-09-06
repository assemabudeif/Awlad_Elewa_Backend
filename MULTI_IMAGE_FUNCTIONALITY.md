# Multi-Image Support for Products

This document explains the multi-image functionality that has been added to the Laravel application for products.

## Overview

The application now supports multiple images per product, allowing you to:
- Upload multiple images when creating a product
- Add additional images to existing products
- Remove individual images from products
- Maintain image sort order for display purposes

## Database Structure

### New Table: `product_images`
- `id` - Primary key
- `product_id` - Foreign key to products table
- `image_path` - Path to the stored image file
- `sort_order` - Integer for ordering images (0-based)
- `created_at` - Timestamp
- `updated_at` - Timestamp

### Updated Models

#### Product Model
- Added `images()` relationship method
- Returns images ordered by `sort_order`

#### ProductImage Model
- New model for managing product images
- Belongs to Product model

## API Endpoints

### 1. Create Product with Multiple Images
**POST** `/api/products`

```json
{
  "name": "Product Name",
  "description": "Product Description",
  "price": 100.00,
  "category_id": 1,
  "image": "main_image_file",
  "images": ["image1_file", "image2_file", "image3_file"]
}
```

### 2. Get Product with Images
**GET** `/api/products/{id}`

Response includes:
```json
{
  "data": {
    "id": 1,
    "name": "Product Name",
    "description": "Product Description",
    "price": "100.00",
    "category_id": 1,
    "image": "products/main_image.jpg",
    "images": [
      {
        "id": 1,
        "image_path": "products/image1.jpg",
        "sort_order": 0
      },
      {
        "id": 2,
        "image_path": "products/image2.jpg",
        "sort_order": 1
      }
    ]
  }
}
```

### 3. Add Images to Existing Product
**POST** `/api/products/{product_id}/images`

```json
{
  "images": ["new_image1_file", "new_image2_file"]
}
```

### 4. Remove Image from Product
**DELETE** `/api/products/{product_id}/images/{image_id}`

### 5. Update Product with New Images
**PUT** `/api/products/{id}`

When updating with new images, all existing images will be replaced with the new ones.

## Usage Examples

### Frontend Integration

#### Displaying Product Images
```javascript
// Get product data
const product = await fetch('/api/products/1').then(r => r.json());

// Display main image
const mainImage = product.data.image;

// Display additional images
product.data.images.forEach((image, index) => {
  console.log(`Image ${index + 1}: ${image.image_path}`);
});
```

#### Uploading Multiple Images
```javascript
const formData = new FormData();
formData.append('name', 'Product Name');
formData.append('price', '100.00');
formData.append('category_id', '1');

// Main image
formData.append('image', mainImageFile);

// Additional images
additionalImageFiles.forEach(file => {
  formData.append('images[]', file);
});

fetch('/api/products', {
  method: 'POST',
  body: formData
});
```

### Backend Usage

#### Creating Product with Images
```php
$product = Product::create([
    'name' => $request->name,
    'description' => $request->description,
    'price' => $request->price,
    'category_id' => $request->category_id,
    'image' => $request->hasFile('image') ? $request->image->store('products', 'public') : null,
]);

// Add multiple images
if ($request->hasFile('images')) {
    foreach ($request->file('images') as $index => $image) {
        $imagePath = $image->store('products', 'public');
        ProductImage::create([
            'product_id' => $product->id,
            'image_path' => $imagePath,
            'sort_order' => $index,
        ]);
    }
}
```

#### Retrieving Product with Images
```php
$product = Product::with('images')->find($id);
$allImages = $product->images; // Collection of ProductImage models
```

## File Storage

- Images are stored in `storage/app/public/products/`
- Main product image: `products/image_name.jpg`
- Additional images: `products/image_name.jpg`
- Images are accessible via `/storage/products/image_name.jpg`

## Validation Rules

- `image`: `nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048`
- `images`: `nullable|array`
- `images.*`: `image|mimes:jpeg,png,jpg,gif,svg|max:2048`

## Migration

The functionality includes a migration that creates the `product_images` table:

```bash
php artisan migrate
```

## Seeder

The `DemoDataSeeder` has been updated to include sample product images for testing purposes.

## Notes

- Images are automatically ordered by `sort_order` field
- When deleting a product, all associated images are also deleted
- Image files are automatically deleted from storage when removed
- The main `image` field is preserved for backward compatibility
- Additional images are stored in the `images` relationship
