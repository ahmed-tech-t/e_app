import 'package:freezed_annotation/freezed_annotation.dart';

part 'Product.freezed.dart';

@freezed
abstract class Product with _$Product {
  const factory Product({
    int? id,
    String? originalCode,
    String? nameAr,
    String? nameEn,
    String? code,
    String? description,
    String? brand,
    String? image,
    double? retailprice,
    double? wholeprice,
    num? unitsPerCarton,
  }) = _Product;
}
