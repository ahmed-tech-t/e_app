import 'package:hive_flutter/hive_flutter.dart';
import 'package:pos_mobile/features/home/data/local/models/product_hive_model.dart';
import 'package:pos_mobile/features/home/data/mapper/product_mapper.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';

class ProductLocalDataSource {
  final Box<ProductHiveModel> _box = Hive.box<ProductHiveModel>('products_box');
  Future<void> cacheProducts(List<Product> products) async {
    await _box.clear();
    // Super clean and readable:
    await _box.addAll(products.toHive());
  }

  List<Product> getCachedProducts() {
    // Super clean and readable:
    return _box.values.toList().toDomain();
  }
}
