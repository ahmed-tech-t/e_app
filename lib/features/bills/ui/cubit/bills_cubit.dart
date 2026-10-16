import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:freezed_annotation/freezed_annotation.dart';
import 'package:pos_mobile/features/bills/domain/models/bill.dart';
import 'package:pos_mobile/features/bills/domain/repository/bill_repository.dart';

part 'bills_state.dart';
part 'bills_cubit.freezed.dart';

class BillsCubit extends Cubit<BillsState> {
  final BillRepository _billRepository;

  BillsCubit(this._billRepository) : super(const BillsState.initial());

  Future<void> fetchBills({int? page, int? limit}) async {
    emit(const BillsState.loading());
    try {
      final bills = await _billRepository.getBills(page: page, limit: limit);
      emit(BillsState.loaded(bills));
    } catch (e) {
      emit(BillsState.error(e.toString()));
    }
  }

  Future<bool> addBill(Map<String, dynamic> billData) async {
    try {
      final bill = await _billRepository.createBill(billData);
      if (bill != null) {
        state.whenOrNull(
          loaded: (currentBills) {
            emit(BillsState.loaded([bill, ...currentBills]));
          },
        );
        return true;
      }
      return false;
    } catch (e) {
      return false;
    }
  }
}

