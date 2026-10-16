import 'package:pos_mobile/features/bills/domain/models/bill.dart';

abstract class BillRepository {
  Future<List<Bill>> getBills({int? page, int? limit});
  Future<Bill?> previewBill(Map<String, dynamic> params);
  Future<Bill?> createBill(Map<String, dynamic> billData);
}
