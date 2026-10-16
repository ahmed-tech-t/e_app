part of 'bills_cubit.dart';

@freezed
abstract class BillsState with _$BillsState {
  const factory BillsState.initial() = _Initial;
  const factory BillsState.loading() = _Loading;
  const factory BillsState.loaded(List<Bill> bills) = _Loaded;
  const factory BillsState.error(String message) = _Error;
}
