import 'package:flutter/material.dart';
import 'package:hive_flutter/adapters.dart';
import 'package:pos_mobile/PosApp.dart';
import 'package:pos_mobile/core/config/app_config.dart';
import 'package:pos_mobile/core/di/dependency_injection.dart';
import 'package:pos_mobile/features/home/data/local/models/product_hive_model.dart';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();
  await Hive.initFlutter();
  Hive.registerAdapter(ProductHiveModelAdapter());
  await Hive.openBox<ProductHiveModel>('products_box');

  AppConfig.instance = AppConfig(
    appName: 'POS',
    baseUrl: 'http://10.0.2.2:8000/api/',
  );
  initDependencyInjection();
  runApp(const PosApp());
}
