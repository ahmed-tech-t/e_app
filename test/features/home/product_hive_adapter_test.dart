// ignore_for_file: depend_on_referenced_packages
import 'package:flutter_test/flutter_test.dart';
import 'package:hive/src/binary/binary_reader_impl.dart';
import 'package:hive/src/binary/binary_writer_impl.dart';
import 'package:hive/src/hive_impl.dart';
import 'package:pos_mobile/features/home/data/local/models/product_hive_model.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';

void main() {
  test('ProductHiveModelAdapter serializes and deserializes all fields including name and barcode',
      () {
    final adapter = ProductHiveModelAdapter();
    final registry = HiveImpl();

    const originalProduct = Product(
      id: 42,
      code: 'PLA-4731',
      originalCode: '8462047073676',
      nameAr: 'اطباق',
      nameEn: 'Veritatis Plates',
      description: 'Test description',
      brand: 'Pacocha-Gottlieb',
      image: 'https://example.com/item.png',
      retailprice: 986.37,
      wholeprice: 793.91,
      unitsPerCarton: 3,
    );

    final hiveModel = ProductHiveModel.fromDomain(originalProduct);

    final writer = BinaryWriterImpl(registry);
    adapter.write(writer, hiveModel);

    final reader = BinaryReaderImpl(writer.toBytes(), registry);
    final restoredModel = adapter.read(reader);
    final restoredProduct = restoredModel.toDomain();

    expect(restoredProduct.id, equals(42));
    expect(restoredProduct.code, equals('PLA-4731'));
    expect(restoredProduct.originalCode, equals('8462047073676'));
    expect(restoredProduct.nameAr, equals('اطباق'));
    expect(restoredProduct.nameEn, equals('Veritatis Plates'));
    expect(restoredProduct.brand, equals('Pacocha-Gottlieb'));
    expect(restoredProduct.image, equals('https://example.com/item.png'));
    expect(restoredProduct.retailprice, equals(986.37));
    expect(restoredProduct.wholeprice, equals(793.91));
    expect(restoredProduct.unitsPerCarton, equals(3));
  });
}
