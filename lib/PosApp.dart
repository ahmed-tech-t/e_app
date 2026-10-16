import 'package:flutter/material.dart';
import 'package:get/get.dart';
import 'package:pos_mobile/core/config/app_routes.dart';
import 'package:pos_mobile/core/theme/app_theme.dart';
import 'package:pos_mobile/screens/common/offline_banner.dart';

class PosApp extends StatelessWidget {
  const PosApp({super.key});

  @override
  Widget build(BuildContext context) {
    return GetMaterialApp(
      title: 'POS Mobile',
      debugShowCheckedModeBanner: false,
      theme: AppTheme.lightTheme,
      darkTheme: AppTheme.darkTheme,
      themeMode: ThemeMode.system,
      initialRoute: AppRoutes.splash,
      getPages: AppRoutes.routes,
      builder: (context, child) {
        return Column(
          children: [
            const OfflineBanner(),
            Expanded(child: child ?? const SizedBox.shrink()),
          ],
        );
      },
    );
  }
}
