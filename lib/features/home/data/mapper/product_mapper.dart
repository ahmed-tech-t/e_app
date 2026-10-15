import 'package:pos_mobile/features/home/data/remote/dto/product_dto.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';

// lib/features/home/data/mapper/product_mapper.dart
import 'package:pos_mobile/features/home/data/local/models/product_hive_model.dart';

// ==========================================
// 1. DTO ➔ Domain (Remote API)
// ==========================================
extension ProductDtoMapper on ProductDto {
  Product toDomain() {
    return Product(
      originalCode: originalCode,
      nameAr: nameAr,
      nameEn: nameEn,
      code: code,
      description: description,
      brand: brand,
      image: image,
      retailprice: retailprice,
      wholeprice: wholeprice,
      unitsPerCarton: unitsPerCarton,
    );
  }
}

extension ProductDtoListMapper on List<ProductDto> {
  List<Product> toDomain() => map((dto) => dto.toDomain()).toList();
}

// ==========================================
// 2. Hive ➔ Domain (Local Cache to App)
// ==========================================
extension ProductHiveMapper on ProductHiveModel {
  Product toDomain() {
    return Product(
      originalCode: originalCode,
      nameAr: nameAr,
      nameEn: nameEn,
      code: code,
      description: description,
      brand: brand,
      image: image,
      retailprice: retailprice,
      wholeprice: wholeprice,
      unitsPerCarton: unitsPerCarton,
    );
  }
}

extension ProductHiveListMapper on List<ProductHiveModel> {
  List<Product> toDomain() => map((model) => model.toDomain()).toList();
}

// ==========================================
// 3. Domain ➔ Hive (App to Local Cache)
// ==========================================
extension ProductToHiveMapper on Product {
  ProductHiveModel toHive() {
    return ProductHiveModel(
      originalCode: originalCode,
      nameAr: nameAr,
      nameEn: nameEn,
      code: code,
      description: description,
      brand: brand,
      image: image,
      retailprice: retailprice,
      wholeprice: wholeprice,
      unitsPerCarton: unitsPerCarton,
    );
  }
}

extension ProductListToHiveMapper on List<Product> {
  List<ProductHiveModel> toHive() =>
      map((product) => product.toHive()).toList();
}
