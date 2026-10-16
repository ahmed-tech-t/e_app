import 'package:pos_mobile/features/home/domain/models/Product.dart';

abstract class ProductRepository {
  Future<List<Product>> getProducts();
  List<Product> getCachedProducts();
  Product? getProductByCode(String code);
}
