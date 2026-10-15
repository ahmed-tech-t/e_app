import 'package:pos_mobile/features/home/domain/models/Product.dart';

abstract class ProductRepository {
  Future<List<Product>> getProducts({int? page, int? limit});
}
