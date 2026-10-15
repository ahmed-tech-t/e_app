import 'package:freezed_annotation/freezed_annotation.dart';

part 'Product.freezed.dart';

@freezed
class Product with _$Product {
  const factory Product({
    String? originalCode,
    required String nameAr,
    required String nameEn,
    String? code,
    String? description,
    String? brand,
    String? image,
    double? retailprice,
    double? wholeprice,
    num? unitsPerCarton,
  }) = _Product;
}
