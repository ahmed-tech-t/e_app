import 'package:pos_mobile/features/bills/data/remote/dto/bill_dto.dart';
import 'package:pos_mobile/features/bills/data/remote/dto/sale_item_dto.dart';
import 'package:pos_mobile/features/bills/domain/models/bill.dart';
import 'package:pos_mobile/features/bills/domain/models/sale_item.dart';

extension SaleItemDtoMapper on SaleItemDto {
  SaleItem toDomain() {
    return SaleItem(
      id: id,
      productId: productId,
      billId: billId,
      quantity: quantity,
      price: price,
      total: total,
    );
  }
}

extension SaleItemDtoListMapper on List<SaleItemDto> {
  List<SaleItem> toDomain() => map((item) => item.toDomain()).toList();
}

extension BillDtoMapper on BillDto {
  Bill toDomain() {
    return Bill(
      id: id,
      code: code,
      customerName: customerName,
      customerPhone: customerPhone,
      total: total,
      discount: discount,
      tax: tax,
      grandTotal: grandTotal,
      items: items.toDomain(),
    );
  }
}

extension BillDtoListMapper on List<BillDto> {
  List<Bill> toDomain() => map((bill) => bill.toDomain()).toList();
}

