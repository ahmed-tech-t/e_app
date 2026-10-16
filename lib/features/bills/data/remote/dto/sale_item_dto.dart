import 'package:freezed_annotation/freezed_annotation.dart';

part 'sale_item_dto.freezed.dart';
part 'sale_item_dto.g.dart';

@freezed
abstract class SaleItemDto with _$SaleItemDto {
  const factory SaleItemDto({
    int? id,
    @JsonKey(name: 'product_id') int? productId,
    @JsonKey(name: 'bill_id') int? billId,
    int? quantity,
    double? price,
    double? total,
  }) = _SaleItemDto;

  factory SaleItemDto.fromJson(Map<String, dynamic> json) =>
      _$SaleItemDtoFromJson(json);
}
