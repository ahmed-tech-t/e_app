import 'package:freezed_annotation/freezed_annotation.dart';
import 'package:pos_mobile/features/bills/domain/models/sale_item.dart';

part 'bill.freezed.dart';

@freezed
abstract class Bill with _$Bill {
  const factory Bill({
    int? id,
    String? code,
    String? customerName,
    String? customerPhone,
    double? total,
    double? discount,
    double? tax,
    double? grandTotal,
    @Default([]) List<SaleItem> items,
  }) = _Bill;
}

