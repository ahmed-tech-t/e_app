import 'package:flutter_test/flutter_test.dart';
import 'package:pos_mobile/features/bills/domain/models/bill.dart';
import 'package:pos_mobile/features/bills/domain/models/price_type.dart';
import 'package:pos_mobile/features/bills/domain/repository/bill_repository.dart';
import 'package:pos_mobile/features/bills/ui/cubit/create_bill_cubit.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';
import 'package:pos_mobile/features/home/domain/repository/product_repository.dart';

class FakeProductRepository implements ProductRepository {
  final Map<String, Product> products;
  FakeProductRepository(this.products);

  @override
  Product? getProductByCode(String code) => products[code];

  @override
  List<Product> getCachedProducts() => products.values.toList();

  @override
  Future<List<Product>> getProducts() async => products.values.toList();
}

class FakeBillRepository implements BillRepository {
  Bill? previewReturnBill;
  Bill? createReturnBill;
  Map<String, dynamic>? lastPreviewParams;
  Map<String, dynamic>? lastCreateParams;

  @override
  Future<List<Bill>> getBills({int? page, int? limit}) async => [];

  @override
  Future<Bill?> previewBill(Map<String, dynamic> params) async {
    lastPreviewParams = params;
    return previewReturnBill;
  }

  @override
  Future<Bill?> createBill(Map<String, dynamic> billData) async {
    lastCreateParams = billData;
    return createReturnBill;
  }
}

void main() {
  late FakeProductRepository productRepo;
  late FakeBillRepository billRepo;
  late CreateBillCubit cubit;

  const testProduct = Product(
    id: 1,
    code: 'PRO-100',
    nameEn: 'Coffee Beans',
    retailprice: 15.0,
    wholeprice: 10.0,
  );

  setUp(() {
    productRepo = FakeProductRepository({'PRO-100': testProduct});
    billRepo = FakeBillRepository();
    cubit = CreateBillCubit(productRepo, billRepo);
  });

  tearDown(() {
    cubit.close();
  });

  group('CreateBillCubit Form Validation', () {
    test('validateForm returns error when customer name is empty', () {
      cubit.addProductByCode('PRO-100');
      final error = cubit.validateForm();
      expect(error, 'Customer name is required');
    });

    test('validateForm returns error when no items are present', () {
      cubit.setCustomerName('John Doe');
      final error = cubit.validateForm();
      expect(error, 'Please scan or add at least one product');
    });

    test('validateForm returns error when discount is out of range', () {
      cubit.setCustomerName('John Doe');
      cubit.addProductByCode('PRO-100');
      cubit.setDiscount(150);
      final error = cubit.validateForm();
      expect(error, 'Discount must be between 0% and 100%');
    });

    test('validateForm passes with valid name, items, and discount', () {
      cubit.setCustomerName('John Doe');
      cubit.setCustomerPhone('01012345678');
      cubit.addProductByCode('PRO-100');
      cubit.setDiscount(10);
      final error = cubit.validateForm();
      expect(error, isNull);
    });
  });

  group('CreateBillCubit Preview & Submit', () {
    test('previewBill emits serverPreview when successful', () async {
      cubit.setCustomerName('John Doe');
      cubit.setCustomerPhone('01012345678');
      cubit.addProductByCode('PRO-100');
      cubit.setPriceType(PriceType.retail);

      const previewBill = Bill(
        customerName: 'John Doe',
        total: 15.0,
        discount: 0.0,
        tax: 15.0,
        grandTotal: 17.25,
      );
      billRepo.previewReturnBill = previewBill;

      final result = await cubit.previewBill();

      expect(result, isNotNull);
      expect(cubit.state.serverPreview, equals(previewBill));
      expect(billRepo.lastPreviewParams?['customer_name'], 'John Doe');
      expect(billRepo.lastPreviewParams?['bill_type'], 'retail');
      expect(billRepo.lastPreviewParams?['items[0][product_id]'], 1);
    });

    test('submitBill creates and returns bill', () async {
      cubit.setCustomerName('John Doe');
      cubit.addProductByCode('PRO-100');

      const createdBill = Bill(
        id: 42,
        customerName: 'John Doe',
        total: 15.0,
        grandTotal: 17.25,
      );
      billRepo.createReturnBill = createdBill;

      final result = await cubit.submitBill();

      expect(result?.id, 42);
      expect(billRepo.lastCreateParams?['customer_name'], 'John Doe');
    });
  });
}
