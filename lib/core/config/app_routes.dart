import 'package:flutter_bloc/flutter_bloc.dart';
import 'package:get/get.dart';
import 'package:pos_mobile/features/bills/ui/bills_screen.dart';
import 'package:pos_mobile/features/bills/ui/create_bill_screen.dart';
import 'package:pos_mobile/features/bills/ui/cubit/bills_cubit.dart';
import 'package:pos_mobile/features/bills/ui/cubit/create_bill_cubit.dart';
import 'package:pos_mobile/features/home/ui/cubit/home_cubit.dart';
import 'package:pos_mobile/features/home/ui/home_screen.dart';
import 'package:pos_mobile/features/splash/ui/cubit/splash_cubit.dart';
import 'package:pos_mobile/features/splash/ui/splash_screen.dart';

class AppRoutes {
  static const splash = '/';
  static const home = '/home';
  static const bills = '/bills';
  static const createBill = '/create-bill';

  static final routes = <GetPage>[
    GetPage(
      name: splash,
      page: () => BlocProvider(
        create: (_) => Get.find<SplashCubit>()..syncProducts(),
        child: const SplashScreen(),
      ),
    ),
    GetPage(
      name: home,
      page: () => BlocProvider(
        create: (_) => Get.find<HomeCubit>()..getCachedProducts(),
        child: const HomeScreen(),
      ),
    ),
    GetPage(
      name: bills,
      page: () => BlocProvider(
        create: (_) => Get.find<BillsCubit>()..fetchBills(),
        child: const BillsScreen(),
      ),
    ),
    GetPage(
      name: createBill,
      page: () => BlocProvider(
        create: (_) => Get.find<CreateBillCubit>(),
        child: const CreateBillScreen(),
      ),
    ),
  ];
}
