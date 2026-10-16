// GENERATED CODE - DO NOT MODIFY BY HAND

part of 'bill_dto.dart';

// **************************************************************************
// JsonSerializableGenerator
// **************************************************************************

_BillDto _$BillDtoFromJson(Map<String, dynamic> json) => _BillDto(
  id: (json['id'] as num?)?.toInt(),
  code: json['code'] as String?,
  customerName: json['customer_name'] as String?,
  customerPhone: json['customer_phone'] as String?,
  total: (json['total'] as num?)?.toDouble(),
  discount: (json['discount'] as num?)?.toDouble(),
  tax: (json['tax'] as num?)?.toDouble(),
  grandTotal: (json['grand_total'] as num?)?.toDouble(),
  items:
      (json['items'] as List<dynamic>?)
          ?.map((e) => SaleItemDto.fromJson(e as Map<String, dynamic>))
          .toList() ??
      const [],
);

Map<String, dynamic> _$BillDtoToJson(_BillDto instance) => <String, dynamic>{
  'id': instance.id,
  'code': instance.code,
  'customer_name': instance.customerName,
  'customer_phone': instance.customerPhone,
  'total': instance.total,
  'discount': instance.discount,
  'tax': instance.tax,
  'grand_total': instance.grandTotal,
  'items': instance.items,
};
