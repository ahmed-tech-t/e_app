// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'sale_item_dto.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_SaleItemDto _$SaleItemDtoFromJson(Map<String, dynamic> json) => _SaleItemDto(
  id: (json['id'] as num?)?.toInt(),
  productId: (json['product_id'] as num?)?.toInt(),
  billId: (json['bill_id'] as num?)?.toInt(),
  quantity: (json['quantity'] as num?)?.toInt(),
  price: (json['price'] as num?)?.toDouble(),
  total: (json['total'] as num?)?.toDouble(),
);

Map<String, dynamic> _$SaleItemDtoToJson(_SaleItemDto instance) =>
    <String, dynamic>{
      'id': instance.id,
      'product_id': instance.productId,
      'bill_id': instance.billId,
      'quantity': instance.quantity,
      'price': instance.price,
      'total': instance.total,
    };
