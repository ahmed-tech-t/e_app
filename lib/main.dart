import 'package:flutter/material.dart';
import 'package:hive_flutter/adapters.dart';
import 'package:pos_mobile/core/config/app_config.dart';

void main() async {
  await Hive.initFlutter();
  // 2. Open the box for products
  await Hive.openBox('products_box');

  AppConfig.instance = AppConfig(
    appName: 'POS',
    baseUrl: 'https://api.yoursite.com',
  );
  runApp(const MyApp());
}
