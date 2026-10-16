import 'package:pos_mobile/features/bills/data/mapper/bill_mapper.dart';
import 'package:pos_mobile/features/bills/data/remote/data_source/bill_data_source.dart';
import 'package:pos_mobile/features/bills/domain/models/bill.dart';
import 'package:pos_mobile/features/bills/domain/repository/bill_repository.dart';

class BillRepositoryImpl implements BillRepository {
  final BillDataSource remoteDataSource;

  BillRepositoryImpl({required this.remoteDataSource});

  @override
  Future<List<Bill>> getBills({int? page, int? limit}) async {
    try {
      final response = await remoteDataSource.getBills(
        page: page,
        limit: limit,
      );
      return response.data?.items.toDomain() ?? [];
    } catch (_) {
      return [];
    }
  }

  @override
  Future<Bill?> previewBill(Map<String, dynamic> params) async {
    final response = await remoteDataSource.previewBill(params);
    return response.data?.toDomain();
  }

  @override
  Future<Bill?> createBill(Map<String, dynamic> billData) async {
    final response = await remoteDataSource.createBill(billData);
    return response.data?.toDomain();
  }
}

