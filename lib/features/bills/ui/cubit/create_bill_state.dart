part of 'create_bill_cubit.dart';

@freezed
abstract class CreateBillState with _$CreateBillState {
  const factory CreateBillState({
    @Default('') String customerName,
    @Default('') String customerPhone,
    @Default(PriceType.retail) PriceType priceType,
    @Default(0.0) double discount,
    @Default([]) List<BillCartItem> items,
    @Default(false) bool isPreviewLoading,
    @Default(false) bool isSubmitting,
    Bill? serverPreview,
    String? errorMessage,
  }) = _CreateBillState;

  factory CreateBillState.initial() => const CreateBillState();
}

