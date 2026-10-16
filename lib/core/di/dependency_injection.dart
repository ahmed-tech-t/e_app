import 'package:get/get.dart';
import 'package:pos_mobile/core/di/api_client.dart';
import 'package:pos_mobile/features/bills/data/remote/data_source/bill_data_source.dart';
import 'package:pos_mobile/features/bills/data/repository/bill_repository_impl.dart';
import 'package:pos_mobile/features/bills/domain/repository/bill_repository.dart';
import 'package:pos_mobile/features/bills/ui/cubit/bills_cubit.dart';
import 'package:pos_mobile/features/bills/ui/cubit/create_bill_cubit.dart';
import 'package:pos_mobile/features/home/data/local/data_source/product_local_data_source.dart';
import 'package:pos_mobile/features/home/data/remote/data_source/product_data_source.dart';
import 'package:pos_mobile/features/home/data/repository/product_repository_impl.dart';
import 'package:pos_mobile/features/home/domain/repository/product_repository.dart';
import 'package:pos_mobile/features/home/ui/cubit/home_cubit.dart';
import 'package:pos_mobile/features/splash/ui/cubit/splash_cubit.dart';

void initDependencyInjection() {
  // Core
  final dio = ApiClient().dio;

  // Data Sources
  Get.lazyPut<ProductDataSource>(() => ProductDataSource(dio), fenix: true);
  Get.lazyPut<ProductLocalDataSource>(
    () => ProductLocalDataSource(),
    fenix: true,
  );

  // Repository
  Get.lazyPut<ProductRepository>(
    () => ProductRepositoryImpl(
      remoteDataSource: Get.find<ProductDataSource>(),
      localDataSource: Get.find<ProductLocalDataSource>(),
    ),
    fenix: true,
  );
  Get.lazyPut<BillDataSource>(() => BillDataSource(dio), fenix: true);
  Get.lazyPut<BillRepository>(
    () => BillRepositoryImpl(remoteDataSource: Get.find<BillDataSource>()),
    fenix: true,
  );

  // Cubits
  Get.lazyPut<HomeCubit>(
    () => HomeCubit(Get.find<ProductRepository>()),
    fenix: true,
  );
  Get.lazyPut<SplashCubit>(
    () => SplashCubit(Get.find<ProductRepository>()),
    fenix: true,
  );
  Get.lazyPut<BillsCubit>(
    () => BillsCubit(Get.find<BillRepository>()),
    fenix: true,
  );
  Get.lazyPut<CreateBillCubit>(
    () => CreateBillCubit(
      Get.find<ProductRepository>(),
      Get.find<BillRepository>(),
    ),
    fenix: true,
  );
}
