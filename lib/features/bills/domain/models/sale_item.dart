import 'package:freezed_annotation/freezed_annotation.dart';

part 'sale_item.freezed.dart';

@freezed
abstract class SaleItem with _$SaleItem {
  const factory SaleItem({
    int? id,
    int? productId,
    int? billId,
    int? quantity,
    double? price,
    double? total,
  }) = _SaleItem;
}

