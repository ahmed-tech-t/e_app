import 'package:freezed_annotation/freezed_annotation.dart';
import 'package:pos_mobile/features/bills/data/remote/dto/sale_item_dto.dart';

part 'bill_dto.freezed.dart';
part 'bill_dto.g.dart';

@freezed
abstract class BillDto with _$BillDto {
  const factory BillDto({
    int? id,
    String? code,
    @JsonKey(name: 'customer_name') String? customerName,
    @JsonKey(name: 'customer_phone') String? customerPhone,
    double? total,
    double? discount,
    double? tax,
    @JsonKey(name: 'grand_total') double? grandTotal,
    @Default([]) List<SaleItemDto> items,
  }) = _BillDto;

  factory BillDto.fromJson(Map<String, dynamic> json) =>
      _$BillDtoFromJson(json);
}

