import 'package:pos_mobile/features/home/data/local/data_source/product_local_data_source.dart';
import 'package:pos_mobile/features/home/data/remote/data_source/product_data_source.dart';
import 'package:pos_mobile/features/home/data/mapper/product_mapper.dart';
import 'package:pos_mobile/features/home/domain/models/Product.dart';
import 'package:pos_mobile/features/home/domain/repository/product_repository.dart';

class ProductRepositoryImpl implements ProductRepository {
  final ProductDataSource remoteDataSource;
  final ProductLocalDataSource localDataSource;

  ProductRepositoryImpl({
    required this.remoteDataSource,
    required this.localDataSource,
  });

  @override
  Future<List<Product>> getProducts() async {
    try {
      final response = await remoteDataSource.getProducts();

      final products = response.data?.toDomain() ?? [];
      if (products.isNotEmpty) {
        await localDataSource.cacheProducts(products);
      }
      return products;
    } catch (_) {
      // Fallback to local cache if request fails (timeout, network error, server offline)
      return localDataSource.getCachedProducts();
    }
  }

  @override
  List<Product> getCachedProducts() {
    return localDataSource.getCachedProducts();
  }

  @override
  Product? getProductByCode(String code) {
    return localDataSource.getProductByCode(code);
  }
}
