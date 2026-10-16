import 'package:hive_flutter/hive_flutter.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';

part 'product_hive_model.g.dart';

@HiveType(typeId: 0)
class ProductHiveModel {
  @HiveField(0)
  final String? originalCode;

  @HiveField(1)
  final String? nameAr;

  @HiveField(2)
  final String? nameEn;

  @HiveField(3)
  final String? code;

  @HiveField(4)
  final String? description;

  @HiveField(5)
  final String? brand;

  @HiveField(6)
  final String? image;

  @HiveField(7)
  final double? retailprice;

  @HiveField(8)
  final double? wholeprice;

  @HiveField(9)
  final num? unitsPerCarton;

  @HiveField(10)
  final int? id;

  ProductHiveModel({
    this.id,
    this.originalCode,
    this.nameAr,
    this.nameEn,
    this.retailprice,
    this.wholeprice,
    this.code,
    this.description,
    this.brand,
    this.image,
    this.unitsPerCarton,
  });

  // Mapper: From Domain to Hive
  factory ProductHiveModel.fromDomain(Product product) {
    return ProductHiveModel(
      id: product.id,
      originalCode: product.originalCode,
      nameAr: product.nameAr,
      nameEn: product.nameEn,
      code: product.code,
      description: product.description,
      brand: product.brand,
      image: product.image,
      retailprice: product.retailprice,
      wholeprice: product.wholeprice,
      unitsPerCarton: product.unitsPerCarton,
    );
  }

  // Mapper: From Hive to Domain
  Product toDomain() {
    return Product(
      id: id,
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
