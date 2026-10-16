import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:freezed_annotation/freezed_annotation.dart';
import 'package:pos_mobile/features/bills/domain/models/bill.dart';
import 'package:pos_mobile/features/bills/domain/models/price_type.dart';
import 'package:pos_mobile/features/bills/domain/repository/bill_repository.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';
import 'package:pos_mobile/features/home/domain/repository/product_repository.dart';

part 'create_bill_state.dart';
part 'create_bill_cubit.freezed.dart';

class BillCartItem {
  final Product product;
  final int quantity;

  const BillCartItem({
    required this.product,
    this.quantity = 1,
  });

  BillCartItem copyWith({Product? product, int? quantity}) {
    return BillCartItem(
      product: product ?? this.product,
      quantity: quantity ?? this.quantity,
    );
  }

  double getPrice(PriceType type) {
    if (type == PriceType.wholesale) {
      return product.wholeprice ?? product.retailprice ?? 0.0;
    }
    return product.retailprice ?? product.wholeprice ?? 0.0;
  }

  double getSubtotal(PriceType type) => getPrice(type) * quantity;

  @override
  bool operator ==(Object other) =>
      identical(this, other) ||
      other is BillCartItem &&
          runtimeType == other.runtimeType &&
          product == other.product &&
          quantity == other.quantity;

  @override
  int get hashCode => Object.hash(product, quantity);
}

class CreateBillCubit extends Cubit<CreateBillState> {
  final ProductRepository _productRepository;
  final BillRepository _billRepository;

  CreateBillCubit(
    this._productRepository,
    this._billRepository,
  ) : super(CreateBillState.initial());

  void setCustomerName(String name) {
    emit(state.copyWith(customerName: name));
  }

  void setCustomerPhone(String phone) {
    emit(state.copyWith(customerPhone: phone));
  }

  void setPriceType(PriceType type) {
    emit(state.copyWith(priceType: type));
  }

  void setDiscount(double discount) {
    emit(state.copyWith(discount: discount));
  }

  bool addProductByCode(String code) {
    final cleanCode = code.trim();
    if (cleanCode.isEmpty) return false;

    final product = _productRepository.getProductByCode(cleanCode);
    if (product == null) {
      emit(state.copyWith(errorMessage: 'Product not found with code: $cleanCode'));
      return false;
    }

    final currentItems = List<BillCartItem>.from(state.items);
    final existingIndex = currentItems.indexWhere(
      (item) => item.product.code == product.code || item.product.id == product.id,
    );

    if (existingIndex >= 0) {
      final existing = currentItems[existingIndex];
      currentItems[existingIndex] = existing.copyWith(quantity: existing.quantity + 1);
    } else {
      currentItems.add(BillCartItem(product: product, quantity: 1));
    }

    emit(state.copyWith(
      items: currentItems,
      errorMessage: null,
    ));
    return true;
  }

  void updateQuantity(int index, int quantity) {
    if (index < 0 || index >= state.items.length) return;
    if (quantity <= 0) {
      removeItem(index);
      return;
    }
    final currentItems = List<BillCartItem>.from(state.items);
    currentItems[index] = currentItems[index].copyWith(quantity: quantity);
    emit(state.copyWith(items: currentItems));
  }

  void removeItem(int index) {
    if (index < 0 || index >= state.items.length) return;
    final currentItems = List<BillCartItem>.from(state.items);
    currentItems.removeAt(index);
    emit(state.copyWith(items: currentItems));
  }

  String? validateForm() {
    final name = state.customerName.trim();
    if (name.isEmpty) {
      return 'Customer name is required';
    }
    if (name.length > 255) {
      return 'Customer name cannot exceed 255 characters';
    }

    final phone = state.customerPhone.trim();
    if (phone.isNotEmpty) {
      final phoneRegex = RegExp(r'^[0-9+\s\-]{6,20}$');
      if (!phoneRegex.hasMatch(phone)) {
        return 'Please enter a valid phone number';
      }
    }

    if (state.discount < 0 || state.discount > 100) {
      return 'Discount must be between 0% and 100%';
    }

    if (state.items.isEmpty) {
      return 'Please scan or add at least one product';
    }

    for (final item in state.items) {
      if (item.product.id == null) {
        return 'Product ${item.product.code ?? ''} is missing a valid product ID';
      }
      if (item.quantity < 1) {
        return 'Quantity for each item must be at least 1';
      }
    }

    return null;
  }

  Map<String, dynamic> _buildPayload() {
    final Map<String, dynamic> payload = {
      'customer_name': state.customerName.trim(),
      'store_id': 1,
      'bill_type': state.priceType.value,
      'discount': state.discount,
    };

    final phone = state.customerPhone.trim();
    if (phone.isNotEmpty) {
      payload['customer_phone'] = phone;
    }

    for (int i = 0; i < state.items.length; i++) {
      payload['items[$i][product_id]'] = state.items[i].product.id;
      payload['items[$i][quantity]'] = state.items[i].quantity;
    }

    return payload;
  }

  Future<Bill?> previewBill() async {
    final error = validateForm();
    if (error != null) {
      emit(state.copyWith(errorMessage: error));
      return null;
    }

    emit(state.copyWith(isPreviewLoading: true, errorMessage: null));

    try {
      final params = _buildPayload();
      final preview = await _billRepository.previewBill(params);
      emit(state.copyWith(
        isPreviewLoading: false,
        serverPreview: preview,
      ));
      return preview;
    } catch (e) {
      emit(state.copyWith(
        isPreviewLoading: false,
        errorMessage: 'Failed to calculate bill preview: ${e.toString()}',
      ));
      return null;
    }
  }

  Future<Bill?> submitBill() async {
    final error = validateForm();
    if (error != null) {
      emit(state.copyWith(errorMessage: error));
      return null;
    }

    emit(state.copyWith(isSubmitting: true, errorMessage: null));

    try {
      final payload = _buildPayload();
      final bill = await _billRepository.createBill(payload);
      emit(state.copyWith(isSubmitting: false));
      return bill;
    } catch (e) {
      emit(state.copyWith(
        isSubmitting: false,
        errorMessage: 'Failed to create bill: ${e.toString()}',
      ));
      return null;
    }
  }
}
