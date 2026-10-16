import 'package:freezed_annotation/freezed_annotation.dart';

part 'product_dto.freezed.dart';
part 'product_dto.g.dart';

@freezed
abstract class ProductDto with _$ProductDto {
  const factory ProductDto({
    int? id,
    @JsonKey(name: 'original_code') String? originalCode,
    @JsonKey(name: 'name_ar') @Default('') String nameAr,
    @JsonKey(name: 'name_en') @Default('') String nameEn,
    String? code,
    String? description,
    String? brand,
    String? image,
    @JsonKey(name: 'retail_price') double? retailprice,
    @JsonKey(name: 'wholesale_price') double? wholeprice,
    @JsonKey(name: 'units_per_carton') num? unitsPerCarton,
  }) = _ProductDto;

  factory ProductDto.fromJson(Map<String, dynamic> json) =>
      _$ProductDtoFromJson(json);
}
