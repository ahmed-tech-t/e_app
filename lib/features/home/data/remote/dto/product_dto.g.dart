// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'product_dto.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_ProductDto _$ProductDtoFromJson(Map<String, dynamic> json) => _ProductDto(
  id: (json['id'] as num?)?.toInt(),
  originalCode: json['original_code'] as String?,
  nameAr: json['name_ar'] as String? ?? '',
  nameEn: json['name_en'] as String? ?? '',
  code: json['code'] as String?,
  description: json['description'] as String?,
  brand: json['brand'] as String?,
  image: json['image'] as String?,
  retailprice: (json['retail_price'] as num?)?.toDouble(),
  wholeprice: (json['wholesale_price'] as num?)?.toDouble(),
  unitsPerCarton: json['units_per_carton'] as num?,
);

Map<String, dynamic> _$ProductDtoToJson(_ProductDto instance) =>
    <String, dynamic>{
      'id': instance.id,
      'original_code': instance.originalCode,
      'name_ar': instance.nameAr,
      'name_en': instance.nameEn,
      'code': instance.code,
      'description': instance.description,
      'brand': instance.brand,
      'image': instance.image,
      'retail_price': instance.retailprice,
      'wholesale_price': instance.wholeprice,
      'units_per_carton': instance.unitsPerCarton,
    };
