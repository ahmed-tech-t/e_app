import 'package:connectivity_plus/connectivity_plus.dart';
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
  Future<List<Product>> getProducts({int? page, int? limit}) async {
    // 1. Check internet connectivity
    final connectivity = await Connectivity().checkConnectivity();
    final hasInternet = !connectivity.contains(ConnectivityResult.none);

    if (hasInternet) {
      try {
        // 2. Fetch from Retrofit Remote DataSource
        final response = await remoteDataSource.getProducts(
          page: page,
          limit: limit,
        );

        final products = response.data?.items.toDomain() ?? [];

        // 3. Cache into Hive if we got products
        if (products.isNotEmpty) {
          await localDataSource.cacheProducts(products);
        }
        return products;
      } catch (_) {
        // Fallback to local cache if request fails (timeout, server error, etc.)
        return localDataSource.getCachedProducts();
      }
    } else {
      // 4. Offline: load directly from Hive
      return localDataSource.getCachedProducts();
    }
  }
}
